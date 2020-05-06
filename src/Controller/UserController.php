<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use App\Entity\Instruments;
use App\Repository\InstrumentsRepository;
use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder;

class UserController extends Controller
{
    public function index()
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();
        return $this->render('user/index.html.twig', ['listCategories' => $listCategories]);
    }

    public function login(Request $request)
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            return $this->redirectToRoute('index', ['listCategories' => $listCategories]);
        }
      
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('user/login.html.twig', ['last_username' => $authenticationUtils->getLastUsername(), 'error' => $authenticationUtils->getLastAuthenticationError(), 'listCategories' => $listCategories]);
    }

    public function signUp(Request $request)
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();

        $user = new User();

		$form   = $this->createForm(UserType::class, $user);

        if ($request->isMethod('POST'))
        {
			
			$form->handleRequest($request);
	  
            if ($form->isValid())
            {
                $cmd = new Commande();
                $cmd->setDone(1);
                $user->setCommande($cmd);
                $user->setSalt('');
                $user->setRoles(array('ROLE_USER'));
			    $em = $this->getDoctrine()->getManager();
			    $em->persist($user);
			    $em->flush();
	  
			    $request->getSession()->getFlashBag()->add('notice', 'Bienvenu.e nouveau membre!');
	  
			    return $this->redirectToRoute('login', ['listCategories' => $listCategories]);
			}
		  }
	  
		return $this->render('user/signUp.html.twig', ['form' => $form->createView(), 'listCategories' => $listCategories]);
    }

    public function edit(Request $request, ?UserInterface $user)
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();

        if ($user)
        {
            $id = $user->getId();
            $user_cur = $this->getDoctrine()->getManager()->getRepository('App\Entity\User')->find($id);

            $form   = $this->createForm(UserType::class, $user);

            if ($request->isMethod('POST'))
            {
			
			    $form->handleRequest($request);
	  
                if ($form->isValid())
                {
			        $em = $this->getDoctrine()->getManager();
			        $em->flush();
	  
			        $request->getSession()->getFlashBag()->add('notice', 'Modifications prises en compte!');
	  
			        return $this->redirectToRoute('index', ['listCategories' => $listCategories]);
			    }
            }

            return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'listCategories' => $listCategories]);
        }

        return $this->redirectToRoute('login', ['listCategories' => $listCategories]);
    }

    public function delete(Request $request)
    {
        if (!$user = $this->getUser())
        {
            throw $this->createNotFoundException("Un problème est survenu lors de votre connexion.");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Compte supprimé.');
        $session = new Session();
        $session->invalidate();
        return $this->redirectToRoute('index');
    }

    public function usersAdminView()
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();
        $listUsers = $this->getDoctrine()->getManager()->getRepository('App\Entity\User')->findAll();
        return $this->render('user/adminUsers.html.twig', ['listCategories' => $listCategories, 'listUsers' => $listUsers]);
    }

    public function deleteAdminUser($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('App\Entity\User')->find($id);
        $cmd = $em->getRepository('App\Entity\Commande')->find($user->getCommande()->getId());
        $em->remove($cmd);
        $em->remove($user);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Client supprimé');
        return $this->redirectToRoute('admin_user');
    }

    public function editAdminUser($id, Request $request)
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();

        $user = $this->getDoctrine()->getManager()->getRepository('App\Entity\User')->find($id);

        $form   = $this->createForm(UserType::class, $user);

        if ($request->isMethod('POST'))
        {	
			$form->handleRequest($request);
	  
            if ($form->isValid())
            {
			    $em = $this->getDoctrine()->getManager();
			    $em->flush();
	  
			    $request->getSession()->getFlashBag()->add('notice', 'Modifications prises en compte!');
	  
			    return $this->redirectToRoute('admin_user', ['listCategories' => $listCategories]);
			}
        }

        return $this->render('user/editAdmin.html.twig', ['form' => $form->createView(), 'listCategories' => $listCategories]);
    }
}
