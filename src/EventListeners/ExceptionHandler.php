<?php

namespace App\EventListeners;

use Psr\Log\LoggerInterface;
use App\Entity\HypermidiaResponse;
use App\Helper\EntityFactoryException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionHandler implements EventSubscriberInterface
{
    protected $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleEntityException', 1],
                ['handle404Exception', 0],
                ['handleGenericException', -1],
            ]
        ];
    }

    public function handle404Exception(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof NotFoundHttpException) {
            // $event->setResponse(new JsonResponse(['mensagem' => 'Erro 404']));
            $response = HypermidiaResponse::fromError($exception)->getResponse();
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $event->setResponse($response);
        }
    }

    public function handleEntityException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof EntityFactoryException) {
            $response = HypermidiaResponse::fromError($exception)->getResponse();
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            $event->setResponse($response);
        }
    }

    public function handleGenericException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $this->logger->critical('Uma excessao ocorreu. {stack}', [
            'stack' => $exception->getTraceAsString(),
        ]);

        $response = HypermidiaResponse::fromError($exception)->getResponse();
        $event->setResponse($response);
    }
}