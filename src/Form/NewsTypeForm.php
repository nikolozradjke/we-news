<?php

namespace App\Form;

use App\Dto\NewsDto;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class NewsTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(message: 'Title is required.'),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Title must not exceed {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('shortDescription', TextType::class, [
                'label' => 'Short Description',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(message: 'Short description is required.'),
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Content',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control', 'rows' => 6],
                'constraints' => [
                    new Assert\NotBlank(message: 'Content is required.'),
                ],
            ])
            ->add('picture', FileType::class, [
                'label' => 'Picture',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'mapped' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please upload a picture.',
                    ]),
                    new Assert\Image([
                        'maxSize' => '2M',
                        'maxSizeMessage' => 'The picture cannot be larger than 2MB.',
                    ]),
                ],
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'multiple' => true,
                'label' => 'Categories',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-select'],
                'expanded' => false,
                'constraints' => [
                    new Assert\Count([
                        'min' => 1,
                        'minMessage' => 'Please select at least one category.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NewsDto::class,
        ]);
    }
}
