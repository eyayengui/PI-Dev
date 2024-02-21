<?php


namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ConsultationRepository;
use App\Entity\Consultation;
use App\Entity\Fichemedicale;
use App\Form\ConsultationType;
use App\Form\ConsultationType1Type;
use App\Form\FicheType;
use App\Repository\FichemedicaleRepository;

class ConsultationController extends AbstractController
{
    #[Route('/consultation', name: 'app_consultation')]
    public function index(): Response
    {
        return $this->render('consultation/index.html.twig', [
            'controller_name' => 'ConsultationController',
        ]);
    }
    #[Route('/listaddconsultation', name: 'Listaddconsultation')]
    public function Listaddconsultation(EntityManagerInterface $em,ConsultationRepository $CRepo, Request $request ): Response
    {
    $con =New Consultation;
    $cons =$CRepo->findAll();
    $form=$this->createform(ConsultationType::class,$con);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()){
    $em->persist($con);
    $em->flush();
    return $this->redirectToRoute('Listaddconsultation');
    }
    return $this->render('consultation/listadd.html.twig',['formA' =>$form->createView(),'consultations'=>$cons]);
    }






    #[Route('/listaddconsultation1', name: 'Listaddconsultation1')]
    public function Listaddconsultation1(EntityManagerInterface $em, ConsultationRepository $CRepo, Request $request,FichemedicaleRepository $fichemedicaleRepository): Response
    {
    $fiche = $fichemedicaleRepository->find(0);
    $cons = $CRepo->findAll();
    return $this->render('consultation/list.html.twig', [
        'consultations' => $cons,
        'fiche' => $fiche,
    ]);
    }


    #[Route('/listaddconsultation2', name: 'Listaddconsultation2')]
    public function Listaddconsultation2(EntityManagerInterface $em, ConsultationRepository $CRepo, Request $request,FichemedicaleRepository $fichemedicaleRepository): Response
    {
    $fiche = $fichemedicaleRepository->find(0);
    $cons = $CRepo->findAll();
    return $this->render('consultation/list1.html.twig', [
        'consultations' => $cons,
        'fiche' => $fiche,
    ]);
    }




    
    #[Route('/deleteconsultation/{id}', name: 'supp_consultation')]
    public function deleteconsultation($id, ConsultationRepository $arepo, ManagerRegistry $doctrine): Response
    {
    $em = $doctrine->getManager();
    $con = $arepo->find($id);
    if (!$con) {
        return new Response("consultation introuvable", Response::HTTP_NOT_FOUND);
    }
    $em->remove($con);
    $em->flush();
    return $this->redirectToRoute('Listaddconsultation');
    }


    
    #[Route('/editconsultation/{id}', name: 'edit_consultation')]
    public function editconsultation($id, ConsultationRepository $arepo, Request $request, EntityManagerInterface $em): Response
    {
    $con = $arepo->find($id);
    if (!$con) {
        return new Response("consultation introuvable", Response::HTTP_NOT_FOUND);
    }
    $form = $this->createForm(ConsultationType::class, $con);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        return $this->redirectToRoute('Listaddconsultation'); 
    }
    return $this->render('consultation/edit.html.twig', ['formA' => $form->createView(), 'consultation' => $con]);
    }

    #[Route('/editconsultation1/{id}', name: 'edit_consultation1')]
    public function editconsultation1($id, ConsultationRepository $arepo, Request $request, EntityManagerInterface $em): Response
    {
    $con = $arepo->find($id);
    if (!$con) {
        return new Response("consultation introuvable", Response::HTTP_NOT_FOUND);
    }
    $form = $this->createForm(ConsultationType1Type::class, $con);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        return $this->redirectToRoute('Listaddconsultation1'); 
    }
    return $this->render('consultation/edit1.html.twig', ['formC' => $form->createView(), 'consultation' => $con]);
    }
    }
