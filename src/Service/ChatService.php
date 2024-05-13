<?php
// src/Service/ChatService.php

namespace App\Service;

class ChatService
{
    private $intents = [
        "greeting" => [
            "patterns" => ["salut", "bonjour", "hello", "coucou","heyy"],
            "responses" => ["Salut. Qu'est-ce qui vous amène ici aujourd'hui ?","Bonjour. Comment vous sentez-vous aujourd'hui ?"]
        ],
        "goodbye" => [
            "patterns" => ["au revoir", "à plus tard", "adieu", "au revoir", "sayonara", "ok au revoir", "au revoir alors", "porte-toi bien", "à plus", "à bientôt", "bye bye", "à plus tard", "je m'en vais", "bonne journée", "à bientôt", "à tout à l'heure", "je dois y aller", "gtg"],
            "responses" => ["À plus tard.", "Bonne journée.", "Au revoir! Revenez encore.", "Je vous verrai bientôt."]
        ],
        "merci" => [
            "patterns" => ["thanks", "thank you", "thank you very much", "merci", "je vous remercie", "merci beaucoup"],
            "responses" => ["À tout moment!", "Avec plaisir", "Vous êtes le bienvenu!"]
        ],
        "creator" => [
            "patterns" =>["équipe", "quel est le nom de vos développeurs", "quel est le nom de vos créateurs", "qui vous a créé", "vos développeurs", "vos créateurs", "qui sont vos développeurs", "développeurs", "vous êtes fait par", "qui vous a fait", "qui vous a conçu"],
            "responses" => ["Équipe Webaholic"]
        ],
        "aider" => [
            "patterns" =>  ["Pouvez-vous m'aider?", "aide","donnez-moi un coup de main s'il vous plaît", "Pouvez-vous aider?", "J'ai besoin d'un thérapeute", "j'ai besoin d'une consultation", "Que pouvez-vous faire pour moi?", "J'ai besoin de soutien", "J'ai besoin d'aide", "Soutenez-moi s'il vous plaît"],
            "responses" => ["Veuillez demander de l'aide en contactant notre téléphone vert 9152987821.", "Bien sûr. Dites-moi comment puis-je vous assister", "Dites-moi votre problème afin que je puisse vous aider", "Oui, bien sûr. Comment puis-je vous aider?"]
        ],
        "suicide" => [
            "patterns" => ["Je veux me tuer", "J'ai pensé à me tuer.", "Je veux mourir", "Je vais me tuer", "Je vais me suicider"],
            "responses" => ["Veuillez contacter notre ligne verte au 9152987821 immédiatement.","Je suis très désolé de l'entendre mais vous avez tant de choses à espérer. Veuillez contacter notre téléphone vert 9152987821."]
        ],
        "default" => [
            "patterns" => ["examens", "amis", "relation", "petit ami", "petite amie", "famille", "argent", "problèmes financiers"],
            "responses" => ["Oh je vois. Dites-m'en plus", "Je vois. Quoi d'autre?", "Parlez-moi en plus.", "Oh d'accord. Pourquoi ne pas m'en dire plus?", "Je vous écoute. Parlez-moi en plus."]
        ],    
        "sad" => [
            "patterns" =>["Je me sens seul","triste" ,"Je suis tellement seul", "Je me sens mal", "Je me sens triste", "Je suis triste", "Je me sens tellement seul", "Je me sens vide", "Je n'ai personne"],
            "responses" => ["Je suis désolé de l'entendre. Je suis là pour vous. Alors, dites-moi pourquoi pensez-vous vous sentir ainsi?", "Je suis là pour vous. Pourriez-vous me dire pourquoi vous vous sentez ainsi?", "Pourquoi pensez-vous vous sentir ainsi?", "Depuis combien de temps vous sentez-vous ainsi?"]
        ],
        "stressed" => [
            "patterns" =>["Je suis tellement stressé", "stress","Je suis stressé", "Je me sens coincé", "Je me sens encore stressé", "Je suis épuisé"],
            "responses" => ["Qu'est-ce qui, selon vous, en est la cause?", "Respirez profondément et rassemblez vos pensées. Allez vous promener si possible. Restez hydraté", "Donnez-vous une pause. Allez-y doucement.", "Je suis désolé de l'entendre. Quelle en est la raison?"]
        ],
        
        "depressed" => [
            "patterns" => ["Je n'en peux plus", "Je suis tellement déprimé", "Je pense que je suis déprimé.", "J'ai une dépression"],
            "responses" => ["Vous allez vous en sortir.", "Parfois, lorsque nous sommes déprimés, il est difficile de se soucier de quoi que ce soit. Il peut être difficile de faire les choses les plus simples. Donnez-vous le temps de guérir."]
        ],
        "happy" => [
            "patterns" => ["Je me sens super aujourd'hui.","heureux", "Je suis heureux.", "Je me sens heureux.", "Je vais bien.", "joyeux", "Je vais bien", "Je me sens bien"],
            "responses" => ["C'est génial de l'entendre. Je suis content que vous vous sentiez ainsi.", "Oh, je vois. C'est super.", "Qu'est-ce qui s'est passé pour que vous vous sentiez ainsi?"]
        ],
        "casual" => [
            "patterns" => ["Peu importe", "K", "Bien", "oui", "oui", "non", "pas vraiment"],
            "responses" => ["Discutons plus en détail de pourquoi vous vous sentez ainsi.", "Comment vous sentiez-vous la semaine dernière?", "Je vous écoute. Continuez s'il vous plaît.", "Dites-m'en plus.", "Pouvez-vous élaborer cela?", "Allez, expliquez vos pensées."]
        ],

        "appointment" => [
            "patterns" => ["rendez-vous", "j'ai besoins d'un rendez-vous","rendez vous"],
            "responses" => ["Consultez notre site web pour plus d'informations."]
        ],
        ];
    
    // After defining all your intents, you can use this array in your ChatService or anywhere suitable within your Symfony project.
    
    public function getResponseForMessages($message)
    {
        $message = strtolower($message);
        foreach ($this->intents as $intent) {
            foreach ($intent["patterns"] as $pattern) {
                if (strpos($message, $pattern) !== false) {
                    // If message matches pattern, return a random response from the intent
                    return $intent["responses"][array_rand($intent["responses"])];
                }
            }
        }
        
        // Default fallback response
        return "Sorry, I do not understand. Can you phrase it differently?";
    }
    public function getResponseForMessage($message)
    {
        $message = strtolower($message);
        foreach ($this->intents as $intentKey => $intent) {
            $patternMatched = $this->getBestMatch($message, $intent["patterns"]);
            if ($patternMatched) {
                return $intent["responses"][array_rand($intent["responses"])];
            }
        }
        
        return "Sorry, I do not understand. Can you phrase it differently?";
    }

    private function getBestMatch($input, $patterns) {
        $bestMatch = null;
        $highestPercentage = 0;

        foreach ($patterns as $pattern) {
            similar_text($input, $pattern, $percent);
            if ($percent > 60) { // You can adjust the threshold here
                if ($percent > $highestPercentage) {
                    $highestPercentage = $percent;
                    $bestMatch = $pattern;
                }
            }
        }
        return $bestMatch;
    }
}
