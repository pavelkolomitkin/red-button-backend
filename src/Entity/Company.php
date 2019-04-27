<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 * @ORM\Table(name="company")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Company
{
    use SerializeTimestampableTrait;
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;


    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=255)
     */
    private $fullName;

    /**
     * @var CompanyLegalForm
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\CompanyLegalForm", inversedBy="companies")
     * @ORM\JoinColumn(name="legal_form_id", nullable=false)
     */
    private $legalForm;

    /**
     * @var string
     *
     * @ORM\Column(name="head_name", type="string", length=255, nullable=true)
     */
    private $headName;

    /**
     * @var string
     *
     * @ORM\Column(name="inn", type="string", length=30, nullable=true)
     */
    private $INN;

    /**
     * @var string
     *
     * @ORM\Column(name="ogrn", type="string", length=30, nullable=true)
     */
    private $OGRN;


    /**
     * @var string
     *
     * @ORM\Column(name="legal_address", type="string", length=255, nullable=true)
     */
    private $legalAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="actual_address", type="string", length=255, nullable=true)
     */
    private $actualAddress;


    /**
     * @var string
     *
     * @ORM\Column(name="postal_address", type="string", length=255, nullable=true)
     */
    private $postalAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_numbers", type="string", length=255, nullable=true)
     */
    private $phoneNumbers;

    /**
     * @var string
     *
     * @ORM\Column(name="office_hours", type="string", length=255, nullable=true)
     */
    private $officeHours;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="site", type="string", length=255, nullable=true)
     */
    private $site;

    /**
     * @var int
     *
     * @ORM\Column(name="building_number", type="integer", nullable=true)
     */
    private $buildingNumber;

    /**
     * @var float
     *
     * @ORM\Column(name="surface", type="decimal", scale=2, precision=12, nullable=true)
     */
    private $surface;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Issue", mappedBy="company")
     */
    private $issues;

    /**
     * @var AdministrativeUnit
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\AdministrativeUnit", inversedBy="companies")
     * @ORM\JoinColumn(name="administrative_unit_id", nullable=false)
     */
    private $administrativeUnit;

    public function __construct()
    {
        $this->issues = new ArrayCollection();
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
     * @return AdministrativeUnit
     */
    public function getAdministrativeUnit(): AdministrativeUnit
    {
        return $this->administrativeUnit;
    }

    /**
     * @param AdministrativeUnit $administrativeUnit
     * @return Company
     */
    public function setAdministrativeUnit(AdministrativeUnit $administrativeUnit): self
    {
        $this->administrativeUnit = $administrativeUnit;
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
     * @return CompanyLegalForm
     */
    public function getLegalForm(): CompanyLegalForm
    {
        return $this->legalForm;
    }

    /**
     * @param CompanyLegalForm $legalForm
     * @return Company
     */
    public function setLegalForm(CompanyLegalForm $legalForm): self
    {
        $this->legalForm = $legalForm;
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

}
