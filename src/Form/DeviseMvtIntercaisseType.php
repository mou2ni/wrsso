<?php

namespace App\Form;

use App\Entity\DeviseMouvements;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviseMvtIntercaisseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('sens')->remove('taux');
    }

    public function getParent(){
        return DeviseMouvementsType::class;
    }
}
