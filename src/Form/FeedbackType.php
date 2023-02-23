<?php

namespace App\Form;

use App\Entity\Feedback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sujet',ChoiceType::class,['placeholder'=>'** Select your subject **',

                'choices'=>[

                    'Coach' => 'Coach',
                    'Cour' => 'Cour',
                    'Payment' => 'Payment',
                    'Bug' => 'Bug',
                    'Other' => 'Other',

                ]
            ])
            ->add('description');


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feedback::class,
        ]);
    }
}
