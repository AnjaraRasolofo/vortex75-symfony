<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [ 'label' => 'Nom'])
            ->add('company', TextType::class, ['label' => 'Company'])
            ->add('type', TextType::class, ['label' => 'Type'])
            ->add('description')
            ->add('price', TextType::class, ['label' => 'Prix'])
            ->add('image')
            ->add('submit', SubmitType::class, ['label' => "Ajouter"]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
