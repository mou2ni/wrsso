<?php

namespace App\Form;

use App\Entity\Clients;
use App\Entity\Comptes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('typeTier', ChoiceType::class
                ,array('choices'  => ['Client'=>Clients::TYP_CLIENT, 'Fournisseur'=>Clients::TYP_FOURNISSEUR, 'Collaboreur'=>Clients::TYP_PERSONNEL, 'Divers'=>Clients::TYP_DIVERS], 'required' => true
                ))
            //->add('adresse')
            //->add('comptes',CollectionType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Clients::class,
        ]);
    }
}
