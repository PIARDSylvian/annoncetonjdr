<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\User;
use App\Entity\Party;
use App\Entity\Event;
use App\Entity\Association;
use App\Entity\Commentary;
use App\Entity\Report;
use App\Form\ReportType;


class ReportController extends AbstractController
{
    /**
     * @Route("/report/user/{id}", name="app_report_user", requirements={"id"="\d+"})
     */
    public function reportUser(User $user, Request $request)
    {
        $report = new report();
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $report->setOwner($this->getUser());
            $report->setUser($user);
            $report->setDate(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($report);
            $entityManager->flush();

            $this->addFlash('notice', 'Signalement enregistré');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('report.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/report/party/{id}", name="app_report_party", requirements={"id"="\d+"})
     */
    public function reportParty(Party $party, Request $request)
    {
        $report = new report();
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $report->setOwner($this->getUser());
            $report->setParty($party);
            $report->setDate(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($report);
            $entityManager->flush();

            $this->addFlash('notice', 'Signalement enregistré');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('report.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/report/event/{id}", name="app_report_event", requirements={"id"="\d+"})
     */
    public function reportEvent(Event $event, Request $request)
    {
        $report = new report();
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $report->setOwner($this->getUser());
            $report->setEvent($event);
            $report->setDate(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($report);
            $entityManager->flush();

            $this->addFlash('notice', 'Signalement enregistré');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('report.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/report/association/{id}", name="app_report_association", requirements={"id"="\d+"})
     */
    public function reportAssociation(Association $association, Request $request)
    {
        $report = new report();
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $report->setOwner($this->getUser());
            $report->setAssociation($association);
            $report->setDate(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($report);
            $entityManager->flush();

            $this->addFlash('notice', 'Signalement enregistré');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('report.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/report/commentary/{id}", name="app_report_commentary", requirements={"id"="\d+"})
     */
    public function reportCommentary(Commentary $commentary, Request $request)
    {
        $report = new report();
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $report->setOwner($this->getUser());
            $report->setCommentary($commentary);
            $report->setDate(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($report);
            $entityManager->flush();

            $this->addFlash('notice', 'Signalement enregistré');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('report.html.twig', array('form' => $form->createView()));
    }
}
