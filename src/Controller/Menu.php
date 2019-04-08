<?php
/**
 * Created by Hamado.
 * User: houedraogo
 * Date: 28/01/2019
 * Time: 11:12
 */

namespace App\Controller;


use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class Menu  extends Controller
{
    private $menuTop = array();
    private $menu_parametre=array();
    private $menu_guichetier=array();
    private $menu_common=array();
    private $menu_comptable=array();
    private $menu_suivi=array();
    private $menu_rapport=array();
    private $menu_superAdmin=array();

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->menu_superAdmin=[['text'=>'Utilisateurs','lien'=>'utilisateurs_index']
            ,['text'=>'Accueil','lien'=>'admin_main']
            ,
        ];
        $this->menu_parametre=[
            ['text'=>'Tiers', 'lien'=>'clients_index']
            ,['text'=>'Plan comptable', 'lien'=>'comptes_index']
            ,['text'=>'Journaux comptables', 'lien'=>'journaux_comptables_index']
            ,['text'=>'Paramètres comptables', 'lien'=>'param_comptables_index']
            ,['text'=>'Opérations comptables', 'lien'=>'type_operation_comptables_index']
            ,['text'=>'Caisses et banques', 'lien'=>'caisses_index']
            ,['text'=>'Collaborateurs', 'lien'=>'collaborateurs_new']
            ,['text'=>'Opérations comptables', 'lien'=>'type_operation_comptables_index']
            ,['text'=>'Devises', 'lien'=>'devises_index']
            ,['text'=>'Transferts Internationaux', 'lien'=>'transfert_internationaux_index']
            ,['text'=>'Transferts électroniques', 'lien'=>'system_elects_index']
            ,['text'=>'Devises', 'lien'=>'devises_index']
            ,['text'=>'Billets', 'lien'=>'billets_index']
            ,['text'=>'Pays et zones', 'lien'=>'pays_index']
            ,['text'=>'Système transferts', 'lien'=>'system_transfert_index']
            ,
        ];

        $this->menu_common=[
            ['text'=>'Gerer Caisse','lien'=>'journee_caisses_gerer']
            ,['text'=>'Changer de caisse', 'lien'=>'journee_caisses_init']
            ,['text'=>'Intercaisses', 'lien'=>'intercaisses_ajout']
            ,['text'=>'Appro-Versements', 'lien'=>'appro_versements_ajout']
            ,['text'=>'Recettes-Depenses', 'lien'=>'recette_depenses_comptant']
            ,['text'=>'Credits et dettes', 'lien'=>'detteCredits_divers']
            ,['text'=>'Achat vente divers', 'lien'=>'#']
            ,['text'=>'Historiques caisses','lien'=>'journee_caisses_etat_de_caisse']
        ];
        $this->menu_guichetier=[
            ['text'=>'Transfert internationaux', 'lien'=>'transfert_internationaux_saisie']
            ,['text'=>'Devises Achat vente', 'lien'=>'devise_recus_achat_vente']
            ,['text'=>'Devises - Intercaisse', 'lien'=>'devise_intercaisses_gestion']
            ,['text'=>'Depôts', 'lien'=>'depot_retraits_depot']
            ,['text'=>'Retraits', 'lien'=>'depot_retraits_retrait']
            ,['text'=>'Solde', 'lien'=>'comptes_solde']
            ,
        ];

        $this->menu_comptable=[
            ['text'=>'Recettes - Depenses','lien'=>'recette_depenses_index']
            ,['text'=>'Saisie Caisses/Banques','lien'=>'compta_saisie_tresorerie']
            ,['text'=>'Compensation', 'lien'=>'compenses_saisie']
           // ,['text'=>'Recettes Depenses Comptant','lien'=>'recette_depenses_comptant_groupee']
            ,['text'=>'Salaires-Positionnement', 'lien'=>'salaires_positionnement']
            ,['text'=>'Ecritures comptables', 'lien'=>'transactions_index']
            ,['text'=>'Journaux comptables', 'lien'=>'journaux_comptables_ecritures']
            ,['text'=>'Grand livre', 'lien'=>'compta_grand_livre']
            ,['text'=>'Balance', 'lien'=>'compta_balance']
            ,['text'=>'Maintenance comptes', 'lien'=>'compta_maintenir_solde_compte']
            //,['text'=>'Compte de résultat', 'lien'=>'#']
            //,['text'=>'Soldes de gestion', 'lien'=>'#']
            //,['text'=>'Recettes Depenses à terme', 'lien'=>'#']
            //,['text'=>'Salaires-Paiement', 'lien'=>'#']
            //,['text'=>'Rapprochement bancaire', 'lien'=>'#']
            //,['text'=>'Pointage des écritures', 'lien'=>'#']
            ,
        ];

        $this->menu_suivi=[
            ['text'=>'Journées Caisses', 'lien'=>'journee_caisses_index']
            ,['text'=>'Suivi transferts', 'lien'=>'transfert_internationaux_tc']
            ,['text'=>'Suivi Compensation', 'lien'=>'compenses_index']
            ,['text'=>'Depots retraits', 'lien'=>'depot_retraits_index']
            ,['text'=>'Etat consolidé tresorerie', 'lien'=>'journee_caisses_tresorerie']
            ,['text'=>'Mouvements de Devises', 'lien'=>'devise_mouvements_etat']
            //,['text'=>'Tableau de bord', 'lien'=>'#']
            ,
        ];

        $this->menu_rapport=[
            ['text'=>'Transferts BCEAO MINEFID', 'lien'=>'etats_rapport_transfert']
            ,['text'=>'Devises BCEAO MINEFID','lien'=>'etats_rapport_devises']
            ,
        ];
    }

    public function getSideBarMenu($active_route ) : Response
    {
        $menu=array();
        $menu[]=['text'=>'Gestion Caisse','child'=>$this->menu_common, 'lien'=>'#','open'=>$this->getOpenMenu($this->menu_common,$active_route)];

        if ($this->get('security.authorization_checker')->isGranted('ROLE_GUICHETIER')) {
            $menu[]=['text'=>'Opération de GUICHET','child'=>$this->menu_guichetier, 'lien'=>'#','open'=>$this->getOpenMenu($this->menu_guichetier,$active_route)];
        }
        if ($this->get('security.authorization_checker')->isGranted('ROLE_COMPTABLE')) {
            $menu[]=['text'=>'Comptabilité','child'=>$this->menu_comptable, 'lien'=>'#','open'=>$this->getOpenMenu($this->menu_comptable,$active_route)];
            $menu[]=['text'=>'Suivi','child'=>$this->menu_suivi, 'lien'=>'#','open'=>$this->getOpenMenu($this->menu_suivi,$active_route)];
            $menu[]=['text'=>'Rapports','child'=>$this->menu_rapport, 'lien'=>'#','open'=>$this->getOpenMenu($this->menu_rapport,$active_route)];
            $menu[]=['text'=>'Paramètres','child'=>$this->menu_parametre, 'lien'=>'#','open'=>$this->getOpenMenu($this->menu_parametre,$active_route)];
        }
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $menu[]=['text'=>'Administration','child'=>$this->menu_superAdmin, 'lien'=>'#','open'=>$this->getOpenMenu($this->menu_superAdmin,$active_route)];
        }

        return $this->render( 'sidebar_menu.html.twig',
            ['menu' => $menu]
        );
    }
    
   private function getOpenMenu($childrens, $active_route){
       foreach ($childrens as $child){
           if($child['lien']== $active_route){
               return 'open';
           }
       }
       return '';
   }
    ///////////////////////////////////////////////////////

}