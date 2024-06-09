<?php

// src/Controller/MailController.php
namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    private MailerInterface $mailerService;

    public function __construct(MailerInterface $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    /**
     * @Route("/send-test-email", name="send_test_email")
     */
    #[Route('/send-test-email', name: 'send_test_email')]
    public function sendTestEmail(): Response
    {
        $email = (new Email())
            ->from('contact@lpdwca.xyz')
            ->to('wilfried.koussouri@sfr.fr')
            ->html('<p>Lorem ipsum...</p>');

        $this->mailerService->send(
            $email
        );

        return new Response('Test email sent successfully.');
    }
}
