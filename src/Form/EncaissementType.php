<?php

namespace App\Form;

use App\Entity\RecetteDepenses;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('mRetrait')
            ->remove('numCompteSaisie')
        ;
    }

    public function getParent(){
        return DepotRetraitsType::class;
    }
}
