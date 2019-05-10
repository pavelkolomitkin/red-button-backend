<?php

namespace App\Service\EntityManager;

use App\Entity\Issue;
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

    public function addComment(Issue $issue, array $commentData)
    {
        $comment = new IssueComment();
        $comment->setAuthor($this->getUser());

        $issue->addComment($comment);

        return $this->update($comment, $commentData);
    }

    /**
     * @param IssueComment $entity
     * @throws Exception\ManageEntityException
     */
    public function remove($entity)
    {
        $issue = $entity->getIssue();
        $issue->removeComment($entity);

        parent::remove($entity);
    }
}