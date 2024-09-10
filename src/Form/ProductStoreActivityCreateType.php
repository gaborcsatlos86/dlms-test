<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\ProductStoreActivity;
use App\Enums\ProductStoreAction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{EnumType, NumberType, SubmitType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductStoreActivityCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('action', EnumType::class, ['class' => ProductStoreAction::class])
            ->add('amount', NumberType::class)
            ->add('Save', SubmitType::class)
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductStoreActivity::class,
        ]);
    }
}
