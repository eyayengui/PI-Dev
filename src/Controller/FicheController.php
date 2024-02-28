<?php

namespace App\Controller;
use DateTime;
use App\Entity\Fichemedicale;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\FicheType;
use App\Repository\ConsultationRepository;
use App\Repository\FichemedicaleRepository;

class FicheController extends AbstractController
{
    #[Route('/fiche', name: 'app_fiche')]
    public function index(): Response
    {
        return $this->render('fiche/index.html.twig', [
            'controller_name' => 'FicheController',
        ]);
    }


    #[Route('/listaddfiche', name: 'listaddfiche')]
    public function ListaddFiche(EntityManagerInterface $em,FichemedicaleRepository $fRepo, Request $request ): Response
    {
    $f =New Fichemedicale;
    $fs =$fRepo->findAllExcept0();
    $form=$this->createform(FicheType::class,$f);
    $form->handleRequest($request);
    if ($form->isSubmitted()&& $form->isValid()){
    $em->persist($f);
    $em->flush();
    return $this->redirectToRoute('listaddfiche');
    }
    return $this->render('fiche/listadd.html.twig',['formB' =>$form->createView(),'fiches'=>$fs]);
    }



    #[Route('/listaddfiche1', name: 'listaddfiche1')]
    public function ListaddFiche1(EntityManagerInterface $em, FichemedicaleRepository $fRepo, Request $request): Response
    {
        $fs = $fRepo->findAllExcept0();
        return $this->render('fiche/list.html.twig', ['fiches' => $fs]);
    }









    #[Route('/editfiche/{id}', name: 'edit_fiche')]
    public function editFiche($id, FicheMedicaleRepository $frepo, Request $request, EntityManagerInterface $em): Response
    {
    $f = $frepo->find($id);
    if (!$f) {
        return new Response("fiche introuvable", Response::HTTP_NOT_FOUND);
    }
    $form = $this->createForm(FicheType::class, $f);
    $form->handleRequest($request);

    if ($form->isSubmitted()&& $form->isValid()) {
        $em->flush();
        return $this->redirectToRoute('listaddfiche'); 
    }
    if ($form->isSubmitted()&& !$form->isValid())
    {return $this->redirectToRoute('edit_fiche'); }
    return $this->render('fiche/edit.html.twig', ['formB' => $form->createView(),'fiche' => $f]);
    }


    #[Route('/deletefiche/{id}', name: 'supp_fiche')]
    public function deletefiche($id, FicheMedicaleRepository $arepo, ManagerRegistry $doctrine): Response
    {
    $em = $doctrine->getManager();
    $f = $arepo->find($id);
    if (!$f) {
        return new Response("fiche introuvable", Response::HTTP_NOT_FOUND);
    }
    $em->remove($f);
    $em->flush();
    return $this->redirectToRoute('listaddfiche');    
}


#[Route('/searchFicheMedicale', name: 'search_fiche_medicale')]
public function searchFicheMedicale(Request $request, FichemedicaleRepository $fichemedicaleRepository): Response
{
    $id = $request->query->get('id'); 
    $ficheMedicale = $fichemedicaleRepository->find($id); 

    if (!$ficheMedicale) {
        return new Response('Fiche medicale not found.', Response::HTTP_NOT_FOUND);
    }

    return $this->render('fiche/search.html.twig', [
        'fiche' => $ficheMedicale,
    ]);
}

#[Route('/ficheMedicaleOrderedByDateCreation', name: 'fiche_medicale_ordered_by_date_creation')]
public function ficheMedicaleOrderedByDateCreation(FicheMedicaleRepository $ficheMedicaleRepository): Response
{
    $fiches = $ficheMedicaleRepository->findAllFicheMedicaleOrderedByDateCreation();
    return $this->render('fiche/orderbydate.html.twig', [
        'fiches' => $fiches,
    ]);
}

#[Route('/searchFicheMedicale1', name: 'search_fiche_medicale1')]
public function searchFicheMedicale1(Request $request, FichemedicaleRepository $fichemedicaleRepository): Response
{
    $id = $request->query->get('id'); 
    $ficheMedicale = $fichemedicaleRepository->find($id); 

    if (!$ficheMedicale) {
        return new Response('Fiche medicale not found.', Response::HTTP_NOT_FOUND);
    }

    return $this->render('fiche/search1.html.twig', [
        'fiche' => $ficheMedicale,
    ]);
}

#[Route('/ficheMedicaleOrderedByDateCreation1', name: 'fiche_medicale_ordered_by_date_creation1')]
public function ficheMedicaleOrderedByDateCreation1(FicheMedicaleRepository $ficheMedicaleRepository): Response
{
    $fiches = $ficheMedicaleRepository->findAllFicheMedicaleOrderedByDateCreation();
    return $this->render('fiche/orderbydate1.html.twig', [
        'fiches' => $fiches,
    ]);
}
#[Route('/searchfichebetweendate', name: 'search_fichedate')]
    public function searchConsultation(Request $request, FichemedicaleRepository $ficheMedicaleRepository): Response
    {
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        $fiches = $ficheMedicaleRepository->findFichesBetweenDates($startDate, $endDate);
        if (!$fiches) {
            return new Response('Fiche not found.', Response::HTTP_NOT_FOUND);
        }
        return $this->render('fiche/searchdate.html.twig', [
            'fiches' => $fiches,
        ]);
    }
    #[Route('/searchfichebetweendate1', name: 'search_fichedate1')]
    public function searchConsultation1(Request $request, FichemedicaleRepository $ficheMedicaleRepository): Response
    {
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        $fiches = $ficheMedicaleRepository->findFichesBetweenDates($startDate, $endDate);
        if (!$fiches) {
            return new Response('Fiche not found.', Response::HTTP_NOT_FOUND);
        }
        return $this->render('fiche/searchdate1.html.twig', [
            'fiches' => $fiches,
        ]);
    }

}
