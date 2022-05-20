<?php

namespace App\Controller;

use App\Entity\UserType;
use App\Form\UserTypeType;
use App\Repository\UserTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user-type')]
class UserTypeController extends AbstractController
{
    #[Route('/', name: 'app_user_type_index', methods: ['GET'])]
    public function index(UserTypeRepository $userTypeRepository): Response
    {
        return $this->render('user_type/index.html.twig', [
            'user_types' => $userTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserTypeRepository $userTypeRepository): Response
    {
        $userType = new UserType();
        $form = $this->createForm(UserTypeType::class, $userType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userTypeRepository->add($userType, true);

            return $this->redirectToRoute('app_user_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_type/new.html.twig', [
            'user_type' => $userType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_type_show', methods: ['GET'])]
    public function show(UserType $userType): Response
    {
        return $this->render('user_type/show.html.twig', [
            'user_type' => $userType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserType $userType, UserTypeRepository $userTypeRepository): Response
    {
        $form = $this->createForm(UserTypeType::class, $userType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userTypeRepository->add($userType, true);

            return $this->redirectToRoute('app_user_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_type/edit.html.twig', [
            'user_type' => $userType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_type_delete', methods: ['POST'])]
    public function delete(Request $request, UserType $userType, UserTypeRepository $userTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userType->getId(), $request->request->get('_token'))) {
            $userTypeRepository->remove($userType, true);
        }

        return $this->redirectToRoute('app_user_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
