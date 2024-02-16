<?php

namespace App\Form;

use App\Entity\Link;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class LinkFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'bg-transparent block border-b-2 border-purple-dark mb-4
                        outline-none p-4 w-full text-purple-dark md:w-64',
                    'placeholder' => 'Enter the channel name'
                ],
                // 'label' => false,
            ])
            ->add('link', UrlType::class, [
                'attr' => [
                    'class' => 'bg-transparent block border-b-2 border-purple-dark mb-4
                    outline-none p-4 w-full text-purple-dark md:w-[28rem]',
                    'placeholder' => 'Enter the RSS link URL in full'
                ],
                // 'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Link::class,
        ]);
    }
}
