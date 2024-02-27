<?php


namespace App\Controller;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ConsultationRepository;
use App\Entity\Consultation;
use App\Form\ConsultationType;
use App\Form\ConsultationType1Type;
use App\Services\MailerService;
use App\Repository\FichemedicaleRepository;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Manager\ContactManager;
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
    $cons =$CRepo->findByPatientId();
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
    public function Listaddconsultation1(ConsultationRepository $CRepo, Request $request,FichemedicaleRepository $fichemedicaleRepository): Response
    {
    $fiche = $fichemedicaleRepository->find(0);
    $cons = $CRepo->findByTherapistId();
    return $this->render('consultation/list.html.twig', [
        'consultations' => $cons,
        'fiche' => $fiche,
    ]);
    }


    #[Route('/listaddconsultation2', name: 'Listaddconsultation2')]
    public function Listaddconsultation2(ConsultationRepository $CRepo, Request $request,FichemedicaleRepository $fichemedicaleRepository): Response
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



    #[Route('/searchConsultation', name: 'search_consultation')]
    public function searchConsultation(Request $request, ConsultationRepository $consultationRepository,FichemedicaleRepository $fichemedicaleRepository): Response
    {
        $id = $request->query->get('id'); 
        $consultation = $consultationRepository->find($id); 
        $fiche = $fichemedicaleRepository->find(0);
        dump($consultation);
        if (!$consultation) {
            return new Response('Consultation not found.', Response::HTTP_NOT_FOUND);
        }
        return $this->render('consultation/search.html.twig', [
            'consultation' => $consultation,
            'fiche' => $fiche,
        ]);
    }

    #[Route('/searchConsultationByDate', name: 'search_consultation_by_date')]
    public function searchConsultationByDate(Request $request, ConsultationRepository $consultationRepository, FichemedicaleRepository $fichemedicaleRepository): Response
    {
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        $consultations = $consultationRepository->findConsultationsBetweenDates($startDate, $endDate);
        if (!$consultations) {
            return new Response('Consultation not found.', Response::HTTP_NOT_FOUND);
        }
        return $this->render('consultation/between.html.twig', [
            'consultations' => $consultations,
        ]);
    }
    

    #[Route('/searchConsultation1', name: 'search_consultation1')]
    public function searchConsultation1(Request $request, ConsultationRepository $consultationRepository,FichemedicaleRepository $fichemedicaleRepository): Response
    {
        $id = $request->query->get('id'); 
        $consultation = $consultationRepository->find($id); 
        $fiche = $fichemedicaleRepository->find(0);
        dump($consultation);
        if (!$consultation) {
            return new Response('Consultation not found.', Response::HTTP_NOT_FOUND);
        }
        return $this->render('consultation/search1.html.twig', [
            'consultation' => $consultation,
            'fiche' => $fiche,
        ]);
    }

    #[Route('/searchConsultationByDate1', name: 'search_consultation_by_date1')]
    public function searchConsultationByDate1(Request $request, ConsultationRepository $consultationRepository, FichemedicaleRepository $fichemedicaleRepository): Response
    {
        $fiche = $fichemedicaleRepository->find(0);
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        $consultations = $consultationRepository->findConsultationsBetweenDates($startDate, $endDate);
        if (!$consultations) {
            return new Response('Consultation not found.', Response::HTTP_NOT_FOUND);
        }
        return $this->render('consultation/between1.html.twig', [
            'consultations' => $consultations,
            'fiche' => $fiche,
        ]);
    }

    #[Route('/searchConsultation2', name: 'search_consultation2')]
    public function searchConsultation2(Request $request, ConsultationRepository $consultationRepository,FichemedicaleRepository $fichemedicaleRepository): Response
    {
        $id = $request->query->get('id'); 
        $consultation = $consultationRepository->find($id); 
        $fiche = $fichemedicaleRepository->find(0);
        dump($consultation);
        if (!$consultation) {
            return new Response('Consultation not found.', Response::HTTP_NOT_FOUND);
        }
        return $this->render('consultation/search2.html.twig', [
            'consultation' => $consultation,
            'fiche' => $fiche,
        ]);
    }

    #[Route('/searchConsultationByDate2', name: 'search_consultation_by_date2')]
    public function searchConsultationByDate2(Request $request, ConsultationRepository $consultationRepository, FichemedicaleRepository $fichemedicaleRepository): Response
    {
        $fiche = $fichemedicaleRepository->find(0);
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        $consultations = $consultationRepository->findConsultationsBetweenDates($startDate, $endDate);
        if (!$consultations) {
            return new Response('Consultation not found.', Response::HTTP_NOT_FOUND);
        }
        return $this->render('consultation/between2.html.twig', [
            'consultations' => $consultations,
            'fiche' => $fiche,
        ]);
    }

    #[Route('/consultations_ordered_by_date', name: 'consultations_ordered_by_date')]
    public function consultationsOrderedByDate(ConsultationRepository $consultationRepository, FichemedicaleRepository $fichemedicaleRepository): Response
    {
        $fiche = $fichemedicaleRepository->find(0);
        $consultations = $consultationRepository->findAllOrderedByDate();
        $fiche = $fichemedicaleRepository->find(0);
        return $this->render('consultation/orderbydate.html.twig', [
            'consultations' => $consultations,
            'fiche' => $fiche,
        ]);
    }

    #[Route('/consultations_ordered_by_date1', name: 'consultations_ordered_by_date1')]
    public function consultationsOrderedByDate1(ConsultationRepository $consultationRepository, FichemedicaleRepository $fichemedicaleRepository): Response
    {
        $consultations = $consultationRepository->findAllOrderedByDate();
        $fiche = $fichemedicaleRepository->find(0);
        return $this->render('consultation/orderbydate1.html.twig', [
            'consultations' => $consultations,
            'fiche' => $fiche,
        ]);
    }

    #[Route('/consultations_ordered_by_date2', name: 'consultations_ordered_by_date2')]
    public function consultationsOrderedByDate2(ConsultationRepository $consultationRepository, FichemedicaleRepository $fichemedicaleRepository): Response
    {
        $consultations = $consultationRepository->findAllOrderedByDate();
        $fiche = $fichemedicaleRepository->find(0);
        return $this->render('consultation/orderbydate2.html.twig', [
            'consultations' => $consultations,
            'fiche' => $fiche,
        ]);
    }

}

