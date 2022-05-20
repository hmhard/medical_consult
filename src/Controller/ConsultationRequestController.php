<?php

namespace App\Controller;

use App\Entity\ConsultationRequest;
use App\Form\ConsultationRequestType;
use App\Repository\ConsultationRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/consultation-request')]
class ConsultationRequestController extends AbstractController
{
    #[Route('/', name: 'app_consultation_request_index', methods: ['GET'])]
    public function index(ConsultationRequestRepository $consultationRequestRepository): Response
    {
        return $this->render('consultation_request/index.html.twig', [
            'consultation_requests' => $consultationRequestRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_consultation_request_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConsultationRequestRepository $consultationRequestRepository): Response
    {
        $consultationRequest = new ConsultationRequest();
        $form = $this->createForm(ConsultationRequestType::class, $consultationRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consultationRequestRepository->add($consultationRequest, true);

            return $this->redirectToRoute('app_consultation_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('consultation_request/new.html.twig', [
            'consultation_request' => $consultationRequest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consultation_request_show', methods: ['GET'])]
    public function show(ConsultationRequest $consultationRequest): Response
    {
        return $this->render('consultation_request/show.html.twig', [
            'consultation_request' => $consultationRequest,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_consultation_request_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ConsultationRequest $consultationRequest, ConsultationRequestRepository $consultationRequestRepository): Response
    {
        $form = $this->createForm(ConsultationRequestType::class, $consultationRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consultationRequestRepository->add($consultationRequest, true);

            return $this->redirectToRoute('app_consultation_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('consultation_request/edit.html.twig', [
            'consultation_request' => $consultationRequest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consultation_request_delete', methods: ['POST'])]
    public function delete(Request $request, ConsultationRequest $consultationRequest, ConsultationRequestRepository $consultationRequestRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultationRequest->getId(), $request->request->get('_token'))) {
            $consultationRequestRepository->remove($consultationRequest, true);
        }

        return $this->redirectToRoute('app_consultation_request_index', [], Response::HTTP_SEE_OTHER);
    }
}
