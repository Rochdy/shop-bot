<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Annotation as Sonata;

/**
 * @Sonata\Admin(
 *   class="AppBundle\Entity\Cart",
 *   baseControllerName="Sonata\AdminBundle\Controller\CRUDController",
 *   group="Shop Admin",
 *   label="Carts",
 *   showInDashboard=true,
 *   icon="<i class='fa fa-shopping-cart'></i>",
 *   keepOpen=true,
 * )
 */

class CartAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('customer')
            ->add('product')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('customer')
            ->add('product',null, [
                'route' => ['name' => '']
            ])
            ->add('quantity', null, [
            'row_align' => 'center'
        ]);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept('list');
    }
}