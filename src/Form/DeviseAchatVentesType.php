<?php

namespace App\Form;

use App\Entity\DeviseAchatVentes;
use App\Entity\Devises;
use phpDocumentor\Reflection\Types\Float_;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class DeviseAchatVentesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('devise', Devises::class )
            ->add('sens', ChoiceType::class
                ,array('choices'  => ['Achat'=>'a', 'Vente'=>'v']
                        ,'expend'=>true))
            ->add('nombre', Integer::class)
            ->add('taux', Float_::class)
            ->add('dateRecu', DateTime::class)
            ->add('nomPrenom', String_::class)
            ->add('typePiece', ChoiceType::class
                ,array('choices'  => ['CNI'=>1, 'Passport'=>2, 'Autre'=>3]))
            ->add('numPiece', String_::class)
            ->add('expireLe', DateTime::class)
            ->add('motif', String_::class)
        ;
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeviseAchatVentes::class,
        ]);
    }
}
