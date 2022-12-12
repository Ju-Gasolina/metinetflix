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
            $catalogArray = [];
            //TODO gérer les cas ou il y'a une recherche et la pagination en même temps.
            if ($queryForm->isSubmitted() && $queryForm->isValid()) {
                $data = $queryForm->getData();

                $catalogArray = $catalogParsing->queryParsing($page, $data['alphabetical']);
                usort($catalogArray, function($first,$second){
                    return strtolower($first->getTitle()) > strtolower($second->getTitle());
                });

            }
            else if($filtersForm->isSubmitted() && $filtersForm->isValid()){

                $data = $filtersForm->getData();

                //TODO Faire les tris de tableaux pour chaque cas

                $catalogArray = $catalogParsing->sortParsing($page, $data['alphabetical']);
                if($data['alphabetical'] === 'date.asc'){
                    usort($catalogArray, function($first,$second){
                        return $first->getReleaseDate() > $second->getReleaseDate();
                    });
                }
                else if($data['alphabetical'] === 'date.desc'){
                    usort($catalogArray, function($first,$second){
                        return $first->getReleaseDate() < $second->getReleaseDate();
                    });
                }else{
                    usort($catalogArray, function($first,$second){
                        return strtolower($first->getTitle()) > strtolower($second->getTitle());
                    });
                }



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
                'filtersForm' => $filtersForm->createView()
            ]);
        }


    }

//    #[Route('/catalog/query', name: 'app_catalog_query')]
//    public function query(Request $request, CatalogParsing $catalogParsing): Response
//    {
//
//        $page = $request->query->get('page');
//        $query = $request->query->get('query');
//
//
//
//        if(empty($page))
//        {
//            return $this->redirectToRoute('app_catalog_query', ['page' => 1, 'query' => $query], Response::HTTP_SEE_OTHER);
//        }
//        else if($page < 1 || $page > 10)
//        {
//            throw $this->createNotFoundException('The page does not exist');
//        }
//        else
//        {
//            $catalogArray = $catalogParsing->queryParsing($page, 'coucou');
//
//            usort($catalogArray, function($first,$second){
//                return strtolower($first->getTitle()) > strtolower($second->getTitle());
//            });
//
//
//
//            //shuffle($catalogArray);
//            return $this->render('catalog/index.html.twig', [
//                'controller_name' => 'CatalogController',
//                'catalog' =>  $catalogArray,
//                'currentPage' => $page,
//            ]);
//        }


//    }
}
