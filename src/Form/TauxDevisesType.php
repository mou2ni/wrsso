<?php

namespace App\Form;

use App\Entity\Devises;
use App\Entity\DevisesCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TauxDevisesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->remove('sens')
            ->remove('libelle')
        ;
    }

    public function getParent(){
        return DevisesType::class;
    }
}
