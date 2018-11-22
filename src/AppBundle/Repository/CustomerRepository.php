<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Customer;
use Doctrine\ORM\EntityRepository;

class CustomerRepository extends EntityRepository
{
    /**
     * @param array $criteria
     * @return Customer|null|object
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function findOneOrCreate(array $criteria)
    {
        $customer = $this->findOneBy($criteria);

        if (null === $customer) {
            /** @var Customer $customer */
            $customer = new Customer();
            $customer->setFacebookRecipientId($criteria['facebookRecipientId']);
            $customer->setCreatedAt(new \DateTime());
            $this->_em->persist($customer);
            $this->_em->flush();
        }

        return $customer;
    }
}