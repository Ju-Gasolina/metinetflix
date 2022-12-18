<?php

namespace App\Form;

use App\Entity\WatchlistItem;
use Symfony\Component\Form\AbstractType;
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
            ->add('start_date', DateType::class, array('required' => false))
            ->add('finish_date', DateType::class, array('required' => false))
            ->add('episode_progress', IntegerType::class, array('required' => false))
            ->add('mark', IntegerType::class, array('required' => false))
            ->add('personnal_note', TextType::class, array('required' => false));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WatchlistItem::class,
        ]);
    }
}
