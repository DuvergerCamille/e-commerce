<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Instruments;
use App\Entity\Categories;
use App\Repository\InstrumentsRpository;
use App\Repository\CategoriesRepository;
use App\Entity\Sheets;
use App\Repository\SheetsRpository;
use App\Form\InstrumentsType;
use App\Form\SheetsType;

class ShopController extends Controller
{
    public function instruments()
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();
        $listInstruments = $this->getDoctrine()->getManager()->getRepository('App\Entity\Instruments')->findAll();
        return $this->render('shop/instruments.html.twig', ['listCategories' => $listCategories, 'listInstruments' => $listInstruments]);
    }

    public function sheets()
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();
        $listSheets = $this->getDoctrine()->getManager()->getRepository('App\Entity\Sheets')->findAll();
        return $this->render('shop/sheets.html.twig', ['listCategories' => $listCategories, 'listSheets' => $listSheets]);
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

    public function addSheet(Request $request)
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();

        $sheet = new Sheets();

		$form   = $this->createForm(SheetsType::class, $sheet);

        if ($request->isMethod('POST'))
        {
			$form->handleRequest($request);
	  
            if ($form->isValid())
            {

			    $em = $this->getDoctrine()->getManager();
			    $em->persist($sheet);
			    $em->flush();
	  
			    $request->getSession()->getFlashBag()->add('notice', 'Partition ajoutée.');
	  
			    return $this->redirectToRoute('index', ['listCategories' => $listCategories]);
			}
		  }
	  
		return $this->render('shop/addSheet.html.twig', ['form' => $form->createView(), 'listCategories' => $listCategories]);
    }

    public function viewInstrument()
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();
        $listInstruments = $this->getDoctrine()->getManager()->getRepository('App\Entity\Instruments')->findAll();
        return $this->render('shop/instrumentsView.html.twig', ['listCategories' => $listCategories, 'listInstruments' => $listInstruments]);
    }

    public function viewSheet()
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();
        $listSheets = $this->getDoctrine()->getManager()->getRepository('App\Entity\Sheets')->findAll();
        return $this->render('shop/sheetsView.html.twig', ['listCategories' => $listCategories, 'listSheets' => $listSheets]);
    }
}
