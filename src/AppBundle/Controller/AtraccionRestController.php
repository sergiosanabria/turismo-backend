<?php

namespace AppBundle\Controller;

//use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Atraccion;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class AtraccionRestController extends FOSRestController
{

    public function getAtraccionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
//		$noticias = $em->getRepository( 'AppBundle:Atr' )->getAtraccionByPage($request->get('page', 1));
        $atracciones = $em->getRepository('AppBundle:Atraccion')->get();

        $ip = $request->getHost();
        //$ip = $this->getParameter('app.path.ip');

        $host = $request->getScheme() . '://' . $ip . $request->getBasePath() . $this->getParameter('app.path.images.atraccion');


        /**
         * @var $a Atraccion
         */
        foreach ($atracciones as $a) {

            foreach ($a->getFotoAtraccion() as $foto) {
                if ($foto->getRuta()) {
                    $foto->setRuta($host . '/' . $foto->getRuta());
                }

            }

        }
        $vista = $this->view($atracciones,
            200)
//			->setTemplate( "MyBundle:Users:getUsers.html.twig" )
//			->setTemplateVar( 'noticias' )
        ;

        return $this->handleView($vista);
    }

//    public function getAtraccionAction() {
//        $em      = $this->getDoctrine()->getManager();
//        $noticias = $em->getRepository( 'AppBundle:Noticia' )->getUltimasAtraccion();
//
//        header('Access-Control-Allow-Origin: *');
//        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
//        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
//        return array( 'noticias' => $noticias );
//    }
}
