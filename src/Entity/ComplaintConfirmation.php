<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComplaintConfirmationRepository")
 * @ORM\Table(name="complaint_confirmation")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class ComplaintConfirmation
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
     * @var ComplaintConfirmationStatus
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ComplaintConfirmationStatus", inversedBy="confirmations")
     * @ORM\JoinColumn(name="status_id", nullable=false)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $status;

    /**
     * @var Complaint
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Complaint", inversedBy="complaintConfirmations")
     * @ORM\JoinColumn(name="complaint_id", nullable=false)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $complaint;

    /**
     * @var Issue
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Issue", inversedBy="complaintConfirmations")
     * @ORM\JoinColumn(name="issue_id", nullable=false)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $issue;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ComplaintConfirmationStatus
     */
    public function getStatus(): ComplaintConfirmationStatus
    {
        return $this->status;
    }

    /**
     * @param ComplaintConfirmationStatus $status
     * @return ComplaintConfirmation
     */
    public function setStatus(ComplaintConfirmationStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return Complaint
     */
    public function getComplaint(): Complaint
    {
        return $this->complaint;
    }

    /**
     * @param Complaint $complaint
     * @return ComplaintConfirmation
     */
    public function setComplaint(Complaint $complaint): self
    {
        $this->complaint = $complaint;
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
     * @return ComplaintConfirmation
     */
    public function setIssue(Issue $issue): self
    {
        $this->issue = $issue;
        return $this;
    }
}
