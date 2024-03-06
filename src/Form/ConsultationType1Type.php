<?php

namespace App\Form;

use App\Entity\Consultation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Fichemedicale;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
class ConsultationType1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder

        /*->add('fichemedicale', EntityType::class, [
            'class' => Fichemedicale::class,
            'choice_label' => 'id',
            'multiple' => false,
            'expanded' => false,
            'label' => 'Id fiche medicale:',
        ])*/
        ->add('date_c', null, [
            'label' => 'Date Consultation:', 
            'disabled' => true, // Disable the label
        ])
        ->add('pathologie', null, [
            'label' => 'Pathologie:', 
            'disabled' => true, // Disable the label
        ])
        ->add('remarques', null, [
            'label' => 'Remarques:', 
            'disabled' => true, // Disable the label
        ])
        ->add('confirmation', ChoiceType::class, [
            'label' => 'Confirmation',
            'choices' => [
                'Confirmé' => true,
                'Non Confirmé' => false,
            ],
            'required' => true, // Depending on your requirements
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
