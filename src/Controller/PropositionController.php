<?php

namespace App\Controller;

use App\Entity\Proposition;
use App\Form\PropositionType;
use App\Repository\PropositionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/proposition')]
class PropositionController extends AbstractController
{
    #[Route('/', name: 'app_proposition_index', methods: ['GET'])]
    public function index(PropositionRepository $propositionRepository): Response
    {
        return $this->render('proposition/index.html.twig', [
            'propositions' => $propositionRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_proposition_show', methods: ['GET'])]
    public function show(Proposition $proposition): Response
    {
        return $this->render('proposition/show.html.twig', [
            'proposition' => $proposition,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_proposition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Proposition $proposition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PropositionType::class, $proposition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_proposition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('proposition/edit.html.twig', [
            'proposition' => $proposition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_proposition_delete', methods: ['POST'])]
    public function delete(Request $request, Proposition $proposition, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$proposition->getId(), $request->request->get('_token'))) {
            $entityManager->remove($proposition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_proposition_index', [], Response::HTTP_SEE_OTHER);
    }
}
