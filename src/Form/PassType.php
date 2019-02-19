<?php

namespace App\Form;

use App\Entity\Utilisateurs;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PassType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('login')
            ->add('actmdp',\Symfony\Component\Form\Extension\Core\Type\TextType::class,['label'=>'Mot de passe actuel', 'mapped'=>false])
            ->add('mdp', RepeatedType::class,['type'=>PasswordType::class,
                'invalid_message' => 'Les mots de passes doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Repeter le mot de passe'],])
            //->add('')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}
