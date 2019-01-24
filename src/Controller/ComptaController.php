<?php
/**
 * Created by PhpStorm.
 * User: houedraogo
 * Date: 23/01/2019
 * Time: 15:37
 */

namespace App\Controller;


use App\Entity\JourneeCaisses;
use App\Entity\ParamComptables;
use App\Entity\Caisses;
use App\Entity\Comptes;
use App\Utils\SessionUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     */
    public function accueil(Request $request) : Response
    {
        if ($this->utilisateur->getEstCaissier()){
            return $this->redirectToRoute('compta_saisie_cmd');
        }

    }

    /**
     * @Route("/caissecmd", name="compta_saisie_cmd", methods="GET")
     */
    public function saisieCmd(Request $request) : Response
    {

        return $this->render('journee_caisses/gerer_cmd.html.twig', ['journeeCaisse' => $this->journeeCaisse]);

    }
    
    private function initCaisse(){
        //journeeCaisse à jour
    }

}