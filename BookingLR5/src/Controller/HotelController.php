<?php

namespace App\Controller;

use App\Entity\Hotel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HotelController extends AbstractController
{
    public function getHotels () {
        $hotels = $this->getDoctrine()
            ->getRepository(Hotel::class)
            ->findAll();
        if (!$hotels){
            return new Response('Hotels not found');
        }
        $hotelsArray = array();

        foreach($hotels as $hotel) {
            $hotelsArray[] = array(
                'id' => $hotel->getId(),
                'name' => $hotel->getName(),
                'description' => $hotel->getDescription(),
                'starNumber' => $hotel->getStarNumber()
            );
        }

        return new JsonResponse($hotelsArray);
    }

    public function getHotel ($id) {
        $hotel = $this->getDoctrine()
            ->getRepository(Hotel::class)
            ->find($id);
        if (!$hotel){
            return new Response('Hotel not found');
        }
        $hotelArray = [
            'id' => $hotel->getId(),
            'name' => $hotel->getName(),
            'description' => $hotel->getDescription(),
            'starNumber' => $hotel->getStarNumber()
        ];
        return new JsonResponse($hotelArray);
    }

    public function createHotel (Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $hotel = new Hotel();
        $hotel->setName($request->request->get('name'));
        $hotel->setDescription($request->request->get('description'));
        $hotel->setStarNumber($request->request->get('starNumber'));
        $entityManager->persist($hotel);
        $entityManager->flush();
        return new Response('Hotel has been created id: '.$hotel->getId());
    }

    public function patchHotel ($id, Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $hotel = $this->getDoctrine()
            ->getRepository(Hotel::class)
            ->find($id);
        if (!$hotel) {
            return new Response('Hotel not found');
        } else {
            $hotel->setName($request->request->get('name'));
            $hotel->setDescription($request->request->get('description'));
            $hotel->setStarNumber($request->request->get('starNumber'));
            $entityManager->flush();
            return new Response('Hotel has been updated id: ' . $hotel->getId());
        }
    }

    public function deleteHotel ($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $hotel = $entityManager->getRepository(Hotel::class)->find($id);
        if (!$hotel) return new Response('Hotel not found');
        $entityManager->remove($hotel);
        $entityManager->flush();
        return new Response('Hotel with id '.$id.' has been deleted');
    }
}
