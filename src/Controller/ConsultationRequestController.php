<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\CaseCategory;
use App\Entity\ConsultationConversation;
use App\Entity\ConsultationRequest;
use App\Entity\Doctor;
use App\Entity\Feedback;
use App\Entity\Patient;
use App\Form\ConsultationRequestType;
use App\Repository\ConsultationRequestRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
            'consultation_requests' => $consultationRequestRepository->getData(),
        ]);
    }


    #[Route('/new', name: 'app_consultation_request_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConsultationRequestRepository $consultationRequestRepository, EntityManagerInterface $em): Response
    {
        $consultationRequest = new ConsultationRequest();
        $form = $this->createForm(ConsultationRequestType::class, $consultationRequest);
        $form->handleRequest($request);

        $caseCategories = $em->getRepository(CaseCategory::class)->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            if($consultationRequestRepository->hasActiveRequest()){
                $this->addFlash("warning", "already there is active request");
                return $this->redirectToRoute('app_consultation_request_index', [], Response::HTTP_SEE_OTHER);
   
            }
            $patient = $this->getUser()->getPatient();

            /**
             * @var \App\User $user =null; 
             */
            $user = $this->getUser();
            if (!$patient) {
                $patient = new Patient();
                $patient->setFirstName($user->getFirstName());
                $patient->setMiddleName($user->getMiddleName());
                $patient->setLastName($user->getLastName());
                $patient->setGender($user->getGender());
                $patient->setBirthDate(new \DateTime());
                $patient->setUser($user);
                $em->persist($patient);
            }
            $consultationRequest->setPatient($patient);
            $consultationRequest->setAssignedTo($em->getRepository(Doctor::class)->find(2));
            $consultationRequest->setRfn(strtoupper(bin2hex(random_bytes(10))));
            $consultationRequestRepository->add($consultationRequest, true);

            $this->addFlash("success", "Consultation sent, please process payment");
            return $this->redirectToRoute('app_payment_process_fee', ["id" => $consultationRequest->getId()]);
        }

        return $this->renderForm('consultation_request/new.html.twig', [
            'consultation_request' => $consultationRequest,
            'case_categories' => $caseCategories,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consultation_request_show', methods: ['GET', "POST"])]
    public function show(ConsultationRequest $consultationRequest, Request $request, EntityManagerInterface $em): Response
    {
        /**
         * redirect to payment
         */
        if ($consultationRequest->getStatus() == 0) {
            return $this->redirectToRoute('app_payment_process_fee', ["id" => $consultationRequest->getId()]);
        }

        /**
         * close case
         */
        if ($request->request->get('close_case')) {

            $consultationRequest->setStatus(4);
            $em->flush();
            $this->addFlash("success", "Case Closed");
            return $this->redirectToRoute('app_consultation_request_show', ["id" => $consultationRequest->getId()], Response::HTTP_SEE_OTHER);
        }

        /**
         *  feed back
         */
        if ($request->request->get('set-feedback')) {


            $feedback = new Feedback();

            $feedback->setUser($this->getUser());
            $feedback->setIsSeen(0);
            $feedback->setSentAt(new \DateTime());
            $feedback->setContent($request->request->get('input-feedback'));

            $em->persist($feedback);
            $em->flush();
            $this->addFlash("success", "Thanks for your feedback");
            return $this->redirectToRoute('app_consultation_request_show', ["id" => $consultationRequest->getId()], Response::HTTP_SEE_OTHER);
        }

        /**
         * create appointment request
         */

        if ($request->request->get('appoint')) {

            // dd($request->request->all());
            // dd($request->request->get('date'));
           
            $date = new \DateTime($request->request->get('date'));
            $time = new \DateTime($request->request->get('time'));
            // // dd($date->format('l'));
            // dd($date->format('h:i'));

            $doctor=$em->getRepository(Doctor::class)->find($request->request->get('doctor'));
            $object_values = $doctor->getAllFields();

            $exists = 0;
            foreach ($object_values as $key => $value) {
                if (ucfirst($key) == $date->format('l') && $value == 1) {
                    $exists = 1;
                }

            }
            $is_between=false;
            // dd($time);
            $paymentDate=$time->format('h:i');
            if (($paymentDate >= $doctor->getAvailableTimeFrom()->format('h:i')) && ($paymentDate <= $doctor->getAvailableTimeTo()->format('h:i'))) {
                $is_between=true;
            }
          
            $doctor=$em->getRepository(Doctor::class)->find($request->request->get('doctor'));
           $has_active_appointment= $em->getRepository(Appointment::class)->hasActiveAppointment($doctor,$request->request->get('date'));
          if ($exists ) {
           if ($is_between ) {
               if(!$has_active_appointment){

                $appointment = new Appointment();
                $appointment->setDoctor($doctor);
                $appointment->setPatient($consultationRequest->getPatient());
                $appointment->setDescription($request->request->get('description'));
                $appointment->setAppointmentDate(new \Datetime($request->request->get('date')." ".$request->request->get('time')));
                $appointment->setAppointmentCase($consultationRequest);
                $appointment->setIsMade(false);
                $consultationRequest->setAssignedTo($doctor);
                $em->persist($appointment);
                $em->flush();
                $this->addFlash("success", "New Appointment created");
                return $this->redirectToRoute('app_consultation_request_show', ["id" => $consultationRequest->getId()]);
            }
            else{
                $this->addFlash("warning", $doctor." has active appointment on ".$request->request->get('date')." Please Choose another date");
                return $this->redirectToRoute('app_consultation_request_show', ["id" => $consultationRequest->getId()]);
            }
        } else{
                $this->addFlash("warning", "Doctor is not available on the selected time");
                return $this->redirectToRoute('app_consultation_request_show', ["id" => $consultationRequest->getId()]);
            }
        } else{
                $this->addFlash("warning", "Doctor is not available on the selected day");
                return $this->redirectToRoute('app_consultation_request_show', ["id" => $consultationRequest->getId()]);
            }
        }
        if ($request->request->get('response')) {
            $conversation = new ConsultationConversation();
            $conversation->setSentBy($this->getUser());
            if ($consultationRequest->getPatient()->getUser() == $this->getUser())
                $type = 1;
            else
                $type = 2;

            $conversation->setSentBy($this->getUser());
            $conversation->setContent($request->request->get('response_description'));
            $conversation->setCaseRequest($consultationRequest);
            $conversation->setType($type);

            $em->persist($conversation);
            $em->flush();
        }
        // $dateTime = new \DateTime(
        //     'now'
        // );
        // $day = $dateTime->format('l');
        // dd($day);

        $caseCategory = $em->getRepository(CaseCategory::class)->findOneBy(["id" => $consultationRequest->getCaseCategory()]);

        return $this->render('consultation_request/show.html.twig', [
            'consultation_request' => $consultationRequest,
            'case_category' => $caseCategory,

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
        if ($this->isCsrfTokenValid('delete' . $consultationRequest->getId(), $request->request->get('_token'))) {
            $consultationRequestRepository->remove($consultationRequest, true);
        }

        return $this->redirectToRoute('app_consultation_request_index', [], Response::HTTP_SEE_OTHER);
    }
}
