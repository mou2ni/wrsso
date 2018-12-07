<?php

namespace App\Form;

use App\Entity\SystemElectLigneInventaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemElectLigneInventairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('solde', NumberType::class,array('grouping'=>3,'scale'=>0, 'constraints'=>[new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual(0)]))
            //->add('idSystemElectInventaire')
            ->add('idSystemElect',TextType::class,array('disabled'=>true))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SystemElectLigneInventaires::class,
        ]);
    }
}
