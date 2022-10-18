<?php
namespace App\EventListener;

use App\Repository\ClienteRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class OnLoginListener
{

    private $repo;

    public  function __construct(ClienteRepository $repository){
        $this->repo= $repository;
    }


    //Metodo que se va a quedar a la escucha de que se dispare el evento de on login succes response
    //traducido--> respuesta del login conseguido

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        //cojo los datos de la respuesta
        $data = $event->getData();
        //Me traigo el user
        $user = $event->getUser();

        //Comprobamos	que	el	usuario	es	una	instancia	de	UserInterface.
        if(!$user instanceof UserInterface)
        {
            return;
        }
        //Creamos	un	array	data	con	la	llave	‘user’	y	guardamos	en	él	el	identificador	del	usuario.
        //Como	el	método	getId()	no	existe	en	UserInterface	se	lo	añadimos.
        $data['userId'] = $user->getId();
        //Traer el cliente
        $clienteId= null;// esto y el if siguiente lo hacemos  para que la primera vez que creemos el cliente
        // y hagamos el login con el token no explote al no haber ningun cliente creado aun
        $cliente= $this->repo->findOneBy(['user'=>$user]);
        if ($cliente){
            $clienteId= $cliente->getId();
        }
        //Añadimos los campos a la respuesta
        $data['idCliente']=$clienteId;
        $event->setData($data);
    }





}