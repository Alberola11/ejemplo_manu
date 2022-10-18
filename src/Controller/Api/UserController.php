<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Rest\Route ("/user")
 */

class UserController extends AbstractFOSRestController
{
    private $repo;
    private $hasher;
    public function __construct(UserRepository $repo, UserPasswordHasherInterface $hasher){
        $this->repo = $repo;
        $this->hasher = $hasher;
    }
    /**
     * @Rest\Post (path="/")
     * @Rest\View (serializerGroups={"user"},serializerEnableMaxDepthChecks=true)
     */

    public function createUser(Request $request)

    {

        $user= $request->get('user');//esto coge email y pasword, pero no lo guarda en formato array
        $role= $request->get('roles');//el 'roles es lo que le pasamos en el postman escrito en el post, luego nos devolvera el contenido con el nombre real de sus atributos'
        $form= $this->createForm(UserType::class);
        //No podemos enviarlo por handle request porque enviamos el user y el role por separado entonces tenemos que hacerlo
        //de forma plana

        $form->submit($user);//lo guardamos en formato array
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $form;
        }
        //como vemos no lo hemos realizado creando antes el new user, esta es otra forma.
        //Como php no tiene tipado podemos hacerlo con anotaciones diciendole que new user es de tipo objeto
        /**
         * @var  User $newUser
         */
        $newUser= $form->getData();
        //Tenemos que establecer el role--> guardando el role en un array
        $roles[]= $role;
        $newUser->setRoles($roles);//como el set role espera un array le metemos el array por ese motivo lo hemos creado
        //codificamos el pasword--> tambien se podria hacer en el set de la clase

        $hashedPasword= $this->hasher->hashPassword($newUser, $user['password']);//esto espera el usuario y el pasword
                                                                                 //el pasword lo sacmos de user, a traves de un arrau de su atributo pasword

        $newUser->setPassword($hashedPasword);
        //guardamos en base de datos

        $this->repo->add($newUser, true);
        return $newUser;
    }

}