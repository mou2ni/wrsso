<?php

namespace App\Form;

use App\Entity\Devises;
use App\Entity\DevisesCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TauxDevisesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('code', EntityType::class, ['class'=>Devises::class, 'placeholder'=>'CHOISIR'])
            ->add('code', TextType::class, ['disabled'=>true])
            ->add('txReference')
            ->add('formuleAchat')
            ->add('formuleVente')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Devises::class,
        ]);
    }
}
