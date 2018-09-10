<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plan
 *
 * @ORM\Table(name="plan")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanRepository")
 */
class Plan
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
     * @var \DateTime
     *
     * @ORM\Column(name="commence", type="datetime")
     */
    private $commence;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="conclude", type="datetime")
     */
    private $conclude;

    /**
     * @var string
     *
     * @ORM\Column(name="todo", type="string", length=255 )
     */
    private $todo;

    /**
     * @ORM\ManyToOne(targetEntity="Choir", inversedBy="plans")
     * @ORM\JoinColumn(name="choir_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $choir;

    /**
     * @ORM\ManyToOne(targetEntity="Song", inversedBy="plans")
     * @ORM\JoinColumn(name="song_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $song;

    /**
     * @ORM\ManyToOne(targetEntity="Rehearsal", inversedBy="plans")
     * @ORM\JoinColumn(name="rehearsal_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $rehearsal;

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
     * Set commence
     *
     * @param \DateTime $commence
     *
     * @return Plan
     */
    public function setCommence($commence)
    {
        $this->commence = $commence;

        return $this;
    }

    /**
     * Get commence
     *
     * @return \DateTime
     */
    public function getCommence()
    {
        return $this->commence;
    }

    /**
     * Set conclude
     *
     * @param \DateTime $conclude
     *
     * @return Plan
     */
    public function setConclude($conclude)
    {
        $this->conclude = $conclude;

        return $this;
    }

    /**
     * Get conclude
     *
     * @return \DateTime
     */
    public function getConclude()
    {
        return $this->conclude;
    }


    /**
     * Set choir
     *
     * @param \AppBundle\Entity\Choir $choir
     *
     * @return Plan
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
     * Set song
     *
     * @param \AppBundle\Entity\Song $song
     *
     * @return Plan
     */
    public function setSong(\AppBundle\Entity\Song $song = null)
    {
        $this->song = $song;

        return $this;
    }

    /**
     * Get song
     *
     * @return \AppBundle\Entity\Song
     */
    public function getSong()
    {
        return $this->song;
    }

    /**
     * Set rehearsal
     *
     * @param \AppBundle\Entity\Rehearsal $rehearsal
     *
     * @return Plan
     */
    public function setRehearsal(\AppBundle\Entity\Rehearsal $rehearsal = null)
    {
        $this->rehearsal = $rehearsal;

        return $this;
    }

    /**
     * Get rehearsal
     *
     * @return \AppBundle\Entity\Rehearsal
     */
    public function getRehearsal()
    {
        return $this->rehearsal;
    }

    /**
     * Set todo
     *
     * @param string $todo
     *
     * @return Plan
     */
    public function setTodo($todo)
    {
        $this->todo = $todo;

        return $this;
    }

    /**
     * Get todo
     *
     * @return string
     */
    public function getTodo()
    {
        return $this->todo;
    }
}
