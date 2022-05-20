<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $role=["System Admin" => "ROLE_ADMIN","Doctor"=>"ROLE_DOCTOR","Patient"=>"ROLE_PATIENT"];

        $builder
            ->add('email')
            ->add('roles',ChoiceType::class,[
                "choices"=>$role,'mapped'=>false,"multiple"=>true,"placeholder"=>"Select Role"
            ])
            ->add('firstName')
            ->add('middleName')
            ->add('lastName')
            ->add('gender',ChoiceType::class,[
                "choices"=>["Male"=>"Male", "Female"=>"Female"],"placeholder"=>"Select Gender"
            ])
            ->add('phone')
            ->add('userType')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
