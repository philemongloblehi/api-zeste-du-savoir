<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

use App\Form\Type\UserType;


use App\Entity\User;

class UserController extends FOSRestController
{
    /**
     * @Rest\Get(
     *      path = "/users",
     *      name = "user_list"
     * )
     * 
     * @Rest\View()
     */
    public function getUsersAction(Request $request)
    {
        $users = $this->getDoctrine()->getManager()
                      ->getRepository('App:User')
                      ->findAll();

        return $users;
    }

    /**
     * @Rest\Get(
     *      path = "/users/{id}",
     *      name = "user_show",
     *      requirements = {"id" = "\d+"}
     * )
     * 
     * @Rest\View()
     */
    public function getUserAction(Request $request, $id) {
        $user = $this->getDoctrine()->getManager()
                     ->getRepository('App:User')
                     ->find($id);

        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $user;
    }

    /**
     * @Rest\Post(
     *      path = "/users",
     *      name = "user_create"
     * )
     * 
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     */
    public function postUsersAction(Request $request) {
        
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
        
    }

    /**
     * @Rest\Delete(
     *      path = "/users/{id}",
     *      name = "users_remove",
     *      requirements = {"id" = "\d+"}
     * )
     * 
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     */
    public function removeUserAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('App:User')
                   ->find($id);
        
        if ($user) {
            $em->remove($user);
            $em->flush();
        }
    }
}

