<?php

// src/Service/HealthAnalysisService.php

namespace App\Service;

use App\Entity\Answer;
use App\Entity\Questionnaire;
use Doctrine\ORM\EntityManagerInterface;

class HealthAnalysisService
{
    // public function calculateTotalScore(array $patientAnswers): int
    // {
    //     $totalScore = 0;
    //     foreach ($patientAnswers as $answer) {
    //         /** @var Answer $answer */
    //         $totalScore += $answer->getPropositionChoisie()->getScore();
    //     }
    //     return $totalScore;
    // }

    // public function analyzeHealth(int $totalScore): string
    // {
    //     if ($totalScore <= 10) {
    //         return 'Excellent Health';
    //     } elseif ($totalScore <= 20) {
    //         return 'Good Health';
    //     } elseif ($totalScore <= 30) {
    //         return 'Moderate Health';
    //     } else {
    //         return 'Poor Health';
    //     }
    // }
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function calculateTotalScore(Questionnaire $questionnaire): int
    {
        $totalScore = 0;
        foreach ($questionnaire->getQuestions() as $question) {
            // /** @var Answer $answer */
            // $totalScore += $question->getPropositionChoisie()->getScore();
            foreach ($question->getAnswers() as $answer) {
                // Assuming the answer has a score field
                $totalScore += $answer->getPropositionChoisie()->getScore();
            }
        }
        return $totalScore;
    }

    public function analyzeHealth(int $totalScore): string
    {
        if ($totalScore <= 5) {
            return 'Poor Health';
        // } elseif ($totalScore <= 5) {
        //     return 'Moderate Health';
        } else {
            return 'Good Health';
        }
    }

    public function calculateHealthStatus(int $questionnaireId): string
    {
        // Fetch the questionnaire from the database
        $questionnaire = $this->entityManager->getRepository(Questionnaire::class)->find($questionnaireId);
        if (!$questionnaire) {
            throw new \Exception('Questionnaire not found');
        }

        // Calculate the total score
        $totalScore = $this->calculateTotalScore($questionnaire);

        // Analyze the health status
        return $this->analyzeHealth($totalScore);
    }
}

?>