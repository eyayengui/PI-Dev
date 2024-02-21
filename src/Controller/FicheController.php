<?php

namespace App\Controller;

use App\Entity\Fichemedicale;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\FicheType;
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
}
