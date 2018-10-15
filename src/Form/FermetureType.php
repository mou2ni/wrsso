<?php

namespace App\Form;

use App\Entity\JourneeCaisses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FermetureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('caisse')
        ;
    }

    public function getParent(){
        return JourneeCaissesType::class;
    }
}
