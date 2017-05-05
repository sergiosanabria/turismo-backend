<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class AgendasRestController extends FOSRestController
{

    public function getAgendasAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $agendas = $em->getRepository('AppBundle:Agenda')->getEventosByPage($request->get('page', 1));
        $ip = $request->getHost();
        //$ip = $this->getParameter('app.path.ip');

        $host = $request->getScheme() . '://' . $ip . $request->getBasePath() . $this->getParameter('app.path.images.agenda');

        $aAgendas = array();

        if ($agendas) {
            foreach ($agendas as $agenda) {

                foreach ($agenda->getFotoAgenda() as $foto) {
                    if ($foto->getRuta()) {
                        $foto->setRuta($host . '/' . $foto->getRuta());
                    }

                }


//                AGRUPA POR CATEGORIA LAS AGENDAS
//                $texto = $this->formatoFecha($agenda->getFechaEventoDesde()->format('Y-m-d'));
//                $index = $this->findGrupoIndex($aAgendas, $texto);
//                if ($index !== false) {
//                    //es un grupo
//                    $aAgendas[$index]['eventos'][] = $agenda;
//                } else {
//                    //creo otro grupo
//                    $index = count($aAgendas);
//
//                    $aAgendas[$index]['texto'] = $texto;
//                    $aAgendas[$index]['eventos'][] = $agenda;
//                }

            }
        }


        $vista = $this->view($agendas,
            200)
            ->setTemplate("AppBundle:Rest:getAgendas.html.twig")
            ->setTemplateVar('agendas');

        return $this->handleView($vista);
    }

    private function findGrupoIndex($array, $texto)
    {
        foreach ($array as $index => $item) {
            if ($item['texto'] == $texto) {
                return $index;
            }
        }

        return false;
    }

    /**
     * Devuelve una fecha formateada  asi 'Miercoles, 20 de Abril del 2016'
     *
     * @param DateTime $fecha format Y-m-d
     *
     * @return string
     */
    private function formatoFecha($fecha)
    {
        $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
        $meses = array(
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        );
        $timeStamp = strtotime(str_replace("/", "-", $fecha));
        $textoFecha = $dias[date('w', $timeStamp)] . ", " . date('d', $timeStamp) . " de " . $meses[date('n',
                $timeStamp) - 1] . " del " . date('Y', $timeStamp);

        return $textoFecha;
    }

}
