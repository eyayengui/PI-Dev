<?php



// src/Controller/ChatbotController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ChatService;
use Symfony\Component\HttpFoundation\Response;

class ChatbotController extends AbstractController
{
    private $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * @Route("/chat", name="chat")
     */
    public function chat(): Response
    {
        return $this->render('questionnaire/chat.html.twig');
    }

    /**
     * @Route("/chat/message", name="chat_message", methods={"POST"})
     */
    public function chatMessage(Request $request): Response
    {
        $message = $request->request->get('message', '');
        $response = $this->chatService->getResponseForMessage($message);

        return $this->json(['response' => $response]);
    }
}

///////////////////// TENSOOOOOOOOOOOOOOOOOOOOOOORFLOOOOOW MODEEEEEL ////////////////////////////
// // src/Controller/ChatbotController.php

// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Process\Process;
// use Symfony\Component\Process\Exception\ProcessFailedException;

// class ChatbotController extends AbstractController
// {
//     /**
//      * @Route("/chatbot", name="chatbot", methods={"POST"})
//      */

//      public function chat(Request $request): Response
//     {
//     $userInput = $request->request->get('input');
//     $process = new Process([
//         'C:/Users/Tifa/anaconda3/envs/tensorflow-development/python.exe', // Path to the Python executable in your environment
//         'C:/Users/Tifa/Downloads/chat/script.py',
//         $userInput,
//          // Use 'cmd /c' to run the commands in sequence
//         // 'cmd', '/c', 
//         // '"C:/Users/Tifa/anaconda3/Scripts/activate" tensorflow-development &&',
//         // 'python', // Now you can just use 'python' since the environment is activated
//         // 'C:/Users/Tifa/Downloads/chat/script.py',
//         // $userInput,
//     ]);
    
    
//     $process->run();

//         // Debugging (commented out for now)
//     // echo "Output: " . $process->getOutput();
//     // echo "Error Output: " . $process->getErrorOutput();

//     if (!$process->isSuccessful()) {
//         throw new ProcessFailedException($process);
//     }

//     $response = $process->getOutput();

//     return $this->json([
//         'response' => $response,
//     ]);

//     }

//     // public function chat(Request $request): Response
//     // { 
//         // $userInput = $request->request->get('input');

//         // $process = new Process([
//         //     'python',
//         //     'C:/Users/Tifa/Downloads/chat/script.py',
//         //     $userInput,
//         // ]);
//         // $process->run();

//         // if (!$process->isSuccessful()) {
//         //     throw new ProcessFailedException($process);
//         // }

//         // $response = $process->getOutput();

//         // return $this->json([
//         //     'response' => $response,
//         // ]);
        
//     // }
//     // src/Controller/ChatbotController.php

// // Add this method to your ChatbotController

//     /**
//      * @Route("/chatbot", name="chatbot_interface", methods={"GET"})
//      */
//     public function index(): Response
//     {
//         return $this->render('questionnaire/chat.html.twig');
//     }

// }
