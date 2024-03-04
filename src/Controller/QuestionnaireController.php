<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Service\HealthAnalysisService;
use App\Entity\Proposition;
use App\Entity\Questionnaire;
use App\Form\QuestionnaireType;
use App\Repository\QuestionnaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/questionnaire')]
class QuestionnaireController extends AbstractController
{
    private $healthAnalysisService;

    public function __construct(HealthAnalysisService $healthAnalysisService)
    {
        $this->healthAnalysisService = $healthAnalysisService;
    }

    #[Route('/', name: 'app_questionnaire_index', methods: ['GET'])]
    public function index(QuestionnaireRepository $questionnaireRepository): Response
    {
        return $this->render('questionnaire/index.html.twig', [
            'questionnaires' => $questionnaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_questionnaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $questionnaire = new Questionnaire();
        $form = $this->createForm(QuestionnaireType::class, $questionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($questionnaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_questionnaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('questionnaire/new.html.twig', [
            'questionnaire' => $questionnaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_questionnaire_show', methods: ['GET'])]
    public function show(Questionnaire $questionnaire): Response
    {
        return $this->render('questionnaire/show.html.twig', [
            'questionnaire' => $questionnaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_questionnaire_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'app_questionnaire_delete', methods: ['POST'])]
    public function delete(Request $request, Questionnaire $questionnaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$questionnaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($questionnaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_questionnaire_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{questionnaireId}/question/{questionIndex}', name: 'questionnaire_show_question', methods: ['GET', 'POST'])]
    public function showQuestion(Request $request, EntityManagerInterface $entityManager, int $questionnaireId, int $questionIndex = 0): Response
    {
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
}
