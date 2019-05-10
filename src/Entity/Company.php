<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 * @ORM\Table(name="company")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class Company
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
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $title;


    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=255)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="legal_form_text", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $legalFormText;

    /**
     * @var string
     *
     * @ORM\Column(name="head_name", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"admin", "company"})
     * @JMSSerializer\Expose
     */
    private $headName;

    /**
     * @var string
     *
     * @ORM\Column(name="inn", type="string", length=30, nullable=true)
     *
     * @JMSSerializer\Groups({"admin", "company"})
     * @JMSSerializer\Expose
     */
    private $INN;

    /**
     * @var string
     *
     * @ORM\Column(name="ogrn", type="string", length=30, nullable=true)
     *
     * @JMSSerializer\Groups({"admin", "company"})
     * @JMSSerializer\Expose
     */
    private $OGRN;


    /**
     * @var string
     *
     * @ORM\Column(name="legal_address", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $legalAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="actual_address", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $actualAddress;


    /**
     * @var string
     *
     * @ORM\Column(name="postal_address", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $postalAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_numbers", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $phoneNumbers;

    /**
     * @var string
     *
     * @ORM\Column(name="office_hours", type="text", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $officeHours;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="site", type="string", length=255, nullable=true)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $site;

    /**
     * @var int
     *
     * @ORM\Column(name="building_number", type="integer", nullable=true)
     *
     * @JMSSerializer\Groups({"admin", "company"})
     * @JMSSerializer\Expose
     */
    private $buildingNumber;

    /**
     * @var float
     *
     * @ORM\Column(name="surface", type="decimal", scale=2, precision=12, nullable=true)
     *
     * @JMSSerializer\Groups({"admin", "company"})
     * @JMSSerializer\Expose
     */
    private $surface;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Issue", mappedBy="company")
     */
    private $issues;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\AdministrativeUnit", mappedBy="companies")
     */
    private $administrativeUnits;

    public function __construct()
    {
        $this->issues = new ArrayCollection();
        $this->administrativeUnits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getIssues()
    {
        return $this->issues;
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
     * @return Company
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }



    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     * @return Company
     */
    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLegalFormText(): ?string
    {
        return $this->legalFormText;
    }

    /**
     * @param string $legalFormText
     * @return Company
     */
    public function setLegalFormText(?string $legalFormText = null): self
    {
        $this->legalFormText = $legalFormText;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeadName(): ?string
    {
        return $this->headName;
    }

    /**
     * @param string $headName
     * @return Company
     */
    public function setHeadName(?string $headName = null): Company
    {
        $this->headName = $headName;
        return $this;
    }

    /**
     * @return string
     */
    public function getINN(): ?string
    {
        return $this->INN;
    }

    /**
     * @param string $INN
     * @return Company
     */
    public function setINN(?string $INN = null): Company
    {
        $this->INN = $INN;
        return $this;
    }

    /**
     * @return string
     */
    public function getOGRN(): ?string
    {
        return $this->OGRN;
    }

    /**
     * @param string $OGRN
     * @return Company
     */
    public function setOGRN(?string $OGRN = null): Company
    {
        $this->OGRN = $OGRN;
        return $this;
    }

    /**
     * @return string
     */
    public function getLegalAddress(): ?string
    {
        return $this->legalAddress;
    }

    /**
     * @param string $legalAddress
     * @return Company
     */
    public function setLegalAddress(?string $legalAddress = null): Company
    {
        $this->legalAddress = $legalAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getActualAddress(): ?string
    {
        return $this->actualAddress;
    }

    /**
     * @param string $actualAddress
     * @return Company
     */
    public function setActualAddress(?string $actualAddress = null): Company
    {
        $this->actualAddress = $actualAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalAddress(): ?string
    {
        return $this->postalAddress;
    }

    /**
     * @param string $postalAddress
     * @return Company
     */
    public function setPostalAddress(?string $postalAddress = null): Company
    {
        $this->postalAddress = $postalAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumbers(): ?string
    {
        return $this->phoneNumbers;
    }

    /**
     * @param string $phoneNumbers
     * @return Company
     */
    public function setPhoneNumbers(?string $phoneNumbers = null): Company
    {
        $this->phoneNumbers = $phoneNumbers;
        return $this;
    }

    /**
     * @return string
     */
    public function getOfficeHours(): ?string
    {
        return $this->officeHours;
    }

    /**
     * @param string $officeHours
     * @return Company
     */
    public function setOfficeHours(?string $officeHours = null): Company
    {
        $this->officeHours = $officeHours;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Company
     */
    public function setEmail(?string $email = null): Company
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getSite(): ?string
    {
        return $this->site;
    }

    /**
     * @param string $site
     * @return Company
     */
    public function setSite(?string $site = null): Company
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return int
     */
    public function getBuildingNumber(): ?int
    {
        return $this->buildingNumber;
    }

    /**
     * @param int $buildingNumber
     * @return Company
     */
    public function setBuildingNumber(?int $buildingNumber = null): Company
    {
        $this->buildingNumber = $buildingNumber;
        return $this;
    }

    /**
     * @return float
     */
    public function getSurface(): ?float
    {
        return $this->surface;
    }

    /**
     * @param float $surface
     * @return Company
     */
    public function setSurface(?float $surface = null): Company
    {
        $this->surface = $surface;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAdministrativeUnits()
    {
        return $this->administrativeUnits;
    }

    /**
     * @param ArrayCollection $administrativeUnits
     * @return Company
     */
    public function setAdministrativeUnits($administrativeUnits): self
    {
        $this->administrativeUnits = $administrativeUnits;
        return $this;
    }


}
