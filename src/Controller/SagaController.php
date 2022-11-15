<?php

namespace App\Controller;

use App\Entity\Saga;
use App\Form\SagaType;
use App\Repository\SagaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/saga')]
class SagaController extends AbstractController
{
    #[Route('/', name: 'app_saga_index', methods: ['GET'])]
    public function index(SagaRepository $sagaRepository): Response
    {
        return $this->render('saga/index.html.twig', [
            'sagas' => $sagaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_saga_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SagaRepository $sagaRepository): Response
    {
        $saga = new Saga();
        $form = $this->createForm(SagaType::class, $saga);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sagaRepository->save($saga, true);

            return $this->redirectToRoute('app_saga_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('saga/new.html.twig', [
            'saga' => $saga,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_saga_show', methods: ['GET'])]
    public function show(Saga $saga): Response
    {
        return $this->render('saga/show.html.twig', [
            'saga' => $saga,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_saga_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Saga $saga, SagaRepository $sagaRepository): Response
    {
        $form = $this->createForm(SagaType::class, $saga);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sagaRepository->save($saga, true);

            return $this->redirectToRoute('app_saga_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('saga/edit.html.twig', [
            'saga' => $saga,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_saga_delete', methods: ['POST'])]
    public function delete(Request $request, Saga $saga, SagaRepository $sagaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$saga->getId(), $request->request->get('_token'))) {
            $sagaRepository->remove($saga, true);
        }

        return $this->redirectToRoute('app_saga_index', [], Response::HTTP_SEE_OTHER);
    }
}
