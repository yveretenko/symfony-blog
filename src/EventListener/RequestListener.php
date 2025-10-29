<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener(event: 'kernel.request')]
final readonly class RequestListener
{
    public function __construct(private LoggerInterface $logger) {}

    public function __invoke(RequestEvent $event): void
    {
        $this->logger->info(sprintf('User-Agent: %s', $event->getRequest()->headers->get('User-Agent')));
    }
}
