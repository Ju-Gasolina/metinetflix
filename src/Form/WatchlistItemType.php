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
                'required' => false,
            ])
            ->add('finish_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Finish date',
                'required' => false,
            ])
            ->add('episode_progress', IntegerType::class, [
                'required' => false,
            ])
            ->add('mark', IntegerType::class, [
                'required' => false,
            ])
            ->add('personnal_note', TextType::class, [
                'required' => false,
            ])
            ->add('status', ChoiceType::class,[
                'attr' => ['class'=>''],
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
                    'class' => 'btn btn-primary',
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
