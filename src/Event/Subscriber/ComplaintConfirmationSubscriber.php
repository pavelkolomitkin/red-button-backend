<?php

namespace App\Event\Subscriber;

use App\Entity\ComplaintConfirmation;
use App\Entity\ComplaintConfirmationStatus;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

/**
 * Class ComplaintConfirmationSubscriber
 * @package App\Event\Subscriber
 */
class ComplaintConfirmationSubscriber implements EventSubscriber
{
    /**
     * @var ComplaintConfirmationStatus
     */
    private $pendingStatus;


    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var ComplaintConfirmation $entity */
        $entity = $args->getObject();

        if ($entity instanceof ComplaintConfirmation)
        {

            if (!$this->pendingStatus)
            {
                $this->pendingStatus = $args->getObjectManager()->getRepository(ComplaintConfirmationStatus::class)->findOneBy(
                    ['code' => ComplaintConfirmationStatus::STATUS_PENDING]
                );
            }

            $entity->setStatus($this->pendingStatus);
        }
    }
}