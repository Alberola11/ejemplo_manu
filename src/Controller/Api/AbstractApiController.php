<?php

namespace App\Controller\Api;
//Creamos una clase padre para sobreescribir las opciones del formulario y desactivar el Csrf_token--> que sirve para proteger
// de ataques ddos (denegacion de servicio)
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Form\FormInterface;

class AbstractApiController extends AbstractFOSRestController
{

}