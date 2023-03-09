<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;


class PdfController extends AbstractController
{
    #[Route('/admin/dashboard/generateinvoice', name: 'app_pdf')]
    public function index(): Response
    {

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
   
        $dompdf = new Dompdf($pdfOptions);



        $html = $this->renderView('dashboard/admin/pdf.html.twig', [
            'title' => "Welcome to our PDF Test"
        ]);
         $dompdf->loadHtml($html);



         $dompdf->setPaper('A4', 'portrait');

         $dompdf->render();
        $output = $dompdf->output();
        $response = new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"'
        ]);
        return $response;
    }
}
