<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PubRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Pub
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Beer", cascade={"persist"})
     * @Assert\Valid()
     *
     * @var Collection
     */
    private $beers;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Beer", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="beer_of_month")
     * @Assert\Valid()
     *
     * @var Beer
     */
    public $beer;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Wine", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @Assert\Valid()
     *
     * @var Collection
     */
    private $wines;

    public function __construct()
    {
        $this->beers = new ArrayCollection();
        $this->wines = new ArrayCollection();
    }

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

    /**
     * @return Collection
     */
    public function getBeers(): Collection
    {
        return $this->beers;
    }

    /**
     * @param Beer $beer
     *
     * @return $this
     */
    public function addBeer(Beer $beer): Pub
    {
        $this->beers->add($beer);

        return $this;
    }

    /**
     * @param Beer $beer
     *
     * @return $this
     */
    public function removeBeer(Beer $beer): Pub
    {
        $this->beers->remove($beer);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getWines(): Collection
    {
        return $this->wines;
    }

    /**
     * @param Wine $beer
     *
     * @return $this
     */
    public function addWine(Wine $beer): Pub
    {
        $this->wines->add($beer);

        return $this;
    }

    /**
     * @param Wine $beer
     *
     * @return $this
     */
    public function removeWine(Wine $beer): Pub
    {
        $this->wines->remove($beer);

        return $this;
    }

    /**
     * @return Beer
     */
    public function getBeer(): Beer
    {
        return $this->beer;
    }

    /**
     * @param Beer $beer
     */
    public function setBeer(Beer $beer): void
    {
        $this->beer = $beer;
    }

    /**
     * @ORM\PostPersist()
     */
    public function doSomething()
    {
        // do something
    }
}
