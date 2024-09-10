<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Product;
use App\Enums\ProductState;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{TextType, TextareaType, EnumType, SubmitType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class ProductEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('productNumber', TextType::class)
            ->add('description', TextareaType::class)
            ->add('state', EnumType::class, ['class' => ProductState::class])
            ->add('Save', SubmitType::class)
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => Product::class,
                    'fields' => 'productNumber',
                ]),
            ],
        ]);
    }
}
