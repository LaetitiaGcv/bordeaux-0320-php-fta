<?php


namespace App\Controller;

use App\Entity\ScheduleVolunteer;
use App\Entity\User;
use App\Service\CalendarService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

/**
 * Class ScheduleController
 * @package App\Controller
 */
class ScheduleController extends AbstractController
{
    /**
     * @Route("/calendar/{id}", name="calendar_schedule")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(int $id)
    {
        $calendar = $this->getDoctrine()
            ->getRepository(ScheduleVolunteer::class)
            ->findBy(['user' => $id]);

        return $this->render('schedule/calendar.html.twig', [
            'calendars' => $calendar

        ]);
    }

    /**
     * @Route("/ajax/schedule", name="ajax_schedule")
     * @param Request $request
     * @param SessionInterface $session
     * @return JsonResponse
     * @throws \Exception
     */
    public function ajaxAddSchedule(Request $request, SessionInterface $session): JsonResponse
    {
        $entityManager = $this->getDoctrine()
            ->getManager();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['mobicoopId' => $session->get('user')->getMobicoopId()]); //TODO: test
        $schedule = new ScheduleVolunteer();
        $date = new DateTime($request->request->get('datePicker'));
        $schedule->setDate($date);
        $schedule->setUser($user);
        $schedule->setIsAfternoon($request->request->has('afternoon') ? true : false);
        $schedule->setIsMorning($request->request->has('morning') ? true : false);
        $entityManager->persist($schedule);
        $entityManager->flush();
        $availabilityUsers = $user->getScheduleVolunteers();
        $table = CalendarService::transformToJson($availabilityUsers);
        return new JsonResponse($table);
    }
}
