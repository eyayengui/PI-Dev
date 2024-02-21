<?php

namespace App\Form;

use App\Entity\Fichemedicale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class FicheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_p', null, [
            'label' => 'Id Patient:', // Set custom label for Id Patient field
            ])
            ->add('id_t', null, [
                'label' => 'Id Therapeute:', // Set custom label for Id Patient field
                ])
            ->add('date_creation')
            ->add('derniere_maj', null, [
                'label' => 'Date de derniére mise à jour:', // Set custom label for Id Patient field
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
