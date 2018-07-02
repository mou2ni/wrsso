<?php

namespace App\Form;

use App\Entity\SystemElectLigneInventaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemElectLigneInventairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('solde')
            ->add('idSystemElectInventaire')
            ->add('idSystemElect')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SystemElectLigneInventaires::class,
        ]);
    }
}
