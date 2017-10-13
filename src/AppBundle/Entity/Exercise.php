<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Exercise
 *
 * @ORM\Table(name="exercise")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ExerciseRepository")
 */
class Exercise
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
     * @ORM\Column(name="description", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max = 255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="weight", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max = 255)
     */
    private $weight;

    /**
     * @var int
     *
     * @ORM\Column(name="number_repetition", type="integer")
     * @Assert\NotBlank()
     */
    private $numberRepetition;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     * @Assert\NotBlank()
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="time")
     */
    private $time;


    /**
     * Get id
     *
     * @return int
     */
    public function getId() :?int
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Exercise
     */
    public function setDescription(string $description) : Exercise
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() :?string
    {
        return $this->description;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return Exercise
     */
    public function setWeight(string $weight) :?Exercise
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight() :?string
    {
        return $this->weight;
    }

    /**
     * Set numberRepetition
     *
     * @param integer $numberRepetition
     *
     * @return Exercise
     */
    public function setNumberRepetition(int $numberRepetition) :?Exercise
    {
        $this->numberRepetition = $numberRepetition;

        return $this;
    }

    /**
     * Get numberRepetition
     *
     * @return int
     */
    public function getNumberRepetition() :?int
    {
        return $this->numberRepetition;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Exercise
     */
    public function setDate($date) :?Exercise
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate() :?\DateTime
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Exercise
     */
    public function setTime($time) :?Exercise
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime() :?\DateTime
    {
        return $this->time;
    }
}

