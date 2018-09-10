<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rehearsal
 *
 * @ORM\Table(name="rehearsal")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RehearsalRepository")
 */
class Rehearsal
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
     * @var \Date
     *
     * @ORM\Column(name="day", type="date")
     */
    private $day;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="starttime", type="datetime")
     */
    private $starttime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endtime", type="datetime")
     */
    private $endtime;

    /**
     * @ORM\OneToMany(targetEntity="Mark", mappedBy="rehearsal")
     */
    private $marks;

    /**
     * @ORM\OneToMany(targetEntity="Plan", mappedBy="rehearsal")
     */
    private $plans;

    /**
     * @ORM\ManyToOne(targetEntity="Choir", inversedBy="rehearsals")
     * @ORM\JoinColumn(name="choir_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $choir;

    public function __construct()
    {
        $this->marks = new \Doctrine\Common\Collections\ArrayCollection();        
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
     * Set day
     *
     * @param string $day
     *
     * @return Rehearsal
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set starttime
     *
     * @param \DateTime $starttime
     *
     * @return Rehearsal
     */
    public function setStartTime($starttime)
    {
        $this->starttime = $starttime;

        return $this;
    }

    /**
     * Get starttime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->starttime;
    }

    /**
     * Set endtime
     *
     * @param string $endtime
     *
     * @return Rehearsal
     */
    public function setEndtime($endtime)
    {
        $this->endtime = $endtime;

        return $this;
    }

    /**
     * Get endtime
     *
     * @return string
     */
    public function getEndtime()
    {
        return $this->endtime;
    }

    /**
     * Add plan
     *
     * @param \AppBundle\Entity\Plan $plan
     *
     * @return Rehearsal
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

    /**
     * Set choir
     *
     * @param \AppBundle\Entity\Choir $choir
     *
     * @return Rehearsal
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
     * Add mark
     *
     * @param \AppBundle\Entity\Mark $mark
     *
     * @return Rehearsal
     */
    public function addMark(\AppBundle\Entity\Mark $mark)
    {
        $this->marks[] = $mark;

        return $this;
    }

    /**
     * Remove mark
     *
     * @param \AppBundle\Entity\Mark $mark
     */
    public function removeMark(\AppBundle\Entity\Mark $mark)
    {
        $this->marks->removeElement($mark);
    }

    /**
     * Get marks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMarks()
    {
        return $this->marks;
    }
}
