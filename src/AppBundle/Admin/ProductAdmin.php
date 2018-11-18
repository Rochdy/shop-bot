<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Sonata\AdminBundle\Annotation as Sonata;

/**
 * @Sonata\Admin(
 *   class="AppBundle\Entity\Product",
 *   baseControllerName="Sonata\AdminBundle\Controller\CRUDController",
 *   group="Shop Admin",
 *   label="Products",
 *   showInDashboard=true,
 *   icon="<i class='fa fa-shopping-cart'></i>",
 *   keepOpen=true,
 * )
 */

class ProductAdmin extends AbstractAdmin
{
   protected function configureFormFields(FormMapper $formMapper)
   {
       $formMapper
           ->add('name', TextType::class)
           ->add('price', NumberType::class)
       ;
   }

   protected function configureDatagridFilters(DatagridMapper $datagridMapper)
   {
       $datagridMapper
           ->add('name')
           ->add('price')
       ;
   }

   protected function configureListFields(ListMapper $listMapper)
   {
       $listMapper
           ->addIdentifier('name')
           ->add('price')
       ;
   }
}