<?php

namespace App\Controller\Api;

use App\Repository\DireccionRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

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

    }

}