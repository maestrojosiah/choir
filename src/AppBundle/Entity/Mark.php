<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mark
 *
 * @ORM\Table(name="mark")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MarkRepository")
 */
class Mark
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
     * @ORM\Column(name="timein", type="datetime")
     */
    private $timein;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timeout", type="datetime")
     */
    private $timeout;

    /**
     * @ORM\ManyToOne(targetEntity="Chorister", inversedBy="marks")
     * @ORM\JoinColumn(name="chorister_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $chorister;

    /**
     * @ORM\ManyToOne(targetEntity="Rehearsal", inversedBy="marks")
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
     * Set timein
     *
     * @param \DateTime $timein
     *
     * @return Mark
     */
    public function setTimein($timein)
    {
        $this->timein = $timein;

        return $this;
    }

    /**
     * Get timein
     *
     * @return \DateTime
     */
    public function getTimein()
    {
        return $this->timein;
    }
    /**
     * Set timeout
     *
     * @param \DateTime $timeout
     *
     * @return Mark
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Get timeout
     *
     * @return \DateTime
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Set chorister
     *
     * @param \AppBundle\Entity\Chorister $chorister
     *
     * @return Mark
     */
    public function setChorister(\AppBundle\Entity\Chorister $chorister = null)
    {
        $this->chorister = $chorister;

        return $this;
    }

    /**
     * Get chorister
     *
     * @return \AppBundle\Entity\Chorister
     */
    public function getChorister()
    {
        return $this->chorister;
    }

    /**
     * Set rehearsal
     *
     * @param \AppBundle\Entity\Rehearsal $rehearsal
     *
     * @return Mark
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
}
