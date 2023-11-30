<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire de catégories.
 */
class CategorieType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('name', null, [
                    'required' => true,
                    'label' => 'Nom de la catégorie',
                ])
                ->add('submit', SubmitType::class, [
                    'label' => 'Valider'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => 'name',
                    'message' => 'Le nom de catégorie doit être unique'
                        ])
            ]
        ]);
    }
}
