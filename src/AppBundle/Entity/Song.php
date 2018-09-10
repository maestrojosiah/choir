<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Song
 *
 * @ORM\Table(name="song")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SongRepository")
 */
class Song
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @ORM\Column(name="verses", type="string", length=255)
     */
    private $verses;

    /**
     * @var bool
     *
     * @ORM\Column(name="chorus", type="boolean")
     */
    private $chorus;

    /**
     * @ORM\ManyToOne(targetEntity="Choir", inversedBy="songs")
     * @ORM\JoinColumn(name="choir_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $choir;

    /**
     * @ORM\OneToMany(targetEntity="Plan", mappedBy="song")
     */
    private $plans;

    public function __construct()
    {
        $this->plans = new \Doctrine\Common\Collections\ArrayCollection();        
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Song
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set verses
     *
     * @param string $verses
     *
     * @return Song
     */
    public function setVerses($verses)
    {
        $this->verses = $verses;

        return $this;
    }

    /**
     * Get verses
     *
     * @return string
     */
    public function getVerses()
    {
        return $this->verses;
    }

    /**
     * Set chorus
     *
     * @param boolean $chorus
     *
     * @return Song
     */
    public function setChorus($chorus)
    {
        $this->chorus = $chorus;

        return $this;
    }

    /**
     * Get chorus
     *
     * @return bool
     */
    public function getChorus()
    {
        return $this->chorus;
    }

    /**
     * Set choir
     *
     * @param \AppBundle\Entity\Choir $choir
     *
     * @return Song
     */
    public function setChoir(\AppBundle\Entity\Choir $choir = null)
    {
        $this->choir = $choir;

        return $this;
    }

    /**
     * Get choir
     *
     * @return \AppBundle\Entity\Choir
     */
    public function getChoir()
    {
        return $this->choir;
    }

    /**
     * Add plan
     *
     * @param \AppBundle\Entity\Plan $plan
     *
     * @return Song
     */
    public function addPlan(\AppBundle\Entity\Plan $plan)
    {
        $this->plans[] = $plan;

        return $this;
    }

    /**
     * Remove plan
     *
     * @param \AppBundle\Entity\Plan $plan
     */
    public function removePlan(\AppBundle\Entity\Plan $plan)
    {
        $this->plans->removeElement($plan);
    }

    /**
     * Get plans
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlans()
    {
        return $this->plans;
    }
}
