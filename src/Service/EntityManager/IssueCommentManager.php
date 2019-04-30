<?php

namespace App\Service\EntityManager;

use App\Entity\IssueComment;
use App\Form\IssueCommentType;
use App\Service\UserAwareServiceTrait;
use Symfony\Component\Form\FormInterface;

/**
 * Class IssueCommentManager
 * @package App\Service\EntityManager
 */
class IssueCommentManager extends CommonEntityManager
{
    use UserAwareServiceTrait;

    protected function getCreationForm(): FormInterface
    {
        $comment = new IssueComment();
        $comment->setAuthor($this->getUser());

        return $this->formFactory->create(IssueCommentType::class, $comment);
    }

    protected function getUpdatingForm(): FormInterface
    {
        return $this->formFactory->create(IssueCommentType::class);
    }
}