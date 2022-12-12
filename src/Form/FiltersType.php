<?php

namespace App\Form;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;

#[ORM\Entity]
#[ORM\Table(name: 'filters_type')]
class FiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add(
                'alphabetical',
                ChoiceType::class,
                [

                    'choices' => [

                        'Vote average ↑' => 'vote_average.asc',
                        'Vote average ↓' => 'vote_average.desc',
                        'Popularity ↑' => 'popularity.asc',
                        'Popularity ↓' => 'popularity.desc',
                        'Release Date ↑' => 'date.asc',
                        'Release Date ↓' => 'date.desc',
                        'None' => 'none',

                    ],
                    'choice_attr' => [
                        'Vote average ↑' => ['class' => 'btn-check'],
                        'Vote average ↓' => ['class' => 'btn-check'],
                        'Popularity ↑' => ['class' => 'btn-check'],
                        'Popularity ↓' => ['class' => 'btn-check'],
                        'Release Date ↑' => ['class' => 'btn-check'],
                        'Release Date ↓' => ['class' => 'btn-check'],
                        'None' => ['class'=>'btn-check'],
                    ],
                    'expanded' => true,

                ]
            )
            ->add( 'minDate', DateType::class, [

                    'widget' => 'choice',
                    'required' => 'false',
                ]
            )
            ->add( 'maxDate', DateType::class, [

                    'widget' => 'choice',
                    'required' => 'false',
                ]
            )

            ->add('maxTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'choice',
                'required' => 'false',
            ])
            ->add('includeAdult', CheckboxType::class, [
                'label'    => 'Show this entry publicly?',
                'required' => false,
                'attr' => [
                    'class' => ''
                ]
            ])
            ->add('search', SubmitType::class, array('attr' => array('class' => 'button-basic-custom text-center')));


    }

}