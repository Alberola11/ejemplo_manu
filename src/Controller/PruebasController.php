<?php

namespace App\Controller; //Muy importante si no se hace solo

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PruebasController extends AbstractController
{

    //Cuando vatamos a utilizar un metodo en todas las dependencias imporrtante crearlo en el constructor
    //Por este motivo hemos quitado del get esta funcion y lo ponemos con el $this
    private $logger;
    public  function __construct(LoggerInterface $logger){
        $this->logger=$logger;
    }

    //tenemos que definir como es el endpoint para poder hacer la peticion dese el cliente
    //ENDPOINT--> Un punto final o Endopint es un dispositivo informático remoto que se comunica a través de una red a
    // la que está conectado. Normalmente se refiere a los dispositivos que utilizamos a diario como ordenadores de
    // escritorio, portátiles, teléfonos inteligentes, tablets o dispositivos de Internet de las cosas
    /**
     * @Route ("/get/usuarios", name="get_users")
     */
    //el request para capturar las peticiones
    //El logger para meter mensjaes personalizados tanto aqui como en el fichero. devlog esta dentro de dev
    public function getAllUser(Request $request){
        //Llamará a base de datos y trae toda la lista de Users
        //devolver una respuesta en formato Json
        //Request-->Es la peticion que se hace. Es decir si introduzco un id que me muestre el que estoy pidiendo
        //Response-->Hay que devolver una respuesta

       // $response=new Response();
        //$response ->setContent('<div>Hola Mundo</div>');//sino ponemos nada el response siempre sera un 200

        //capturamos los datos que vienen en el Request
        $id= $request->get('id');
       $this-> logger->alert('Mensajito');
        //No lo estamos haciendo pero tendriamos que usar una query de la base de datos
        $response= new JsonResponse();
        $response->setData([
            'succes'=>true,
            'data'=>[[
                'id'=>$id,
                'nombre'=>'Pepe',
                'email'=>'pepe@email.com'
            ]

        ]]);

        return $response;
    }
}