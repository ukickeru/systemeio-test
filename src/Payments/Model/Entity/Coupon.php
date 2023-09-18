<?php

namespace App\Payments\Model\Entity;

use App\Payments\Model\Entity\Coupon\AbsoluteCoupon;
use App\Payments\Model\Entity\Coupon\PercentCoupon;
use App\Shared\Entity\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discr', type: Types::STRING, length: 8)]
#[ORM\DiscriminatorMap(['absolute' => AbsoluteCoupon::class, 'percent' => PercentCoupon::class])]
abstract class Coupon
{
    use IdTrait;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
