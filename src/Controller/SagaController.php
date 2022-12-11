<?php

namespace App\Controller;

use App\Entity\Saga;
use App\Form\SagaType;
use App\Repository\SagaRepository;
use App\Service\SagaParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/saga')]
class SagaController extends AbstractController
{
    #[Route('/new/{idTMDB}', name: 'app_saga_new', methods: ['GET'])]
    public function new(Int $idTMDB, SagaParsing $sagaParsing, SagaRepository $sagaRepository): Response
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
