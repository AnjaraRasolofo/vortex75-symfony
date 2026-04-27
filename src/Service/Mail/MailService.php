<?php

namespace App\Service\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Psr\Log\LoggerInterface;

class MailService
{
    public function __construct(
        private string $host,
        private string $user,
        private string $pass,
        private string $from,
        private string $fromName,
        private string $to,
        private string $port,
        private string $encryption,
        private LoggerInterface $logger
    ) {}

    public function sendContactMail(string $name, string $email, string $message): bool
    {
        $mail = new PHPMailer(true);

        try {
            // SMTP config
            $mail->isSMTP();
            $mail->Host = $this->host;
            $mail->SMTPAuth = true;
            $mail->Username = $this->user;
            $mail->Password = $this->pass;
            $mail->SMTPSecure = $this->encryption;
            $mail->Port = (int) $this->port;

            // Headers
            $mail->setFrom($this->from, $this->fromName);
            $mail->addAddress($this->to);
            $mail->addReplyTo($email, $name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Nouveau message contact Vortex75';

            $mail->Body = $this->buildHtmlBody($name, $email, $message);

            $mail->send();

            return true;

        } catch (Exception $e) {
            $this->logger->error('Mail error: ' . $e->getMessage());
            return false;
        }
    }

    private function buildHtmlBody(string $name, string $email, string $message): string
    {
        return '
        <div style="font-family:Arial;padding:20px">
            <h2>Nouveau message de contact</h2>

            <p><strong>Nom :</strong> ' . htmlspecialchars($name) . '</p>
            <p><strong>Email :</strong> ' . htmlspecialchars($email) . '</p>

            <hr>

            <p><strong>Message :</strong><br>' . nl2br(htmlspecialchars($message)) . '</p>
        </div>';
    }
}