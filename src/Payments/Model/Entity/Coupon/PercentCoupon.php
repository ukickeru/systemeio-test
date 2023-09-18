<?php

namespace App\Payments\Model\Entity\Coupon;

use App\Payments\Model\Entity\Coupon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PercentCoupon extends Coupon
{
    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $discountPercent;

    public function __construct(string $code, int $discountPercent)
    {
        $this->discountPercent = $discountPercent;

        parent::__construct($code);
    }

    public function getDiscountPercent(): int
    {
        return $this->discountPercent;
    }

    public function setDiscountPercent(int $discountPercent): self
    {
        $this->discountPercent = $discountPercent;

        return $this;
    }
}
