<?php

namespace App\Form;

use App\Entity\WatchlistItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WatchlistItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('item_type')
            ->add('start_date')
            ->add('finish_date')
            ->add('episode_progress')
            ->add('mark')
            ->add('personnal_note')
            ->add('watchlist')
            ->add('serie')
            ->add('season')
            ->add('episode')
            ->add('saga')
            ->add('movie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WatchlistItem::class,
        ]);
    }
}
