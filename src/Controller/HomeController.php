<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\ConsultationRequest;
use App\Entity\Doctor;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/profile', name: 'app_profile')]
    public function profile(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
    {

        /** 
         * @var \App\Entity\User|null $user
         */

        $user = $this->getUser();

        $doctor = $user->getDoctor();


        //reset password
        if ($request->request->has('reset')) {

            if (!$userPasswordHasher->isPasswordValid($user, $request->request->get('current-password')) || ($request->request->get('new-password') != $request->request->get('confirm-password'))) {
                $this->addFlash("danger", "Password not match");
            } else {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $request->request->get('new-password')
                    )
                );
                $em->flush();
                $this->addFlash("success", "Profile Updated");
                return $this->redirectToRoute('app_profile');
            }
        }

        //update schedule
        if ($request->request->has('update-profile') && $doctor) {

            // dd($request->request->all());

            $doctor->setMonday($request->request->get("monday", 0));
            $doctor->setTuesday($request->request->get("tuesday", 0));
            $doctor->setWednesday($request->request->get("wednesday", 0));
            $doctor->setThursday($request->request->get("thursday", 0));
            $doctor->setFriday($request->request->get("friday", 0));
            $doctor->setSaturday($request->request->get("saturday", 0));
            $doctor->setSunday($request->request->get("sunday", 0));
            $doctor->setAvailableTimeFrom(new \DateTime($request->request->get("time-from")));
            $doctor->setAvailableTimeTo(new \DateTime($request->request->get("time-to")));
            $em->flush();
            $this->addFlash("success", "Updated");
            return $this->redirectToRoute('app_profile');
        }
        $user = $this->getUser();
        return $this->render('home/profile.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $my_requests = sizeof($em->getRepository(ConsultationRequest::class)->findBy(["patient" => $this->getUser()->getPatient()]));
        $my_consultation = sizeof($em->getRepository(ConsultationRequest::class)->findBy(["assignedTo" => $this->getUser()->getDoctor()]));
        $active_appointment = $em->getRepository(Appointment::class)->getCount(["doctor" => $this->getUser()->getDoctor(), "active" => true]);
        $active_cases = $em->getRepository(ConsultationRequest::class)->getCount(["doctor" => $this->getUser()->getDoctor(), "active" => true]);
        $total_cases = $em->getRepository(ConsultationRequest::class)->getCount();
        $total_users = $em->getRepository(User::class)->getCount();
        $active_users = $em->getRepository(User::class)->getCount(["active" => true]);
        $total_doctors = $em->getRepository(Doctor::class)->getCount(["active" => true]);

        return $this->render('home/dashboard.html.twig', [
            "my_requests" => $my_requests,
            "my_consultation" => $my_consultation,
            "active_appointment" => $active_appointment,
            "active_cases" => $active_cases,
            "total_cases" => $total_cases,
            "total_users" => $total_users,
            "active_users" => $active_users,
            "total_doctors" => $total_doctors,
        ]);
    }
}
