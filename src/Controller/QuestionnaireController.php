<?php

namespace App\Controller;
use Knp\Snappy\Pdf;
use App\Entity\Answer;
use App\Entity\Proposition;
use App\Entity\Questionnaire;
use App\Form\QuestionnaireType;
use App\Repository\QuestionnaireRepository;
use App\Service\HealthAnalysisService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Service\FastAPIService;

class QuestionnaireController extends AbstractController
{
    private $security;
    private $healthAnalysisService;
    private $snappy;
    private HttpClientInterface $client;
    private $fastAPIService;


    public function __construct(Pdf $snappy, Security $security, HealthAnalysisService $healthAnalysisService, HttpClientInterface $client, FastAPIService $fastAPIService)
    {   $this->snappy = $snappy;
        $this->security = $security;
        $this->healthAnalysisService = $healthAnalysisService;
        $this->client = $client;
        $this->fastAPIService = $fastAPIService;
    }

    
    #[Route('/questionnaire', name: 'app_questionnaire_index', methods: ['GET'])]
    public function index(QuestionnaireRepository $questionnaireRepository, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            // Rediriger vers la page de connexion ou afficher un message d'erreur
            $this->addFlash('error', 'Vous devez être connecté pour ajouter une publication.');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('questionnaire/index.html.twig', [
            'questionnaires' => $questionnaireRepository->findAll(),
        ]);
    }

