<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entitny\User;
use App\Entity\Categories;
use App\Repository\CategoriesRepository;

class UserController extends Controller
{
    public function index()
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();

        return $this->render('user/test.html.twig', ['listCategories' => $listCategories]);
    }

    public function login(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            return $this->redirectToRoute('index');
        }
      
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('user/login.html.twig', ['last_username' => $authenticationUtils->getLastUsername(), 'error' => $authenticationUtils->getLastAuthenticationError()]);
    }

    public function signUp()
    {
        return $this->render('user/test.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
