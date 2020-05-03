<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Instruments;
use App\Entity\Categories;
use App\Repository\InstrumentsRpository;
use App\Repository\CategoriesRepository;
use App\Entity\Sheets;
use App\Repository\SheetsRpository;

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
}
