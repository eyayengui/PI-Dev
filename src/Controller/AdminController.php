<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
use App\Form\UserType;
use App\Repository\UserRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class AdminController extends AbstractController
{
    #[Route('/index', name: 'app_admin_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, PaginatorInterface $paginatorInterface,Request $request): Response
    {
        $data = $userRepository->findAll();
        $users = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page',1),
            9
        );
        $nbtherapeutes = $userRepository->countTherapists();
        $nbpatients = $userRepository->countPatients();
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable([
        ['Status', 'Count'],
        ['Confirmed', $nbtherapeutes],
        ['Unconfirmed', $nbpatients],
    ]);

    // Set chart options
    $pieChart->getOptions()->setTitle('utilisateurs');
    $pieChart->getOptions()->setHeight(500);
    $pieChart->getOptions()->setWidth(900);
    $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
    $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
    $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
    $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
    $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'piechart' => $pieChart,
        ]);
    }

    #[Route('/listtherap', name: 'app_therap_index', methods: ['GET'])]
    public function indext(UserRepository $userRepository, PaginatorInterface $paginatorInterface,Request $request): Response
    {
        $data = $this->findAllTherapists($userRepository);
        $users = $paginatorInterface->paginate(
            $data,
            $request->query->getInt('page',1),
            9
        );
        return $this->render('admin/therapists.html.twig', [
            'users' => $users,
        ]);
    }

        public function findAllTherapists(UserRepository $userRepository): array
    {
        $users = $userRepository->findAll(); // Retrieve all users

        $therapists = [];
        foreach ($users as $user) {
            if (in_array('ROLE_THERAPEUTE', $user->getRoles(), true)) { // Ensure strict comparison
                $therapists[] = $user;
            }
        }

        return $therapists;
    }



    #[Route('/new', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/show.html.twig', [
            'user' => $user,
        ]);
    }

 
    #[Route('/{id}/edit', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->remove('plainPassword');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    

    #[Route('/delete/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/ban/{id}', name: 'ban_user')]
   
    public function banUser($id): Response
    {
        $currentUser = $this->getUser();
        
        
            $em = $this->getDoctrine()->getManager();
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            $user->setIsBanned(true);
            $em->flush();

            return $this->redirectToRoute('app_admin_index');
        
    }
    
     
     #[Route("/admin/unban/{id}",name:"unban_user")]
     
     public function UnbanUser($id): Response
     {
         $currentUser = $this->getUser();
         
         
             $em = $this->getDoctrine()->getManager();
             $user = $this->getDoctrine()->getRepository(User::class)->find($id);
             $user->setIsBanned(false);
             $em->flush();
 
             return $this->redirectToRoute('app_admin_index');
         
     }
    
     #[Route("/admin/verify/{id}", name:"verify_user")]
     
    public function verifyUserAccount($id): Response
    {
        $user = $this->getUser();
       
       
            $em = $this->getDoctrine()->getManager();
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            $user->setIsVerified(true);
            $em->flush();

            return $this->redirectToRoute('app_admin_index');
       
    }
    #[Route('/export-pdf', name: 'app_generer_pdf_historique')]
    public function exportPdf(): Response
    {
    // Récupérez les données à afficher dans le PDF (utilisateurs dans votre cas)
    $users = $this->getDoctrine()->getRepository(User::class)->findAll();

    // Créez une instance de Dompdf avec les options nécessaires
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');

    $dompdf = new Dompdf($pdfOptions);

    // Générez le HTML pour représenter la table d'utilisateurs
    $html = $this->renderView('admin/pdf.html.twig', ['users' => $users]);

    // Chargez le HTML dans Dompdf et générez le PDF
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Générer un nom de fichier pour le PDF
    $filename = 'user_list.pdf';

    // Streamer le PDF vers le navigateur
    $response = new Response($dompdf->output());
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

    // Retournez la réponse
    return $response;
}
}
 