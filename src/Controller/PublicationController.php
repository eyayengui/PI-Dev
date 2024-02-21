<?php

namespace App\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Publication;
use App\Form\PublicationType;
use App\Repository\PublicationRepository; // Ajoutez cette ligne pour importer le trait
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;

class PublicationController extends AbstractController
{
    #[Route('/publication', name: 'app_publication')]
    public function index(): Response
    {
        return $this->render('publication/index.html.twig', [
            'controller_name' => 'PublicationController',
        ]);
    }


    #[Route('/ListPublication', name: 'ListPublication')]
    public function ListPublication(PublicationRepository $publicationRepo, CommentaireRepository $commentaireRepo): Response
    {
        // Récupération de toutes les publications
        $publications = $publicationRepo->findAll();
    
        // Calculer le nombre de commentaires pour chaque publication
        $commentairesParPublication = [];
        foreach ($publications as $publication) {
            $commentaires = $commentaireRepo->findBy(['publication' => $publication]);
            $commentairesParPublication[$publication->getId()] = count($commentaires);
        }
    
        return $this->render('publication/listPublication.html.twig', [
            'publications' => $publications,
            'commentairesParPublication' => $commentairesParPublication,
        ]);
    }

    #[Route('/ListBack', name: 'ListBack')]
    public function ListBack(PublicationRepository $publicationRepo, CommentaireRepository $commentaireRepo): Response
    {
        // Récupération de toutes les publications
        $publications = $publicationRepo->findAll();
    
        // Calculer le nombre de commentaires pour chaque publication
        $commentairesParPublication = [];
        foreach ($publications as $publication) {
            $commentaires = $commentaireRepo->findBy(['publication' => $publication]);
            $commentairesParPublication[$publication->getId()] = count($commentaires);
        }
    
        return $this->render('publication/ListBack.html.twig', [
            'publications' => $publications,
            'commentairesParPublication' => $commentairesParPublication,
        ]);
    }




