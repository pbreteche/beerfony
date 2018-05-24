<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BeerRepository")
 * @ORM\Table()
 */
class Beer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var Pub
     *
     * ORM\OneToOne(targetEntity="App\Entity\Pub", mappedBy="beer", fetch="LAZY")
     */
    private $pub;

    /**
     * @ORM\Column(type="integer")
     * @Assert\LessThan(1000)
     * @Assert\GreaterThan(0)
     */
    private $alcoholContent;

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAlcoholContent(): ?int
    {
        return $this->alcoholContent;
    }

    public function setAlcoholContent(int $alcoholContent): self
    {
        $this->alcoholContent = $alcoholContent;

        return $this;
    }
}
