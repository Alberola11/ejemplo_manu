<?php

namespace App\Controller\Api;

use App\Entity\Categoria;
use App\Repository\CategoriaRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//Al hacerlo asi conseguimos que en cada Routa y cada anotacion venga de categoria y asi no lo hacemos en cada una
/**
 * @Rest\Route("/categoria")
 */

class CategoriaController extends AbstractFOSRestController
{
    private $categoriaRepository;

    public  function __construct(CategoriaRepository $repo)
    {
        $this->categoriaRepository=$repo;
    }

    //Ahora ya utilizamos la anotacion de forrestBundle. Si el rest nos sale en amarillo tenemos que importarlo
    //Ademas gracias al hacer la anotacion fuera de la clase no hace falta que para cada una del rest\get o \post pongamos
    //el \categoria
    // si es en una entidad que no tiene realaciones el serialize lo podemos poner en false y evitamos que consuma memoria.
    /**
     * @Rest\Get(path="/")
     * @Rest\View (serializerGroups={"get_categorias"}, serializerEnableMaxDepthChecks= true)
     */

    public function getCategorias()
    {
        return $this->categoriaRepository->findAll();
    }

    /**
     * @Rest\Post (path="/")
     * @Rest\View (serializerGroups={"post_categoria"}, serializerEnableMaxDepthChecks= true)
     */

    public  function  createCategoria( Request  $request)
    {
     $categoria= $request->get('categoria');

     //este significa que si no llega categoria entonces enviamos el mensaje de error en la peticion
     if (!$categoria)
     {
        return new JsonResponse('Error en la petición', Response::HTTP_BAD_REQUEST);
     }

     $cat = new Categoria();
     $cat->setCategoria($categoria);
     //guardamos en base de datos

        $this->categoriaRepository->add($cat,true);

        //enviar una respuesta al cliente

        return $cat;

    }
}