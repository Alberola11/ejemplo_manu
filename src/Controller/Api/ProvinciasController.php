<?php

namespace App\Controller\Api;

use App\Repository\MunicipiosRepository;
use App\Repository\ProvinciasRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Rest\Route("/provincias")
 */

class ProvinciasController extends AbstractFOSRestController
{

    private $provinciasRepository;
    private $municipiosRepository;

    public  function __construct(ProvinciasRepository $repoP, MunicipiosRepository $repoM){
        $this->provinciasRepository= $repoP;
        $this->municipiosRepository=$repoM;
    }

    //1. devolver todas las provincias(id y nombre)

    /**
     * @Rest\Get (path="/")
     * @Rest\View (serializerGroups={"get_provincias"}, serializerEnableMaxDepthChecks= true)
     */

    public  function getProvincias(Request $request)
    {

        $provincias=$this->provinciasRepository->findAll();
        if (!$provincias) {
            return new JsonResponse("No se ha encontrado ninguna provincia", Response::HTTP_NOT_FOUND);
        }
        return $provincias;
    }

    //2. devolver los municipios de una provincia(id y nombre)

    /**
     * @Rest\Get (path="/{id}")
     * @Rest\View (serializerGroups={"get_municipios"}, serializerEnableMaxDepthChecks= true)
     */

    public function getMunicipiosByProvincia(Request $request)
    {
        return $this->municipiosRepository->findBy(['idProvincia' =>$request->get('id')]);
    }
}