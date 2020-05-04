<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Instruments;
use App\Entity\Categories;
use App\Repository\InstrumentsRpository;
use App\Repository\CategoriesRepository;
use App\Entity\CommandeProduit;
use App\Repository\CommandeProduitRpository;
use App\Entity\Commande;
use App\Repository\CommandeRpository;
use App\Form\BasketType;
use Symfony\Component\HttpFoundation\Response;

class BasketController extends Controller
{
    public function buy($id, Request $request)
    {
        $prod = $this->getDoctrine()->getManager()->getRepository('App\Entity\Instruments')->find($id);
        if (null === $prod)
        {
	        throw $this->createNotFoundException("L'instrument d'id ".$id." n'existe pas.");
	    }
               
        if (!$user = $this->getUser())
        {
            throw $this->createNotFoundException("Un problème est survenu lors de votre connexion.");
        }
        
        if (!$user->getCommande())
        {
            $cmd = new Commande();
            $cmd->setDone(1);
            $user->setCommande($cmd);
        }
        else
        {
            $cmd = $this->getDoctrine()->getManager()->getRepository('App\Entity\Commande')->find($user->getCommande()->getId());
        }
        $bsk = new CommandeProduit();

        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();

        $form   = $this->createForm(BasketType::class, $bsk);

		if ($request->isMethod('POST'))
        {
			$form->handleRequest($request);
	  
			if ($form->isValid())
            {
                $bsk->setCommande($cmd);
                $bsk->setInstrument($prod);

                $em = $this->getDoctrine()->getManager();
                $em->persist($bsk);
                $em->flush();
	  
                $request->getSession()->getFlashBag()->add('notice', 'Article ajouté à votre panier.');
	  
			    return $this->redirectToRoute('panier', ['listCategories' => $listCategories]);
			}
		  }
	   
		return $this->render('basket/addToBasket.html.twig', ['form' => $form->createView(), 'listCategories' => $listCategories, 'prod' => $prod]);
    }

    public function viewBasket()
    {
        if (!$user = $this->getUser())
        {
            throw $this->createNotFoundException("Un problème est survenu lors de votre connexion.");
        }

        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();
        
        if (!$user->getCommande())
        {
            $cmd = new Commande();
            $cmd->setDone(1);
            $user->setCommande($cmd);
        }
        else
        {
            $cmd = $this->getDoctrine()->getManager()->getRepository('App\Entity\Commande')->find($user->getCommande()->getId());
        }

        if ($cmd->getDone() === 2)
        {
            $done = 'Vous avez déjà passé une commande.';
            return $this->render('basket/basket.html.twig', ['listCategories' => $listCategories, 'done' => $done]);
        }

        $listArticles = $this->getDoctrine()->getManager()->getRepository('App\Entity\CommandeProduit')->getArticlesInCommande($cmd->getId());
        return $this->render('basket/basket.html.twig', ['listCategories' => $listCategories, 'listArticles' => $listArticles]);
    }

    public function commande(Request $request)
    {
        if (!$user = $this->getUser())
        {
            throw $this->createNotFoundException("Un problème est survenu lors de votre connexion.");
        }

        $cmd = $this->getDoctrine()->getManager()->getRepository('App\Entity\Commande')->find($user->getCommande()->getId());
        $cmd->setDone(2);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Commande bien passée ! Merci de votre confiance !');
        return $this->redirectToRoute('panier');
    }

    public function reception(Request $request)
    {
        if (!$user = $this->getUser())
        {
            throw $this->createNotFoundException("Un problème est survenu lors de votre connexion.");
        }

        $em = $this->getDoctrine()->getManager();
        $cmd = $em->getRepository('App\Entity\Commande')->find($user->getCommande()->getId());
        $listArticles = $em->getRepository('App\Entity\CommandeProduit')->getArticlesInCommande($user->getCommande()->getId());
        $em->remove($cmd);
        foreach ($listArticles as $article)
        {
            $em->remove($article);
        }
        $cmd1 = new Commande();
        $cmd1->setDone(1);
        $user->setCommande($cmd1);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Heureux de savoir votre commande entre vos mains !  Nous espérons vous revoir vite !');
        return $this->redirectToRoute('panier');
    }

    public function delete(Request $request)
    {
        if (!$user = $this->getUser())
        {
            throw $this->createNotFoundException("Un problème est survenu lors de votre connexion.");
        }

        $em = $this->getDoctrine()->getManager();
        $cmd = $em->getRepository('App\Entity\Commande')->find($user->getCommande()->getId());
        $listArticles = $em->getRepository('App\Entity\CommandeProduit')->getArticlesInCommande($user->getCommande()->getId());
        $em->remove($cmd);
        foreach ($listArticles as $article)
        {
            $em->remove($article);
        }
        $cmd1 = new Commande();
        $cmd1->setDone(1);
        $user->setCommande($cmd1);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Votre panier a bien été vidé.');
        return $this->redirectToRoute('index');
    }

    public function commandesAdminView()
    {
        $listCategories = $this->getDoctrine()->getManager()->getRepository('App\Entity\Categories')->findAll();
        $listCommandes = $this->getDoctrine()->getManager()->getRepository('App\Entity\Commande')->findBy(array('done' => 2));
        return $this->render('basket/adminCommande.html.twig', ['listCategories' => $listCategories, 'listCommandes' => $listCommandes]);
    }

    public function deleteAdminCommande($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cmd = $em->getRepository('App\Entity\Commande')->find($id);
        $listArticles = $em->getRepository('App\Entity\CommandeProduit')->getArticlesInCommande($id);
        foreach ($listArticles as $article)
        {
            $em->remove($article);
        }
        $cmd1 = new Commande();
        $cmd1->setDone(1);
        $user = $em->getRepository('App\Entity\User')->findOneBy(array('commande' => $cmd));
        $em->remove($cmd);
        $user->setCommande($cmd1);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Commande client supprimée');
        return $this->redirectToRoute('admin_commande');
    }
}