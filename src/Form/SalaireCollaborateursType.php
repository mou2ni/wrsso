<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;


class SalaireCollaborateursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('dateNaissance')
            ->remove('dateEntree')
            ->remove('dateSortie')
            ->remove('statut')
            ->remove('nbEnfant')
            ->remove('categorie')
            ->remove('nSecuriteSociale')
            ->remove('compteVirement')
            ->remove('entreprise')
            ->remove('dernierSalaire')
        ;
    }

    public function getParent(){
        return CollaborateursType::class;
    }
}
