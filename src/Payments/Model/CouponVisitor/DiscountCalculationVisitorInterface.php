<?php

namespace App\Payments\Model\CouponVisitor;

use App\Payments\Model\Entity\Coupon\AbsoluteCoupon;
use App\Payments\Model\Entity\Coupon\PercentCoupon;

interface DiscountCalculationVisitorInterface
{
    /**
     * @return \Closure(float $productPrice): float
     */
    public function visitAbsolute(AbsoluteCoupon $coupon): \Closure;

    /**
     * @return \Closure(float $productPrice): float
     */
    public function visitPercent(PercentCoupon $coupon): \Closure;
}
