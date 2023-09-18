<?php

namespace DataFixtures;

use App\Payments\Model\Entity\Coupon;
use App\Payments\Model\Entity\Coupon\AbsoluteCoupon;
use App\Payments\Model\Entity\Coupon\PercentCoupon;
use App\Payments\Model\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PaymentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getEntities() as $entity) {
            $manager->persist($entity);
        }

        $manager->flush();
    }

    /**
     * @return iterable<Product|Coupon>
     */
    private function getEntities(): iterable
    {
        yield new Product('Iphone', 10000);
        yield new Product('Headphones', 2000);
        yield new Product('Case', 1000);

        yield new AbsoluteCoupon('ABS1', 2000);
        yield new PercentCoupon('PER1', 6);
    }
}
