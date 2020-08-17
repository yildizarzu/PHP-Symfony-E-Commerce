<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categoryid')
            ->add('subcategoryid')
            ->add('urunadi')
            ->add('marka')
            ->add('fiyat')
            ->add('stok')
            ->add('durum')
            ->add('teknikozellikler')
            ->add('digerozellikler')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'allow_extra_fields' => true
        ]);
    }
}
