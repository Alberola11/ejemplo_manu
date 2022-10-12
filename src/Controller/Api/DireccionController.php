<?php

namespace App\Controller\Api;

use App\Entity\Direccion;
use App\Form\DireccionType;
use App\Repository\ClienteRepository;
use App\Repository\DireccionRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Rest\Route("/direccion")
 */

class DireccionController extends AbstractFOSRestController
{
    private $direccionRepository;

    public  function __construct(DireccionRepository $repo){
        $this->direccionRepository= $repo;
    }

    /**
     * @Rest\Post (path="/")
     * @Rest\View (serializerGroups={"post_dir"}, serializerEnableMaxDepthChecks=true)
     */

    public function CreateDirrecion(Request  $request)
    {
        //no hacenos nada diferente porque el que se encarga de controloar la relacion es el type donde especificamos
        //como se ralaciononan los atributos
        $direccion= new Direccion();
        $form= $this->createForm(DireccionType::class, $direccion);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid())
        {
            return $form;
        }

        $this->direccionRepository->add($direccion,true);
        return $direccion;
    }

    //endPoint que devuelva todas las direcciones de un cliente en base a su id

    /**
     * @Rest\Get (path="/{id}")
     * @Rest\View (serializerGroups={"get_dir_cliente"}, serializerEnableMaxDepthChecks=true)
     */

    //en este caso en  Direccion.yaml  no dvolveremos el campo cliente porque ya lo conocemos y no nos interesa ese campo
    public function getDireccionesByCliente(Request $request, ClienteRepository $clienteRepository){
        $idCliente= $request->get('id');
        //1. Al no estar en cliente necesito trareme los clientes de la BD

        $cliente= $clienteRepository->find($idCliente);//El find siempre sobre el id. Sobre otro atributo es el findBy

        //2. Compruebo si existe y sino envio un error
        if(!$cliente)
        {
            return new JsonResponse('No se ha encontrado el cliente', Response::HTTP_NOT_FOUND);
        }

        //3. Si existe entonces busco en la tabla direccion por el campo cliente

        $direcciones= $this->direccionRepository->findBy(['cliente'=>$cliente]);// se pueden buscar por más campos poniendo una , despues de cliente incluso  odren by ascendemte

        return $direcciones;

    }

    //update. Ademas si se quiere se puede utilizar el mismo serializer gruops en diferentes funciones.
    //Esto se hace cuando quiero traerme los mismos datos y ademas quiero no tenener que actualizar el serializer
    //lo que cambiariamos podria ser el path como si fuera un caso de get all quitariamos el id y si fuera uno solo  pondriamos el id

    /**
     * @Rest\Patch (path="/{id}")
     * @Rest\View (serializerGroups={"get_dir_cliente"}, serializerEnableMaxDepthChecks=true)
     */

    public  function  updateDireccion(Request $request)
    {
        $idDireccion= $request->get('id');
        $direccion= $this->direccionRepository->find($idDireccion);
        if (!$direccion){
            return new JsonResponse('no existe esa direccion', Response::HTTP_NOT_FOUND);
        }

        $form= $this->createForm(DireccionType::class, $direccion, ['method'=>$request->getMethod()]);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid())
        {
            return $form;
        }
        $this->direccionRepository->add($direccion, true);
        return $direccion;
    }

    //Delete
    /**
     * @Rest\Delete (path="/{id}")
     *
     */

    public  function deleteDireccion(Request $request)
    {
        $idDireccion= $request->get('id');
        $direccion= $this->direccionRepository->find($idDireccion);
        if (!$direccion){
           throw new NotFoundHttpException("No existe la direccion");
        }
        $this->direccionRepository->remove($direccion, true);
        return new Response('Eliminado', Response::HTTP_OK);
    }

}