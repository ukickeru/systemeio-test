<?php

namespace App\Payments\Model\Entity;

use App\Shared\Entity\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Product
{
    use IdTrait;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    /**
     * @var int price in cents
     */
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $price;

    public function __construct(string $name, int $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
