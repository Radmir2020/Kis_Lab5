<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\Room;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends AbstractController
{
    public function getRoomsByHotelId($hotelId)
    {
        $hotel = $this->getDoctrine()
            ->getRepository(Hotel::class)
            ->find($hotelId);
        $rooms = $hotel->getRooms();
        foreach ($rooms as $room) {
            $roomArray[] = [
                'id' => $room->getId(),
                'type' => $room->getType(),
                'description' => $room->getDescription(),
                'price' => $room->getPrice(),
                'number' => $room->getNumber(),
                'hotel' => $room->getHotel()->getName(),
            ];
        }
        return new JsonResponse($roomArray);
    }

    public function getRoom ($id) {
        $room = $this->getDoctrine()
            ->getRepository(Room::class)
            ->find($id);
        if (!$room){
            return new Response('Room not found');
        }
        $roomArray = [
            'id' => $room->getId(),
            'type' => $room->getType(),
            'description' => $room->getDescription(),
            'price' => $room->getPrice(),
            'number' => $room->getNumber(),
            'hotel' => $room->getHotel()->getName(),
        ];
        return new JsonResponse($roomArray);
    }

    public function createRoom (Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $room = new Room();
        $room->setType($request->request->get('type'));
        $room->setDescription($request->request->get('description'));
        $room->setPrice($request->request->get('price'));
        $room->setNumber($request->request->get('number'));
        $hotel = $this->getDoctrine()
            ->getRepository(Hotel::class)
            ->find($request->request->get('hotelId'));
        $room->setHotel($hotel);
        $entityManager->persist($room);
        $entityManager->flush();
        return new Response('Room has been created id: '.$room->getId());
    }

    public function patchRoom ($id, Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $room = $this->getDoctrine()
            ->getRepository(Room::class)
            ->find($id);
        if (!$room) {
            return new Response('Room not found');
        } else {
            $room->setType($request->request->get('type'));
            $room->setDescription($request->request->get('description'));
            $room->setPrice($request->request->get('price'));
            $room->setNumber($request->request->get('number'));
            $hotel = $this->getDoctrine()
                ->getRepository(Hotel::class)
                ->find($request->request->get('hotelId'));
            $room->setHotel($hotel);
            $entityManager->flush();
            return new Response('Room has been updated id: ' . $room->getId());
        }
    }

    public function deleteRoom ($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $room = $entityManager->getRepository(Room::class)->find($id);
        if (!$room) return new Response('Room not found');
        $entityManager->remove($room);
        $entityManager->flush();
        return new Response('Room with id '.$id.' has been deleted');
    }
}
