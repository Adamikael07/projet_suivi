<?php

namespace PNC\ChiroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConditionsObservation
 */
class ConditionsObservation
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $obs_temperature;

    /**
     * @var string
     */
    private $obs_humidite;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set obs_temperature
     *
     * @param string $obsTemperature
     * @return ConditionsObservation
     */
    public function setObsTemperature($obsTemperature)
    {
        $this->obs_temperature = $obsTemperature;

        return $this;
    }

    /**
     * Get obs_temperature
     *
     * @return string 
     */
    public function getObsTemperature()
    {
        return $this->obs_temperature;
    }

    /**
     * Set obs_humidite
     *
     * @param string $obsHumidite
     * @return ConditionsObservation
     */
    public function setObsHumidite($obsHumidite)
    {
        $this->obs_humidite = $obsHumidite;

        return $this;
    }

    /**
     * Get obs_humidite
     *
     * @return string 
     */
    public function getObsHumidite()
    {
        return $this->obs_humidite;
    }
    /**
     * @var integer
     */
    private $obs_id;

    /**
     * @var \PNC\BaseAppBundle\Entity\Observation
     */
    private $parent_obs;


    /**
     * Set obs_id
     *
     * @param integer $obsId
     * @return ConditionsObservation
     */
    public function setObsId($obsId)
    {
        $this->obs_id = $obsId;

        return $this;
    }

    /**
     * Get obs_id
     *
     * @return integer 
     */
    public function getObsId()
    {
        return $this->obs_id;
    }

    /**
     * Set parent_obs
     *
     * @param \PNC\BaseAppBundle\Entity\Observation $parentObs
     * @return ConditionsObservation
     */
    public function setParentObs(\PNC\BaseAppBundle\Entity\Observation $parentObs = null)
    {
        $this->parent_obs = $parentObs;

        return $this;
    }

    /**
     * Get parent_obs
     *
     * @return \PNC\BaseAppBundle\Entity\Observation 
     */
    public function getParentObs()
    {
        return $this->parent_obs;
    }
}
