<?php

namespace App\Controller;

use App\Form\FiltersType;
use App\Form\RegistrationFormType;
use App\Form\SearchType;
use App\Service\CatalogParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogController extends AbstractController
{
    #[Route('/catalog', name: 'app_catalog_index')]
    public function index( Request $request, CatalogParsing $catalogParsing): Response
    {

        $queryForm = $this->createForm(SearchType::class);
        $queryForm->handleRequest($request);

        $filtersForm = $this->createForm(FiltersType::class);
        $filtersForm->handleRequest($request);

        $currentQuery = $request->query->get('query');
        $page = $request->query->get('page');

        // TODO passer en string le tableau de filtrers puis le passer dans la requête.



        if(empty($page))
            return $this->redirectToRoute('app_catalog_index', ['page' => 1], Response::HTTP_SEE_OTHER);
        else if($page < 1 || $page > 10)
            throw $this->createNotFoundException('The page does not exist');
        else
        {

            //TODO gérer les cas ou il y'a une recherche et la pagination en même temps.
            if ($queryForm->isSubmitted() && $queryForm->isValid()) {
                $page = 1;

                $data = $queryForm->getData();
                $currentQuery = $data['query'];

                $catalogArray = $catalogParsing->queryParsing($page, $data['query']);
                usort($catalogArray, function($first,$second){
                    return strtolower($first->getTitle()) > strtolower($second->getTitle());
                });

            }
            else if($filtersForm->isSubmitted() && $filtersForm->isValid()){
                $page = 1;
                $data = $filtersForm->getData();

                $catalogArray = $catalogParsing->queryMaker(
                    $page,
                    ['primary_release_date.gte' => $data['minDate'],
                        'primary_release_date.lte' => $data['maxDate'],
                        'include_adult' => $data['includeAdult'],
                        'with_runtime.lte' => $data['maxTime'],
                    ],
                    $data['sortBy']
                );

                usort($catalogArray, function($first,$second){
                    return $first->getReleaseDate() > $second->getReleaseDate();
                });


            }
            else if($currentQuery){

                $catalogArray = $catalogParsing->queryParsing($page, $currentQuery);
                usort($catalogArray, function($first,$second){
                    return strtolower($first->getTitle()) > strtolower($second->getTitle());
                });
            }
            else{
                $catalogArray = $catalogParsing->popularParsing($page);
                shuffle($catalogArray);
            }



            return $this->render('catalog/index.html.twig', [
                'controller_name' => 'CatalogController',
                'catalog' =>  $catalogArray,
                'currentPage' => $page,
                'queryForm' => $queryForm->createView(),
                'filtersForm' => $filtersForm->createView(),
                'currentFilters' => is_null($filtersForm->getData()) ? null : $filtersForm->getData(),
                'currentQuery' => $currentQuery,

            ]);
        }


    }

}
