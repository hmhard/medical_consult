<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/appointment')]
class AppointmentController extends AbstractController
{
    #[Route('/', name: 'app_appointment_index', methods: ['GET',"POST"])]
    public function index(AppointmentRepository $appointmentRepository,Request $request, EntityManagerInterface $em): Response
    {
        if($request->request->has('approve-appointment')){
           
            $appointmentRepository->find($request->request->get('appointment'))?->setStatus($request->request->has('approve')?1:2);
            $this->addFlash("success","Action Done");
            $em->flush();
            return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
     
        }
        $appointmentRepository->clearOld();
        return $this->render('appointment/index.html.twig', [
            'appointments' => $appointmentRepository->getData(),
        ]);
    }

    #[Route('/new', name: 'app_appointment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AppointmentRepository $appointmentRepository): Response
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appointmentRepository->add($appointment, true);

            return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appointment/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appointment_show', methods: ['GET'])]
    public function show(Appointment $appointment): Response
    {
        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appointment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appointment $appointment, AppointmentRepository $appointmentRepository): Response
    {
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appointmentRepository->add($appointment, true);

            return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_appointment_delete', methods: ['POST'])]
    public function delete(Request $request, Appointment $appointment, AppointmentRepository $appointmentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $appointmentRepository->remove($appointment, true);
        }

        return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
    }
}
