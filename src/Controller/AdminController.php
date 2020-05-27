<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use App\Entity\User;
use App\Entity\Report;
use App\Entity\Event;
use App\Entity\Association;
use App\Service\AdminLogger;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends EasyAdminController
{
    private $logger;

    public function __construct(AdminLogger $adminLogger)
    {
        $this->logger = $adminLogger;
    }

    public function removeEntity($entity)
    {
        $logMesage = $this->getUser()->__toString() . ' a supprimer ' . (new \ReflectionClass(get_class($entity)))->getShortName() .' : '. $entity->__toString();
        $this->logger->warning($logMesage);

        parent::removeEntity($entity);
    }

    public function persistGameEntity($entity)
    {
        $logMesage = $this->getUser()->__toString() . ' a créee ' . (new \ReflectionClass(get_class($entity)))->getShortName() .' : '. $entity->getName() . ', imageUrl : ' . $entity->getImageUrl();
        $this->logger->notice($logMesage);

        parent::persistEntity($entity);
    }

    public function updateGameEntity($entity)
    {
        $logMesage = $this->getUser()->__toString() . ' a édité ' . (new \ReflectionClass(get_class($entity)))->getShortName() .' : '. $entity->getName() . ', imageUrl : ' . $entity->getImageUrl();
        $this->logger->notice($logMesage);

        parent::updateEntity($entity);
    }

    public function removeUserEntity($entity)
    {
        $logMesage = $this->getUser()->__toString() . ' a tenter du supprimer ' . (new \ReflectionClass(get_class($entity)))->getShortName() .' : '. $entity->__toString();
        $this->logger->notice($logMesage);

        if ($entity == $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez vous supprimer vous même');
            return $this->redirectToRoute('easyadmin', ['action' => 'list', 'entity' => $this->entity['name']]);
        } elseif (in_array('ROLE_ADMIN', $entity->getRoles()) && !in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer un admin.');
            return $this->redirectToRoute('easyadmin', ['action' => 'list', 'entity' => $this->entity['name']]);
        } elseif (in_array('ROLE_SUPER_ADMIN', $entity->getRoles()) ) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer un super admin.');
            return $this->redirectToRoute('easyadmin', ['action' => 'list', 'entity' => $this->entity['name']]);
        }

        $logMesage = $this->getUser()->__toString() . ' a supprimer ' . (new \ReflectionClass(get_class($entity)))->getShortName() .' : '. $entity->__toString();
        $this->logger->warning($logMesage);

        parent::removeEntity($entity);
    }

    /**
     * @Route("/admin/user/suspend", name="suspend_Admin")
     */
    public function SuspendAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);

        $id = $request->query->get('id');
        $entity = $repository->find($id);

        if ($entity == $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez vous desactiver');
            return $this->redirectToRoute('easyadmin', ['action' => 'show', 'entity' => $request->query->get('entity'), 'id' => $entity->getId()]);
        } elseif (in_array('ROLE_ADMIN', $entity->getRoles()) && !in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())) {
            $this->addFlash('error', 'Vous ne pouvez pas desactiver un admin.');
            return $this->redirectToRoute('easyadmin', ['action' => 'show', 'entity' => $request->query->get('entity'), 'id' => $entity->getId()]);
        } elseif (in_array('ROLE_SUPER_ADMIN', $entity->getRoles()) ) {
            $this->addFlash('error', 'Vous ne pouvez pas desactiver un SUPER_ADMIN.');
            return $this->redirectToRoute('easyadmin', ['action' => 'show', 'entity' => $request->query->get('entity'), 'id' => $entity->getId()]);
        }

        $entity->setSuspend(true);
        $em->flush();

        $logMesage = $this->getUser()->__toString() . ' a desactiver ' . (new \ReflectionClass(get_class($entity)))->getShortName() .' : '. $entity->__toString();
        $this->logger->notice($logMesage);

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => $request->query->get('entity'),
        ));
    }

    /**
     * @Route("/admin/user/activate", name="activate_Admin")
     */
    public function ActivateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);

        $id = $request->query->get('id');
        $entity = $repository->find($id);

        if ($entity == $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez vous activer');
            return $this->redirectToRoute('easyadmin', ['action' => 'show', 'entity' => $request->query->get('entity'), 'id' => $entity->getId()]);
        } elseif (in_array('ROLE_ADMIN', $entity->getRoles()) && !in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())) {
            $this->addFlash('error', 'Vous ne pouvez pas activer un admin.');
            return $this->redirectToRoute('easyadmin', ['action' => 'show', 'entity' => $request->query->get('entity'), 'id' => $entity->getId()]);
        } elseif (in_array('ROLE_SUPER_ADMIN', $entity->getRoles()) ) {
            $this->addFlash('error', 'Vous ne pouvez pas activer un SUPER_ADMIN.');
            return $this->redirectToRoute('easyadmin', ['action' => 'show', 'entity' => $request->query->get('entity'), 'id' => $entity->getId()]);
        }

        $logMesage = $this->getUser()->__toString() . ' à activer ' . (new \ReflectionClass(get_class($entity)))->getShortName() .' : '. $entity->__toString();
        $this->logger->notice($logMesage);

        $entity->setSuspend(false);
        $em->flush();

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => $request->query->get('entity'),
        ));
    }

    /**
     * @Route("/admin/user/addAdmin", name="add_Admin")
     */
    public function AddAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);

        $id = $request->query->get('id');
        $entity = $repository->find($id);

        if (in_array('ROLE_SUPER_ADMIN', $entity->getRoles()) ) {
            $this->addFlash('error', 'Vous ne pouvez pas redéfinir un SUPER_ADMIN.');
            return $this->redirectToRoute('easyadmin', ['action' => 'list', 'entity' => $request->query->get('entity')]);
        }

        $entity->setRoles(['ROLE_ADMIN']);
        $em->flush();

        $logMesage = $this->getUser()->__toString() . ' a ajouter le role ROLE_ADMIN à ' . (new \ReflectionClass(get_class($entity)))->getShortName() .' : '. $entity->__toString();
        $this->logger->notice($logMesage);

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => $request->query->get('entity'),
        ));
    }

    /**
     * @Route("/admin/user/removeAdmin", name="remove_Admin")
     */
    public function RemoveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);

        $id = $request->query->get('id');
        $entity = $repository->find($id);

        if ($entity == $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez vous enlever vos propre droit');
            return $this->redirectToRoute('easyadmin', ['action' => 'show', 'entity' => $request->query->get('entity'), 'id' => $entity->getId()]);
        } elseif (in_array('ROLE_ADMIN', $entity->getRoles()) && !in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())) {
            $this->addFlash('error', 'Vous ne pouvez vous enlever les droits d\' un admin.');
            return $this->redirectToRoute('easyadmin', ['action' => 'show', 'entity' => $request->query->get('entity'), 'id' => $entity->getId()]);
        } elseif (in_array('ROLE_SUPER_ADMIN', $entity->getRoles()) ) {
            $this->addFlash('error', 'Vous ne pouvez pas redéfinir un SUPER_ADMIN.');
            return $this->redirectToRoute('easyadmin', ['action' => 'show', 'entity' => $request->query->get('entity'), 'id' => $entity->getId()]);
        }

        $logMesage = $this->getUser()->__toString() . ' à retirer le role ROLE_ADMIN à ' . (new \ReflectionClass(get_class($entity)))->getShortName() .' : '. $entity->__toString();
        $this->logger->notice($logMesage);

        $entity->setRoles([]);
        $em->flush();

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => $request->query->get('entity'),
        ));
    }

    /**
     * @Route("/admin/openreport/closedAction", name="closed_Admin")
     */
    public function ClosedAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Report::class);
        
        $id = $request->query->get('id');
        $entity = $repository->find($id);
        $entity->setSolved(true);
        $em->flush();

        $logMesage = $this->getUser()->__toString() . ' à fermer le ' . (new \ReflectionClass(get_class($entity)))->getShortName() .' : '. $entity->__toString();
        $this->logger->notice($logMesage);

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => $request->query->get('entity'),
        ));
    }

    /**
     * @Route("/admin/acceptAction", name="accept_Admin")
     */
    public function AcceptAction(Request $request)
    {
        $entityName = $request->query->get('entity');
        $em = $this->getDoctrine()->getManager();
        if ($entityName == "Event") {
            $repository = $this->getDoctrine()->getRepository(Event::class);
        } else {
            $repository = $this->getDoctrine()->getRepository(Association::class);
        }
        
        $id = $request->query->get('id');
        $entity = $repository->find($id);
        $entity->setPendding(false);
        $em->flush();

        $logMesage = $this->getUser()->__toString() . ' à accepeter l\' ' . (new \ReflectionClass(get_class($entity)))->getShortName() .' : '. $entity->__toString();
        $this->logger->notice($logMesage);

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => $request->query->get('entity'),
        ));
    }
}
