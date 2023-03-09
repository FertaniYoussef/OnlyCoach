<?php

namespace App\Controller;

use App\Repository\AbonnementRepository;
use App\Repository\CoachRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Request;

class PdfController extends AbstractController
{
    #[Route('/admin/dashboard/generateinvoice/?', name: 'app_pdf')]
    public function index(Request $request,ManagerRegistry $doctrine,CoachRepository $coachrepo,UserRepository $userrepo): Response
    {   

       
        $Nom = $request->query->get('Nom');
        $Prenom= $request->query->get('Prenom');
        $ammount= $request->query->get('ammount');
        $date= $request->query->get('date');
        $randomNumber = random_int(0, 10000);
        $coach=$coachrepo->find($request->query->get('coachid'));
        $id=$request->query->get('coachid');
        $user=$userrepo->find($coach->getIdUser());
        $entityManager = $doctrine->getManager();
        $entityManager->initializeObject($user);
        
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled',true);    
   
        $dompdf = new Dompdf($pdfOptions);



        $html = $this->render('dashboard/admin/pdf.html.twig', [
            'Nom' => $Nom,
            'Email' => $user->getEmail(),
            
            'Prenom' => $Prenom,
            'ammount' => $ammount,
            'date' => $date,
            'num' => $randomNumber,
        ]);
    
         $dompdf->loadHtml($html);



         $dompdf->setPaper('A4', 'portrait');

         $dompdf->render();
        $output = $dompdf->output();
        $filename = 'invoice_' . str_replace(' ', '_', $id) . '.pdf';
        $response = new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
        return $response;
    }

    #[Route('/coach/dashboard/generateinvoice/?', name: 'app_coach_invoice_pdf')]
    public function coach_pdf(Request $request,ManagerRegistry $doctrine,CoachRepository $coachrepo,UserRepository $userrepo): Response
    {   

       
        $Nom = $request->query->get('Nom');
        $Prenom= $request->query->get('Prenom');
        $ammount= $request->query->get('ammount');
        $date= $request->query->get('date');
        $randomNumber = random_int(0, 10000);
        $id=$request->query->get('coachid');
        $coach=$coachrepo->find($request->query->get('coachid'));
        $user=$userrepo->find($coach->getIdUser());
        $entityManager = $doctrine->getManager();
        $entityManager->initializeObject($user);
        
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled',true);    
   
        $dompdf = new Dompdf($pdfOptions);



        $html = $this->render('dashboard/admin/pdf.html.twig', [
            'Nom' => $Nom,
            'Email' => $user->getEmail(),
            
            'Prenom' => $Prenom,
            'ammount' => $ammount,
            'date' => $date,
            'num' => $randomNumber,
        ]);
    
         $dompdf->loadHtml($html);



         $dompdf->setPaper('A4', 'portrait');

         $dompdf->render();
        $output = $dompdf->output();
        $filename = 'invoice_' . str_replace(' ', '_', $id) . '.pdf';
        $response = new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
        return $response;
    }
}
