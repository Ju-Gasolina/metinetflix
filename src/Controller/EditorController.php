<?php

namespace App\Controller;

use App\Entity\Editor;
use App\Form\EditorType;
use App\Repository\EditorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/editor')]
class EditorController extends AbstractController
{
    #[Route('/', name: 'app_editor_index', methods: ['GET'])]
    public function index(EditorRepository $editorRepository): Response
    {
        return $this->render('editor/index.html.twig', [
            'editors' => $editorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_editor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EditorRepository $editorRepository): Response
    {
        $editor = new Editor();
        $form = $this->createForm(EditorType::class, $editor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $editorRepository->save($editor, true);

            return $this->redirectToRoute('app_editor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('editor/new.html.twig', [
            'editor' => $editor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_editor_show', methods: ['GET'])]
    public function show(Editor $editor): Response
    {
        return $this->render('editor/show.html.twig', [
            'editor' => $editor,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_editor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Editor $editor, EditorRepository $editorRepository): Response
    {
        $form = $this->createForm(EditorType::class, $editor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $editorRepository->save($editor, true);

            return $this->redirectToRoute('app_editor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('editor/edit.html.twig', [
            'editor' => $editor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_editor_delete', methods: ['POST'])]
    public function delete(Request $request, Editor $editor, EditorRepository $editorRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$editor->getId(), $request->request->get('_token'))) {
            $editorRepository->remove($editor, true);
        }

        return $this->redirectToRoute('app_editor_index', [], Response::HTTP_SEE_OTHER);
    }
}
