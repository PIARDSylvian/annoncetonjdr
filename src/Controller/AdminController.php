<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use App\Entity\User;
use App\Entity\Report;
use App\Entity\Association;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends EasyAdminController
{
    public function removeUserEntity($entity)
    {
        if ($entity == $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez vous supprimer vous même');
            return $this->redirectToRoute('easyadmin', ['action' => 'list', 'entity' => $this->entity['name']]);
        } elseif (in_array('ROLE_ADMIN', $entity->getRoles()) && !in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer un admin.');
            return $this->redirectToRoute('easyadmin', ['action' => 'list', 'entity' => $this->entity['name']]);
        }

        parent::removeEntity($entity);
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
        $entity->setRoles(['ROLE_ADMIN']);
        $em->flush();

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
            $this->addFlash('error', 'Vous ne pouvez vous enlever les droits vous même');
            return $this->redirectToRoute('easyadmin', ['action' => 'show', 'entity' => $request->query->get('entity'), 'id' => $entity->getId()]);
        } elseif (in_array('ROLE_ADMIN', $entity->getRoles()) && !in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())) {
            $this->addFlash('error', 'Vous ne pouvez vous enlever les droits d\' un admin.');
            return $this->redirectToRoute('easyadmin', ['action' => 'show', 'entity' => $request->query->get('entity'), 'id' => $entity->getId()]);
        }
        

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

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => $request->query->get('entity'),
        ));
    }

    /**
     * @Route("/admin/association/acceptAction", name="accept_Admin")
     */
    public function AcceptAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Association::class);
        
        $id = $request->query->get('id');
        $entity = $repository->find($id);
        $entity->setPendding(false);
        $em->flush();

        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => $request->query->get('entity'),
        ));
    }
}
