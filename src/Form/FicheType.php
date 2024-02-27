<?php

namespace App\Form;
use App\Validator\Constraints\DerniereMajGreaterThanCreation;
use App\Entity\Fichemedicale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
class FicheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_p', null, [
            'label' => 'Id Patient:',
            ])
            ->add('id_t', null, [
                'label' => 'Id Therapeute:', 
                ])
            ->add('date_creation')
            ->add('derniere_maj', null, [
                'label' => 'Date de derniére mise à jour:',
            ])
            ->add('Ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fichemedicale::class,
        ]);
    }
}
