<?php

namespace App\Form;
use App\Validator\Constraints\DerniereMajGreaterThanCreation;
use App\Entity\Fichemedicale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
class FicheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_p', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom', // Use the username property as the choice label
            ])
            ->add('id_t', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom', // Use the username property as the choice label
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
