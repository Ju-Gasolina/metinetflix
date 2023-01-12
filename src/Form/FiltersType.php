<?php

namespace App\Form;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\DateTime;

#[ORM\Entity]
#[ORM\Table(name: 'filters_type')]
class FiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add(
                'sortBy',
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

                    'attr' => ['class' => 'input-date d-flex'],
                    'widget' => 'single_text',
                    'required' => 'false',
                    'years' => range(1899, date('Y')+100),
                    'months' => range(date('m'), 12),
                    'days' => range(date('d'), 31),
                    'data' => new \DateTime(),
//                    'data' => new DateTime()
                ]
            )
            ->add( 'maxDate', DateType::class, [

                    'attr' => ['class' => 'input-date d-flex'],
                    'widget' => 'single_text',
                    'required' => 'false',
                    'years' => range(1899, date('Y')+100),
                    'months' => range(date('m'), 12),
                    'days' => range(date('d'), 31),
                    'data' => new \DateTime(),
                ]
            )

            ->add('maxTime', IntegerType::class, [
                'attr'=>[
                    'class'=>'form-control form-icon-trailing input-max-time',
                    'placeholder'=>'Max time (m)'

                ],
                'required' => false

            ])

            ->add('includeAdult', CheckboxType::class, [
                'label'    => 'Show this entry publicly?',
                'value' => false,
                'required' => false,
                'attr' => [
                    'class' => 'toggle-checkbox'
                ],
                'label_attr' => [
                    'class' => 'form-check-label'
                ]
            ])
            ->add('search', SubmitType::class, array('attr' => array('class' => 'button-basic-custom text-center')));


    }

}