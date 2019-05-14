<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CompanyRepresentativeUser
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepresentativeUserRepository")
 * @ORM\Table(name="company_representative_user")
 *
 * @JMSSerializer\ExclusionPolicy("all")
 */
class CompanyRepresentativeUser extends User
{
    /**
     * @var Company
     *
     * @Assert\NotNull(message="Choose a company")
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="representatives")
     * @ORM\JoinColumn(name="company_id", nullable=false)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     */
    private $company;

    /**
     * @return Company
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     * @return CompanyRepresentativeUser
     */
    public function setCompany(Company $company): self
    {
        $this->company = $company;
        return $this;
    }

    public function getRoles(): array
    {
        $result = parent::getRoles();

        $result[] = 'ROLE_COMPANY_REPRESENTATIVE_USER';

        return $result;
    }
}
