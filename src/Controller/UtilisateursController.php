<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Comptes;
use App\Entity\Utilisateurs;
use App\Form\PassType;
use App\Form\ProfileType;
use App\Form\UtilisateursType;
use App\Utils\SessionUtilisateur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/utilisateurs")
 */
class UtilisateursController extends Controller
{
    private $utilisateur;
    //private $paramComptable;

    public function __construct(SessionUtilisateur $sessionUtilisateur)
    {
        $this->utilisateur=$sessionUtilisateur->getUtilisateur();
    }

    /**
     * @Route("/", name="utilisateurs_index", methods="GET")
     */
    public function index(): Response
    {
        $utilisateurs = $this->getDoctrine()
            ->getRepository(Utilisateurs::class)
            ->liste(10);
        //dump($utilisateurs);die();

        return $this->render('utilisateurs/index.html.twig', ['utilisateurs' => $utilisateurs]);
    }

    /**
     * @Route("/new", name="utilisateurs_new", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function new(Request $request): Response
    {
        $utilisateur = new Utilisateurs();
        $form = $this->createForm(UtilisateursType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $utilisateur->setMdp($this->container->get('security.password_encoder')->encodePassword($utilisateur,$utilisateur->getMdp()));
            //dump($form['role']->getData()); die();
            $utilisateur->setRoles($form['role']->getData());
            /*$compte = new Comptes();
            $compte->setNumCompte($utilisateur->getCompte())
                ->setClient($em->getRepository(Clients::class)->findOneBy(['nom'=>'Comptes']))
                //->setIntitule($em->getRepository(Clients::class)->findOneBy(['nom'=>'Comptes']))
                ->setTypeCompte(Comptes::INTERNE)
                ->setIntitule('Ecart Caissier '.$utilisateur->getNom());
            $utilisateur->setCompteEcartCaisse($compte);*/
            //dump($utilisateur);die();
            //$encoded = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());

            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('utilisateurs_index');
        }

        return $this->render('utilisateurs/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile", name="utilisateurs_profile", methods="GET|POST")
     */
    public function profile(Request $request): Response
    {
        $form = $this->createForm(ProfileType::class, $this->utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($this->utilisateur);
            $em->flush();

            return $this->redirectToRoute('utilisateurs_index');
        }

        return $this->render('utilisateurs/new.html.twig', [
            'utilisateur' => $this->utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/passchg", name="pass_change", methods="GET|POST")
     */
    public function changer(Request $request): Response
    {
        $form = $this->createForm(PassType::class, $this->utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
             //if ($form['actmdp']->getData()
            //== $em->getRepository(Utilisateurs::class)->find($this->utilisateur)->getPassword())
             //{
                $this->utilisateur->setMdp($this->container->get('security.password_encoder')->encodePassword($this->utilisateur,$this->utilisateur->getMdp()));
                $em->persist($this->utilisateur);
                $em->flush();
                $this->addFlash('success', 'Mot de passe modifié avec succes!');
                return $this->redirectToRoute('logout');
            //}
            //else
              //  $this->addFlash('error', 'Echec : Mot de passe actuel erroné!');
        }

        return $this->render('utilisateurs/new.html.twig', [
            'utilisateur' => $this->utilisateur,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="utilisateurs_show", methods="GET")
     */
    public function show(Utilisateurs $utilisateur): Response
    {
        return $this->render('utilisateurs/show.html.twig', ['utilisateur' => $utilisateur]);
    }



    /**
     * @Route("/{id}/edit", name="utilisateurs_edit", methods="GET|POST")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function edit(Request $request, Utilisateurs $utilisateur): Response
    {
        $form = $this->createForm(UtilisateursType::class, $utilisateur);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setMdp($this->container->get('security.password_encoder')->encodePassword($utilisateur,$utilisateur->getMdp()));
            $utilisateur->setRoles($form['role']->getData());
            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('utilisateurs_edit', ['id' => $utilisateur->getId()]);
        }

        return $this->render('utilisateurs/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="utilisateurs_delete", methods="DELETE")
     * @Security("has_role('ROLE_COMPTABLE')")
     */
    public function delete(Request $request, Utilisateurs $utilisateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($utilisateur);
            $em->flush();
        }

        return $this->redirectToRoute('utilisateurs_index');
    }
}
