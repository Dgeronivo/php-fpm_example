<?php

declare(strict_types=1);

namespace App\PresentationLayer\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsEventListener]
class JsonConvertListener
{
    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (str_starts_with($request->headers->get('Content-Type'), 'application/json')) {
            $content = $request->headers->has('data') ? $request->headers->get('data') : $request->getContent();
            $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
            if (false === $data) {
                throw new BadRequestHttpException('Request data is invalid');
            }
            $request->request->replace(is_array($data) ? $data : []);
        }
    }
}
