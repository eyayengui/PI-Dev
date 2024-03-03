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

#[Route('/ficheMedicaleOrderedByDateCreation', name: 'fiche_medicale_ordered_by_date_creation')]
public function ficheMedicaleOrderedByDateCreation(FicheMedicaleRepository $ficheMedicaleRepository): Response
{
    $fiches = $ficheMedicaleRepository->findAllFicheMedicaleOrderedByDateCreation();
    return $this->render('fiche/orderbydate.html.twig', [
        'fiches' => $fiches,
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
    public function searchConsultation1(Request $request, FichemedicaleRepository $ficheMedicaleRepository): Response
    {
       
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
    public function exportPdf($id, FichemedicaleRepository $ficheMedicaleRepository): Response
{
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
    public function generateQrCode(Request $request): Response
    {
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
}
   
