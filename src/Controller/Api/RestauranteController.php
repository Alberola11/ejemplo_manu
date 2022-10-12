<?php

namespace App\Controller\Api;

use App\Repository\RestauranteRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Rest\Route("/restaurante")
 */
class RestauranteController extends AbstractFOSRestController
{
    private $restauranteRepository;

    public  function __construct(RestauranteRepository $repo){
        $this->restauranteRepository= $repo;
    }

    //1. Devolver Restaurante por id. Me servira para mostrar la pagina del restaurante con toda su informacion

    /**
     * @Rest\Get (path="/{id}")
     * @Rest\View (serializerGroups={"get_restaurante"}, serializerEnableMaxDepthChecks=true)
     */

    public function  getRestaurante(Request $request)
    {
        //Aqui hemos adelantado pero deberiamos primero comprobar que esxiste igual que en otros controller
        return $this->restauranteRepository->find($request->get('id'));
    }

    //2. Devolver listado de restaurantes, según dia, hora y municipio-->
    //Primero selecionamos la direccion a la que se va a enviar
    //luego selecionamos  dia y hora del reparto
    //mostrar los restaurantes que cumplen esas condiciones

    /**
     * @Rest\Post (path="/filtered")
     * @Rest\View (serializerGroups={"res_filtered"}, serializerEnableMaxDepthChecks=true)
     */

    public  function getRestaurantesBy(Request $request)
    {
        $dia= $request->get('dia');
        $hora= $request->get('hora');
        $idMunicipio= $request->get('municipio');
        //Aqui por falta de tiempo no comprobamos, tocaria controlar que el form pasa de foma correcta como en otros casos

        //Como necesitamos una query más especifica, necesitamos crearla en el repository--> en este caso del restaurante

        $restaurantes=$this->restauranteRepository->findByDayTimeMunicipio($dia,$hora,$idMunicipio);
        if(!$restaurantes){
            return new Response('Not found', Response::HTTP_NOT_FOUND);
        }

        return $restaurantes;

    }


}