    #[Route('questionnaire/new', name: 'app_questionnaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            // Rediriger vers la page de connexion ou afficher un message d'erreur
            $this->addFlash('error', 'Vous devez être connecté pour ajouter une publication.');
            return $this->redirectToRoute('app_login');
        }
        $questionnaire = new Questionnaire();
        $form = $this->createForm(QuestionnaireType::class, $questionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser(); // Ensure you have a way to get the current user
            $questionnaire->setIDUser($user);
            $entityManager->persist($questionnaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_questionnaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('questionnaire/new.html.twig', [
            'questionnaire' => $questionnaire,
            'form' => $form,
        ]);
    }

    #[Route('questionnaire/{id}', name: 'app_questionnaire_show', methods: ['GET'])]
    public function show(Questionnaire $questionnaire): Response
    {
        return $this->render('questionnaire/show.html.twig', [
            'questionnaire' => $questionnaire,
        ]);
    }

    #[Route('questionnaire/{id}/edit', name: 'app_questionnaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Questionnaire $questionnaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionnaireType::class, $questionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_questionnaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('questionnaire/edit.html.twig', [
            'questionnaire' => $questionnaire,
            'form' => $form,
        ]);
    }

    #[Route('questionnaire/{id}', name: 'app_questionnaire_delete', methods: ['POST'])]
    public function delete(Request $request, Questionnaire $questionnaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$questionnaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($questionnaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_questionnaire_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('questionnaire/{questionnaireId}/question/{questionIndex}', name: 'questionnaire_show_question', methods: ['GET', 'POST'])]
    public function showQuestion(Request $request, EntityManagerInterface $entityManager, int $questionnaireId, int $questionIndex = 0): Response
    {   
        $user = $this->security->getUser();
        if (!$user) {
            // Redirect to the login page or show an error message
            $this->addFlash('error', 'Vous devez être connecté pour répondre au questionnaire.');
            return $this->redirectToRoute('app_login');}
        // Retrieve the questionnaire based on the ID

        $questionnaire = $entityManager->getRepository(Questionnaire::class)->find($questionnaireId);
        if (!$questionnaire) {
            throw $this->createNotFoundException('Questionnaire not found.');
        }
    
        // Retrieve the questions for the questionnaire
        $questions = $questionnaire->getQuestions();
        if ($questionIndex < 0 || $questionIndex >= count($questions)) {
            // Redirect to a summary page if the question index is out of bounds
            return $this->redirectToRoute('questionnaire_summary', ['questionnaireId' => $questionnaireId]);
        }
    
        // Retrieve the current question based on the question index
        $question = $questions[$questionIndex];
    
        // Handle form submission
        if ($request->isMethod('POST')) {
            // Retrieve the selected proposition ID from the request
            $propositionId = $request->request->get('propositionId');
            // Find the proposition based on the ID
            $proposition = $entityManager->getRepository(Proposition::class)->find($propositionId);
            if (!$proposition) {
                throw $this->createNotFoundException('Proposition not found.');
            }
    
            // Create a new Answer entity and associate it with the question and proposition
            $answer = new Answer();
            $answer->setIDUser($user);
            $answer->setQuestion($question);
            $answer->setPropositionChoisie($proposition);
    
            // Persist and flush the answer entity
            $entityManager->persist($answer);
            $entityManager->flush();
            $nextQuestionIndex = $questionIndex + 1;
        if ($nextQuestionIndex < count($questions)) {
            // There are more questions, redirect to the next question
            return $this->redirectToRoute('questionnaire_show_question', [
                'questionnaireId' => $questionnaireId,
                'questionIndex' => $nextQuestionIndex,
            ]);
        } else {
            // No more questions, redirect to summary or perform prediction
            return $this->redirectToRoute('questionnaire_summary', [
                'questionnaireId' => $questionnaireId,
            ]);
        }
    }

    return $this->render('questionnaire/afficher.html.twig', [
        'questionnaire' => $questionnaire,
        'question' => $question,
        'questionIndex' => $questionIndex,
        'totalQuestions' => count($questions),
    ]);
}
    
//     #[Route('/{questionnaireId}/summary', name: 'questionnaire_summary', methods: ['GET'])]
//     public function summary(int $questionnaireId): Response
// {
//     // Calculate the health status based on the questionnaire ID
//     $healthStatus = $this->healthAnalysisService->calculateHealthStatus($questionnaireId);

//     // Render the summary view
//     return $this->render('questionnaire/summary.html.twig', [
//         'healthStatus' => $healthStatus,
//         'questionnaireId' => $questionnaireId,
//     ]);

// }
private function getQuestionnaireFeatures(int $questionnaireId): array
{
    $questionnaire = $this->getDoctrine()->getRepository(Questionnaire::class)->find($questionnaireId);
    if (!$questionnaire) {
        throw $this->createNotFoundException('Questionnaire not found.');
    }

    $features = [];
    foreach ($questionnaire->getQuestions() as $question) {
        foreach ($question->getAnswers() as $answer) {
            // Append the score of each proposition chosen in answers to the features array
            $features[] = $answer->getPropositionChoisie()->getScore();
        }
    }

    // Ensure the features array matches the expected size by the model
    // Pad the array with zeros if necessary to match the expected feature length by your model
    // Adjust the expected feature length according to your model's requirements

    return $features;
}
#[Route('questionnaire/{questionnaireId}/summary', name: 'questionnaire_summary', methods: ['GET'])]
public function summary(int $questionnaireId): Response
{
$questionnaire = $this->getDoctrine()->getRepository(Questionnaire::class)->find($questionnaireId);
    if (!$questionnaire) {
        throw $this->createNotFoundException('The questionnaire does not exist.');
    }
    $features = $this->getQuestionnaireFeatures($questionnaireId);
    $result = $this->fastAPIService->predict($features);

    if ($result['success']) {
        $healthStatus = $result['prediction'];
    } else {
        $healthStatus = 'unknown'; // Handle error or provide default
    }
    return $this->render('questionnaire/summary.html.twig',  [
        'questionnaireId' => $questionnaireId,
        'healthStatus' => $healthStatus,   ]);
}

    #[Route('questionnaire/{questionnaireId}/pdf', name: 'questionnaire_generate_pdf', methods: ['GET'])]
    public function generateQuestionnairePdf(int $questionnaireId): Response
    {
        $questionnaire = $this->getDoctrine()->getRepository(Questionnaire::class)->find($questionnaireId);
        if (!$questionnaire) {
            throw $this->createNotFoundException('The questionnaire does not exist.');
        }

        // Assuming you have a method to calculate the total score and analyze health
        $healthStatus = $this->healthAnalysisService->calculateTotalScore($questionnaire);

        // Generate the HTML for the PDF
        $html = $this->renderView('questionnaire/pdf_summary.html.twig', [
            'questionnaire' => $questionnaire,
            'healthStatus' => $healthStatus,
        ]);

        // Generate PDF from the HTML
        $pdfContent = $this->snappy->getOutputFromHtml($html);

        // Return the PDF as a response
        return new Response(
            $pdfContent,
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="questionnaire_summary.pdf"'
            ]
        );
    }

    
    #[Route('questionnaire/{questionnaireId}/statistics', name: 'questionnaire_statistics')]
    public function questionnaireStatistics(QuestionnaireRepository $questionnaireRepository): Response
    {
        $data = $questionnaireRepository->findAllQuestionnairesWithUserCount();
        // In your Symfony controller method
        $questionnaireIds = [];
        $userCounts = [];
    
        // Loop through the data fetched from the repository
        foreach ($data as $item) {
            $questionnaireIds[] = $item['questionnaireId']; // Adjust based on actual structure
            $userCounts[] = $item['userCount']; // Adjust based on actual structure
        }
    
        // Pass these arrays to Twig
        return $this->render('questionnaire/statistics.html.twig', [
            'questionnaireIds' => $questionnaireIds,
            'userCounts' => $userCounts,
        ]);

}
}