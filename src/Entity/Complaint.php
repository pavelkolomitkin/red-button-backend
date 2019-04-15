<?php

namespace App\Entity;

use App\Validator\Constraints\ComplaintPictureOwnerConstraint;
use App\Validator\Constraints\ComplaintVideoOwnerConstraint;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComplaintRepository")
 * @ORM\Table(name="complaint")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Complaint
{
    use SerializeTimestampableTrait;
    use GeoLocationTrait;
    use SoftDeleteableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="5000")
     */
    private $message;

    /**
     * @var ClientUser
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ClientUser", inversedBy="complaints")
     * @ORM\JoinColumn(name="client_id", nullable=false)
     */
    private $client;

    /**
     * @var ServiceType
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ServiceType", inversedBy="complaints")
     * @ORM\JoinColumn(name="service_type_id", nullable=true)
     */
    private $serviceType;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\ComplaintTag", inversedBy="complaints")
     * @ORM\JoinTable(name="complain_tag",
     *  joinColumns={@ORM\JoinColumn(name="complaint_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *     )
     */
    private $tags;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\ComplaintConfirmation", mappedBy="complaint", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $complaintConfirmations;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="complaints")
     * @ORM\JoinColumn(name="region_id", nullable=false)
     */
    private $region;

    /**
     * @var ArrayCollection
     *
     * @Assert\All(
     *     @ComplaintPictureOwnerConstraint()
     * )
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComplaintPicture", mappedBy="complaint", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $pictures;

    /**
     * @var ArrayCollection
     *
     * @Assert\All(
     *     @ComplaintVideoOwnerConstraint()
     * )
     *
     * @ORM\OneToMany(targetEntity="App\Entity\VideoMaterial", mappedBy="complaint")
     */
    private $videos;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->complaintConfirmations = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Complaint
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return ClientUser
     */
    public function getClient(): ClientUser
    {
        return $this->client;
    }

    /**
     * @param ClientUser $client
     * @return Complaint
     */
    public function setClient(ClientUser $client): Complaint
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return ServiceType
     */
    public function getServiceType(): ServiceType
    {
        return $this->serviceType;
    }

    /**
     * @param ServiceType $serviceType
     * @return Complaint
     */
    public function setServiceType(ServiceType $serviceType = null): self
    {
        $this->serviceType = $serviceType;

        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    public function addTag(ComplaintTag $tag): self
    {
        if (!$this->tags->contains($tag))
        {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(ComplaintTag $tag): self
    {
        if ($this->tags->contains($tag))
        {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getComplaintConfirmations()
    {
        return $this->complaintConfirmations;
    }

    /**
     * @return Region
     */
    public function getRegion(): Region
    {
        return $this->region;
    }

    /**
     * @param Region $region
     * @return Complaint
     */
    public function setRegion(Region $region): self
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    public function setPictures($pictures)
    {
        $this->pictures = $pictures;

        return $this;
    }

    public function addPicture(ComplaintPicture $picture): self
    {
        if (!$this->pictures->contains($picture))
        {
            $this->pictures[] = $picture;
            $picture->setComplaint($this);
        }

        return $this;
    }

    public function removePicture(ComplaintPicture $picture): self
    {
        if ($this->pictures->contains($picture))
        {
            $this->pictures->removeElement($picture);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getVideos()
    {
        return $this->videos;
    }

    public function setVideos($videos)
    {
        $this->videos = $videos;

        return $videos;
    }

    public function addVideo(VideoMaterial $video): self
    {
        if (!$this->videos->contains($video))
        {
            $this->videos[] = $video;
            $video->setComplaint($this);
        }

        return $this;
    }

    public function removeVideo(VideoMaterial $video): self
    {
        if ($this->videos->contains($video))
        {
            $this->videos->removeElement($video);
            $video->setComplaint(null);
        }

        return $this;
    }
}
