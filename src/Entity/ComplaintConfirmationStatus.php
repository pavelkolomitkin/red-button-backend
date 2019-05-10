<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComplaintConfirmationStatusRepository")
 * @ORM\Table(name="complaint_confirmation_status")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class ComplaintConfirmationStatus
{
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_REJECTED = 'rejected';

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
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=15)
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $code;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComplaintConfirmation", mappedBy="status")
     */
    private $confirmations;

    public function __construct()
    {
        $this->confirmations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return ComplaintConfirmationStatus
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return ComplaintConfirmationStatus
     */
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getConfirmations()
    {
        return $this->confirmations;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
