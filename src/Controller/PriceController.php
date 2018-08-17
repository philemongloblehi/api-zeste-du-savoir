<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toute les annotations 
use App\Entity\Price;

class PriceController extends Controller
{
    /**
     * @Rest\Get(
     *      path = "/places/{id}/prices",
     *      name = "prices_places_show",
     *      requirements = {"id" = "\d+"}
     * )
     * 
     * @Rest\View()
     */
    public function getPricesAction(Request $request, $id)
    {
        $place = $this->getDoctrine()->getManager()
                      ->getRepository('App:Place')
                      ->find($id);
        if (empty($place)) {
            return $this->placeNotFound();
        }

        return $place->getPrices();
    }

    /**
     * @Rest\Post(
     *      path = "/places/{id}/prices",
     *      name = "prices_places_create",
     *      requirements = {"id" = "\d+"}
     * )
     * 
     * @Rest\View()
     */
    public function postPricesAction(Request $request, $id)
    {
        $place = $this->getDoctrine()->getManager()
                      ->getRepository('App:Place')
                      ->find($id);
        
        if (empty($place)) {
            return $this->placeNotFound();
        }

        $price = new Price();
        $price->setPlace($place); // Ici, le lieu est associé au prix
        $form = $this->createForm(PriceType::class, $price);

        // Le paramètre false dit  a Symfony de garder les valeurs dans notre
        // entité si l'utilisateur n'en fournit pas une dans sa requete
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($price);
            $em->flush();
            return $price;
        } else {
            return $form;
        }
    }

    private function placeNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Place not found'], Response::HTP_NOT_FOUND);
    }
}
