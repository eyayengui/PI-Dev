<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PublicationRepository; // Ajoutez cette ligne pour importer le trait
use Symfony\Component\Security\Core\Security;



class CommentaireController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/commentaire', name: 'app_commentaire')]
    public function index(): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }

    // #[Route('/add_commentaire', name: 'add_commentaire')]
    // public function addCommentaire(EntityManagerInterface $em, Request $request): Response
    // {
    //     $commentaire = new Commentaire;
    //     $form = $this->createForm(CommentaireType::class, $commentaire);
    //     $form->add('Ajouter', SubmitType::class);
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
            
    //         $em->persist($commentaire);
    //         $em->flush();

    //         $this->addFlash('success', 'Commentaire ajouté avec succès!');

    //         return $this->redirectToRoute('ListCommentaire');
    //     }
    //     return $this->render('commentaire/addCommentaire.html.twig', [
    //         'formB' => $form->createView(),
    //          ]);
    // }
    
    // #[Route('/add_commentaire/{id}', name: "add_commentaire")]
    // public function addCommentaire($id, EntityManagerInterface $em, Request $request, PublicationRepository $publicationRepo): Response
    // {
    //     $publication = $publicationRepo->find($id);
    
    //     if (!$publication) {
    //         throw $this->createNotFoundException('La publication n\'existe pas.');
    //     }
    
    //     $commentaire = new Commentaire;
    //     $commentaire->setPublication($publication); // Associer le commentaire à la publication
    
    //     $form = $this->createForm(CommentaireType::class, $commentaire);
    //     $form->add('Ajouter', SubmitType::class);
    //     $form->handleRequest($request);
    
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em->persist($commentaire);
    //         $em->flush();
    
    //         $this->addFlash('success', 'Commentaire ajouté avec succès!');
    
    //         return $this->redirectToRoute('publication_details', ['id' => $id]);
    //     }
    
    //     return $this->render('publication/publication_details.html.twig', [
    //         'formB' => $form->createView(),
    //         'publication' => $publication,
    //     ]);
    // }
    


    #[Route('/ListCommentaire', name: 'ListCommentaire')]
    public function ListCommentaire(CommentaireRepository $repo): Response
    {
        $commentaires = $repo->findAll();
        return $this->render('commentaire/listcommentaire.html.twig', [
            'commentaires' => $commentaires,
             ]);
    }
  
    #[Route('/edit_commentaire/{id}', name: 'edit_commentaire', methods: ['GET', 'POST'])]
    public function edit_commentaire(Request $request, CommentaireRepository $repository, int $id): Response
    {
        $commentaire = $repository->findOneBy(["id" => $id]);
    
        if (!$commentaire) {
            throw $this->createNotFoundException('Le commentaire n\'existe pas.');
        }
    
        $publicationId = $commentaire->getPublication()->getId(); // Récupérer l'ID de la publication à laquelle le commentaire appartient
        $form = $this->createForm(CommentaireType::class, $commentaire);
       
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush(); // Cette ligne met à jour les modifications dans la base de données
       
            $this->addFlash('success', 'Commentaire modifié avec succès!');
       
            // Rediriger vers la page de détails de la publication
            return $this->redirectToRoute('publication_details', ['id' => $publicationId]);
        }
       
        return $this->render('commentaire/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/delete_commentaire/{id}', name: 'delete_commentaire', methods: ['POST'])]
    public function delete(EntityManagerInterface $manager, int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Commentaire::class);
        $commentaire = $repository->find($id);
    
        if (!$commentaire) {
            $this->addFlash('error', 'Le commentaire n\'existe pas !');
            return $this->redirectToRoute('ListCommentaire'); // Redirect to the appropriate route
        }
    
        $publicationId = $commentaire->getPublication()->getId(); // Get the associated publication ID
    
        $manager->remove($commentaire);
        $manager->flush();
    
        $this->addFlash('success', 'Suppression réussie !');
    
        return $this->redirectToRoute('publication_details', ['id' => $publicationId]); // Redirect to the publication details page with the correct ID
    }
    
    
   
}
