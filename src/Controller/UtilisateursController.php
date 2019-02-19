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
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function index(Request $request): Response
    {
        $limit=10;
        $_page=$request->query->get('_page');
        $offset = ($_page)?($_page-1)*$limit:0;
        $utilisateurs = $this->getDoctrine()
            ->getRepository(Utilisateurs::class)
            ->liste($offset,$limit);
        $pages = round(count($utilisateurs)/$limit);
        //dump($utilisateurs);die();

        return $this->render('utilisateurs/index.html.twig',
            [
                'utilisateurs' => $utilisateurs,
                'pages'=>$pages,
            ]);
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
        $utilisateurs = $this->getDoctrine()
            ->getRepository(Utilisateurs::class)
            ->liste(10);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $utilisateur->setMdp($this->container->get('security.password_encoder')->encodePassword($utilisateur,$utilisateur->getMdp()));
            //dump($form['role']->getData()); die();
            $utilisateur->setRoles($form['role']->getData());
            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('utilisateurs_index');
        }

        return $this->render('utilisateurs/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
            'utilisateurs' => $utilisateurs
        ]);
    }

    /**
     * @Route("/profile", name="utilisateurs_profile", methods="GET|POST")
     */
    public function profile(Request $request): Response
    {
        $form = $this->createForm(ProfileType::class, $this->utilisateur);
        $form->handleRequest($request);
        $utilisateurs = $this->getDoctrine()
            ->getRepository(Utilisateurs::class)
            ->liste(10);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($this->utilisateur);
            $em->flush();

            return $this->redirectToRoute('utilisateurs_index');
        }

        return $this->render('utilisateurs/profile.html.twig', [
            'utilisateur' => $this->utilisateur,
            'utilisateurs' => $utilisateurs,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/passchg", name="utilisateur_pass_change", methods="GET|POST")
     */
    public function changer(Request $request): Response
    {
        $user = new Utilisateurs();
        $form = $this->createForm(PassType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if(password_verify($form['actmdp']->getData(), $this->utilisateur->getMdp()))
            {
                //dump($this->getUser());die();
                $this->utilisateur->setMdp($this->container->get('security.password_encoder')->encodePassword($this->utilisateur,$user->getMdp()));
                $em->persist($this->utilisateur);
                $em->flush();
                $this->addFlash('success', 'Mot de passe modifié avec succes!');
                return $this->redirectToRoute('logout');
            }
            else
                $this->addFlash('error', 'Mot de passe actuel incorrect!');
        }

        return $this->render('utilisateurs/passe.html.twig', [
            'utilisateur' => $this->utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/passchg/{id}", name="utilisateur_pass_change_by_admin", methods="GET|POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function changerUser(Request $request, Utilisateurs $utilisateur): Response
    {
        $user = new Utilisateurs();
        $form = $this->createForm(PassType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if(password_verify($form['actmdp']->getData(), $this->utilisateur->getMdp()))
            {
                //dump($this->getUser());die();
                $utilisateur->setMdp($this->container->get('security.password_encoder')->encodePassword($utilisateur,$user->getMdp()));
                $em->persist($utilisateur);
                $em->flush();
                $this->addFlash('success', 'Mot de passe modifié avec succes!');
                return $this->redirectToRoute('utilisateurs_index');
            }
            else
                $this->addFlash('error', 'Mot de passe actuel incorrect!');
        }

        return $this->render('utilisateurs/passe.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="utilisateurs_show", methods="GET")
     */
    public function show(Request $request, Utilisateurs $utilisateur): Response
    {
        $limit=10;
        $_page=$request->query->get('_page');
        $offset = ($_page)?($_page-1)*$limit:0;
        $utilisateurs = $this->getDoctrine()
            ->getRepository(Utilisateurs::class)
            ->liste($offset,$limit);
        $pages = round(count($utilisateurs)/$limit);
        return $this->render('utilisateurs/show.html.twig', [
            'utilisateur' => $utilisateur,
            'utilisateurs' => $utilisateurs,
            'pages' => $pages
        ]);
    }



    /**
     * @Route("/{id}/edit", name="utilisateurs_edit", methods="GET|POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function edit(Request $request, Utilisateurs $utilisateur): Response
    {
        $form = $this->createForm(UtilisateursType::class, $utilisateur);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $utilisateurs = $this->getDoctrine()
            ->getRepository(Utilisateurs::class)
            ->liste(10);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($form['role']->getData()))$utilisateur->setRoles($form['role']->getData());
            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute('utilisateurs_edit', ['id' => $utilisateur->getId()]);
        }

        return $this->render('utilisateurs/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
            'utilisateurs' => $utilisateurs
        ]);
    }

    /**
     * @Route("/{id}", name="utilisateurs_delete", methods="DELETE")
     * @Security("has_role('ROLE_AROLE_ADMIN')")
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
