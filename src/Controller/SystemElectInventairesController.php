<?php

namespace App\Controller;

use App\Entity\JourneeCaisses;
use App\Entity\SystemElectInventaires;
use App\Entity\SystemElectLigneInventaires;
use App\Entity\SystemElects;
use App\Form\SystemElectInventairesType;
use App\Form\SystemElectsType;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SystemElectInventairesController extends Controller
{
    private $journeeCaisse;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->journeeCaisse=$sessionUtilisateur->getJourneeCaisse();
        if(!$this->journeeCaisse){
            return $this->redirectToRoute('app_login');
        }
    }
    /**
     * @Route("/system/elect/inventaires", name="system_elect_inventaires_index", methods="GET")
     */
    public function index(): Response
    {
        $systemElectInventaires = $this->getDoctrine()
            ->getRepository(SystemElectInventaires::class)
            ->findAll();

        return $this->render('system_elect_inventaires/index.html.twig', ['system_elect_inventaires' => $systemElectInventaires]);
    }

    /**
     * @Route("/system/elect/inventaires/new", name="system_elect_inventaires_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $systemElectInventaire = new SystemElectInventaires();
        $form = $this->createForm(SystemElectInventairesType::class, $systemElectInventaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($systemElectInventaire);
            $em->flush();

            return $this->redirectToRoute('system_elect_inventaires_index');
        }

        return $this->render('system_elect_inventaires/new.html.twig', [
            'system_elect_inventaire' => $systemElectInventaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/system/elect/inventaires/{id}", name="system_elect_inventaires_show", methods="GET|UPDATE")
     */
    public function show(SystemElectInventaires $systemElectInventaire): Response
    {
        foreach (explode(';',$systemElectInventaire->getSystemElectLigneInventaire()) as $lg)
        {
            //dump(count(explode('x',$lg)));
            if (count(explode('=',$lg))>1){
                $valeur = explode('=',$lg)['1'];
                $libelle = explode('=',$lg)['0'];
                //dump($libelle);dump($valeur);

            $elect = $this->getDoctrine()
                ->getRepository(SystemElects::class)
                ->findOneBy(['Libelle' => $libelle]);
            $seli = new SystemElectLigneInventaires();
            $seli->setIdSystemElect($elect)->setSolde($valeur);
            //dump($seli);
            $systemElectInventaire->addSystemElectLigneInventaires($seli);
            }
            //$em->persist($bl);
        }
        //dump($systemElectInventaire);die();
        $systemElectLigneInventaires = $this->getDoctrine()
            ->getRepository(SystemElectLigneInventaires::class)
            ->findBy(['idSystemElectInventaire' => $systemElectInventaire->getId()]);
        return $this->render('system_elect_inventaires/show.html.twig',[
            'system_elect_inventaire' => $systemElectInventaire,
            'system_elect_Ligne_inventaires' => $systemElectLigneInventaires
            ]);
    }

    /**
     * @Route("/system/elect/inventaires/{id}/edit", name="system_elect_inventaires_edit", methods="GET|POST")
     */
    public function edit(Request $request, SystemElectInventaires $systemElectInventaire): Response
    {
        $form = $this->createForm(SystemElectInventairesType::class, $systemElectInventaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('system_elect_inventaires_edit', ['id' => $systemElectInventaire->getId()]);
        }

        return $this->render('system_elect_inventaires/edit.html.twig', [
            'system_elect_inventaire' => $systemElectInventaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/system/elect/inventaires/{id}", name="system_elect_inventaires_delete", methods="DELETE")
     */
    public function delete(Request $request, SystemElectInventaires $systemElectInventaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$systemElectInventaire->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($systemElectInventaire);
            $em->flush();
        }

        return $this->redirectToRoute('system_elect_inventaires_index');
    }

    ////////////////cette partie s'occupe de l'enregistrement des soldes Electroniques
    ///

    /**
     * @Route("/electroniques/{operation}", name="electroniques_ajout")
     */
    public function ajoutAction(Request $request, $operation)
    {
        $em = $this->getDoctrine()->getManager();
        $systemElects = $em->getRepository('App:SystemElects')->findAll();
        $systemElectInventaire =($operation == 'OUV')? $this->journeeCaisse->getSystemElectInventOuv():
            $this->journeeCaisse->getSystemElectInventFerm();

                //////////// creation du formulaire personnalise///////////////////////////////
        if (!$systemElectInventaire) {
            //dump("$systemElectInventaire n'existe pas");
            $systemElectInventaire=new SystemElectInventaires();
            $systemElectInventaire->setDateInventaire(new \DateTime());
            ////////////////Creation des lignes ///////////////////////////////
            foreach ($systemElects as $elect) {
                $systemElectLigneInventaire = new SystemElectLigneInventaires();
                $systemElectLigneInventaire->setIdSystemElect($elect)->setSolde(0)->setIdSystemElectInventaire($systemElectInventaire->getId());
                $systemElectInventaire->addSystemElectLigneInventaires($systemElectLigneInventaire);
            }
        }
        elseif ($systemElectInventaire->getSystemElectLigneInventaires()->isEmpty()){
            //dump("$systemElectInventaire->getSystemElectLigneInventaires() est vide");
            $systemElectInventaire->setDateInventaire(new \DateTime());
            ////////////////Creation des lignes ///////////////////////////////
            foreach ($systemElects as $elect) {
                $systemElectLigneInventaire = new SystemElectLigneInventaires();
                $systemElectLigneInventaire->setIdSystemElect($elect)->setSolde(0)->setIdSystemElectInventaire($systemElectInventaire->getId());
                $systemElectInventaire->addSystemElectLigneInventaires($systemElectLigneInventaire);
            }
        }
        //////SUPPRESSION D'EVENTUELLES LIGNES SUPPLEMENTAIRES
        while($systemElectInventaire->getSystemElectLigneInventaires()->count()>count($systemElects)) {
            $occurence=0;
            foreach ($systemElectInventaire->getSystemElectLigneInventaires() as $seli1){
                foreach ($systemElectInventaire->getSystemElectLigneInventaires() as $seli2) {
                    if ($seli1->getIdSystemElect() == $seli2->getIdSystemElect() && $seli1 != $seli2) {
                        $systemElectInventaire->removeSystemElectLigneInventaires($seli2);
                        $occurence=$occurence+1;
                        break;
                    }
                }
                if ($occurence>0){
                    break;
                }
            }

        }

        $form = $this->createForm(SystemElectInventairesType::class, $systemElectInventaire);
        // only handles data on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $this->journeeCaisse) {
            $systemElectInventaire->setSystemElectLigneInventaire('');
            foreach ($systemElectInventaire->getSystemElectLigneInventaires() as $seli){
                $systemElectInventaire->setSystemElectLigneInventaire($systemElectInventaire->getSystemElectLigneInventaire().''.$seli->getIdSystemElect()->getLibelle().'='.$seli->getSolde().';');
                $em->persist($seli);
            }

            $em->persist($systemElectInventaire);
            ($operation == 'OUV')?$this->journeeCaisse->setMSoldeElectOuv($this->journeeCaisse->getSystemElectInventOuv()->getSoldeTotal()):
            $this->journeeCaisse->setMSoldeElectFerm($this->journeeCaisse->getSystemElectInventFerm()->getSoldeTotal());
            $em->persist($this->journeeCaisse);
            $em->flush();
            $this->addFlash('success', 'Inventaire electronique Créé!');
            //die();
            return $this->redirectToRoute('journee_caisses_gerer');
            //return $this->render('system_elect_ligne_inventaires/index.html.twig', ['system_elect_ligne_inventaires' => $systemElectInventaire->getSystemElectLigneInventaires()]);
        }
        return $this->render('system_elect_inventaires/ajout.html.twig', [
            'form' => $form->createView()
            ,'elects'=>$systemElects,
            'journeeCaisse'=>$this->journeeCaisse,
            'operation'=>$operation
        ]);
    }

    /**
     * @Route("Wrsso/electronique/lignes/ajout", name="electronique_ligne_ajout")

    public function ajoutAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $systemElects = $em->getRepository('App:SystemElects')->findAll();
        //////////// creation du formulaire personnalise///////////////////////////////
        $formElect = $this->get('form.factory')->createNamedBuilder('formElectronic')->getForm();
        $i=1;
        foreach ($systemElects as $elect) {
            $formElect->add('SystemElect'.$i, TextType::class, array('disabled'=>true))
                ->add('solde'.$i,IntegerType::class);
            $i++;
        }
        $formElect->add('soldeTotal',IntegerType::class);
        //->add('Ajouter',SubmitType::class);

        //////////////////////// mise en place de valeurs par defaut du formulaire /////////////////
        //$elects = $em->getRepository('App:SystemElects')->findAll();
        $i=1;
        foreach ($systemElects as $elect) {
            $formElect['SystemElect'.$i]->setData($elect);
            $formElect['solde'.$i]->setData(0);
            $i++;
        }
        $formElect['soldeTotal']->setData(10);


        // only handles data on POST
        $formElect->handleRequest($request);
        if ($formElect->isSubmitted() && $formElect->isValid()) {
            $electInventaire=new SystemElectInventaires();
            $electInventaire->setSoldeTotal($formElect->get('soldeTotal')->getData());
            $electInventaire->setDateInventaire(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($electInventaire);
            $this->get('session')->set('electronic', $electInventaire);
            $em->flush();
            $i=1;
            foreach ($systemElects as $elect){
                $electLigneInventaire = new SystemElectLigneInventaires();
                $electLigneInventaire->setIdSystemElectInventaire($electInventaire);
                //$electLigneInventaire->setNbBillet($formElect->get('nbBillet'.$i)->getData());
                $electLigneInventaire->setIdSystemElect($formElect->get('SystemElect'.$i)->getData());
                $electLigneInventaire->setSolde($formElect->get('solde'.$i)->getData());
                $i++;
                $em->persist($electLigneInventaire);

                $em->flush();
            }
            $this->addFlash('success', 'Billet Créé!');
            return $this->redirectToRoute('system_elect_ligne_inventaires_index');
        }
        return $this->render('system_elect_ligne_inventaires/ajout.html.twig', [
            'systemElectsForm' => $formElect->createView()
            ,'elects'=>$systemElects
        ]);
    }*/
}
