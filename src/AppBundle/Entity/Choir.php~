<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Choir
 *
 * @ORM\Table(name="choir")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChoirRepository")
 */
class Choir
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="choirs")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Rehearsal", mappedBy="choir")
     */
    private $rehearsals;

    /**
     * @ORM\OneToMany(targetEntity="Song", mappedBy="choir")
     */
    private $songs;

    /**
     * @ORM\OneToMany(targetEntity="Plan", mappedBy="choir")
     */
    private $plans;

    /**
     * @ORM\OneToMany(targetEntity="Chorister", mappedBy="choir")
     */
    private $choristers;

    public function __construct()
    {
        $this->rehearsals = new \Doctrine\Common\Collections\ArrayCollection();        
        $this->songs = new \Doctrine\Common\Collections\ArrayCollection();        
        $this->choristers = new \Doctrine\Common\Collections\ArrayCollection();        
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
     * @return Choir
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Choir
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add rehearsal
     *
     * @param \AppBundle\Entity\Rehearsal $rehearsal
     *
     * @return Choir
     */
    public function addRehearsal(\AppBundle\Entity\Rehearsal $rehearsal)
    {
        $this->rehearsals[] = $rehearsal;

        return $this;
    }

    /**
     * Remove rehearsal
     *
     * @param \AppBundle\Entity\Rehearsal $rehearsal
     */
    public function removeRehearsal(\AppBundle\Entity\Rehearsal $rehearsal)
    {
        $this->rehearsals->removeElement($rehearsal);
    }

    /**
     * Get rehearsals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRehearsals()
    {
        return $this->rehearsals;
    }

    /**
     * Add song
     *
     * @param \AppBundle\Entity\Song $song
     *
     * @return Choir
     */
    public function addSong(\AppBundle\Entity\Song $song)
    {
        $this->songs[] = $song;

        return $this;
    }

    /**
     * Remove song
     *
     * @param \AppBundle\Entity\Song $song
     */
    public function removeSong(\AppBundle\Entity\Song $song)
    {
        $this->songs->removeElement($song);
    }

    /**
     * Get songs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSongs()
    {
        return $this->songs;
    }

    /**
     * Add chorister
     *
     * @param \AppBundle\Entity\Chorister $chorister
     *
     * @return Choir
     */
    public function addChorister(\AppBundle\Entity\Chorister $chorister)
    {
        $this->choristers[] = $chorister;

        return $this;
    }

    /**
     * Remove chorister
     *
     * @param \AppBundle\Entity\Chorister $chorister
     */
    public function removeChorister(\AppBundle\Entity\Chorister $chorister)
    {
        $this->choristers->removeElement($chorister);
    }

    /**
     * Get choristers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChoristers()
    {
        return $this->choristers;
    }

    /**
     * Add plan
     *
     * @param \AppBundle\Entity\Plan $plan
     *
     * @return Choir
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
