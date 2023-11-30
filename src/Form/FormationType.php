<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DateTime;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

/**
 * Formulaire de formation.
 */
class FormationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('publishedAt', DateType::class,[
                    'widget'=>'single_text',
                    'data'=> isset($options['data'])&&
                    $options['data']->getPublishedAt() != null ? $options['data']->getPublishedAt(): new DateTime('now'),
                    'label'=>'Date de publication',
                    'constraints'=> new LessThanOrEqual('today')
                ])
                ->add('title',null,[
                    'required'=>true,
                    'label'=>'Titre'
                ])
                ->add('description')
                
                ->add('playlist', EntityType::class,[
                    'class'=>Playlist::class,
                    'choice_label'=>'name',
                    'required'=>true
                ])
                ->add('categories', EntityType::class,[
                    'class'=> Categorie::class,
                    'choice_label'=>'name',
                    'multiple'=>true,
                    'required'=>false
                ])
                ->add('submit', SubmitType::class,[
                    'label'=>'Enregistrer'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
