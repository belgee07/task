<?php

// src/Form/SalesType.php
namespace App\Form;

use App\Entity\Sales;
use App\Entity\Department;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'departmentName',
            ])
            ->add('amount', NumberType::class, [
                'label' => 'Sales Amount',
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Sales Date',
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sales::class,
        ]);
    }
}
