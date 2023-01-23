<?php

namespace App\Form;

use App\Entity\WatchlistItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WatchlistItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Start date',
                'label_attr' => ['class'=>'log-form-label'],
                'attr' => ['autocomplete' => 'new-password','class'=>'form-control','placeholder'=>'************'],
                'required' => false,
            ])
            ->add('finish_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Finish date',
                'label_attr' => ['class'=>'log-form-label'],
                'attr' => ['autocomplete' => 'new-password','class'=>'form-control','placeholder'=>'************'],
                'required' => false,
            ])
            ->add('episode_progress', IntegerType::class, [
                'required' => false,
                'label_attr' => ['class'=>'log-form-label'],
                'attr' => ['autocomplete' => 'new-password','class'=>'form-control','placeholder'=>'************'],
            ])
            ->add('mark', IntegerType::class, [
                'required' => false,
                'label_attr' => ['class'=>'log-form-label'],
                'attr' => ['autocomplete' => 'new-password','class'=>'form-control','placeholder'=>'************'],
            ])
            ->add('personnal_note', TextType::class, [
                'required' => false,
                'label_attr' => ['class'=>'log-form-label'],
                'attr' => ['autocomplete' => 'new-password','class'=>'form-control','placeholder'=>'************'],
            ])
            ->add('status', ChoiceType::class,[
                'attr' => ['autocomplete' => 'new-password','class'=>'form-control','placeholder'=>'************'],
                'label_attr' => ['class'=>'log-form-label'],
                'choices'  => [
                    'Plan to watch' => 'Plan to watch',
                    'Completed' => 'Completed',
                    'Watching' => 'Watching',
                    'Paused' => 'Paused',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save',
                'attr'  => [
                    'class' => 'log-button-basic-custom',
                    'hx-post' => $options['action'],
                    'hx-target' => '#body'
                ],

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WatchlistItem::class,
        ]);
    }
}
