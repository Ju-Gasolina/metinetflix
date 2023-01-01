<?php

namespace App\Controller;

use App\Form\FiltersType;
use App\Form\RegistrationFormType;
use App\Form\SearchType;

use App\Service\CatalogParsing;
use ContainerFS3jsxr\getHeaderUtilsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CatalogController extends AbstractController
{
    #[Route('/catalog', name: 'app_catalog_index')]
    public function index(Request $request, CatalogParsing $catalogParsing): Response
    {

        $queryForm = $this->createForm(SearchType::class);
        $queryForm->handleRequest($request);

        $filtersForm = $this->createForm(FiltersType::class);
        $filtersForm->handleRequest($request);

        $currentQuery = $request->query->get('query');
        $currentFilters = $request->query->get('filters');
        $page = $request->query->get('page');


        if (empty($page))
            return $this->redirectToRoute('app_catalog_index', ['page' => 1], Response::HTTP_SEE_OTHER);
        else if ($page < 1 || $page > 10)
            throw $this->createNotFoundException('The page does not exist');
        else {

            if ($queryForm->isSubmitted() && $queryForm->isValid()) {

                $data = $queryForm->getData();
                return $this->redirectToRoute('app_catalog_index', ['page' => 1, 'query' => $data['query']], Response::HTTP_SEE_OTHER);

            } else if ($filtersForm->isSubmitted() && $filtersForm->isValid()) {

                $data = $filtersForm->getData();
                $currentFilters = str_replace(" ", "", HeaderUtils::toString(
                    [
                        'primary_release_date.gte' => $data['minDate']->format('Y-m-d'),
                        'primary_release_date.lte' => $data['maxDate']->format('Y-m-d'),
                        'include_adult' => json_encode($data['includeAdult']),
                        'with_runtime.lte' => strval($data['maxTime']),
                        'sortBy' => $data['sortBy']
                    ],
                    '!'));


                return $this->redirectToRoute('app_catalog_index', ['page' => 1, 'filters' => $currentFilters], Response::HTTP_SEE_OTHER);

            } else if ($currentQuery) {
                $catalogArray = $catalogParsing->queryParsing($page, $currentQuery);

            } else if ($currentFilters) {

                $arrayFilters = array_map(function ($item) {
                    return $item[0];
                }, HeaderUtils::parseQuery($currentFilters, true, '!'));

                $catalogArray = $catalogParsing->queryMaker(
                    $page,
                    $arrayFilters
                );

            } else {
                $catalogArray = $catalogParsing->popularParsing($page);
                shuffle($catalogArray);
            }


            return $this->render('catalog/index.html.twig', [
                'controller_name' => 'CatalogController',
                'catalog' => $catalogArray,
                'currentPage' => $page,
                'queryForm' => $queryForm->createView(),
                'filtersForm' => $filtersForm->createView(),
                'currentFilters' => $currentFilters,
                'currentQuery' => $currentQuery,

            ]);
        }
    }



}
