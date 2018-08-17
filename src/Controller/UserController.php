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
}
