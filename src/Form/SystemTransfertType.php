<?php

namespace App\Form;

use App\Entity\Caisses;
use App\Entity\SystemTransfert;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemTransfertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('banque', EntityType::class, array (
                'class' => 'App\Entity\Caisses',
                'choice_label' => 'code',
                'multiple' => false,
                'expanded'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.typeCaisse=:typeCaisse')->setParameter('typeCaisse', Caisses::COMPENSE)
                        ->orderBy('c.code', 'ASC');
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SystemTransfert::class,
        ]);
    }
}
