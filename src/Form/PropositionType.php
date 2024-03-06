<?php

namespace App\Form;

use App\Entity\Proposition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title_proposition')
            ->add('title_proposition', TextType::class, [
                'label' => 'Titre de la proposition',
                'attr' => ['class' => 'form-control']
            ])
            ->add('score', NumberType::class, [
                'label' => 'Score',
                'attr' => ['class' => 'form-control']
            ])
            
            ->add('question', HiddenType::class, [
                'mapped' => false //si vous gérez cette propriété manuellement
           ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Proposition::class,
        ]);
    }
}
