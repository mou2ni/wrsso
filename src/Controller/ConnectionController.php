<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Tests\Extension\Core\Type\PasswordTypeTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/Wrsso", name="connection")
 */
class ConnectionController extends Controller
{
    /**
     * @Route(".", name="connection")
     */
    public function index(Request $request)
    {
        $formConnection = $this->get('form.factory')->createNamedBuilder('formConnection')
            ->add('login')
            ->add('mdp', PasswordType::class)
            ->getForm();
        if ($formConnection->isSubmitted() && $formConnection->isValid()) {
            //$em = $this->getDoctrine()->getManager();
            //$em->persist($utilisateur);
            //$em->flush();
            dump($formConnection->getData());
            die();
            return $this->redirectToRoute('billetages_index');

        }
        return $this->render('connection/index.html.twig', [
            'formLogin' => $formConnection->createView(),
        ]);
    }

    /**
     * @Route("", name="login", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $utilisateur = new Utilisateurs();
        $form = $this->createForm(LoginType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            checkConnection($utilisateur);
            //return $this->redirectToRoute('billetages_index');
        }

        return $this->render('connection/index.html.twig', [
            'formLogin' => $form->createView(),
        ]);
    }

    public function checkConnection(Utilisateurs $utilisateur){
        $pass=hash('SHA1', "".$utilisateur->getMdp());
        $utilisateurTrouves = $this->getDoctrine()
            ->getRepository(Utilisateurs::class)
            ->findBy(['mdp' => $pass, 'login' => $utilisateur -> getLogin()]);
        return $this->redirectToRoute('billetages_index');
    }

}


