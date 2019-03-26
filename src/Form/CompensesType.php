<?php

namespace App\Form;

use App\Entity\Compenses;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompensesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('dateDebut', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text'
            ])*/
            ->add('totalEnvoi', NumberType::class, array('disabled' => true,'grouping'=>3,'scale'=>0))
            ->add('totalReception', NumberType::class, array('disabled' => true,'grouping'=>3,'scale'=>0))
            ->add('caisse', EntityType::class, array (
                'class' => 'App\Entity\Caisses',
                'choice_label' => 'libelle',
                'multiple' => false,
                'expanded'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->qbFindBanques();
                }))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Compenses::class,
        ]);
    }
}
