<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IssueCommentRepository")
 * @ORM\Table(name="issue_comment")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class IssueComment
{

    use SerializeTimestampableTrait;
    use SoftDeleteableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=2000, nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="2000")
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $message;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="issueComments")
     * @ORM\JoinColumn(name="author_id", nullable=false)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $author;

    /**
     * @var Issue
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Issue", inversedBy="comments")
     * @ORM\JoinColumn(name="issue_id", nullable=false)
     *
     * @JMSSerializer\Groups({"admin_default"})
     * @JMSSerializer\Expose
     */
    private $issue;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return IssueComment
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return IssueComment
     */
    public function setAuthor(User $author): self
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return Issue
     */
    public function getIssue(): Issue
    {
        return $this->issue;
    }

    /**
     * @param Issue $issue
     * @return IssueComment
     */
    public function setIssue(Issue $issue): self
    {
        $this->issue = $issue;
        return $this;
    }
}
