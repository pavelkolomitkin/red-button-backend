<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * Trait GeoLocationTrait
 * @package App\Entity
 *
 * @JMSSerializer\ExclusionPolicy("all")
 */
trait GeoLocationTrait
{
    /**
     * @var double
     *
     * @ORM\Column(type="decimal", scale=9, precision=12)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     * @JMSSerializer\Type(name="float")
     */
    private $latitude;


    /**
     * @var double
     *
     * @ORM\Column(type="decimal", scale=9, precision=12)
     *
     * @JMSSerializer\Groups({"default"})
     * @JMSSerializer\Expose
     * @JMSSerializer\Type(name="float")
     */
    private $longitude;

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return (float) $this->latitude;
    }

    /**
     * @param float $latitude
     * @return GeoLocationTrait
     */
    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return (float) $this->longitude;
    }

    /**
     * @param float $longitude
     * @return GeoLocationTrait
     */
    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }
}
