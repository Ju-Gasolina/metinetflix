<?php

namespace App\Controller;

use App\Service\CatalogParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogController extends AbstractController
{
    #[Route('/catalog', name: 'app_catalog_index')]
    public function index(Request $request, CatalogParsing $catalogParsing): Response
    {

        $page = $request->query->get('page');

        if(empty($page))
        {
            return $this->redirectToRoute('app_catalog_index', ['page' => 1], Response::HTTP_SEE_OTHER);
        }
        else if($page < 1 || $page > 10)
        {
            throw $this->createNotFoundException('The page does not exist');
        }
        else
        {
            $catalogArray = $catalogParsing->popularParsing($page);
            shuffle($catalogArray);
            return $this->render('catalog/index.html.twig', [
                'controller_name' => 'CatalogController',
                'catalog' =>  $catalogArray,
                'currentPage' => $page
            ]);
        }


    }
}
