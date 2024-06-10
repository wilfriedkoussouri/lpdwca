<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new Email())
                ->from('contact@lpdwca.xyz')
                ->to('contact@lpdwca.xyz')
                ->subject('Contact Form Submission')
                ->text(
                    sprintf(
                        "First Name: %s\nLast Name: %s\nCompany: %s\nCountry: %s\nPhone Number: %s\n\nMessage:\n%s",
                        $data['firstName'],
                        $data['lastName'],
                        $data['email'],
                        $data['company'],
                        $data['phoneNumber'],
                        $data['message']
                    )
                );

            $mailer->send($email);

            $this->addFlash('success', 'Your message has been sent successfully!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // #[Route('/send_mail', name: 'app_send_mail')]
    // public function sendMail(MailerInterface $mailer): Response
    // {
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $data = $form->getData();

    //         // Send the email
    //         $email = (new Email())
    //             ->from($data['email'])
    //             ->to('your_email@example.com')
    //             ->subject('Contact Form Submission')
    //             ->text(
    //                 sprintf(
    //                     "First Name: %s\nLast Name: %s\nCompany: %s\nCountry: %s\nPhone Number: %s\n\nMessage:\n%s",
    //                     $data['firstName'],
    //                     $data['lastName'],
    //                     $data['company'],
    //                     $data['country'],
    //                     $data['phoneNumber'],
    //                     $data['message']
    //                 )
    //             );

    //         $mailer->send($email);

    //         $this->addFlash('success', 'Your message has been sent successfully!');

    //         return $this->redirectToRoute('app_contact');
    //     }

    //     return $this->render('contact.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }
}
