<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('middleName')
            ->add('lastName')
            ->add('gender',ChoiceType::class,[
                "choices"=>["Male"=>"Male", "Female"=>"Female"],"placeholder"=>"Select Gender"
            ])
            ->add('birthDate')
         
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
