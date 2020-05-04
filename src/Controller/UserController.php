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

class UserController extends Controller
{
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listCategories = $em->getRepository('App\Entity\Categories')->findAll();
        $test = $em->getRepository('App\Entity\Instruments')->getInstrumentsInCategory('Vent');

        //NE PAS OUBLIER DE SE CHARGER DE CE TRUC


        return $this->render('user/test.html.twig', ['listCategories' => $listCategories, 'test' => $test]);
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
	  
			    return $this->redirectToRoute('index', ['listCategories' => $listCategories]);
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
}
