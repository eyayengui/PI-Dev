<?php

namespace App\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Programme;
use App\Entity\Activite;
use App\Form\ProgrammeType;
use App\Form\ActiviteType;
use App\Repository\ProgrammeRepository;
use App\Repository\ActiviteRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Service\ProgramEmailNotifier;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;



class ProgrammeController extends AbstractController
{   
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/programme', name: 'app_programme_index', methods: ['GET'])]
    public function index(ProgrammeRepository $programmeRepository): Response
    {
        return $this->render('programme/index.html.twig', [
            'programmes' => $programmeRepository->findAll(),
        ]);
    }

    #[Route('programme/new', name: 'app_programme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FlashBagInterface $flashy,ParameterBagInterface $params): Response
    {
        $programme = new Programme();
        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilePictureFile = $form->get('image')->getData();
        if ($profilePictureFile) {
            $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = preg_replace('/[^a-zA-Z0-9_.-]/', '', $originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePictureFile->guessExtension();

            try {
                $profilePictureFile->move(
                    $params->get('image_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle exception if something happens during file upload
                $this->addFlash('error', 'Une erreur s\'est produite lors du téléchargement de la photo de profil.');
                return $this->redirectToRoute('app_register');
            }
            $programme->setImage($newFilename);
        }

            $entityManager->persist($programme);
            $entityManager->flush();

            // Add a success flash message
            $flashy->add('success', 'Programme created successfully.');

            return $this->redirectToRoute('app_programme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('programme/new.html.twig', [
            'programme' => $programme,
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('programme/listeProgramme', name: 'listeProgramme')]
    public function listeProgramme(ProgrammeRepository $programmeRepo, ActiviteRepository $activiteRepo, Security $security): Response
    {
        // Check if a user is logged in
        $user = $security->getUser();
        if (!$user) {
            // Redirect to the login page or display an error message
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
    
        // Retrieve all programmes
        $programmes = $programmeRepo->findAll();

    
        return $this->render('programme/listeProgramme.html.twig', [
            'programmes' => $programmes,
        ]);
    }
    
    
    #[Route('programme/listBackP', name: 'listBackP')]
    public function listBackP(ProgrammeRepository $programmeRepo, ActiviteRepository $activiteRepo): Response
    {
        // Retrieve all programmes
        $programmes = $programmeRepo->findAll();
        
        return $this->render('programme/listBackP.html.twig', [
            'programmes' => $programmes,
        ]);
    }
    
    #[Route('programme/listBackP', name: 'listBackP')]
    public function addProgramme(EntityManagerInterface $em, Request $request, ProgrammeRepository $programmeRepo, ActiviteRepository $activiteRepo, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            // Redirect to the login page or display an error message
            $this->addFlash('error', 'Vous devez être connecté pour ajouter un programme.');
            return $this->redirectToRoute('app_login');
        }
    
        $programme = new Programme();
        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

            // Handle image upload if needed
            $imageFile = $form['imageFile']->getData();
    
            if ($imageFile) {
                // Handle image file upload logic
                // ...
            }
    
            // Set the user ID for the programme
            $programme->setUserID($user);
    
            $em->persist($programme);
            $em->flush();
            $htmlContent = "
            <p><strong>hello</strong></p>
            <
            ";
    
            // Configure the mailer transport (replace with your own email settings)
            $transport = Transport::fromDsn('smtp://esserbenahmed1612@gmail.com:gedmbuxjkwhxakdw@smtp.gmail.com:587');
            $mailer = new Mailer($transport);
    
            // Create the email message
            $emailMessage = (new Email())
                ->from('esserbenahmed1612@gmail.com')
                ->to('benahmedesser@gmail.com') // Use the email from the form
                ->subject('hello')
                ->html($htmlContent);
    
            // Send the email
            $mailer->send($emailMessage);
    
            $this->addFlash('success', 'Programme ajouté avec succès!');
    
            return $this->redirectToRoute('listBackP');
        }
    
        $programmes = $programmeRepo->findAll();
    
        // Calculate the number of activites for each programme
        $activitesParProgramme = [];
        foreach ($programmes as $programme) {
            $activites = $activiteRepo->findBy(['programme' => $programme]);
            $activitesParProgramme[$programme->getId()] = count($activites);
        }
    
        return $this->render('programme/listBackP.html.twig', [
            'form' => $form->createView(),
            'programmes' => $programmes,
            'activitesParProgramme' => $activitesParProgramme,
        ]);
    }
    #[Route("programme/programme/{id}", name: "details")]
    public function showProgrammeDetails($id, ProgrammeRepository $programmeRepo, ActiviteRepository $activiteRepo): Response
    {
        $programme = $programmeRepo->find($id);
        if (!$programme) {
            throw $this->createNotFoundException('Le programme n\'existe pas.');
        }
        return $this->render('programme/details.html.twig', [
            'programme' => $programme,
        ]);
    }
    #[Route('programme/{id}/edit', name: 'app_programme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Programme $programme, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_programme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('programme/edit.html.twig', [
            'programme' => $programme,
            'form' => $form,
        ]);
    }

    #[Route('programme/{id}', name: 'app_programme_delete', methods: ['POST'])]
    public function delete(Request $request, Programme $programme, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$programme->getId(), $request->request->get('_token'))) {
            $entityManager->remove($programme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_programme_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('programme/front', name: 'app_programme_front', methods: ['GET'])]
    public function front(Request $request, ProgrammeRepository $programmeRepository): Response
    {
        $lieu = $request->query->get('lieu');
        $but = $request->query->get('but');
        
        // Ensure $lieu and $but are not null
        $lieu = $lieu ?? '';
        $but = $but ?? '';
        
        // Call the advancedSearch method from ProgrammeRepository
        $programmes = $programmeRepository->advancedSearch($lieu, $but);
        
        return $this->render('Programme/listeProgramme.html.twig', [
            'programmes' => $programmes,
            'lieu' => $lieu,
            'but' => $but,
        ]);
    }
    
}
