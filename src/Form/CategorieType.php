<?php

namespace App\Form;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    
            ->add('Type',TextType::class,[
                'required'=>false,
                'constraints'=>[new NotBlank()]
            ])
            ->add('Submit', SubmitType::class, [
                'label' => 'Ajouter catégorie',
                'attr' => [
                    'class' => 'mt-4 px-4 py-2 bg-sky-600 text-white rounded-md'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
            'validation_groups' => [],
        ]);
    }
}
