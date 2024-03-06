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
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Alignment\LabelAlignmentLeft;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Endroid\QrCode\Builder\BuilderRegistryInterface;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\Label\LabelAlignment;
use Symfony\Component\Security\Core\Security;
use App\Repository\UserRepository;

class FicheController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    
    #[Route('/fiche', name: 'app_fiche')]
    public function index(): Response
    {
        return $this->render('fiche/index.html.twig', [
            'controller_name' => 'FicheController',
        ]);
    }


    #[Route('/listaddfiche', name: 'listaddfiche')]
    public function ListaddFiche(EntityManagerInterface $em,FichemedicaleRepository $fRepo, Request $request,Security $security ): Response
    {$user = $security->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
    $f =New Fichemedicale;
    $f->setidp($user);
    $fs =$fRepo->findByTherapistId($user);
    $form=$this->createform(FicheType::class,$f);
    $form->handleRequest($request);
    if ($form->isSubmitted()&& $form->isValid()){
    $em->persist($f);
    $em->flush();
    return $this->redirectToRoute('listaddconsultation');
    }
    return $this->render('fiche/listadd.html.twig',['formB' =>$form->createView(),'fiches'=>$fs]);
    }


    #[Route('/addfiche/{id}', name: 'addfiche')]
    public function addFiche($id,EntityManagerInterface $em,FichemedicaleRepository $fRepo, Request $request,Security $security,UserRepository $urepo): Response
    {$user = $security->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
    $p=$urepo->find($id);
    $f =New Fichemedicale;
    $f->setidp($user);
    $f->setIdT($p);
    $fs =$fRepo->findByTherapistId($user);
    $form=$this->createform(FicheType::class,$f);
    $form->handleRequest($request);
    if ($form->isSubmitted()&& $form->isValid()){
    $em->persist($f);
    $em->flush();
    return $this->redirectToRoute('Listaddconsultation1');
    }
    return $this->render('fiche/add.html.twig',['formB' =>$form->createView(),'fiches'=>$fs]);
    }



    #[Route('/listaddfiche1', name: 'listaddfiche1')]
