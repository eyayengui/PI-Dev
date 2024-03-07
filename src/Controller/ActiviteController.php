<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ActiviteController extends AbstractController
{   
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/activite', name: 'app_activite_index', methods: ['GET'])]
    public function index(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('activite/index.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

    #[Route('activite/new', name: 'app_activite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($activite);
            $entityManager->flush();

            return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('activite/{id}', name: 'app_activite_show', methods: ['GET'])]
    public function show(Activite $activite): Response
    {
        return $this->render('activite/show.html.twig', [
            'activite' => $activite,
        ]);
    }

    #[Route('activite/{id}/edit', name: 'app_activite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('activite/{id}', name: 'app_activite_delete', methods: ['POST'])]
    public function delete(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($activite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('activite/Calendar', name: 'calendar')]
    public function calendar(ActiviteRepository $activiteRepository, Security $security): Response
    {
        $activites = $activiteRepository->findAll();
        $events = [];
    
        foreach ($activites as $activite) {
            $events[] = [
                'id' => $activite->getId(),
                'title' => sprintf('Nom: %s Genre: %s', $activite->getNom(), $activite->getGenre()),
                'start' => $activite->getDate(),
            ];
        }
    
        // Check if there are any events before encoding
        if (empty($events)) {
            // Handle case where there are no events
            // You may want to set a default message or handle this case differently
            $data = json_encode(['message' => 'No events available']);
        } else {
            // Encode events data into JSON format
            $data = json_encode($events);
        }
    
        return $this->render('activite/calendar.html.twig', [
            'data' => $data,
        ]);
    }
    
    
}
