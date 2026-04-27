<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\Mail\MailService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use PHPMailer\PHPMailer\PHPMailer;

final class ContactController extends AbstractController
{
    #[Route('/contact/send-mail', name: 'contact_send_mail', methods: ['POST'])]
    public function sendMail(Request $request, MailService $mailService): Response
    {
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $message = $request->request->get('message');

        $sent = $mailService->sendContactMail($name, $email, $message);

        if ($sent) {
            $this->addFlash('success', 'Message envoyé avec succès');
        } else {
            $this->addFlash('error', 'Erreur lors de l’envoi');
        }

        return $this->redirectToRoute('app_contact');
    }
}
