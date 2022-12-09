<?php

namespace App\Controller;

use App\Service\CatalogParsing;
use App\Service\MovieParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CatalogParsing $catalogParsing): Response
    {

        $arrayItem = $catalogParsing->upcomingParsing(1);
        shuffle($arrayItem);

        return $this->render('/index.html.twig', [
            'controller_name' => 'HomeController',
            'items' => array_slice($arrayItem, 0, 15),
        ]);
    }
}
