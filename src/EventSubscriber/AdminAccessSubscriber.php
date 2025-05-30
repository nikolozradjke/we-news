<?php

namespace App\EventSubscriber;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Enum\UserRole;

class AdminAccessSubscriber implements EventSubscriberInterface
{
    private Security $security;
    private RouterInterface $router;

    public function __construct(Security $security, RouterInterface $router)
    {
        $this->security = $security;
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        // Check if the request is for an admin route
        if (str_starts_with($path, '/admin')) {
            $user = $this->security->getUser();
            
            if (!$user) {
                $loginUrl = $this->router->generate('app_login');
                $event->setResponse(new RedirectResponse($loginUrl));
                return;
            }

            if (!$this->security->isGranted(UserRole::ADMIN->value)) {
                throw new AccessDeniedException('Access denied. Admin role required.');
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
