<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Positive;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Nom de l'Article",
                'constraints' => [
                    new NotBlank([
                        'message' => "Le nom de l'article est requis"
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank([
                        'message' => 'La description est requise'
                    ])
                ]
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix (€)',
                'html5' => true,
                'attr' => [
                    'min' => '0',
                    'step' => '0.01'
                ],
                'constraints' => [
                    new Positive([
                        'message' => 'Le prix doit être positif'
                    ])
                ]
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Vêtements' => 'vêtements',
                    'Accessoires' => 'accessoires',
                    'Chaussures' => 'chaussures',
                    'Autres' => 'autres'
                ],
                'placeholder' => 'Sélectionnez une catégorie',
                'constraints' => [
                    new NotBlank([
                        'message' => 'La catégorie est requise'
                    ])
                ]
            ])
            ->add('genre', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                    'Unisexe' => 'unisexe'
                ],
                'placeholder' => 'Sélectionnez un genre',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le genre est requis'
                    ])
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => "Image de l'Article",
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPEG, PNG ou WEBP)'
                    ])
                ]
            ])
            ->add('stock', NumberType::class, [
                'label' => 'Quantité en Stock',
                'mapped' => false,
                'constraints' => [
                    new Positive([
                        'message' => 'La quantité doit être positive'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}