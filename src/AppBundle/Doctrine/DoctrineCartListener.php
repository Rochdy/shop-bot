<?php

namespace AppBundle\Doctrine;

use JMS\DiExtraBundle\Annotation\DoctrineListener as DoctrineDI;

/**
 * @DoctrineDI(
 *     events = {"postPersist", "preRemove"},
 *     connection = "default",
 *     lazy = true,
 *     priority = 0,
 * )
 */
class DoctrineCartListener
{
}