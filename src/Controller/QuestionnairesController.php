<?php

namespace App\Controller;
use App\Entity\Proposition;
use App\Entity\Question;
use App\Entity\Answer;

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
    #[Route('/{questionnaireId}/question/{questionIndex}', name: 'questionnaire_show_question')]
    public function showQuestion(Request $request, EntityManagerInterface $entityManager, int $questionnaireId, int $questionIndex = 0): Response
    {
        $questionnaire = $entityManager->getRepository(Questionnaires::class)->find($questionnaireId);
        if (!$questionnaire) {
            throw $this->createNotFoundException('Questionnaire not found.');
        }

        $questions = $questionnaire->getQuestions();

        if ($questionIndex < 0 || $questionIndex >= count($questions)) {
            // Rediriger vers une page de fin ou de résumé du questionnaire
            return $this->redirectToRoute('questionnaire_complete', ['questionnaireId' => $questionnaireId]);
        }

        $question = $questions[$questionIndex];

        return $this->render('questionnaires/afficher.html.twig', [
            'questionnaire' => $questionnaire,
            'question' => $question,
            'questionIndex' => $questionIndex,
            'totalQuestions' => count($questions),
        ]);
    }
    // #[Route('/question/{questionId}/answer', name: 'questionnaire_answer')]
    // public function answer(Request $request, EntityManagerInterface $entityManager, int $questionId): Response
    // {
    //     $propositionId = $request->request->get('propositionId');
    //     $question = $entityManager->getRepository(Question::class)->find($questionId);
    //     $proposition = $entityManager->getRepository(Proposition::class)->find($propositionId);
    
    //     if (!$question || !$proposition) {
    //         throw $this->createNotFoundException('Question ou proposition introuvable.');
    //     }
    
    //     $reponse = new Answer();
    //     $reponse->setQuestion($question);
    //     $reponse->setPropositionChoisie($proposition);
    //     // Assumer que $this->getUser() retourne l'utilisateur actuel (Patient)    
    //     $entityManager->persist($reponse);
    //     $entityManager->flush();
    
    //     // Rediriger vers la question suivante ou vers un résumé à la fin
    //     return $this->redirectToRoute('next_question_or_summary');
    // }

    #[Route('/{questionnaireId}/question/{questionIndex}', name: 'questionnaire_show_question', methods: ['GET', 'POST'])]
    public function showQuestio(Request $request, EntityManagerInterface $entityManager, int $questionnaireId, int $questionIndex = 0): Response
    {
        $questionnaire = $entityManager->getRepository(Questionnaires::class)->find($questionnaireId);
        if (!$questionnaire) {
            throw $this->createNotFoundException('Questionnaire not found.');
        }
        $questions = $questionnaire->getQuestions();
        if ($questionIndex < 0 || $questionIndex >= count($questions)) {
            // Rediriger vers une page de fin ou de résumé du questionnaire
            return $this->redirectToRoute('questionnaire_complete', ['questionnaireId' => $questionnaireId]);
        }
        // Récupération de l'objet Question actuel basé sur $questionIndex
        $question = $questions[$questionIndex];
        
        if ($request->isMethod('POST')) {
            $propositionId = $request->request->get('propositionId');
            $proposition = $entityManager->getRepository(Proposition::class)->find($propositionId);
            if (!$proposition) {
                throw $this->createNotFoundException('Proposition introuvable.');
            }
            //$question = $entityManager->getRepository(Question::class)->find($questionId);


// Création de la nouvelle réponse
        $answer = new Answer();
        $answer->setQuestion($question);
        $answer->setPropositionChoisie($proposition);

// Optionnel : Associer l'utilisateur actuel si nécessaire
// $answer->setUser($this->getUser());

// Persister et enregistrer la réponse
        $entityManager->persist($answer);
        $entityManager->flush();
            $nextIndex = $questionIndex + 1;
            if ($nextIndex < count($questions)) {
                return $this->redirectToRoute('questionnaire_show_question', ['questionnaireId' => $questionnaireId, 'questionIndex' => $nextIndex]);
            } else {
                // Rediriger vers une page de fin ou de résumé du questionnaire
                return $this->redirectToRoute('questionnaire_complete', ['questionnaireId' => $questionnaireId]);
            }
        }

        if ($questionIndex < 0 || $questionIndex >= count($questions)) {
            return $this->redirectToRoute('questionnaire_complete', ['questionnaireId' => $questionnaireId]);
        }

        $question = $questions[$questionIndex];

        return $this->render('questionnaires/afficher.html.twig', [
            'questionnaire' => $questionnaire,
            'question' => $question,
            'questionIndex' => $questionIndex,
            'totalQuestions' => count($questions),
        ]);
    }

}
