<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Repository\CategoriaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoriaController extends AbstractController
{

    /**
     * @Route ("/categoria", name="create_categoria")
     */

    public function  createCategoriaAction(Request $request, EntityManagerInterface  $em)
    {
        //1. Cogemos la informacion a guardar que nos viene en la peticion (request)
        $nombreCategoria = $request->get('categoria');
        //2. Creamos un nuevo objeto jsonResponse que va a ser la respuesta que enviaremos de vuelta
        $response = new JsonResponse();//simpre de httpFoundation

        //3. Tengo que comprobar que la categoria no venga en null o no venga nada

        if (!$nombreCategoria)  //solo pasa si es null, o no tiene valaor asignado, o si es entero es igual a 0, o es falso
        { //Nos han enviado mal los datos en la peticion (request)
            $response->setData([
                'succes' => false,
                'data' => null,
                'error' => 'categoria controller no puede ser null o vacio'
            ]);
            return $response;

        }

        //4. Me ha venido bien el request, tengo que crear un nuevo objeto y setear sus atributos
        $categoria = new Categoria();
        $categoria->setCategoria($nombreCategoria);
        //Aqui se podria crear un constructor para pasarle todos los atributos que se necesitan en la clase

        //5. Una vez creado el objeto ya podemos guardarlo en base de datos con EntityManegerInterface
        //Estas dos deberian ir en un try y catch para evitar fallos
        $em->persist($categoria);//Doctrine -->prepara la query para guardar el objeto en BD
        $em->flush();//Doctrine--> ejecuta las query que tenga pendientes(hasta que no se haga el flush no se sube a la BD)
        //6. Siempre devolvemos una respuesta
        $response->setData([
            'succes' => true,
            'data' => [
                'id' => $categoria->getId(),
                'categoria' => $categoria->getCategoria()
            ]
        ]);
        return $response;
    }

        /**
         * @Route("/categoria/list", name="categoria_list")
         * @return JsonResponse
         */

        public function getAllCategorias(CategoriaRepository $categoriaRepository)
    {
            //1. LLamar al metodo del repositorio
              $categorias= $categoriaRepository->findAll();
              //2. comprobar que hay algo
       // if (!$categorias){
            //lo mismo que arriba con el fallo no lo ponemos pq realmente no se hace asi }

        //Pero ese array de categoria hay que enviarlo en formato Json
        //symfony no sabe pasar de array de objetos a json
        //hay que coger cada objeto del array y pasarlo a json por separado
        $categoriasArray=[];
        foreach ($categorias as $cat){
            $categoriasArray[]=[
                'id'=>$cat->getId(),
                'categoria'=> $cat->getCategoria()
            ];
            $response= new JsonResponse();
            $response ->setData([
                'succes'=>true,
                'data'=>$categoriasArray
            ]);
             return $response;
        }
    }

}