<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Annotation as Sonata;

/**
 * @Sonata\Admin(
 *   class="AppBundle\Entity\Customer",
 *   baseControllerName="Sonata\AdminBundle\Controller\CRUDController",
 *   group="Shop Admin",
 *   label="Customers",
 *   showInDashboard=true,
 *   icon="<i class='fa fa-shopping-cart'></i>",
 *   keepOpen=true,
 * )
 */

class CustomerAdmin extends AbstractAdmin
{
   protected function configureDatagridFilters(DatagridMapper $datagridMapper)
   {
       $datagridMapper->add('facebookRecipientId');
   }

   protected function configureListFields(ListMapper $listMapper)
   {
       $listMapper
           ->addIdentifier('id', null, [
               'row_align' => 'center'
           ])
           ->add('facebookRecipientId')
       ;
   }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept('list');
    }
}