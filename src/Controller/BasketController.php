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
use App\Entity\CommandeProduit;
use App\Repository\CommandeProduitRpository;
use App\Entity\Commande;
use App\Repository\CommandeRpository;
use App\Form\BasketType;

class BasketController extends Controller
{
    public function buy($id, $name, Request $request)
    {
        if ($name === 'instrument')
        {
            $prod = $this->getDoctrine()->getManager()->getRepository('App\Entity\Instruments')->find($id);
            if (null === $prod)
            {
	            throw $this->createNotFoundException("L'instrument d'id ".$id." n'existe pas.");
	        }
        }
        else if ($name === 'sheet')
        {
            $prod = $this->getDoctrine()->getManager()->getRepository('App\Entity\Sheets')->find($id);
            if (null === $prod)
            {
	            throw $this->createNotFoundException("La partition d'id ".$id." n'existe pas.");
	        }
        }
        else
        {
            throw $this->createNotFoundException("Cet article n'existe pas.");
        }

        if (!$session = $request->getSession()->get('basket'))
        {
            $cmd = new Commande();
            $session = $request->getSession()->set('basket', $cmd->getId());
        }
        else
        {
            $cmd = $this->getDoctrine()->getManager()->getRepository('App\Entity\Commande')->find($request->getSession()->get('basket'));
        }

        $bsk = new CommandeProduit();
        $bsk->setCommande($cmd);
        //ne pas oublier le if/else pour les partitions si ça fonctionne
        $bsk->setInstrument($prod);

        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();

        $form   = $this->createForm(BasketType::class, $bsk);

		if ($request->isMethod('POST'))
        {
			$form->handleRequest($request);
	  
			if ($form->isValid())
            {
			    $em = $this->getDoctrine()->getManager()->persist($bsk)->flush();
	  
                $request->getSession()->getFlashBag()->add('notice', 'Article ajouté à votre panier.');
	  
			    return $this->redirectToRoute('index', ['listCategories' => $listCategories]);
			}
		  }
	   
		return $this->render('basket/addToBasket.html.twig', ['form' => $form->createView(), 'listCategories' => $listCategories, 'prod' => $prod]);
    }

    public function viewBasket()
    {
        
    }
}
