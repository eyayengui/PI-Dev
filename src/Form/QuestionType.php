<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Questionnaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title_question')
            ->add('questionnaire', EntityType::class, [
                'class' => Questionnaire::class,
                'choice_label' => 'title_questionnaire',
                'label' => 'Choose Questionnaire',
                'multiple' => false,
                'expanded' => false,])
                ->add('propositions', CollectionType::class, [
                    'entry_type' => PropositionType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
