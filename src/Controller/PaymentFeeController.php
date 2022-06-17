<?php

namespace App\Controller;

use App\Entity\ConsultationRequest;
use App\Entity\PaymentFee;
use App\Form\PaymentFeeType;
use App\Repository\ConsultationRequestRepository;
use App\Repository\PaymentFeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/payment-fee')]
class PaymentFeeController extends AbstractController
{
    #[Route('/', name: 'app_payment_fee_index', methods: ['GET'])]
    public function index(PaymentFeeRepository $paymentFeeRepository): Response
    {
        return $this->render('payment_fee/index.html.twig', [
            'payment_fees' => $paymentFeeRepository->findAll(),
        ]);
    }
    #[Route('/payment-list', name: 'payment_list', methods: ['GET'])]
    public function payment_list(ConsultationRequestRepository $consultationRequestRepository): Response
    {
      $total_price=  $consultationRequestRepository->getTotalPrice();

     
        return $this->render('payment_fee/payment.html.twig', [
            'consultation_requests' => $consultationRequestRepository->getPaymentList(),
       "total_price"=>$total_price
        ]);
    }

    #[Route('/new', name: 'app_payment_fee_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaymentFeeRepository $paymentFeeRepository): Response
    {
        $paymentFee = new PaymentFee();
        $form = $this->createForm(PaymentFeeType::class, $paymentFee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentFeeRepository->add($paymentFee, true);

            return $this->redirectToRoute('app_payment_fee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('payment_fee/new.html.twig', [
            'payment_fee' => $paymentFee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payment_fee_show', methods: ['GET'])]
    public function show(PaymentFee $paymentFee): Response
    {
        return $this->render('payment_fee/show.html.twig', [
            'payment_fee' => $paymentFee,
        ]);
    }
    #[Route('/fee/{id}', name: 'app_payment_process_fee', methods: ['GET',"POST"])]
    public function fee(ConsultationRequest $consultationRequest,Request $request,EntityManagerInterface $em): Response
    {
        if($consultationRequest->getStatus()==1){
            $this->addFlash('warning', 'Payment already Processed');
            return $this->redirectToRoute('app_consultation_request_show', ['id'=>$consultationRequest->getId()], Response::HTTP_SEE_OTHER);
    
        }
        if($request->request->has('input-pay')){

            if($request->request->get('input-rfn')==$consultationRequest->getRfn()){
                $consultationRequest->setStatus(1);
                $em->flush();

                $this->addFlash('success', 'Payment Processed');
                return $this->redirectToRoute('app_consultation_request_show', ['id'=>$consultationRequest->getId()], Response::HTTP_SEE_OTHER);
       
            }
            else{
                $this->addFlash('warning', 'Payment not Processed');

            }
        }
        return $this->render('payment_fee/fee.html.twig', [
            'consultation_request' => $consultationRequest,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_payment_fee_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PaymentFee $paymentFee, PaymentFeeRepository $paymentFeeRepository): Response
    {
        $form = $this->createForm(PaymentFeeType::class, $paymentFee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentFeeRepository->add($paymentFee, true);

            return $this->redirectToRoute('app_payment_fee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('payment_fee/edit.html.twig', [
            'payment_fee' => $paymentFee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payment_fee_delete', methods: ['POST'])]
    public function delete(Request $request, PaymentFee $paymentFee, PaymentFeeRepository $paymentFeeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paymentFee->getId(), $request->request->get('_token'))) {
            $paymentFeeRepository->remove($paymentFee, true);
        }

        return $this->redirectToRoute('app_payment_fee_index', [], Response::HTTP_SEE_OTHER);
    }
}
