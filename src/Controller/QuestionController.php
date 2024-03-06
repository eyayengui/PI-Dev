<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/question')]
class QuestionController extends AbstractController
{
    #[Route('/', name: 'app_question_index', methods: ['GET'])]
    public function index(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_question_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();

        // Créez le formulaire en utilisant Question1Type, qui inclut maintenant les propositions
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ici, les propositions sont automatiquement liées à la question grâce à Symfony Form
            // et à votre configuration Doctrine (par exemple, cascade={"persist"} sur l'entité Question)

            $entityManager->persist($question);
            // Pas besoin de persister chaque proposition individuellement grâce à la cascade

            $entityManager->flush();

            // Redirigez vers la page de votre choix après l'enregistrement
            return $this->redirectToRoute('app_question_index');
        }

        // Rendu du formulaire dans votre template Twig
        return $this->render('question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }


    #[Route('/question/{id}/edit', name: 'app_question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        // Assurez-vous que $question est déjà chargé avec ses propositions associées
        // Cela devrait être le cas si vous utilisez Doctrine et que vos relations sont correctement configurées

        // Créez le formulaire pour la question, qui inclut une collection de propositions
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Avant de persister la question, vérifiez si vous devez gérer manuellement l'association avec les propositions
            // Si vous utilisez un Data Transformer pour id_Q, cela n'est pas nécessaire

            $entityManager->flush();

            // Redirigez vers la liste des questions ou une autre page appropriée après l'enregistrement
            return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
        }

        // Rendu du formulaire d'édition dans le template Twig
        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }
    
   
#[Route('/delete/{id}', name: 'app_question_delete')]
public function deleteQuestion($id, QuestionRepository $arepo, ManagerRegistry $doctrine): Response
{

    $em=$doctrine->getManager();
    $question=$arepo->find($id) ;
    $em->remove($question) ;
    $em->flush();

return $this->redirectToRoute(('app_question_index'));
}
#[Route('/question/{id}', name: 'app_question_show')]
public function afficher(int $id, EntityManagerInterface $entityManager): Response
{
    $question = $entityManager->getRepository(Question::class)->find($id);

    if (!$question) {
        throw $this->createNotFoundException('La question demandée n\'existe pas.');
    }

    return $this->render('question/afficher.html.twig', [
        'question' => $question,
    ]);
}
}
