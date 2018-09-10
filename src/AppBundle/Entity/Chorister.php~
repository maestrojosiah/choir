<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chorister
 *
 * @ORM\Table(name="chorister")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChoristerRepository")
 */
class Chorister
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="voice", type="string", length=255)
     */
    private $voice;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=255)
     */
    private $gender;

    /**
     * @ORM\OneToMany(targetEntity="Mark", mappedBy="chorister")
     */
    private $marks;

    /**
     * @ORM\ManyToOne(targetEntity="Choir", inversedBy="choristers")
     * @ORM\JoinColumn(name="choir_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $choir;

    public function __construct()
    {
        $this->marks = new \Doctrine\Common\Collections\ArrayCollection();        
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
     * Set name
     *
     * @param string $name
     *
     * @return Chorister
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set voice
     *
     * @param string $voice
     *
     * @return Chorister
     */
    public function setVoice($voice)
    {
        $this->voice = $voice;

        return $this;
    }

    /**
     * Get voice
     *
     * @return string
     */
    public function getVoice()
    {
        return $this->voice;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Chorister
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }
    /**
     * Set choir
     *
     * @param \AppBundle\Entity\Choir $choir
     *
     * @return Chorister
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
     * @return Chorister
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
