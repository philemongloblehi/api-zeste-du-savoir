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

use App\Entity\Place;

class PlaceController extends FOSRestController
{
    /**
     * @Rest\Get(
     *      path = "/places",
     *      name = "places_list"
     * )
     * 
     * @Rest\View()
     */
    public function getPlacesAction(Request $request)
    {
        $places = $this->getDoctrine()->getManager()
                        ->getRepository('App:Place')
                        ->findAll();
        return $places;


    }

    /**
     * @Rest\Get(
     *      path = "/places/{id}",
     *      name = "place_show",
     *      requirements = {"id" = "\d+"}
     * )
     * 
     * @Rest\View()
     */
    public function getPlaceAction(Request $request, $id) {
        $place = $this->getDoctrine()->getManager()
                      ->getRepository('App:Place')
                      ->find($id);

        if (empty($place)) {
            return new JsonResponse(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
        }
        
        return $place;
    }

    /**
     * @Rest\Post(
     *      path = "/places"
     * )
     * 
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     */
    public function postPlacesAction(Request $request)
    {
        $place = new Place();
        $place->setName($request->get('name'))
              ->setAddress($request->get('address'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($place);
        $em->flush();

        return $place;
    }
}
