<?php

namespace App\Form;

use App\Entity\Doctor;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoctorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        ->add('user', EntityType::class, [
            'class' => User::class,
            'query_builder' => function (EntityRepository $entityRepository) {
                return $entityRepository->createQueryBuilder('u')
                ->join("u.userType",'ut')
                // ->leftJoin("u.doctor",'d')
                    ->where('ut.id = 2')
                    // ->where('ut.id = 2')
                  
                    ;
                   
                  
                       },
        ])
        ->add('specialization')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Doctor::class,
        ]);
    }
}
