<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Comptes;
use App\Entity\Utilisateurs;
use App\Form\ProfileType;
use App\Form\UtilisateursType;
use App\Utils\SessionUtilisateur;
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
            ->findAll();

        return $this->render('utilisateurs/index.html.twig', ['utilisateurs' => $utilisateurs]);
    }

    /**
     * @Route("/new", name="utilisateurs_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $utilisateur = new Utilisateurs();
        $form = $this->createForm(UtilisateursType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $utilisateur->setMdp($this->container->get('security.password_encoder')->encodePassword($utilisateur,$utilisateur->getMdp()));
            $compte = new Comptes();
            $compte->setNumCompte($utilisateur->getCompte())
                ->setClient($em->getRepository(Clients::class)->findOneBy(['nom'=>'Comptes']))
                //->setIntitule($em->getRepository(Clients::class)->findOneBy(['nom'=>'Comptes']))
                ->setTypeCompte(Comptes::INTERNE)
                ->setIntitule('Ecart Caissier'.$utilisateur->getId());
            $utilisateur->setCompteEcartCaisse($compte);
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
        //$utilisateur = new Utilisateurs();
        $form = $this->createForm(ProfileType::class, $this->utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /*$utilisateur->setMdp($this->container->get('security.password_encoder')->encodePassword($utilisateur,$utilisateur->getMdp()));
            $compte = new Comptes();
            $compte->setNumCompte($utilisateur->getCompte())
                ->setClient($em->getRepository(Clients::class)->findOneBy(['nom'=>'Comptes']))
                //->setIntitule($em->getRepository(Clients::class)->findOneBy(['nom'=>'Comptes']))
                ->setTypeCompte(Comptes::INTERNE)
                ->setIntitule('Ecart Caissier'.$utilisateur->getId());
            $utilisateur->setCompteEcartCaisse($compte);
            //dump($utilisateur);die();
            //$encoded = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
            */
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
     * @Route("/{id}", name="utilisateurs_show", methods="GET")
     */
    public function show(Utilisateurs $utilisateur): Response
    {
        return $this->render('utilisateurs/show.html.twig', ['utilisateur' => $utilisateur]);
    }


    /**
     * @Route("/{id}/edit", name="utilisateurs_edit", methods="GET|POST")
     */
    public function edit(Request $request, Utilisateurs $utilisateur): Response
    {
        $form = $this->createForm(UtilisateursType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setMdp(hash('SHA1',''.$utilisateur->getMdp()));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('utilisateurs_edit', ['id' => $utilisateur->getId()]);
        }

        return $this->render('utilisateurs/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="utilisateurs_delete", methods="DELETE")
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
