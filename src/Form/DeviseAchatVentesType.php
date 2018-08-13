<?php

namespace App\Form;

use App\Form\DeviseMouvementsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DeviseAchatVentesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('sens');
    }

    public function getParent(){
        return DeviseMouvementsType::class;
    }
}