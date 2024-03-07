<?php

namespace App\Form;

use App\Entity\Programme;
use App\Entity\Activite;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProgrammeType extends AbstractType
{   
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lieu')   
            ->add('but')
            ->add('nom_L')
            ->add('activite', EntityType::class, [
                'class' => Activite::class,
                'choice_label' => 'nom',
                'label' => 'Choose Activite',
                'multiple' => false,
                'expanded' => false,    
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (JPEG, PNG)', // Set your desired label
                'mapped' => false,
                'required' => false, // Set to true if the field is required
                // Add more options as needed, such as constraints
            ])
    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
