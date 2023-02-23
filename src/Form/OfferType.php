<?php

namespace App\Form;

use App\Entity\Coach;
use App\Entity\Offre;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
       
            ->add('Nom')
            ->add('Prix')
            ->add('Discount')
        ->add('dateDeb')
            ->add('dateFin')

            ->add('id_coach', EntityType::class, [
                'class' => Coach::class,
                'choice_label' => 'id',
                'attr' => [
                    'class' => 'form-control',

                ]
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
