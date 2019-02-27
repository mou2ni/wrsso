<?php
/**
 * Created by PhpStorm.
 * User: houedraogo
 * Date: 23/01/2019
 * Time: 15:37
 */

namespace App\Controller;


use App\Entity\CriteresEtatsComptas;
use App\Entity\JourneeCaisses;
use App\Entity\ParamComptables;
use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Entity\TransactionComptes;
use App\Form\CriteresEtatsComptasType;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        return $this->render('journee_caisses/gerer.html.twig', ['journeeCaisse' => $journeeCaisse, 'journeeCaisses' => $journeeCaisses]);

    }

    /**
     * @Route("/grandlivre", name="compta_grand_livre", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function grandLivre(Request $request) : Response
    {
        $criteresGrandLivre=new CriteresEtatsComptas();
        $form = $this->createForm(CriteresEtatsComptasType::class, $criteresGrandLivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
            
            
            //$limit=20;
            //$_page=$request->query->get('_page');
            //$offset = ($_page)?($_page-1)*$limit:0;
            //$liste = 
            return $this->render('compta/grand_livre.html.twig',[
                'rubriquesGrandLivres'=>$rubriquesGrandLivres,
                'form' => $form->createView(),
                'criteres'=>$criteresGrandLivre,
            ]);
        }

        return $this->render('compta/criteres_etats.html.twig', [
            'form' => $form->createView(),
            'type'=>'Grand livre',
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
            //$comptes=$this->getDoctrine()->getRepository(Comptes::class)->plageComptes($criteresBalance->getCompteDebut()->getNumCompte(), $criteresBalance->getCompteFin()->getNumCompte());
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
            
            return $this->render('compta/balance_6.html.twig',[
                'rubriquesBalances'=>$rubriquesBalances,
                'form' => $form->createView(),
                'criteres'=>$criteresBalance,
            ]);
        }

        return $this->render('compta/criteres_etats.html.twig', [
            'form' => $form->createView(),
            'type'=>'Balance générale',
        ]);
    }
    /**
     * @Route("/maintenirsoldecomptes", name="compta_maintenir_solde_compte", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function maintenirSoldeCompte(Request $request) : Response
    {
        $balances=$this->getDoctrine()->getRepository(TransactionComptes::class)->getBalanceComptes();

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

    private function initCaisse(){
        //journeeCaisse à jour
    }

}