<?php

namespace AppBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Noticia;
use AppBundle\Form\NoticiaType;

/**
 * Noticia controller.
 *
 */
class NoticiaController extends Controller {
	/**
	 * Lists all Noticia entities.
	 *
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$noticias = $em->getRepository( 'AppBundle:Noticia' )->findAll();

		return $this->render( 'noticia/index.html.twig',
			array(
				'noticias' => $noticias,
			) );
	}

	/**
	 * Creates a new Noticia entity.
	 *
	 */
	public function newAction( Request $request ) {
		$noticium = new Noticia();
		$form     = $this->createForm( 'AppBundle\Form\NoticiaType', $noticium );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$em = $this->getDoctrine()->getManager();

			foreach ( $noticium->getFotoNoticias() as $fotoNoticia ) {
				$fotoNoticia->setNoticia( $noticium );
			}

			$em->persist( $noticium );
			$em->flush();

			return $this->redirectToRoute( 'noticias_edit', array( 'id' => $noticium->getId() ) );
		}

		return $this->render( 'noticia/new.html.twig',
			array(
				'noticium' => $noticium,
				'form'     => $form->createView(),
			) );
	}

	/**
	 * Finds and displays a Noticia entity.
	 *
	 */
	public function showAction( Noticia $noticium ) {
		$deleteForm = $this->createDeleteForm( $noticium );

		return $this->render( 'noticia/show.html.twig',
			array(
				'noticium'    => $noticium,
				'delete_form' => $deleteForm->createView(),
			) );
	}

	/**
	 * Displays a form to edit an existing Noticia entity.
	 *
	 */
	public function editAction( Request $request, Noticia $noticium ) {
		$deleteForm = $this->createDeleteForm( $noticium );
		$editForm   = $this->createForm( 'AppBundle\Form\NoticiaType', $noticium );

		$fotosOriginales = new ArrayCollection();

		// Create an ArrayCollection of the current Tag objects in the database
		foreach ( $noticium->getFotoNoticias() as $fotoNoticia ) {
			$fotosOriginales->add( $fotoNoticia );
		}

		$editForm->handleRequest( $request );

		if ( $editForm->isSubmitted() && $editForm->isValid() ) {

			$em = $this->getDoctrine()->getManager();

			foreach ( $noticium->getFotoNoticias() as $fotoNoticia ) {
				$fotoNoticia->setNoticia( $noticium );
			}

			foreach ( $fotosOriginales as $fotosOriginale ) {
				if ( false === $noticium->getFotoNoticias()->contains( $fotosOriginale ) ) {
					$fotosOriginale->setNoticia( null );
					$em->remove( $fotosOriginale );
				}
			}

			$em->persist( $noticium );
			$em->flush();

			return $this->redirectToRoute( 'noticias_edit', array( 'id' => $noticium->getId() ) );
		}

		return $this->render( 'noticia/edit.html.twig',
			array(
				'noticium'    => $noticium,
				'edit_form'   => $editForm->createView(),
				'delete_form' => $deleteForm->createView(),
			) );
	}

	/**
	 * Deletes a Noticia entity.
	 *
	 */
	public function deleteAction( Request $request, Noticia $noticium ) {
		$form = $this->createDeleteForm( $noticium );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$em = $this->getDoctrine()->getManager();
			$em->remove( $noticium );
			$em->flush();
		}

		return $this->redirectToRoute( 'noticias_index' );
	}

	/**
	 * Creates a form to delete a Noticia entity.
	 *
	 * @param Noticia $noticium The Noticia entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm( Noticia $noticium ) {
		return $this->createFormBuilder()
		            ->setAction( $this->generateUrl( 'noticias_delete', array( 'id' => $noticium->getId() ) ) )
		            ->setMethod( 'DELETE' )
		            ->getForm();
	}
}
