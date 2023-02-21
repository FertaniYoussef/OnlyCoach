<?php

namespace App\Form;

use App\Entity\Feedback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Sujet',ChoiceType::class,[

                'choices'=>[
                    '** Select your subject **' => '** Select your subject **',
                    'Coach' => 'Coach',
                    'Cours' => 'Cours',
                    'Payment' => 'Payment',
                    'Bug' => 'Bug',
                    'Other' => 'Other',

                ]
            ])

            ->add('email')
            ->add('description')
            ->add('Traiter', SubmitType::class, [
                'attr' => ['class' => 'save'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feedback::class,
        ]);
    }
}
