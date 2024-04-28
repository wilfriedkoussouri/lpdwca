<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final class AccessDeniedListener
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    #[AsEventListener(event: KernelEvents::EXCEPTION)]
    public function onKernelException(ExceptionEvent $event): void
    {
        // Obtenez l'exception lancée
        $exception = $event->getThrowable();

        // Vérifiez si c'est une exception d'accès refusé
        if ($exception instanceof AccessDeniedHttpException) {
            // Créez une réponse de redirection
            $response = new RedirectResponse($this->urlGenerator->generate('app_home'));

            // Définissez la réponse pour l'événement, ce qui arrête la propagation de l'événement
            $event->setResponse($response);
        }
    }
}
