<?php
// src/Controller/TestPdfController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfTestController extends AbstractController
{
    /**
     * @Route("/test/pdf-direct", name="test_pdf_direct")
     */
    public function directPdfTest(): Response
    {
        $outputFile = tempnam(sys_get_temp_dir(), 'pdf');
        $inputHtml = "<html><body><h1>Hello World</h1></body></html>";
        file_put_contents($outputFile . '.html', $inputHtml);

        $cmd = sprintf(
            '"C:/Program Files (x86)/wkhtmltopdf/bin/wkhtmltopdf.exe" --lowquality %s %s',
            escapeshellarg($outputFile . '.html'),
            escapeshellarg($outputFile)
        );

        exec($cmd, $output, $returnVar);

        if ($returnVar === 0 && file_exists($outputFile)) {
            return new Response(
                file_get_contents($outputFile),
                Response::HTTP_OK,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="directTest.pdf"'
                ]
            );
        } else {
            // For debugging: Output the command output and error code if PDF generation fails
            return new Response(
                sprintf("Failed to generate PDF: %s, ReturnVar: %d", implode("\n", $output), $returnVar),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}

