<?php

namespace App\Form;
use App\Entity\Consultation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idp', null, [
            'label' => 'id patient:',
            ])
            ->add('idt', null, [
                'label' => 'id therapeute:', 
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

