<?php

namespace App\Payments\Model\Entity\Coupon;

use App\Payments\Model\CouponVisitor\DiscountCalculationVisitorInterface;
use App\Payments\Model\Entity\Coupon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AbsoluteCoupon extends Coupon
{
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $discountValue;

    public function __construct(string $code, int $discountValue)
    {
        $this->discountValue = $discountValue;

        parent::__construct($code);
    }

    public function getDiscountValue(): int
    {
        return $this->discountValue;
    }

    public function setDiscountValue(int $discountValue): self
    {
        $this->discountValue = $discountValue;

        return $this;
    }

    public function acceptDiscountCalculationVisitor(DiscountCalculationVisitorInterface $couponVisitor): \Closure
    {
        return $couponVisitor->visitAbsolute($this);
    }
}
