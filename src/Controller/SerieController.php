<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use App\Service\SerieParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie')]
class SerieController extends AbstractController
{
    #[Route('/', name: 'app_serie_index', methods: ['GET'])]
    public function index(Request $request, SerieParsing $serieParsing): Response
    {
        $page = $request->query->get('page');

        if(empty($page))
        {
            return $this->redirectToRoute('app_serie_index', ['page' => 1], Response::HTTP_SEE_OTHER);
        }
        else if($page < 1 || $page > 10)
        {
            throw $this->createNotFoundException('The page does not exist');
        }
        else
        {
            return $this->render('serie/index.html.twig', [
                'series' => $serieParsing->popularParsing($page),
                'currentPage' => $page
            ]);
        }
    }

    #[Route('/new/{idTMDB}', name: 'app_serie_new', methods: ['GET', 'POST'])]
    public function new(Int $idTMDB, SerieParsing $serieParsing, SerieRepository $serieRepository): Response
    {
        //Serie API request
        $result = $serieParsing->serieParsing($idTMDB);

        //Movie creation
        $serie = $serieRepository->findOneBy(["name" => $result['original_name']]);

        if(!isset($serie))
        {
            $serie = new Serie();
            $serie->setGenres(json_encode($result['genres']));
            $serie->setName($result['original_name']);
            $serieRepository->save($serie, true);
        }

        return $this->redirectToRoute('app_watchlist_item_add', ['type' => 'serie', 'idEntity' => $serie->getId()]);
    }

    #[Route('/{id}', name: 'app_serie_show', methods: ['GET'])]
    public function show(int $id, SerieParsing $serieParsing): Response
    {
        return $this->render('serie/show.html.twig', [
            'serie' => $serieParsing->serieParsing($id),
        ]);
    }
}
