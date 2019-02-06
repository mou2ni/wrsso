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
            ['text'=>'Clients', 'lien'=>'clients_index']
            ,['text'=>'Plan comptable', 'lien'=>'comptes_index']
            ,['text'=>'Paramètres comptables', 'lien'=>'param_comptables_index']
            ,['text'=>'Opérations comptables', 'lien'=>'type_operation_comptables_index']
            ,['text'=>'Caisses et banques', 'lien'=>'caisses_index']
            ,['text'=>'Collaborateurs', 'lien'=>'collaborateurs_new']
            ,['text'=>'Parametres comptables', 'lien'=>'#']
            ,['text'=>'Opérations comptables', 'lien'=>'type_operation_comptables_index']
            ,['text'=>'Taux de Devise', 'lien'=>'#']
            ,['text'=>'Transferts Internationaux', 'lien'=>'transfert_internationaux_index']
            ,['text'=>'Transferts électroniques', 'lien'=>'system_elects_index']
            ,['text'=>'Devises', 'lien'=>'devises_index']
            ,['text'=>'Billets', 'lien'=>'billets_index']
            ,['text'=>'Pays', 'lien'=>'pays_index']
            ,
        ];

        $this->menu_common=[
            ['text'=>'Gerer Caisse','lien'=>'journee_caisses_gerer']
            ,['text'=>'Changer de caisse', 'lien'=>'journee_caisses_init']
            ,['text'=>'Intercaisses', 'lien'=>'intercaisses_ajout']
            ,['text'=>'Credits et dettes', 'lien'=>'detteCredits_divers']
            ,['text'=>'Achat vente divers', 'lien'=>'#']
            ,['text'=>'Historiques caisses','lien'=>'journee_caisses_etat_de_caisse']
        ];
        $this->menu_guichetier=[
            ['text'=>'Transfert internationaux', 'lien'=>'transfert_internationaux_saisie']
            ,['text'=>'Devises Achat vente', 'lien'=>'devise_recus_achat_vente']
            ,['text'=>'Devises - Intercaisse', 'lien'=>'devise_intercaisses_gestion']
            ,['text'=>'Depôts', 'lien'=>'#']
            ,['text'=>'Retraits', 'lien'=>'#']
        ];

        $this->menu_comptable=[
            ['text'=>'Saisie Caisses/Banques','lien'=>'compta_saisie_tresorerie']
            ,['text'=>'Recettes Depenses Comptant','lien'=>'recette_depenses_saisie_groupee']
            ,['text'=>'Recettes Depenses à terme', 'lien'=>'#']
            ,['text'=>'Salaires-Positionnement', 'lien'=>'salaires_positionnement']
            ,['text'=>'Salaires-Paiement', 'lien'=>'#']
            ,['text'=>'Journaux comptables', 'lien'=>'#']
            ,['text'=>'Rapprochement bancaire', 'lien'=>'#']
            ,['text'=>'Pointage des écritures', 'lien'=>'#']
            ,['text'=>'Ecritures comptables', 'lien'=>'#']
            ,
        ];

        $this->menu_suivi=[
            ['text'=>'Tableau de bord', 'lien'=>'#']
            ,['text'=>'Etat consolidé tresorerie', 'lien'=>'#']
            ,['text'=>'Mouvements de Devises', 'lien'=>'#']
            ,['text'=>'Grand livre', 'lien'=>'#']
            ,['text'=>'Balance', 'lien'=>'#']
            ,['text'=>'Compte de résultat', 'lien'=>'#']
            ,['text'=>'Soldes de gestion', 'lien'=>'#']
            ,['text'=>'Journaux comptables', 'lien'=>'#']
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