<?php

namespace AppBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Atraccion;
use AppBundle\Form\AtraccionType;

/**
 * Atraccion controller.
 *
 */
class AtraccionController extends Controller
{
    /**
     * Lists all Atraccion entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $atraccions = $em->getRepository('AppBundle:Atraccion')->findAll();

        return $this->render('atraccion/index.html.twig', array(
            'Atraccions' => $atraccions,
        ));
    }

    /**
     * Creates a new Atraccion entity.
     *
     */
    public function newAction(Request $request)
    {
        $atraccion = new Atraccion();
        $form = $this->createForm('AppBundle\Form\AtraccionType', $atraccion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ( $atraccion->getFotoAtraccion() as $fotoAtraccion ) {
                $fotoAtraccion->setAtraccion( $atraccion );
            }
            
            foreach ( $atraccion->getDireccion() as $direccion ) {
                $direccion->setAtraccion( $atraccion );
            }

            $em->persist($atraccion);
            $em->flush();

            return $this->redirectToRoute('atraccion_edit', array('id' => $atraccion->getId()));
        }

        return $this->render('atraccion/new.html.twig', array(
            'Atraccion' => $atraccion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Atraccion entity.
     *
     */
    public function showAction(Atraccion $atraccion)
    {
        $deleteForm = $this->createDeleteForm($atraccion);

        return $this->render('atraccion/show.html.twig', array(
            'Atraccion' => $atraccion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Atraccion entity.
     *
     */
    public function editAction(Request $request, Atraccion $atraccion)
    {
        $deleteForm = $this->createDeleteForm($atraccion);
        $editForm = $this->createForm('AppBundle\Form\AtraccionType', $atraccion);

        $fotosOriginales = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ( $atraccion->getFotoAtraccion() as $fotoAtraccion ) {
            $fotosOriginales->add( $fotoAtraccion );
        }
        
        $domiciliosOriginales = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ( $atraccion->getDireccion() as $fotoAtraccion ) {
            $domiciliosOriginales->add( $fotoAtraccion );
        }

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ( $atraccion->getFotoAtraccion() as $fotoAtraccion ) {
                $fotoAtraccion->setAtraccion( $atraccion );
            }

            foreach ( $fotosOriginales as $fotosOriginale ) {
                if ( false === $atraccion->getFotoAtraccion()->contains( $fotosOriginale ) ) {
                    $fotosOriginale->setAtraccion( null );
                    $em->remove( $fotosOriginale );
                }
            }
            foreach ( $atraccion->getDireccion() as $direccion ) {
                $direccion->setAtraccion( $atraccion );
            }

            foreach ( $domiciliosOriginales  as $domiciliosOriginale ) {
                if ( false === $atraccion->getDireccion()->contains( $domiciliosOriginale ) ) {
                    $domiciliosOriginale->setAtraccion( null );
                    $em->remove( $domiciliosOriginale );
                }
            }

            $em->persist($atraccion);
            $em->flush();

            return $this->redirectToRoute('atraccion_edit', array('id' => $atraccion->getId()));
        }

        return $this->render('atraccion/edit.html.twig', array(
            'Atraccion' => $atraccion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Atraccion entity.
     *
     */
    public function deleteAction(Request $request, Atraccion $atraccion)
    {
        $form = $this->createDeleteForm($atraccion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($atraccion);
            $em->flush();
        }

        return $this->redirectToRoute('atraccion_index');
    }

    /**
     * Creates a form to delete a Atraccion entity.
     *
     * @param Atraccion $atraccion The Atraccion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Atraccion $atraccion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('atraccion_delete', array('id' => $atraccion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
