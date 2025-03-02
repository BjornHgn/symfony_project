<?php

namespace App\Controller;

use App\Entity\Invoice;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends AbstractController
{
    #[Route('/invoice/{id}', name: 'app_invoice_details')]
    public function details(Invoice $invoice): Response
    {
        // Vérifier que l'utilisateur actuel est bien le propriétaire de la facture
        if ($invoice->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette facture.');
        }

        return $this->render('invoice/details.html.twig', [
            'invoice' => $invoice
        ]);
    }
} 