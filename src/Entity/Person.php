<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Person
 *
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 * @ORM\Table(name="person")
 */
class Person
{
    const STATE_AKTYWNY = 1;
    const STATE_BANNED = 2;
    const STATE_USUNIETY = 3;
    const STATES = [
        self::STATE_AKTYWNY => 'Aktywny',
        self::STATE_BANNED => 'Banned',
        self::STATE_USUNIETY => 'Usunięty'
    ];
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=10, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="l_name", type="string", length=100, nullable=false, options={"comment"="last name"})
     */
    private $lName;

    /**
     * @var string
     *
     * @ORM\Column(name="f_name", type="string", length=100, nullable=false, options={"comment"="first name"})
     */
    private $fName;

    /**
     * @var int
     *
     * @ORM\Column(name="state", type="smallint", nullable=false, options={"unsigned"=true,"comment"="1 - active, 2- banned, 3 - deleted"})
     */
    private $state;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="person")
     * @ORM\JoinTable(name="person_like_product",
     *   joinColumns={
     *     @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     *   }
     * )
     */
    private $product;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getFName() . ' ' . $this->getLName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getLName(): ?string
    {
        return $this->lName;
    }

    public function setLName(string $lName): self
    {
        $this->lName = $lName;

        return $this;
    }

    public function getFName(): ?string
    {
        return $this->fName;
    }

    public function setFName(string $fName): self
    {
        $this->fName = $fName;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->product->removeElement($product);

        return $this;
    }

    /**
     * @param $state
     * @return string
     */
    public function resolveStateName($state) : string
    {
        return self::STATES[$state];
    }

}
