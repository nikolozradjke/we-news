<?php

namespace App\Form;

use App\Dto\CommentDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CommentTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Your Name',
                    'id' => 'name',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Name is required.'),
                    new Assert\Length(max: 255, maxMessage: 'Name cannot exceed {{ limit }} characters.'),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Your Email',
                    'id' => 'email',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Email is required.'),
                    new Assert\Email(message: 'Please enter a valid email address.'),
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Share your thoughts...',
                    'id' => 'comment',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'Content cannot be empty.'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommentDto::class,
        ]);
    }
}