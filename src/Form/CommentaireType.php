<?php

namespace App\Form;

use App\Entity\Commentaire;
use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;  
use Symfony\Component\Security\Core\Security;  
    

class CommentaireType extends AbstractType
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Contenu_C', TextareaType::class, [
                'label' => 'Your Comment',
                'attr' => ['placeholder' => 'Write your comment here']
            ])
            ->add('publication', EntityType::class, [
                'class' => Publication::class,
                'choice_label' => 'Titre_P',
                'label' => 'Choose Publication',
                'multiple' => false,
                'expanded' => false,    
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
