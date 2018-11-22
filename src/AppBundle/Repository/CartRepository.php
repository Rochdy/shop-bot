<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;

class CartRepository extends EntityRepository
{
    /**
     * @param Product $product
     * @param Customer $customer
     * @return object|bool
     */
    public function checkProductExistence(Product $product, Customer $customer)
    {
        $item = $this->findOneBy([
            'product' => $product,
            'customer' => $customer
            ]);
        if($item)
            return $item;
        return false;
    }
}