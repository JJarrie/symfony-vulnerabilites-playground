<?php

namespace App\Form\Type\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends AbstractType<User>
 */
class RegistrationFormType extends AbstractType
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('username', TextType::class, [
            'label' => 'form.registration.username.label',
            'attr' => [
                'placeholder' => 'form.registration.username.placeholder',
                'data-icon-left' => 'ph-bold ph-user',
            ],
        ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'form.registration.password.first.label',
                    'attr' => [
                        'placeholder' => 'form.registration.password.first.placeholder',
                        'data-icon-left' => 'ph-bold ph-lock',
                    ],
                ],
                'second_options' => [
                    'label' => 'form.registration.password.second.label',
                    'attr' => [
                        'placeholder' => 'form.registration.password.second.placeholder',
                        'data-icon-left' => 'ph-bold ph-lock',
                    ],
                ],
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $formData = $event->getData();

            if (\is_array($formData) && \array_key_exists('password', $formData) && \is_string($formData['password'])) {
                $formData['password'] = $this->hasher->hashPassword(new User(), $formData['password']);
            }

            $event->setData($formData);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
