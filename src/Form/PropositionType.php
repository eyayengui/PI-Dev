<?php

namespace App\Form;

use App\Entity\Proposition;
<<<<<<< Updated upstream
use App\Entity\Question;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
=======
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
>>>>>>> Stashed changes
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropositionType extends AbstractType
{
<<<<<<< Updated upstream
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('title_proposition')
                ->add('id_Q', HiddenType::class, [
                     'mapped' => false //si vous gérez cette propriété manuellement
                ])
                ->add('score', IntegerType::class, [
                    'label' => 'Score',
                    'required' => true,
                ]);

            
        }
=======
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title_proposition')
            ->add('question', HiddenType::class, [
                'mapped' => false //si vous gérez cette propriété manuellement
           ])
           ->add('score', IntegerType::class, [
               'label' => 'Score',
               'required' => true,
           ]);
        ;
    }
>>>>>>> Stashed changes

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Proposition::class,
        ]);
    }
}
