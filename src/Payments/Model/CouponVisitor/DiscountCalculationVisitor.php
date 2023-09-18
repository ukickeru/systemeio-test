<?php

namespace App\Payments\Model\CouponVisitor;

use App\Payments\Model\Entity\Coupon\AbsoluteCoupon;
use App\Payments\Model\Entity\Coupon\PercentCoupon;

class DiscountCalculationVisitor implements DiscountCalculationVisitorInterface
{
    public function visitAbsolute(AbsoluteCoupon $coupon): \Closure
    {
        $discountValue = $coupon->getDiscountValue();

        return static fn (float $productPrice) => max($productPrice - $discountValue, 0);
    }

    public function visitPercent(PercentCoupon $coupon): \Closure
    {
        $discountPercent = $coupon->getDiscountPercent();

        return static fn (float $productPrice) => max($productPrice - ($productPrice * ($discountPercent / 100)), 0);
    }
}
