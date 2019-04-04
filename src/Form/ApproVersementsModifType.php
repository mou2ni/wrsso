<?php

namespace App\Form;

use App\Entity\Compenses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApproVersementsModifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('mSaisie')
            ->remove('journeeCaissePartenaire')
            ->add('dateOperation', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('mApproVersement')
            ->add('libelle')
        ;
    }

    public function getParent(){
        return ApproVersementsType::class;
    }
}
