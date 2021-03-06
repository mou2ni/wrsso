<?php
/**
 * Created by PhpStorm.
 * User: houedraogo
 * Date: 23/01/2019
 * Time: 15:37
 */

namespace App\Controller;


use App\Entity\CriteresDates;
use App\Entity\CriteresEtatsComptas;
use App\Entity\JourneeCaisses;
use App\Entity\ParamComptables;
use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Entity\SystemTransfert;
use App\Entity\TransactionComptes;
use App\Entity\TransfertInternationaux;
use App\Form\CriteresDatesType;
use App\Form\CriteresEtatsComptasType;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/compta")
 */
class ComptaController extends Controller
{
    private $journeeCaisse;
    private $utilisateur;
    private $caisse;
    //private $paramComptable;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->utilisateur=$sessionUtilisateur->getUtilisateur();
        //dernière caisse ouverte par l'utilisateur ou null si inexistant
        $this->caisse=$sessionUtilisateur->getLastCaisse();
        //dernière journée de la caisse ou null si inexistant
        $this->journeeCaisse=$sessionUtilisateur->getJourneeCaisse();

    }
    /**
     * @Route("/", name="compta_main", methods="GET")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function accueil(Request $request) : Response
    {
        return $this->redirectToRoute('journee_caisses_gerer');
        /*if ($this->utilisateur->getEstCaissier()){
            return $this->redirectToRoute('compta_saisie_cmd');
        }*/

    }

    /**
     * @Route("/caissecmd", name="compta_saisie_cmd", methods="GET")
     * @Security("has_role('ROLE_CAISSIER')")
     */
    public function saisieCmd(Request $request) : Response
    {
        return $this->render('journee_caisses/gerer.html.twig', ['journeeCaisse' => $this->journeeCaisse,'journeeCaisses'=>null]);

    }

    /**
     * @Route("/banquescaisses", name="compta_saisie_tresorerie", methods="GET")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function saisieTresorerie(Request $request) : Response
    {
        $journeeCaisses=$this->getDoctrine()->getRepository(JourneeCaisses::class)->findJourneeCaisseInterneOuvertes();
        
        //if($id=$request->query->get('id')){
            $journeeCaisse=$this->getDoctrine()->getRepository(JourneeCaisses::class)->findOneBy(['id'=>$request->query->get('id')]);
        //}
        if(!$journeeCaisse) $journeeCaisse=$this->journeeCaisse;
        else{
            $this->utilisateur->setLastCaisse($journeeCaisse->getCaisse());
            $journeeCaisse->setUtilisateur($this->utilisateur);
            $this->getDoctrine()->getManager()->persist($journeeCaisse);
            $this->getDoctrine()->getManager()->flush();
        }

        //if (!$journeeCaisse) $journeeCaisse=$journeeCaisses[0];

        return $this->render('journee_caisses/gerer.html.twig', ['journeeCaisse' => $journeeCaisse, 'journeeCaisses' => $journeeCaisses]);

    }

    /**
     * @Route("/grandlivre", name="compta_grand_livre", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function grandLivre(Request $request) : Response
    {
       // dump($request); //$request->query->get('criteres_etats_comptas');//
        $criteres=$request->request->get('criteres_etats_comptas')?$request->request->get('criteres_etats_comptas'):$request->query->get('criteres_etats_comptas');

        $criteresGrandLivre=new CriteresEtatsComptas();

        //dump($criteres);

        if ($criteres){
            $compteDebut_id=$criteres['compteDebut'];
            $compteFin_id=$criteres['compteFin'];
            $dateDebut=$criteres['dateDebut'];
            $dateFin=$criteres['dateFin'];

            if($compteDebut_id){
                $compteDebut=$this->getDoctrine()->getRepository(Comptes::class)->find($compteDebut_id);
                $criteresGrandLivre->setCompteDebut($compteDebut);
            }
            if($compteFin_id){
                $compteFin=$this->getDoctrine()->getRepository(Comptes::class)->find($compteFin_id);
                $criteresGrandLivre->setCompteFin($compteFin);
            }
            if($dateDebut){
                $criteresGrandLivre->setDateDebut(new \DateTime($dateDebut));
            }
            if($dateFin){
                $criteresGrandLivre->setDateFin(new \DateTime($dateFin));
            }
        }
        /**/

        $form = $this->createForm(CriteresEtatsComptasType::class, $criteresGrandLivre);
        $form->handleRequest($request);

        //if ($form->isSubmitted() && $form->isValid()) {
        if($criteresGrandLivre->getCompteDebut() && $criteresGrandLivre->getCompteFin()) {
            return $this->afficherGrandLivre($form, $criteresGrandLivre);
        }
        //}

        return $this->render('compta/criteres_etats.html.twig', [
            'form' => $form->createView(),
            'type'=>'Grand livre',
        ]);
    }

    /**
     * @Route("/grandlivre/{id}", name="compta_grand_livre_specific", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function grandLivreUnCompte(Request $request, Comptes $compte) : Response
    {
        $criteresGrandLivre=new CriteresEtatsComptas();
        $criteresGrandLivre->setCompteDebut($compte)
            ->setCompteFin($compte);

        $form = $this->createForm(CriteresEtatsComptasType::class, $criteresGrandLivre);
        $form->handleRequest($request);

        return $this->afficherGrandLivre($form,$criteresGrandLivre);
    }

    private function afficherGrandLivre(FormInterface $form, CriteresEtatsComptas $criteresGrandLivre)
    {
        $compteDebut=$criteresGrandLivre->getCompteDebut()->getNumCompte();
        $compteFin=$criteresGrandLivre->getCompteFin()->getNumCompte();

        if ($compteDebut>$compteFin){
            $tmp=$compteDebut;
            $compteDebut=$compteFin;
            $compteFin=$tmp;
        }
        $comptes=$this->getDoctrine()->getRepository(Comptes::class)->plageComptes($compteDebut, $compteFin);
        $rubriquesGrandLivres=array();
        foreach ($comptes as $compte){
            $rubriquesGrandLivres[]=['compte'=>$compte, 'ecritures'=>$this->getDoctrine()->getRepository(TransactionComptes::class)->findEcrituresComptes($compte, $criteresGrandLivre->getDateDebut(), $criteresGrandLivre->getDateFin())];
        }
        //dump($criteresGrandLivre);
        return $this->render('compta/grand_livre.html.twig',[
            'rubriquesGrandLivres'=>$rubriquesGrandLivres,
            'form' => $form->createView(),
            'criteres'=>$criteresGrandLivre,
        ]);
    }


    /**
     * @Route("/balance", name="compta_balance", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function balance(Request $request) : Response
    {
        $criteresBalance=new CriteresEtatsComptas();
        $form = $this->createForm(CriteresEtatsComptasType::class, $criteresBalance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rubriquesBalances=array();
            $compteDebut=$criteresBalance->getCompteDebut()->getNumCompte();
            $compteFin=$criteresBalance->getCompteFin()->getNumCompte();
            if ($compteDebut>$compteFin){
                $tmp=$compteDebut;
                $compteDebut=$compteFin;
                $compteFin=$tmp;
            }
            $classeDebut=substr($compteDebut,0,1);
            $classeFin=substr($compteFin,0,1);
            for($classe=$classeDebut; $classe<=$classeFin; $classe++){

                $rubriquesBalances[]=['classe'=>$classe, 'lignes'=>$this->getDoctrine()->getRepository(TransactionComptes::class)
                    ->getBalanceComptes($classe, true,$compteDebut, $compteFin
                        ,$criteresBalance->getDateDebut(), $criteresBalance->getDateFin())];

            }

            return $this->render('compta/balance.html.twig',[
                'rubriquesBalances'=>$rubriquesBalances,
                'form' => $form->createView(),
                'criteres'=>$criteresBalance,
                'affichage'=> $request->request->get('type_affichage_balance'),
            ]);

        }

        return $this->render('compta/criteres_etats.html.twig', [
            'form' => $form->createView(),
            'type'=>'Balance générale',
            'affichage'=>'4C'
        ]);
    }
    
    /**
     * @Route("/maintenirsoldecomptes", name="compta_maintenir_solde_compte", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function maintenirSoldeCompte(Request $request) : Response
    {
        $balances=$this->getDoctrine()->getRepository(TransactionComptes::class)->getSoldesMvt();

        $resultMaintenances=array();
        foreach ($balances as $balance){

            $id_compte = $balance['compte'];
            $compte=$this->getDoctrine()->getRepository(Comptes::class)->find($id_compte);
            $soldeTheorique=$compte->getSoldeCourant();
            $soldeBalance=$balance['mCredit']-$balance['mDebit'];
            if ($soldeTheorique!=$soldeBalance){
                $compte->setSoldeCourant($soldeBalance);
                $this->getDoctrine()->getManager()->persist($compte);
            }

            $resultMaintenances[]=['id'=>$id_compte,'numCompte'=>$balance['numCompte'], 'soldeTheorique'=>$soldeTheorique, 'soldeBalance'=>$soldeBalance];
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->render('compta/resultat_maintenance_compte.html.twig',
            ['resultMaintenances'=>$resultMaintenances]);

    }

}