    #[Route('/ListBack', name: 'ListBack')]
public function addPublication(EntityManagerInterface $em, Request $request, PublicationRepository $publicationRepo, CommentaireRepository $commentaireRepo): Response
{
    $publication = new Publication();
    $form = $this->createForm(PublicationType::class, $publication);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form['imageFile']->getData();

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            // Sanitize the filename (remove unwanted characters)
            $safeFilename = preg_replace('/[^a-zA-Z0-9_.-]/', '', $originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            // Move the file to the directory where images are stored
            try {
                $imageFile->move(
                    $this->getParameter('image_directory'), // Replace with your actual path
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle file upload error
                $this->addFlash('error', 'Une erreur s\'est produite lors du téléchargement de l\'image.');
                return $this->redirectToRoute('ListBack'); // Redirect to the form page
            }

            // Set the image filename in the Publication entity
            $publication->setImageP($newFilename);
        }

        $em->persist($publication);
        $em->flush();

        $this->addFlash('success', 'Publication ajoutée avec succès!');

        return $this->redirectToRoute('ListBack');
    }

    // Récupération de toutes les publications
    $publications = $publicationRepo->findAll();

    // Calculer le nombre de commentaires pour chaque publication
    $commentairesParPublication = [];
    foreach ($publications as $publication) {
        $commentaires = $commentaireRepo->findBy(['publication' => $publication]);
        $commentairesParPublication[$publication->getId()] = count($commentaires);
    }

    return $this->render('publication/ListBack.html.twig', [
        'formA' => $form->createView(),
        'publications' => $publications,
        'commentairesParPublication' => $commentairesParPublication,
    ]);
}



   

#[Route('/edit_publication/{id}', name: 'edit_publication', methods: ['GET', 'POST'])]
public function edit(Request $request, PublicationRepository $repository, int $id): Response
{
    $publication = $repository->findOneBy(["id" => $id]);
    $form = $this->createForm(PublicationType::class, $publication);
   
    $form->handleRequest($request);
   
    if ($form->isSubmitted() && $form->isValid()) {
        // Handle image file upload
        $imageFile = $form['imageFile']->getData();
        if ($imageFile) {
            // Generate a unique filename for the image
            $newFilename = uniqid().'.'.$imageFile->guessExtension();
            // Move the file to the directory where images are stored
            try {
                $imageFile->move(
                    $this->getParameter('image_directory'), // Replace with your actual path
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle file upload error
                // Optionally, add a flash message and redirect
                // You can modify this part according to your needs
            }
            // Update the image filename in the Publication entity
            $publication->setImageP($newFilename);
        }
        
        // Flush changes to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $this->addFlash('success', 'Publication modifiée avec succès!');

        return $this->redirectToRoute('ListBack');
    }
   
    return $this->render('publication/editPublication.html.twig', [
        'form' => $form->createView(),
    ]);
}



   #[Route('/delete_publication/{id}', name: 'delete_publication', methods: ['POST'])]
public function delete(EntityManagerInterface $manager, int $id): Response
{
    $repository = $this->getDoctrine()->getRepository(Publication::class);
    $publication = $repository->find($id);

    if (!$publication) {
        $this->addFlash('error', 'L\'auteur n\'existe pas !');
        return $this->redirectToRoute('publications'); // Assurez-vous que la route est correcte
    }

    $manager->remove($publication);
    $manager->flush();

    $this->addFlash('success', 'Suppression réussie !');

    return $this->redirectToRoute('ListBack'); // Assurez-vous que la route est correcte
}

// #[Route("/publication/{id}", name:"publication_details")]
// public function showPublicationDetails($id, PublicationRepository $repo): Response
// {
//     $publication = $repo->find($id);

//     if (!$publication) {
//         throw $this->createNotFoundException('La publication n\'existe pas.');
//     }

//     return $this->render('publication/publication_details.html.twig', [
//         'publication' => $publication,
//     ]);
// }


// #[Route("/publication/{id}", name:"publication_details")]
// public function showPublicationDetails($id, PublicationRepository $repo, EntityManagerInterface $em, Request $request): Response
// {
//     $publication = $repo->find($id);

//     if (!$publication) {
//         throw $this->createNotFoundException('La publication n\'existe pas.');
//     }

//     // Création du formulaire pour ajouter un commentaire
//     $commentaire = new Commentaire();
//     $form = $this->createForm(CommentaireType::class, $commentaire);

//     // Gestion de la soumission du formulaire
//     $form->handleRequest($request);
//     if ($form->isSubmitted() && $form->isValid()) {
//         $commentaire->setPublication($publication); // Associer le commentaire à la publication
//         $em->persist($commentaire); 
//         $em->flush();

//         $this->addFlash('success', 'Commentaire ajouté avec succès!');
        
//         // Redirection vers la même page après la soumission du formulaire pour éviter les re-soumissions
//         return $this->redirectToRoute('publication_details', ['id' => $id]);
//     }

//     // Affichage de la page avec les détails de la publication et le formulaire pour ajouter un commentaire
//     return $this->render('publication/publication_details.html.twig', [
//         'formB' => $form->createView(),
//         'publication' => $publication,
//     ]);
// }
    





#[Route("/publication/{id}", name:"publication_details")]
public function showPublicationDetails($id, PublicationRepository $publicationRepo, CommentaireRepository $commentaireRepo, EntityManagerInterface $em, Request $request): Response
{
    $publication = $publicationRepo->find($id);

    if (!$publication) {
        throw $this->createNotFoundException('La publication n\'existe pas.');
    }

    // Récupération des commentaires de cette publication
    $commentaires = $commentaireRepo->findBy(['publication' => $publication]);

    // Récupération de toutes les publications
    $publications = $publicationRepo->findAll();

    // Calculer le nombre de commentaires pour chaque publication
    $commentairesParPublication = [];
    foreach ($publications as $pub) {
        $commentairesParPublication[$pub->getId()] = count($commentaireRepo->findBy(['publication' => $pub]));
    }

    // Création du formulaire pour ajouter un commentaire
    $commentaire = new Commentaire();
    $form = $this->createForm(CommentaireType::class, $commentaire);

    // Gestion de la soumission du formulaire
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $commentaire->setPublication($publication); // Associer le commentaire à la publication
        $em->persist($commentaire); 
        $em->flush();

        $this->addFlash('success', 'Commentaire ajouté avec succès!');
        
        // Redirection vers la même page après la soumission du formulaire pour éviter les re-soumissions
        return $this->redirectToRoute('publication_details', ['id' => $id]);
    }

    // Affichage de la page avec les détails de la publication, les commentaires de la publication, la liste de toutes les publications, le nombre de commentaires par publication et le formulaire pour ajouter un commentaire
    return $this->render('publication/publication_details.html.twig', [
        'formB' => $form->createView(),
        'publication' => $publication,
        'commentaires' => $commentaires,
        'publications' => $publications,
        'commentairesParPublication' => $commentairesParPublication,
    ]);
}

// #[Route("/publication/{id}", name:"publication_details")]
// public function showPublicationDetails($id, PublicationRepository $publicationRepo, CommentaireRepository $commentaireRepo, EntityManagerInterface $em, Request $request): Response
// {
//     // Création du formulaire de recherche par titre
//     $searchForm = $this->createFormBuilder(null)
//         ->add('search', TextType::class, ['label' => 'Rechercher par titre', 'required' => false])
//         ->add('searchBtn', SubmitType::class, ['label' => 'Rechercher'])
//         ->getForm();

//     $searchForm->handleRequest($request);

//     // Récupération de la publication correspondant à l'ID
//     $publication = $publicationRepo->find($id);

//     if (!$publication) {
//         throw $this->createNotFoundException('La publication n\'existe pas.');
//     }

//     // Récupération des commentaires de cette publication
//     $commentaires = $commentaireRepo->findBy(['publication' => $publication]);

//     // Récupération de toutes les publications
//     $publications = $publicationRepo->findAll();

//     // Calculer le nombre de commentaires pour chaque publication
//     $commentairesParPublication = [];
//     foreach ($publications as $pub) {
//         $commentairesParPublication[$pub->getId()] = count($commentaireRepo->findBy(['publication' => $pub]));
//     }

//     // Si le formulaire de recherche est soumis et valide, filtrer les publications par titre
//     $searchedPublications = [];
//     if ($searchForm->isSubmitted() && $searchForm->isValid()) {
//         $searchData = $searchForm->getData();
//         $searchedTitle = $searchData['search'];

//         if ($searchedTitle) {
//             $searchedPublications = $publicationRepo->findByTitle($searchedTitle);
//         }
//     }

//     // Création du formulaire pour ajouter un commentaire
//     $commentaire = new Commentaire();
//     $form = $this->createForm(CommentaireType::class, $commentaire);

//     // Gestion de la soumission du formulaire
//     $form->handleRequest($request);
//     if ($form->isSubmitted() && $form->isValid()) {
//         $commentaire->setPublication($publication); // Associer le commentaire à la publication
//         $em->persist($commentaire);
//         $em->flush();

//         $this->addFlash('success', 'Commentaire ajouté avec succès!');

//         // Redirection vers la même page après la soumission du formulaire pour éviter les re-soumissions
//         return $this->redirectToRoute('publication_details', ['id' => $id]);
//     }

//     // Affichage de la page avec les détails de la publication, les commentaires de la publication, la liste de toutes les publications, le nombre de commentaires par publication, le formulaire pour ajouter un commentaire et les publications filtrées par titre
//     return $this->render('publication/publication_details.html.twig', [
//         'formB' => $form->createView(),
//         'searchForm' => $searchForm->createView(),
//         'publication' => $publication,
//         'commentaires' => $commentaires,
//         'publications' => $publications,
//         'commentairesParPublication' => $commentairesParPublication,
//         'searchedPublications' => $searchedPublications,
//     ]);
// }


}
