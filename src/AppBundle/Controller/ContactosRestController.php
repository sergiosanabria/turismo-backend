<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contacto;
use AppBundle\Form\ContactoType;
use AppBundle\Repository\ContactoRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;


class ContactosRestController extends FOSRestController implements ClassResourceInterface {


	private $manager;
	private $repo;
	private $formFactory;
	private $router;

	/**
	 * Controller constructor
	 * @var ObjectManager $manager
	 * @var ContactoRepository $repo
	 * @var FormFactoryInterface $formFactory
	 * @var RouterInterface $router
	 */
	public function __construct(
		ObjectManager $manager,
		ContactoRepository $repo,
		FormFactoryInterface $formFactory,
		RouterInterface $router
	) {
		$this->manager     = $manager;
		$this->repo        = $repo;
		$this->formFactory = $formFactory;
		$this->router      = $router;
	}

	/**
	 * Create an organisation
	 * @var Request $request
	 * @return View|FormInterface
	 * @Post("/contactos")
	 *
	 */
	public function postAction( Request $request ) {

		$contacto = new Contacto();
		$form     = $this->formFactory->createNamed( '', new ContactoType(), $contacto );
		$form->handleRequest( $request );
		if ( $form->isValid() ) {
			$this->manager->persist( $contacto );
			$this->manager->flush( $contacto );

			return View::create( $contacto, 200 );

		}

		return View::create( $form, 400 );
	}

	/**
	 * Retrieve a Contacto
	 * @var Contacto $contacto
	 * @return Contacto
	 *
	 *
	 * @Rest\View()
	 */
	public function getAction( Contacto $contacto ) {
		return $contacto;
	}


	/**
	 * Retrieve all organisations
	 * @return Collection
	 *
	 * @Rest\View()
	 * @Get("/contactos")
	 */
	public function getContactosAction() {
		$contactos = $this->repo->findAll();

		return $contactos;
	}

}
