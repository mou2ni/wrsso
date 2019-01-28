<?php
/**
 * Created by PhpStorm.
 * User: houedraogo
 * Date: 28/01/2019
 * Time: 11:12
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;

class Menu  extends Controller 
{
    private $menuTop = array();

    public function __construct()
    {
        $profil=[['text'=>'Modifier','lien'=>'#']
            ,['text'=>'Deconnecter', 'lien'=>'logout']
            ,
        ];

        $parametre=[
            ['text'=>'Utilisateurs','lien'=>'utilisateurs_index']
            ,['text'=>'Clients', 'lien'=>'clients_index']
            ,['text'=>'Plan comptable', 'lien'=>'comptes_index']
            ,['text'=>'Caisses et banques', 'lien'=>'caisses_index']
            ,['text'=>'Parametres comptables', 'lien'=>'parametre_comptables_index']
            ,['text'=>'Opérations comptables', 'lien'=>'type_operation_comptables_index']
            ,['text'=>'Taux de Devise', 'lien'=>'#']
            ,['text'=>'sep', 'lien'=>'#']
            ,['text'=>'Transferts Internationaux', 'lien'=>'transfert_internationaux_index']
            ,['text'=>'Transferts électroniques', 'lien'=>'system_elects_index']
            ,['text'=>'Devises', 'lien'=>'devises_index']
            ,['text'=>'Billets', 'lien'=>'billets_index']
            ,['text'=>'Pays', 'lien'=>'pays_index']
            ,
        ];
        
        $caisse=[
            ['text'=>'Gerer Caisse','lien'=>'journee_caisses_gerer']
            ,['text'=>'Changer de caisse', 'lien'=>'journee_caisses_init']
            ,['text'=>'Transfert internationaux', 'lien'=>'transfert_internationaux_saisie']
            ,['text'=>'Intercaisses', 'lien'=>'intercaisses_ajout']
            ,['text'=>'Credits et dettes', 'lien'=>'detteCredits_divers']
            ,['text'=>'Devises Achat vente', 'lien'=>'devise_recus_achat_vente']
            ,['text'=>'Devises - Intercaisse', 'lien'=>'devise_intercaisses_gestion']
            ,['text'=>'Achat vente divers', 'lien'=>'#']
            ,['text'=>'Depôts', 'lien'=>'#']
            ,['text'=>'Retraits', 'lien'=>'#']
            ,
        ];

        $compta=[
            ['text'=>'Caisses menu depenses','lien'=>'compta_saisie_cmd']
            ,['text'=>'Recettes Depenses Comptant','lien'=>'recette_depenses_saisie_groupee']
            ,['text'=>'Recettes Depenses à terme', 'lien'=>'#']
            ,['text'=>'Salaires-Positionnement', 'lien'=>'#']
            ,['text'=>'Salaires-Paiement', 'lien'=>'#']
            ,['text'=>'Journaux comptables', 'lien'=>'#']
            ,['text'=>'Rapprochement bancaire', 'lien'=>'#']
            ,['text'=>'Pointage des écritures', 'lien'=>'#']
            ,['text'=>'Ecritures comptables', 'lien'=>'#']
            ,
        ];

        $suivi=[
            ['text'=>'Tableau de bord', 'lien'=>'#']
            ,['text'=>'Historiques caisses','lien'=>'journee_caisses_etat_de_caisse']
            ,['text'=>'Etat consolidé tresorerie', 'lien'=>'#']
            ,['text'=>'Mouvements de Devises', 'lien'=>'#']
            ,['text'=>'Grand livre', 'lien'=>'#']
            ,['text'=>'Balance', 'lien'=>'#']
            ,['text'=>'Compte de résultat', 'lien'=>'#']
            ,['text'=>'Soldes de gestion', 'lien'=>'#']
            ,['text'=>'Journaux comptables', 'lien'=>'#']
            ,
        ];

        $rapport=[
            ['text'=>'Transferts BCEAO MINEFID', 'lien'=>'etats_rapport_transfert']
            ,['text'=>'Devises BCEAO MINEFID','lien'=>'etats_rapport_devises']
            ,
        ];
        $this->menuTop=[
            ['text'=>'Mon profil','child'=>$profil,'lien'=>'#']
            ,['text'=>'Paramétrages','child'=>$parametre, 'lien'=>'#']
            ,['text'=>'Gestion Caisse','child'=>$caisse, 'lien'=>'#']
            ,['text'=>'Comptabilité','child'=>$compta, 'lien'=>'compta_main']
            ,['text'=>'Etats de suivi','child'=>$suivi, 'lien'=>'#']
            ,['text'=>'Rapports','child'=>$rapport, 'lien'=>'#']
        ];
    }

    public function getSideBarMenu() : Response
    {
        $html="<nav><ul>";
        foreach ($this->menuTop as $item){
            $html.="<li>
                <a href=\"".$item['lien']."\">".$item['text']."</a>
                <ul>";
            foreach ($item['child'] as $child){
                $html.="<li>
                    <a href=\"{{ path('".$child['lien']."') }}\">".$child['text']."</a>
                    </li>";
            }
            $html.="</ul></li>";
        }
        $html.="</ul></nav>";

        return $this->render( 'sidbar_menu.html.twig',
            ['menu_html' => $html]
        );
    }
    
    
    ///////////////////////////////////////////////////////

}