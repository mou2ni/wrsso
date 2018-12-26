<?php

namespace App\Form;

use App\Entity\Caisses;
use App\Entity\Utilisateurs;
use App\Repository\CaissesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateursLastCaisseType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastCaisse', EntityType::class, array(
                'class' => Caisses::class,
                'query_builder' => function(CaissesRepository $repository) {
                    return $repository->getClosedCaisseQb();
                },
                'choice_label' => 'code',
                'multiple' => false,
                'expanded'=>false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}
