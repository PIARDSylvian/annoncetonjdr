<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use App\Entity\Party;
use App\Entity\Commentary;
use App\Entity\Event;
use App\Entity\Note;
use App\Form\PartyType;
use App\Form\CommentaryType;
use App\Form\NoteType;
use Knp\Component\Pager\PaginatorInterface;

class PartyController extends AbstractController {
    
    /**
     * @Route("/party/create", name="app_party_create")
     * @Route("/party/update/{id}", name="app_party_update", requirements={"id"="\d+"})
     */
    public function create(Party $party = null, Request $request)
    {
        if($party && $party->getDate() <= new \DateTime('now')) {
            $this->addFlash('danger', 'Partie terminée');
            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }
        if($party && $party->getDate() <= new \DateTime('+2 hours')) {
            $this->addFlash('danger', 'Modification non autorisé, 2h avant le début');
            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        } elseif (!$party) {
            $party = new Party();
        } 

        $form = $this->createForm(PartyType::class, $party);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $party->setOwner($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($party);
            $entityManager->flush();

            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }

        return $this->render('party/create.html.twig',array('form' => $form->createView()));
    }

    /**
     * @Route("/party/{id}", name="app_party_show", requirements={"id"="\d+"})
     */
    public function show(Party $party, Request $request, PaginatorInterface $paginator)
    {
        $repository = $this->getDoctrine()->getRepository(Commentary::class);

        $partyQB = $repository->findByPartyQueryBuilder($party);
        $commentaries = $paginator->paginate(
            $partyQB,
            $request->query->getInt('page', 1),
            10
        );

        $commentaries->setCustomParameters(['align' => 'center']);

        $commentary = new Commentary();
        $form = $this->createForm(CommentaryType::class, $commentary);

        $note = new Note();
        $noteForm = $this->createForm(NoteType::class, $note,[
            'action' => $this->generateUrl('app_party_note', ['id' => $party->getId()] ),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $commentary->setOwner($this->getUser());
            $commentary->setParty($party);
            $commentary->setCreatedAt(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentary);
            $entityManager->flush();

            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }

        return $this->render('party/show.html.twig', ['party' => $party,'noteForm' => $noteForm->createView(),'form' => $form->createView(),'commentaries' => $commentaries]);
    }

    /**
     * @Route("/party/delete/{id}", name="app_party_delete", requirements={"id"="\d+"})
     */
    public function delete(Party $party)
    {
        if ($this->getUser() === $party->getOwner()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($party);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/party/delete/{id}/com/{comment_id}", name="app_party_delete_com", requirements={"id"="\d+", "cid"="\d+"})
     * @Entity("commentary", expr="repository.find(comment_id)")
     */
    public function deleteCom(Party $party, Commentary $commentary)
    {
        if ($this->getUser() === $party->getOwner() || $this->getUser() === $commentary->getOwner()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentary);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
    }

    /**
     * @Route("/party/delete-all-com/{id}", name="app_party_delete_all_com", requirements={"id"="\d+"})
     */
    public function deleteAllCom(Party $party)
    {
        if ($this->getUser() === $party->getOwner()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($party->getCommentaries() as $commentary) {
                $entityManager->remove($commentary);
            }
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
    }

    /**
     * @Route("/party/addPlayer/{id}", name="app_party_add", requirements={"id"="\d+"})
     */
    public function addPlayer(Party $party)
    {
        if($party && $party->getDate() <= new \DateTime('now')) {
            $this->addFlash('danger', 'Partie terminée');
            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }
        if($party && $party->getDate() <= new \DateTime('+2 hours')) {
            $this->addFlash('danger', 'Inscription non autorisé, 2h avant le début');
            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }
        if (count($party->getRegisteredPlayers()) < $party->getMaxPlayer() && ($this->getUser() != $party->getOwner())) {
            $party->addRegisteredPlayers($this->getUser());
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('notice', 'Inscription validée');
        }

        return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
    }

    /**
     * @Route("/party/removePlayer/{id}", name="app_party_remove", requirements={"id"="\d+"})
     */
    public function removePlayer(Party $party)
    {
        if($party && $party->getDate() <= new \DateTime('now')) {
            $this->addFlash('danger', 'Partie terminée');
            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }
        $party->removeRegisteredPlayers($this->getUser());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $this->addFlash('notice', 'Désinscription validée');

        return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
    }

    /**
     * @Route("/party/note/{id}", name="app_party_note", requirements={"id"="\d+"})
     */
    public function note(Party $party, Request $request)
    {
        if($party && $party->getDate() > new \DateTime('now')) {
            $this->addFlash('danger', 'Partie non terminée');
        } elseif ($party && !$party->getRegisteredPlayers()->contains($this->getUser())) {
            $this->addFlash('danger', 'Non inscrit');
        } elseif ($party && $party->getNote() && $party->getNote()->getNotePlayers()->contains($this->getUser())) {
            $this->addFlash('danger', 'Déjà voté');
        } elseif ($party)  {
            $note = New Note();
            $form = $this->createForm(NoteType::class, $note);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $partyNote = $party->getNote();

                if (!$partyNote) {
                    $party->setNote($note);
                    $partyNote = $note;
                } else {
                    $partyNote->setAmbiance($partyNote->getAmbiance() + $note->getAmbiance());
                    $party->setNote($partyNote);
                }
                
                $partyNote->addNotePlayer($this->getUser());
                $entityManager->flush();
                $this->addFlash('notice', 'Partie noté');
            }
        }
        return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
    }
}
