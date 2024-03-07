<?php
// src/Service/ChatbotService.php

namespace App\Service;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ChatbotService
{
    public function getResponse(string $userInput): string
    {
        $scriptPath = '/path/to/your/python/script.py';
        $process = new Process(['python', $scriptPath, $userInput]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }
}