public function ListaddFiche1(EntityManagerInterface $em, FichemedicaleRepository $fRepo, Request $request, Security $security, PaginatorInterface $paginatorInterface): Response
{
    $user = $security->getUser();
    if (!$user) {
        $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
        return $this->redirectToRoute('app_login');
    }
    
    // Fetch all fiches except those with ID 0
    $fs = $fRepo->findAllExcept0();

    // Paginate the results
    $fiches = $paginatorInterface->paginate(
        $fs,
        $request->query->getInt('page', 1),
        4
    );
    return $this->render('fiche/list.html.twig', ['fiches' => $fiches]);
}



    #[Route('/editfiche/{id}', name: 'edit_fiche')]
    public function editFiche($id, FicheMedicaleRepository $frepo, Request $request, EntityManagerInterface $em,Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
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
    public function deletefiche($id, FicheMedicaleRepository $arepo, ManagerRegistry $doctrine,Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
    $em = $doctrine->getManager();
    $f = $arepo->find($id);
    if (!$f) {
        return new Response("fiche introuvable", Response::HTTP_NOT_FOUND);
    }
    $em->remove($f);
    $em->flush();
    return $this->redirectToRoute('listaddfiche');    
}

#[Route('/ficheMedicaleOrderedByDateCreation', name: 'fiche_medicale_ordered_by_date_creation')]
public function ficheMedicaleOrderedByDateCreation(FicheMedicaleRepository $ficheMedicaleRepository,Security $security): Response
{
    $user = $security->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
    $fiches = $ficheMedicaleRepository->findAllFicheMedicaleOrderedByDateCreation();
    return $this->render('fiche/orderbydate.html.twig', [
        'fiches' => $fiches,
    ]);
}

#[Route('/ficheMedicaleOrderedByDateCreation1', name: 'fiche_medicale_ordered_by_date_creation1')]
public function ficheMedicaleOrderedByDateCreation1(FicheMedicaleRepository $ficheMedicaleRepository,Security $security): Response
{
    $user = $security->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
    $fiches = $ficheMedicaleRepository->findAllFicheMedicaleOrderedByDateCreation();
    return $this->render('fiche/orderbydate1.html.twig', [
        'fiches' => $fiches,
    ]);
}
#[Route('/searchfichebetweendate', name: 'search_fichedate')]
    public function searchConsultation(Request $request, FichemedicaleRepository $ficheMedicaleRepository,Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $startDate1 = $request->query->get('start_date1');
        $endDate1 = $request->query->get('end_date1');
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        $startDate1 = new DateTime($startDate1);
        $endDate1 = new DateTime($endDate1);
        $fiches = $ficheMedicaleRepository->findFichesBetweenDates($startDate, $endDate,$startDate1, $endDate1);
        if (!$fiches) {
            return new Response('Fiche not found.', Response::HTTP_NOT_FOUND);
        }
        return $this->render('fiche/searchdate.html.twig', [
            'fiches' => $fiches,
        ]);
    }
    #[Route('/searchfichebetweendate1', name: 'search_fichedate1')]
    public function searchConsultation1(Request $request, FichemedicaleRepository $ficheMedicaleRepository,Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $startDate1 = $request->query->get('start_date1');
        $endDate1 = $request->query->get('end_date1');
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        $startDate1 = new DateTime($startDate1);
        $endDate1 = new DateTime($endDate1);
        $fiches = $ficheMedicaleRepository->findFichesBetweenDates($startDate, $endDate,$startDate1, $endDate1);
        if (!$fiches) {
            return new Response('Fiche not found.', Response::HTTP_NOT_FOUND);
        }
        return $this->render('fiche/searchdate1.html.twig', [
            'fiches' => $fiches,
        ]);
    }







    #[Route('/export-pdf/{id}', name: 'app_generer_pdf_historiquee')]
    public function exportPdf($id, FichemedicaleRepository $ficheMedicaleRepository,Security $security): Response
{
    $user = $security->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
    // Load the Fiche from the repository
    $fiche = $ficheMedicaleRepository->find($id);

    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    
    // Create Dompdf instance
    $dompdf = new Dompdf($pdfOptions);
    
    // Set the base path for assets
    $dompdf->setBasePath('C:\Users\brahe\ijanahkiw\public');
    
    // Render the HTML
    $html = $this->renderView('fiche/pdf.html.twig', ['fiche' => $fiche]);
    
    // Load HTML content into Dompdf
    $dompdf->loadHtml($html);
    
    // Set paper size and orientation
    $dompdf->setPaper('A4', 'portrait');
    
    // Render the PDF
    $dompdf->render();
    
    // Set filename for download
    $filename = 'fiches_list.pdf';
    
    // Create response object with PDF content
    $response = new Response($dompdf->output());
    
    // Set headers for PDF download
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
    
    return $response;
}

    #[Route('/generate_qr_code', name: 'generate_qr_code', methods: ['POST'])]
    public function generateQrCode(Request $request,Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
        $text = $request->request->get('text');
        $qrCode = QrCode::create($text)
            ->setSize(600)
            ->setMargin(40)
            ->setForegroundColor(new Color(0, 0, 128)) // Dark blue foreground color
            ->setBackgroundColor(new Color(153, 204, 255))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High); // Set error correction level to HIGH
        $label = Label::create("IJA NAHKIW")
            ->setTextColor(new Color(255, 0, 0)) // Red text color
            ->setAlignment(LabelAlignment::Left); // Align label to left
        $writer = new PngWriter();
        $result = $writer->write($qrCode, label: $label);
        return new Response($result->getString(), Response::HTTP_OK, ['Content-Type' => $result->getMimeType()]);
    }






    #[Route('/attribuerfiche/{id}', name: 'attribuerfiche')]
    public function attribuerfiche(EntityManagerInterface $em,$id,Request $request, FichemedicaleRepository $ficheMedicaleRepository,Security $security,ConsultationRepository $crepo): Response
    {
        $user = $security->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
        $con=$crepo->find($id);
        $p=$con->getIdp();
        $fiche=$ficheMedicaleRepository->findByTherapistAndPatientId($user, $p);
        $con->setFichemedicale($fiche);
        $em->flush();
        return $this->redirectToRoute('Listaddconsultation1');
    }
}
   
