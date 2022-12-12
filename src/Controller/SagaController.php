<?php

namespace App\Controller;

use App\Entity\Saga;
use App\Form\SagaType;
use App\Repository\SagaRepository;
use App\Service\SagaParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Movie;
use App\Service\MovieParsing;
use App\Repository\MovieRepository;

use App\Entity\Card;

#[Route('/saga')]
class SagaController extends AbstractController
{
    #[Route('/', name: 'app_saga_index', methods: ['GET'])]
    public function index(Request $request, SagaParsing $sagaParsing): Response
    {
        $page = $request->query->get('page');

        if(empty($page))
        {
            return $this->redirectToRoute('app_saga_index', ['page' => 1], Response::HTTP_SEE_OTHER);
        }
        else if($page < 1 || $page > 10)
        {
            throw $this->createNotFoundException('The page does not exist');
        }
        else
        {
            return $this->render('saga/index.html.twig', [
                'sagas' => $sagaParsing->indexParsing($page),
                'currentPage' => $page
            ]);
        }
    }

    #[Route('/new/{idTMDB}', name: 'app_saga_new', methods: ['GET'])]
    public function new(Int $idTMDB, SagaParsing $sagaParsing, SagaRepository $sagaRepository, MovieParsing $movieParsing, MovieRepository $movieRepository): Response
    {
        //Saga find request
        $saga = $sagaRepository->findOneBy(["idTMDB" => $idTMDB]);

        if(!isset($saga))
        {
            //Saga API request
            $result = $sagaParsing->sagaParsing($idTMDB);

            //Saga creation
            $saga = new Saga();
            $saga->setName($result['name']);
            $saga->setIdTMDB($result['id']);
            $sagaRepository->save($saga, true);

            foreach($result['movies'] as $part)
            {
                //Movie find request
                $movie = $movieRepository->findOneBy(["idTMDB" => $part->getId()]);

                if(!isset($movie))
                {
                    //Movie API request
                    $result2 = $movieParsing->movieParsing($part->getId());

                    //Movie creation
                    $movie = new Movie();
                    $movie->setGenres(json_encode($result2['genres']));
                    $movie->setName($result2['original_title']);
                    $movie->setDuration($result2['runtime']);
                    $movie->setIdTMDB($result2['id']);
                    $movie->setSaga($saga);
                    $movieRepository->save($movie, true);
                }
            }
        }

        return $this->redirectToRoute('app_watchlist_item_add', ['type' => 'saga', 'idEntity' => $saga->getId()]);
    }

    #[Route('/{id}', name: 'app_saga_show', methods: ['GET'])]
    public function show(int $id, SagaParsing $sagaParsing): Response
    {
        return $this->render('saga/show.html.twig', [
            'saga' => $sagaParsing->sagaParsing($id),
        ]);
    }
}
