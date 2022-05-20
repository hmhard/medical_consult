<?php

namespace App\Controller;

use App\Entity\CaseCategory;
use App\Form\CaseCategoryType;
use App\Repository\CaseCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/case-category')]
class CaseCategoryController extends AbstractController
{
    #[Route('/', name: 'app_case_category_index', methods: ['GET'])]
    public function index(CaseCategoryRepository $caseCategoryRepository): Response
    {
        return $this->render('case_category/index.html.twig', [
            'case_categories' => $caseCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_case_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CaseCategoryRepository $caseCategoryRepository): Response
    {
        $caseCategory = new CaseCategory();
        $form = $this->createForm(CaseCategoryType::class, $caseCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $caseCategoryRepository->add($caseCategory, true);

            return $this->redirectToRoute('app_case_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('case_category/new.html.twig', [
            'case_category' => $caseCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_case_category_show', methods: ['GET'])]
    public function show(CaseCategory $caseCategory): Response
    {
        return $this->render('case_category/show.html.twig', [
            'case_category' => $caseCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_case_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CaseCategory $caseCategory, CaseCategoryRepository $caseCategoryRepository): Response
    {
        $form = $this->createForm(CaseCategoryType::class, $caseCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $caseCategoryRepository->add($caseCategory, true);

            return $this->redirectToRoute('app_case_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('case_category/edit.html.twig', [
            'case_category' => $caseCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_case_category_delete', methods: ['POST'])]
    public function delete(Request $request, CaseCategory $caseCategory, CaseCategoryRepository $caseCategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$caseCategory->getId(), $request->request->get('_token'))) {
            $caseCategoryRepository->remove($caseCategory, true);
        }

        return $this->redirectToRoute('app_case_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
