<?php

namespace App\Controller\Api;

use App\Entity\Categoria;
use App\Form\CategoriaType;
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
    // crud create, read, update, delete
    private $categoriaRepository;

    //gracias a esto con el this solo podremos accder a las funciones del symfony como create form
    public function __construct(CategoriaRepository $repo)
    {
        $this->categoriaRepository = $repo;
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

    //Traer una categoria

    /**
     * @Rest\Get (path="/{id}")
     * @Rest\View (serializerGroups={"get_categoria"}, serializerEnableMaxDepthChecks= true)
     */

    public function getCategoria(Request $request)
    {
        $idCategoria = $request->get('id');
        $categoria = $this->categoriaRepository->find($idCategoria);
        if (!$categoria) {
            return new JsonResponse("No se ha encontrado la categoria", Response::HTTP_NOT_FOUND);
        }
        return $categoria;
    }

    // comentamos esta parte pq vamos a modificarlo(asi tenemos un ejemplo con forrest y otro para los formularios para crear categoriaas)
//
//    /**
//     * @Rest\Post (path="/")
//     * @Rest\View (serializerGroups={"post_categoria"}, serializerEnableMaxDepthChecks= true)
//     */
//
//    public  function  createCategoria( Request  $request)
//    {
//     $categoria= $request->get('categoria');
//
//     //este significa que si no llega categoria entonces enviamos el mensaje de error en la peticion
//     if (!$categoria)
//     {
//        return new JsonResponse('Error en la petición', Response::HTTP_BAD_REQUEST);
//     }
//
//     $cat = new Categoria();
//     $cat->setCategoria($categoria);
//     //guardamos en base de datos
//
//        $this->categoriaRepository->add($cat,true);
//
//        //enviar una respuesta al cliente
//
//        return $cat;
//
//    }


    /**
     * @Rest\Post (path="/")
     * @Rest\View (serializerGroups={"post_categoria"}, serializerEnableMaxDepthChecks= true)
     */

    //este es el ejemplo con formularios
    public function createCategoria(Request $request)
    {
        //1. Creo el objeto vacio
        $cat = new Categoria();
        //2. Inicializamos el form: le mandamos el type que es el categoriaType en este caso y la entidad nueva creada
        $form = $this->createForm(CategoriaType::class, $cat);
        //3. Le decimos al formulario que maneje la request a traves del handleRequest
        $form->handleRequest($request);
        //4. comprobar que no hay error(siempre tenemos que comprobar el error). Por tanto si no es valido retornamos
        //sin añadir a la base de datos. Tambien se puede hacer alreves como en los apuntes sin la negacion(comprobando a positivo)
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $form;//se puede hacer asi o con el Json que hemos puesto en otra funcion
        }
        //5. Si es correcto guardamos en base de datos. El categoriaReposistory es donde estan las funciones de añadir borrar en la base de datos
        $categoria = $form->getData();//esto se puede sustituir quitandole i en add $ cat y return $cat
        $this->categoriaRepository->add($categoria, true);
        return $categoria;
    }
    //Update categoria con patch

    /**
     * @Rest\Patch(path="/{id}")
     * @Rest\View (serializerGroups={"update_categoria"}, serializerEnableMaxDepthChecks=true)
     */

    public function updateCategoria(Request $request)
        //me guardo el id de la categoria
    {
        $categoriaId = $request->get('id');
        //ojo--> comprobar que existe la categoria

        $categoria = $this->categoriaRepository->find($categoriaId);

        if (!$categoria) {
            return new JsonResponse("No se ha encontrado", Response::HTTP_NOT_FOUND);
        }
        //aqui si lo encontramos el id lo que hacemos es modificar el nombre a traves del method
        //con esto nos asegurramos que al crear(modificar la entiddad) cumpla con las caracteristicas que hemos especificado en el type
        $form = $this->createForm(CategoriaType::class, $categoria, ['method' => $request->getMethod()]);
        $form->handleRequest($request);//ahora tenmos que asegurnos que se ha creado y disponible para guardar
        if (!$form->isSubmitted() || !$form->isValid()) {
            return new JsonResponse('bad data', 400);
        }
        $this->categoriaRepository->add($categoria, true);
        return $categoria;
    }

    //Delete--> no ponemos la serializacion pq no queremos devolver nada, solo un mensaje que se ha borrado correctamente
    /**
     * @Rest\Delete (path="/{id}")
     */

    public  function deleteCategoria(Request $request)
    {
        $categoriaId = $request->get('id');
        //ojo--> comprobar que existe la categoria

        $categoria = $this->categoriaRepository->find($categoriaId);

        if (!$categoria) {
            return new JsonResponse("No se ha encontrado", Response::HTTP_NOT_FOUND);
        }
        $this->categoriaRepository->remove($categoria, true);
        return new JsonResponse('Categoria borrada', 200);
    }

}