<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use JMS\Serializer\Annotation as JMSSerializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoMaterialRepository")
 * @ORM\Table(name="video_material", uniqueConstraints={
 *     @UniqueConstraint(name="user_video_unique_key", columns={"external_id", "owner_id"})
 *     })
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class VideoMaterial
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
     * @ORM\Column(name="title", type="string", length=500, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="external_id", type="string", length=50, nullable=false)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $externalId;

    /**
     * @var string
     * @ORM\Column(name="originalLink", type="string", nullable=false)
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $originalLink;

    /**
     * @var array
     *
     * @ORM\Column(name="meta_data", type="json", nullable=false)
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $metaData;

    /**
     * @var Complaint
     * @ORM\ManyToOne(targetEntity="App\Entity\Complaint", inversedBy="videos")
     * @ORM\JoinColumn(name="complaint_id", nullable=true, onDelete="SET NULL")
     */
    private $complaint;

    /**
     * @var Issue
     * @ORM\ManyToOne(targetEntity="App\Entity\Issue", inversedBy="videos")
     * @ORM\JoinColumn(name="issue_id", nullable=true, onDelete="SET NULL")
     */
    private $issue;

    /**
     * @var ClientUser
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ClientUser", inversedBy="videos")
     * @ORM\JoinColumn(name="owner_id", nullable=false)
     */
    private $owner;

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
     * @return VideoMaterial
     */
    public function setTitle(string $title = null): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     * @return VideoMaterial
     */
    public function setExternalId(string $externalId): self
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalLink(): string
    {
        return $this->originalLink;
    }

    /**
     * @param string $originalLink
     * @return VideoMaterial
     */
    public function setOriginalLink(string $originalLink): self
    {
        $this->originalLink = $originalLink;
        return $this;
    }

    /**
     * @return array
     */
    public function getMetaData(): array
    {
        return $this->metaData;
    }

    /**
     * @param array $metaData
     * @return VideoMaterial
     */
    public function setMetaData(array $metaData): self
    {
        $this->metaData = $metaData;
        return $this;
    }

    /**
     * @return Complaint
     */
    public function getComplaint(): ?Complaint
    {
        return $this->complaint;
    }

    /**
     * @param Complaint $complaint
     * @return VideoMaterial
     */
    public function setComplaint(Complaint $complaint = null): self
    {
        $this->complaint = $complaint;
        return $this;
    }

    /**
     * @return Issue
     */
    public function getIssue(): ?Issue
    {
        return $this->issue;
    }

    /**
     * @param Issue $issue
     * @return VideoMaterial
     */
    public function setIssue(Issue $issue = null): self
    {
        $this->issue = $issue;
        return $this;
    }

    /**
     * @return ClientUser
     */
    public function getOwner(): ClientUser
    {
        return $this->owner;
    }

    /**
     * @param ClientUser $owner
     * @return VideoMaterial
     */
    public function setOwner(ClientUser $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
