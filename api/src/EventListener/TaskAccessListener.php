<?php

namespace App\EventListener;

use App\Controller\Api\TaskController;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Attribute\Route;

#[AsEventListener]
class TaskAccessListener implements EventSubscriberInterface
{
    public function __construct(
        private Security $security
    ) {

    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'checkTaskAccess',
        ];
    }

    public function checkTaskAccess(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        foreach ($controller as $item) {
            if ($item instanceof TaskController) {
                $user = $this->security->getUser();

                if (is_null($user)) {
                    throw new AccessDeniedHttpException('Доступ запрещен.');
                }
            }
        }
    }
}