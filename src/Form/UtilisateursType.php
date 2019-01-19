<?php

namespace App\Form;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateursType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login')
            ->add('mdp')
            ->add('nom')
            ->add('prenom')
            ->add('estCaissier')
            //->add('status')
            ->add('role', ChoiceType::class
                ,array('choices'  => ['GUICHETIER'=>Utilisateurs::ROLE_GUICHETIER,'COMPTABLE'=>Utilisateurs::ROLE_COMPTABLE, 'ADMIN'=>Utilisateurs::ROLE_ADMIN], 'required' => true, 'mapped' =>false
                ))
            ->add('compte')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}
