<?php

namespace App\Entity;

use App\Validator\Constraints\IssuePictureOwnerConstraint;
use App\Validator\Constraints\VideoOwnerConstraint;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IssueRepository")
 * @ORM\Table(name="issue")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class Issue
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ComplaintConfirmation", mappedBy="issue", cascade={"persist", "remove"}, orphanRemoval=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $complaintConfirmations;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="10000")
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $message;

    /**
     * @var ServiceType
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ServiceType", inversedBy="issues")
     * @ORM\JoinColumn(name="service_type_id", nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $serviceType;

    /**
     * @var ClientUser
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ClientUser", inversedBy="issues")
     * @ORM\JoinColumn(name="client_id", nullable=false)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $client;

    /**
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="issues")
     * @ORM\JoinColumn(name="company_id", nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $company;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="issues")
     * @ORM\JoinColumn(name="region_id", nullable=false)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $region;

    /**
     * @var ArrayCollection
     *
     * @Assert\All(
     *     @IssuePictureOwnerConstraint()
     * )
     *
     * @ORM\OneToMany(targetEntity="App\Entity\IssuePicture", mappedBy="issue", cascade={"persist", "remove"}, orphanRemoval=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $pictures;

    /**
     * @var ArrayCollection
     *
     * @Assert\All(
     *     @VideoOwnerConstraint()
     * )
     *
     * @ORM\OneToMany(targetEntity="App\Entity\VideoMaterial", mappedBy="issue", cascade={"persist"})
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $videos;

    /**
     * @var OSMAddress
     * @ORM\Embedded(class="App\Entity\OSMAddress")
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $address;

    public function __construct()
    {
        $this->complaintConfirmations = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->address = new OSMAddress();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getComplaintConfirmations()
    {
        return $this->complaintConfirmations;
    }


    public function addComplaintConfirmation(ComplaintConfirmation $confirmation): self
    {
        if (!$this->complaintConfirmations->contains($confirmation))
        {
            $this->complaintConfirmations[] = $confirmation;
            $confirmation->setIssue($this);
        }

        return $this;
    }

    public function removeComplaintConfirmation(ComplaintConfirmation $confirmation): self
    {
        if ($this->complaintConfirmations->contains($confirmation))
        {
            $this->complaintConfirmations->removeElement($confirmation);
        }

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
     * @return Issue
     */
    public function setClient(ClientUser $client): self
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return Company
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     * @return Issue
     */
    public function setCompany(Company $company = null): self
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return Region
     */
    public function getRegion(): ?Region
    {
        return $this->region;
    }

    /**
     * @param Region $region
     * @return Issue
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

    public function addPicture(IssuePicture $picture): self
    {
        if (!$this->pictures->contains($picture))
        {
            $this->pictures[] = $picture;
            $picture->setIssue($this);
        }

        return $this;
    }

    public function removePicture(IssuePicture $picture): self
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

    public function addVideo(VideoMaterial $video): self
    {
        if (!$this->videos->contains($video))
        {
            $this->videos[] = $video;
            $video->setIssue($this);
        }

        return $this;
    }

    public function removeVideo(VideoMaterial $video): self
    {
        if ($this->videos->contains($video))
        {
            $this->videos->removeElement($video);
            $video->setIssue(null);
        }

        return $this;
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
     * @return Issue
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
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
     * @return Issue
     */
    public function setServiceType(ServiceType $serviceType): self
    {
        $this->serviceType = $serviceType;
        return $this;
    }

    /**
     * @return OSMAddress
     */
    public function getAddress(): ?OSMAddress
    {
        return $this->address;
    }

    /**
     * @param OSMAddress $address
     * @return Issue
     */
    public function setAddress(OSMAddress $address): self
    {
        $this->address = $address;
        return $this;
    }
}
