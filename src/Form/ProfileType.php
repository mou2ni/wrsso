<?php

namespace App\Form;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login')
            //->add('mdp')
            ->add('nom')
            ->add('prenom')
            ->add('estCaissier')
            //->add('status')
            ->add('compte')
            //->add('compteEcartCaisse')
            ->add('role')
            /*->add('')
            ->add('')
            ->add('')
            ->add('')
            ->add('')
            ->add('')
            ->add('')
            ->add('')*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}
