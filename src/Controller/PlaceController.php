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
use App\Form\Type\PlaceType;

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
            return \FOS\RestBundle\View\View::create(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
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
        $form = $this->createForm(PlaceType::class, $place);

        $form->submit($request->request->all()); // Validation des données
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($place);
            $em->flush();    
            return $place;
    
        } else {
            return $form;
        }

    }

    /**
     * @Rest\Delete(
     *      path = "/places/{id}",
     *      name = "place_remove",
     *      requirements = {"id" = "\d+"}
     * )
     * 
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     */
    public function removePlaceAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $place = $em->getRepository('App:Place')
                    ->find($id);

        if ($place) {
            $em->remove($place);
            $em->flush();    
        }
    }

    /**
     * @Rest\Put(
     *      path = "/places/{id}",
     *      name = "place_update",
     *      requirements = {"id" = "\d+"}
     * )
     * 
     * @Rest\View()
     */
    public function updatePlaceAction(Request $request, $id) {
        $place = $this->getDoctrine()->getManager()
                      ->getRepository('App:Place')
                      ->find($id);
        if (empty($place)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(PlaceType::class, $place);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // L'entité vient de la base, donc le merge n'est pas nécessaire
            // Il est utilisé juste par soucis de clarté
            $em->merge($place);
            $em->flush();
            return $place;
        } else {
            return $form;
        }
        
    }

}
