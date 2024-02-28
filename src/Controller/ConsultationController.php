<?php


namespace App\Controller;
//require_once './vendor/autoload.php';
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
use App\Repository\FichemedicaleRepository;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
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
        $p = $request->query->get('Pathologie'); 
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        $fiche = $fichemedicaleRepository->find(0);
        $consultations = $consultationRepository->findConsultationsBetweenDates($startDate, $endDate,$p);
        if (!$consultations) {
            return new Response('Consultation not found.', Response::HTTP_NOT_FOUND);
        }
        return $this->render('consultation/search.html.twig', [
            'consultations' => $consultations,
            'fiche' => $fiche,
        ]);
    }

    #[Route('/searchConsultation1', name: 'search_consultation1')]
    public function searchConsultation1(Request $request, ConsultationRepository $consultationRepository,FichemedicaleRepository $fichemedicaleRepository): Response
    {
        $p = $request->query->get('Pathologie'); 
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        $fiche = $fichemedicaleRepository->find(0);
        $consultations = $consultationRepository->findConsultationsBetweenDates($startDate, $endDate,$p);
        if (!$consultations) {
            return new Response('Consultation not found.', Response::HTTP_NOT_FOUND);
        }
        return $this->render('consultation/search1.html.twig', [
            'consultations' => $consultations,
            'fiche' => $fiche,
        ]);
    }



    #[Route('/searchConsultation2', name: 'search_consultation2')]
    public function searchConsultation2(Request $request, ConsultationRepository $consultationRepository,FichemedicaleRepository $fichemedicaleRepository): Response
    {
        $p = $request->query->get('Pathologie'); 
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        $fiche = $fichemedicaleRepository->find(0);
        $consultations = $consultationRepository->findConsultationsBetweenDates($startDate, $endDate,$p);
        if (!$consultations) {
            return new Response('Consultation not found.', Response::HTTP_NOT_FOUND);
        }
        return $this->render('consultation/search2.html.twig', [
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

    #[Route('/send_confirmation', name: 'send_emailconf')]
    public function sendEmail(Request $request): Response
    {         
        $transport = Transport::fromDsn('smtp://brahemraed@gmail.com:xrvvnqxmpikopuvb@smtp.gmail.com:587');
        $mailer = new Mailer($transport);
        $email = (new Email());
        $email->from('brahemraed@gmail.com');
        $email->to('brahemraed6@gmail.com');
        $email->subject('Consultation Confirmée');
        $email->text('La consultation que vous avez réservée est confirmée pour le 27/9/2027');
        $email->html('
            <h1 style="color: #fff300; background-color: #0073ff; width: 500px; padding: 16px 0; text-align: center; border-radius: 50px;">
            Consultation confirmée !.
            </h1>
            <h1 style="color: #ff0000; background-color: #5bff9c; width: 500px; padding: 16px 0; text-align: center; border-radius: 50px;">
            27/9/2027
            </h1>
        ');
        try {
            $mailer->send($email);
            return $this->redirectToRoute('Listaddconsultation1');
        } catch (TransportExceptionInterface $e) {
            return new Response('<h1>Error while sending email!</h1>');
        }
    }
}


