<?php

namespace App\Form;

use App\Dto\CategoryDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'Title',
            'required' => true,
            'attr' => ['class' => 'form-control', 'placeholder' => 'Enter category title'],
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Title is required.',
                ]),
                new Assert\Length([
                    'max' => 255,
                    'maxMessage' => 'Title must not exceed {{ limit }} characters.',
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CategoryDto::class,
        ]);
    }
}
