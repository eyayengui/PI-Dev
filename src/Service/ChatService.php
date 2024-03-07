<?php
// src/Service/ChatService.php

namespace App\Service;

class ChatService
{
    private $intents = [
        "greeting" => [
            "patterns" => ["hi", "hey", "is anyone there?", "hi there", "hello", "hey there", "howdy", "hola", "bonjour", "konnichiwa", "guten tag", "ola", "how are you?", "is anyone there?", "hello", "good day", "what's up", "how are ya", "heyy", "whatsup"],
            "responses" => ["Hello there. Tell me how are you feeling today?", "Hi there. What brings you here today?", "Hi there. How are you feeling today?", "Great to see you. How do you feel currently?", "Hello there. Glad to see you're back. What's going on in your world right now?"]
        ],
        "goodbye" => [
            "patterns" => ["bye", "see you later", "goodbye", "au revoir", "sayonara", "ok bye", "bye then", "fare thee well", "cya", "see you", "bye bye", "see you later", "goodbye", "i am leaving", "bye", "have a good day", "talk to you later", "ttyl", "i got to go", "gtg"],
            "responses" => ["See you later.", "Have a nice day.", "Bye! Come back again.", "I'll see you soon."]
        ],
        "thanks" => [
            "patterns" => ["thanks", "thank you", "thank you very much", "okk", "okie", "nice work", "well done", "good job", "thank you", "its ok", "thanks", "good work", "ok", "okay"],
            "responses" => [ "Any time!", "My pleasure", "You're most welcome!"]
        ],
        "creator" => [
            "patterns" => ["team","what is the name of your developers", "what is the name of your creators", "who created you", "your developers", "your creators", "who are your developers", "developers", "you are made by", "who made you", "who designed you"],
            "responses" => ["Webaholic Team"]
        ],
        "help" => [
            "patterns" => ["Could you help me?", "give me a hand please", "Can you help?", "I need a therapist", "i need consultation", "What can you do for me?", "I need support", "I need help", "Support me please"],
            "responses" => ["Please seek Help by contacting our green phone 9152987821.", "Sure. Tell me how can I assist you", "Tell me your problem so that I can assist you", "Yes, sure. How can I help you?"]
        ],
        "suicide" => [
            "patterns" => ["I want to kill myself", "I've thought about killing myself.", "I want to die", "I am going to kill myself", "I am going to commit suicide"],
            "responses" => ["I'm very sorry to hear that but you have so much to look forward to. Please contacte our green phone 9152987821."]
        ],
        "default" => [
            "patterns" => ["exams", "friends", "relationship", "boyfriend", "girlfriend", "family", "money", "financial problems"],
            "responses" => ["Oh I see. Tell me more", "I see. What else?", "Tell me more about it.", "Oh okay. Why don't you tell me more about it?", "I'm listening. Tell me more."]
        ],    
        "sad" => [
            "patterns" => ["I am feeling lonely", "I am so lonely", "I feel down", "I feel sad", "I am sad", "I feel so lonely", "I feel empty", "I don't have anyone"],
            "responses" => ["I'm sorry to hear that. I'm here for you.  So, tell me why do you think you're feeling this way?", "I'm here for you. Could you tell me why you're feeling this way?", "Why do you think you feel this way?", "How long have you been feeling this way?"]
        ],
        "stressed" => [
            "patterns" => ["I am so stressed out", "I am so stressed", "I feel stuck", "I still feel stressed", "I am so burned out"],
            "responses" => ["What do you think is causing this?", "Take a deep breath and gather your thoughts. Go take a walk if possible. Stay hydrated", "Give yourself a break. Go easy on yourself.", "I am sorry to hear that. What is the reason behind this?"]
        ],
        
        "depressed" => [
            "patterns" => ["I can't take it anymore", "I am so depressed", "I think I'm depressed.", "I have depression"],
            "responses" => [" You're going to be okay.","Sometimes when we are depressed, it is hard to care about anything. It can be hard to do the simplest of things. Give yourself time to heal."]
        ],
        
        "happy" => [
            "patterns" => ["I feel great today.", "I am happy.", "I feel happy.", "I'm good.", "cheerful", "I'm fine", "I feel ok"],
            "responses" => ["That's great to hear. I'm glad you're feeling this way.", "Oh, I see. That's great.", "Did something happen which made you feel this way?"]
        ],
        "casual" => [
            "patterns" => ["Oh I see.", "ok", "okay", "nice", "Whatever", "K", "Fine", "yeah", "yes", "no", "not really"],
            "responses" => ["Let's discuss further why you're feeling this way.", "How were you feeling last week?", "I'm listening. Please go on.", "Tell me more.", "Can you elaborate on that?", "Come on, elucidate your thoughts."]
        ],

            // Add the rest of your tags here
        ];
    
    // After defining all your intents, you can use this array in your ChatService or anywhere suitable within your Symfony project.
    

    public function getResponseForMessage($message)
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
}
