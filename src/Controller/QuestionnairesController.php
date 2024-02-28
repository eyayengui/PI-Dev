<?php

namespace App\Controller;
use App\Entity\Proposition;
use App\Entity\Question;
use App\Entity\Answer;

use App\Service\AiModelService;
use App\Service\HealthAnalysisService;

use App\Entity\Questionnaires;
use App\Form\QuestionnairesType;
use App\Repository\QuestionnairesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/questionnaires')]
class QuestionnairesController extends AbstractController
{
    private $healthAnalysisService;

    public function __construct(HealthAnalysisService $healthAnalysisService)
    {
        $this->healthAnalysisService = $healthAnalysisService;
    }
    #[Route('/', name: 'app_questionnaires_index', methods: ['GET'])]
    public function index(QuestionnairesRepository $questionnairesRepository): Response
    {
        return $this->render('questionnaires/index.html.twig', [
            'questionnaires' => $questionnairesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_questionnaires_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $questionnaire = new Questionnaires();
        $form = $this->createForm(QuestionnairesType::class, $questionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($questionnaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_questionnaires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('questionnaires/new.html.twig', [
            'questionnaire' => $questionnaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_questionnaires_show', methods: ['GET'])]
    public function show(Questionnaires $questionnaire): Response
    {
        return $this->render('questionnaires/show.html.twig', [
            'questionnaire' => $questionnaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_questionnaires_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Questionnaires $questionnaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionnairesType::class, $questionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_questionnaires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('questionnaires/edit.html.twig', [
            'questionnaire' => $questionnaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_questionnaires_delete', methods: ['POST'])]
    public function delete(Request $request, Questionnaires $questionnaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$questionnaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($questionnaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_questionnaires_index', [], Response::HTTP_SEE_OTHER);
    }
    // #[Route('/{questionnaireId}/question/{questionIndex}', name: 'questionnaire_show_question')]
    // public function showQuestion(Request $request, EntityManagerInterface $entityManager, int $questionnaireId, int $questionIndex = 0): Response
    // {
    //     $questionnaire = $entityManager->getRepository(Questionnaires::class)->find($questionnaireId);
    //     if (!$questionnaire) {
    //         throw $this->createNotFoundException('Questionnaire not found.');
    //     }

    //     $questions = $questionnaire->getQuestions();

    //     if ($questionIndex < 0 || $questionIndex >= count($questions)) {
    //         // Rediriger vers une page de fin ou de résumé du questionnaire
    //         return $this->redirectToRoute('questionnaire_complete', ['questionnaireId' => $questionnaireId]);
    //     }

    //     $question = $questions[$questionIndex];

    //     return $this->render('questionnaires/afficher.html.twig', [
    //         'questionnaire' => $questionnaire,
    //         'question' => $question,
    //         'questionIndex' => $questionIndex,
    //         'totalQuestions' => count($questions),
    //     ]);
    // }
        //THIS IS THE ONE
    // #[Route('/{questionnaireId}/question/{questionIndex}', name: 'questionnaire_show_question', methods: ['GET', 'POST'])]
    // public function showQuestio(Request $request, EntityManagerInterface $entityManager, int $questionnaireId, int $questionIndex = 0): Response
    // {
    //     $questionnaire = $entityManager->getRepository(Questionnaires::class)->find($questionnaireId);
    //     if (!$questionnaire) {
    //         throw $this->createNotFoundException('Questionnaire not found.');
    //     }
    //     $questions = $questionnaire->getQuestions();
    //     if ($questionIndex < 0 || $questionIndex >= count($questions)) {
    //         // Rediriger vers une page de fin ou de résumé du questionnaire
    //         return $this->redirectToRoute('questionnaire_complete', ['questionnaireId' => $questionnaireId]);
    //     }
    //     // Récupération de l'objet Question actuel basé sur $questionIndex
    //     $question = $questions[$questionIndex];
        
    //     if ($request->isMethod('POST')) {
    //         $propositionId = $request->request->get('propositionId');
    //         $proposition = $entityManager->getRepository(Proposition::class)->find($propositionId);
    //         if (!$proposition) {
    //             throw $this->createNotFoundException('Proposition introuvable.');
    //         }
    //         //$question = $entityManager->getRepository(Question::class)->find($questionId);


    //      // Création de la nouvelle réponse
    //     $answer = new Answer();
    //     $answer->setQuestion($question);
    //     $answer->setPropositionChoisie($proposition);

    //     // Optionnel : Associer l'utilisateur actuel si nécessaire
    //     // $answer->setUser($this->getUser());

    //     // Persister et enregistrer la réponse
    //     $entityManager->persist($answer);
    //     $entityManager->flush();
    //     $totalScore = $this->healthAnalysisService->calculateTotalScore($questionnaire->getAnswers());
    //     $healthStatus = $this->healthAnalysisService->analyzeHealth($totalScore);

    //         $nextIndex = $questionIndex + 1;
    //         if ($nextIndex < count($questions)) {
    //             return $this->redirectToRoute('questionnaire_show_question', ['questionnaireId' => $questionnaireId, 'questionIndex' => $nextIndex]);
    //         } else {
    //             // Rediriger vers une page de fin ou de résumé du questionnaire
    //             return $this->redirectToRoute('questionnaire_complete', ['questionnaireId' => $questionnaireId]);
    //         }
    //     }

    //     if ($questionIndex < 0 || $questionIndex >= count($questions)) {
    //         return $this->redirectToRoute('questionnaire_complete', ['questionnaireId' => $questionnaireId]);
    //     }

    //     $question = $questions[$questionIndex];

    //     return $this->render('questionnaires/afficher.html.twig', [
    //         'questionnaire' => $questionnaire,
    //         'question' => $question,
    //         'questionIndex' => $questionIndex,
    //         'totalQuestions' => count($questions),
    //     ]);
    // }
    
    #[Route('/{questionnaireId}/question/{questionIndex}', name: 'questionnaire_show_question', methods: ['GET', 'POST'])]
    public function showQuestion(Request $request, EntityManagerInterface $entityManager, int $questionnaireId, int $questionIndex = 0): Response
    {
        // Retrieve the questionnaire based on the ID
        $questionnaire = $entityManager->getRepository(Questionnaires::class)->find($questionnaireId);
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
            $answer->setQuestion($question);
            $answer->setPropositionChoisie($proposition);
    
            // Persist and flush the answer entity
            $entityManager->persist($answer);
            $entityManager->flush();
    
            // Calculate the total score
            $healthStatus = $this->healthAnalysisService->calculateTotalScore($questionnaire);
    
            // Analyze the health based on the total score
            //$healthStatus = $this->healthAnalysisService->analyzeHealth($totalScore);
    
            // Redirect to a summary page with the health status
            return $this->redirectToRoute('questionnaire_summary', [
                'questionnaireId' => $questionnaireId,
                'healthStatus' => $healthStatus,
            ]);
        }
    
        // Render the question view
        return $this->render('questionnaires/afficher.html.twig', [
            'questionnaire' => $questionnaire,
            'question' => $question,
            'questionIndex' => $questionIndex,
            'totalQuestions' => count($questions),
        ]);
    }
    
    #[Route('/{questionnaireId}/summary', name: 'questionnaire_summary', methods: ['GET'])]
    public function summary(int $questionnaireId): Response
{
    // Calculate the health status based on the questionnaire ID
    $healthStatus = $this->healthAnalysisService->calculateHealthStatus($questionnaireId);

    // Render the summary view
    return $this->render('questionnaires/summary.html.twig', [
        'healthStatus' => $healthStatus,
    ]);
}

    
    
    // private $aiModelService;

    // public function __construct(AiModelService $aiModelService)
    // {
    //     $this->aiModelService = $aiModelService;
    // }

    // #[Route('/analyze', name: 'analyze_responses', methods: ['GET', 'POST'])]
    // public function analyzeResponses(): Response
    // {
    //     // Example data for training the model
    //     $samples = [[1, 2], [2, 3], [3, 4]];
    //     $labels = ['good', 'moderate', 'poor'];

    //     // Train the model
    //     $this->aiModelService->trainModel($samples, $labels);

    //     // Example patient response to be analyzed
    //     $patientResponse = [2, 3];

    //     // Predict the health status
    //     $healthStatus = $this->aiModelService->predict($patientResponse);

    //     // Use the health status for further processing
    //     return new Response('Health status: ' . $healthStatus);
    // }

}


