<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, array(
                'attr' => array(
                    'class' => 'form-control search-bar-input',
                    'placeholder' => 'Search a tv show or a movie'),
                'label' => false))
            ->add('search', SubmitType::class,  array('attr' => array('class' => 'btn btn-outline col-2 search-bar-btn')));
    }
}