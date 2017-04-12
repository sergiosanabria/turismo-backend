<?php

namespace AppBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Agenda;
use AppBundle\Form\AgendaType;

/**
 * Agenda controller.
 *
 */
class AgendaController extends Controller
{
    /**
     * Lists all Agenda entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $agendas = $em->getRepository('AppBundle:Agenda')->findAll();

        return $this->render('agenda/index.html.twig', array(
            'agendas' => $agendas,
        ));
    }

    /**
     * Creates a new Agenda entity.
     *
     */
    public function newAction(Request $request)
    {
        $agenda = new Agenda();
        $form = $this->createForm('AppBundle\Form\AgendaType', $agenda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ( $agenda->getFotoAgenda() as $fotoAgenda ) {
                $fotoAgenda->setAgenda( $agenda );
            }
            
            foreach ( $agenda->getDireccion() as $direccion ) {
                $direccion->setAgenda( $agenda );
            }

            $em->persist($agenda);
            $em->flush();

            return $this->redirectToRoute('agenda_edit', array('id' => $agenda->getId()));
        }

        return $this->render('agenda/new.html.twig', array(
            'agenda' => $agenda,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Agenda entity.
     *
     */
    public function showAction(Agenda $agenda)
    {
        $deleteForm = $this->createDeleteForm($agenda);

        return $this->render('agenda/show.html.twig', array(
            'agenda' => $agenda,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Agenda entity.
     *
     */
    public function editAction(Request $request, Agenda $agenda)
    {
        $deleteForm = $this->createDeleteForm($agenda);
        $editForm = $this->createForm('AppBundle\Form\AgendaType', $agenda);

        $fotosOriginales = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ( $agenda->getFotoAgenda() as $fotoAgenda ) {
            $fotosOriginales->add( $fotoAgenda );
        }
        
        $domiciliosOriginales = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ( $agenda->getDireccion() as $fotoAgenda ) {
            $domiciliosOriginales->add( $fotoAgenda );
        }

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ( $agenda->getFotoAgenda() as $fotoAgenda ) {
                $fotoAgenda->setAgenda( $agenda );
            }

            foreach ( $fotosOriginales as $fotosOriginale ) {
                if ( false === $agenda->getFotoAgenda()->contains( $fotosOriginale ) ) {
                    $fotosOriginale->setAgenda( null );
                    $em->remove( $fotosOriginale );
                }
            }
            foreach ( $agenda->getDireccion() as $direccion ) {
                $direccion->setAgenda( $agenda );
            }

            foreach ( $domiciliosOriginales  as $domiciliosOriginale ) {
                if ( false === $agenda->getDireccion()->contains( $domiciliosOriginale ) ) {
                    $domiciliosOriginale->setAgenda( null );
                    $em->remove( $domiciliosOriginale );
                }
            }

            $em->persist($agenda);
            $em->flush();

            return $this->redirectToRoute('agenda_edit', array('id' => $agenda->getId()));
        }

        return $this->render('agenda/edit.html.twig', array(
            'agenda' => $agenda,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Agenda entity.
     *
     */
    public function deleteAction(Request $request, Agenda $agenda)
    {
        $form = $this->createDeleteForm($agenda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($agenda);
            $em->flush();
        }

        return $this->redirectToRoute('agenda_index');
    }

    /**
     * Creates a form to delete a Agenda entity.
     *
     * @param Agenda $agenda The Agenda entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Agenda $agenda)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('agenda_delete', array('id' => $agenda->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
