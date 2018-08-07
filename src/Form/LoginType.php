<?php

namespace App\Form;

use App\Entity\Utilisateurs;
use App\Entity\Caisses;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login')
            ->add('mdp', PasswordType::class)
			
			//->add('caisse', $this->getDoctrine()->getManager()->getRepository(Caisses::class)->findAll())
			/*->add('caisse',EntityType::class, [
                'class' => Caisses::class,
				//query_builder=>$this->getDoctrine()->getManager()->getRepository(Caisses::class)->findAll()
                //'query_builder' => function(UtilisateurRepository $repo) {
                //    return $repo->createAlphabeticalQueryBuilder();
               // }
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}
