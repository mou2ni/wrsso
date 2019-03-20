<?php

namespace App\Form;

use App\Entity\TransfertInternationaux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;

class TransfertInternationauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('sens' ChoiceType::class, array(
                'choices'  => array(
                    'Envoi' => 1,
                    'Reception' => 2)))*/
            /*->add('mTransfert', NumberType::class,array('grouping'=>3,'scale'=>0, 'attr'=>['class'=>'mtransfert'], 'constraints'=>[new \Symfony\Component\Validator\Constraints\GreaterThan(0)]))
            ->add('mTransfertTTC', NumberType::class,array('grouping'=>3,'scale'=>0, 'attr'=>['class'=>'transfertttc'], 'constraints'=>[new \Symfony\Component\Validator\Constraints\GreaterThan(0)]))
            ->add('mFraisHt', NumberType::class,array('grouping'=>3,'scale'=>0, 'attr'=>['class'=>'mfraisht'], 'constraints'=>[new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual(0)]))
            ->add('mTva', NumberType::class,array('grouping'=>3,'scale'=>0, 'attr'=>['class'=>'mtva'], 'constraints'=>[new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual(0)]))
            ->add('mAutresTaxes', NumberType::class,array('grouping'=>3,'scale'=>0, 'attr'=>['class'=>'mautrestaxes']))
            //->add('idJourneeCaisse')
            ->add('idSystemTransfert')
            ->add('idPays')
        ;*/
            ->add('sens', ChoiceType::class, array(
                'choices'  => array('Reception' => TransfertInternationaux::RECEPTION,'Envoi' => TransfertInternationaux::ENVOI)
                ,'mapped'=>true))
            ->add('idSystemTransfert')
            ->add('idPays')
            ->add('mTransfert', NumberType::class,array('grouping'=>3,'scale'=>0, 'attr'=>['class'=>'mtransfert'], 'constraints'=>[new \Symfony\Component\Validator\Constraints\GreaterThan(0)]))
            ->add('mTransfertTTC', NumberType::class,array('grouping'=>3,'scale'=>0, 'attr'=>['class'=>'transfertttc'], 'constraints'=>[new \Symfony\Component\Validator\Constraints\GreaterThan(0)]))
            ->add('dateTransfert', DateType::class, [
                'widget' => 'single_text'
            ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TransfertInternationaux::class,
        ]);
    }
}
