<?php

namespace PNC\BaseAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Observation
 */
class Observation
{

    //geometrie geoJson
    public $geom;
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $obs_date;

    /**
     * @var string
     */
    private $obs_commentaire;

    /**
     * @var integer
     */
    private $obs_id_table_src;

    /**
     * @var integer
     */
    private $obs_site_id;


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
     * Set obs_date
     *
     * @param \DateTime $obsDate
     * @return Observation
     */
    public function setObsDate($obsDate)
    {
        $this->obs_date = $obsDate;

        return $this;
    }

    /**
     * Get obs_date
     *
     * @return \DateTime 
     */
    public function getObsDate()
    {
        return $this->obs_date;
    }

    /**
     * Set obs_commentaire
     *
     * @param string $obsCommentaire
     * @return Observation
     */
    public function setObsCommentaire($obsCommentaire)
    {
        $this->obs_commentaire = $obsCommentaire;

        return $this;
    }

    /**
     * Get obs_commentaire
     *
     * @return string 
     */
    public function getObsCommentaire()
    {
        return $this->obs_commentaire;
    }

    /**
     * Set obs_id_table_src
     *
     * @param integer $obsIdTableSrc
     * @return Observation
     */
    public function setObsIdTableSrc($obsIdTableSrc)
    {
        $this->obs_id_table_src = $obsIdTableSrc;

        return $this;
    }

    /**
     * Get obs_id_table_src
     *
     * @return integer 
     */
    public function getObsIdTableSrc()
    {
        return $this->obs_id_table_src;
    }

    /**
     * Set obs_site_id
     *
     * @param integer $obsSiteId
     * @return Observation
     */
    public function setObsSiteId($obsSiteId)
    {
        $this->obs_site_id = $obsSiteId;

        return $this;
    }

    /**
     * Get obs_site_id
     *
     * @return integer 
     */
    public function getObsSiteId()
    {
        return $this->obs_site_id;
    }
    /**
     * @var \PNC\BaseAppBundle\Entity\Site
     */
    private $site;


    /**
     * Set site
     *
     * @param \PNC\BaseAppBundle\Entity\Site $site
     * @return Observation
     */
    public function setSite(\PNC\BaseAppBundle\Entity\Site $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return \PNC\BaseAppBundle\Entity\Site 
     */
    public function getSite()
    {
        return $this->site;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $observateurs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->observateurs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add observateurs
     *
     * @param \PNC\ExtBundle\Entity\Observateur $observateurs
     * @return Observation
     */
    public function addObservateur(\PNC\ExtBundle\Entity\Observateur $observateurs)
    {
        $this->observateurs[] = $observateurs;

        return $this;
    }

    /**
     * Remove observateurs
     *
     * @param \PNC\ExtBundle\Entity\Observateur $observateurs
     */
    public function removeObservateur(\PNC\ExtBundle\Entity\Observateur $observateurs)
    {
        $this->observateurs->removeElement($observateurs);
    }

    /**
     * Get observateurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getObservateurs()
    {
        return $this->observateurs;
    }
}
