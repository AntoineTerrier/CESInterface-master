<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @Route("/shop", name="shop")
     */
    public function index(ProductRepository $productrep, PurchaseRepository $purchaserep)
    {
    	$allProducts = $productrep->findAll();
    	$query = $purchaserep->findByBestSellers();
    	
    	$bestThree = [];
    	// $bestThree[0] = $allProducts[$query[0]['id']];
    	// $bestThree[1] = $allProducts[$query[1]['id']];
    	// $bestThree[2] = $allProducts[$query[2]['id']];
        return $this->render('shop/index.html.twig', [
            'allProducts' => $allProducts,
            'bestThree' => $bestThree,
            'query'=> $query
        ]);
    }

    /**
     * @Route("/cgv", name="cgv")
     */
    public function cgv()
    {
        return $this->render('shop/cgv.html.twig');
    }
}
