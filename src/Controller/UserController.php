<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    public function index()
    {
        return $this->render('user/test.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    public function login()
    {
        return $this->render('user/test.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    public function logout()
    {
        return $this->render('user/test.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    public function signUp()
    {
        return $this->render('user/test.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
