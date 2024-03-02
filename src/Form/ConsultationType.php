<?php

namespace App\Form;
use App\Entity\Consultation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idp', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom', // Use the username property as the choice label
            ])
            ->add('idt', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom', // Use the username property as the choice label
            ])
            ->add('date_c', null, [
                'label' => 'Date Consultation:',
            ])
            ->add('pathologie', null, [
                'label' => 'Pathologie:', 
            ])
            ->add('remarques', null, [
                'label' => 'Remarques:', 
            ])
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}

