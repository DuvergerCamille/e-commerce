<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Instruments;
use App\Entity\Categories;
use App\Repository\InstrumentsRpository;
use App\Repository\CategoriesRepository;
use App\Form\InstrumentsType;
use App\Form\SheetsType;
use App\Form\CategoriesType;

class ShopController extends Controller
{
    public function index()
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();

        return $this->render('shop/index.html.twig', ['listCategories' => $listCategories]);
    }

    public function instruments()
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();
        $listInstruments = $this->getDoctrine()->getManager()->getRepository('App\Entity\Instruments')->findAll();
        return $this->render('shop/instruments.html.twig', ['listCategories' => $listCategories, 'listInstruments' => $listInstruments]);
    }

    public function addInstrument(Request $request)
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();

        $instru = new Instruments();

		$form   = $this->createForm(InstrumentsType::class, $instru);

        if ($request->isMethod('POST'))
        {
			$form->handleRequest($request);
	  
            if ($form->isValid())
            {

			    $em = $this->getDoctrine()->getManager();
			    $em->persist($instru);
			    $em->flush();
	  
			    $request->getSession()->getFlashBag()->add('notice', 'Instrument ajouté.');
	  
			    return $this->redirectToRoute('index', ['listCategories' => $listCategories]);
			}
		  }
	  
		return $this->render('shop/addInstrument.html.twig', ['form' => $form->createView(), 'listCategories' => $listCategories]);
    }

    public function viewInstrument()
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();
        $listInstruments = $this->getDoctrine()->getManager()->getRepository('App\Entity\Instruments')->findAll();
        return $this->render('shop/instrumentsView.html.twig', ['listCategories' => $listCategories, 'listInstruments' => $listInstruments]);
    }

    public function editInstrument($id, Request $request)
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();
        $instru = $this->getDoctrine()->getManager()->getRepository('App\Entity\Instruments')->find($id);

	    if (null === $instru)
        {
	        throw $this->createNotFoundException("L'instrument d'id ".$id." n'existe pas.");
	    }

		$form   = $this->createForm(InstrumentsType::class, $instru);

		if ($request->isMethod('POST'))
        {
			$form->handleRequest($request);
	  
			if ($form->isValid())
            {
			  $em = $this->getDoctrine()->getManager()->flush();
	  
			  $request->getSession()->getFlashBag()->add('notice', 'Article bien modifié.');
	  
			  return $this->redirectToRoute('view_instruments', ['listCategories' => $listCategories]);
			}
		  }
	   
		return $this->render('shop/editInstrument.html.twig', ['form' => $form->createView(), 'listCategories' => $listCategories]);
    }

    public function view($name)
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();
        $check = false;

        foreach($listCategories as $catego)
        {
            if ($name === $catego->getNom())
            {
                $check = true;
            }
        }

        if ($check === false)
        {
            throw $this->createNotFoundException("La catégorie ".$name." n'existe pas.");
        }

        $em = $this->getDoctrine()->getManager();

        $listInstruments = $em->getRepository('App\Entity\Instruments')->getInstrumentsInCategory($name);
        return $this->render('shop/view.html.twig', ['listCategories' => $listCategories, 'listInstruments' => $listInstruments]);
    }

    public function addCategory(Request $request)
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();

        $catego = new Categories();

		$form   = $this->createForm(CategoriesType::class, $catego);

        if ($request->isMethod('POST'))
        {
			$form->handleRequest($request);
	  
            if ($form->isValid())
            {

			    $em = $this->getDoctrine()->getManager()->persist($catego)->flush();
	  
			    $request->getSession()->getFlashBag()->add('notice', 'Catégorie ajoutée.');
	  
			    return $this->redirectToRoute('index', ['listCategories' => $listCategories]);
			}
		  }
	  
		return $this->render('shop/addCategory.html.twig', ['form' => $form->createView(), 'listCategories' => $listCategories]);
    }
}