<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\ChoiceToValueTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        <input placeholder="name@example.com" class="form-control" type="email" id="username" name="_username" value="{{ last_username }}">
//        <label class="log-form-label" for="username">Email</label>
        $builder
            ->add('email', EmailType::class, [
                'label_attr' => ['class'=>'log-form-label'],
                'attr' => ['class'=>'form-control','placeholder'=>'Email'],

            ])
            ->add('agreeTerms', CheckboxType::class, [
                'attr'=> ['class'=>'form-check-input'],
                'label_attr' =>['class'=>'form-check-label mx-2'],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label_attr' => ['class'=>'log-form-label'],
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password','class'=>'form-control','placeholder'=>'************'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',

                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('firstname', TextType::class,[
                'label_attr' => ['class'=>'log-form-label'],
                'attr' => ['class'=>'form-control','placeholder'=>'Firstname'],
            ])
            ->add('lastname', TextType::class,[
                'label_attr' => ['class'=>'log-form-label'],
                'attr' => ['class'=>'form-control','placeholder'=>'Lastname'],
            ])
            ->add('birthday', DateType::class, [
                'attr' => ['class'=>'form-control log-input-height'],
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('gender', ChoiceType::class,[
                'attr' => ['class'=>'form-select log-input-height', 'aria-label'=>'genders select'],
                'choices'  => [
                    'Unreferenced' => 'unreferenced',
                    'Man' => 'women',
                    'Women' => 'man',
                ],
            ])
            ->add('username', TextType::class,[
                'label_attr' => ['class'=>'log-form-label'],
                'attr' => ['class'=>'form-control','placeholder'=>'Username'],
            ])


        ;
//        <select class="form-select" aria-label="Default select example">
//                                    <option selected>Open this select menu</option>
//                                    <option value="1">One</option>
//                                    <option value="2">Two</option>
//                                    <option value="3">Three</option>
//                                </select>

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
