<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoMaterialRepository")
 * @ORM\Table(name="video_material")
 *
 * @JMSSerializer\ExclusionPolicy("all")
 */
abstract class VideoMaterial
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="originalLink", type="string", nullable=false)
     * @JMSSerializer\Expose
     */
    private $originalLink;

    /**
     * @var array
     *
     * @ORM\Column(name="meta_data", type="json", nullable=false)
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

    public function getId(): ?int
    {
        return $this->id;
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
}
