<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Room;
use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends AbstractController
{
    public function getBooksByCustomerId($customerId)
    {
        /** @var Customer $customer */
        $customer = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->find($customerId);
        $books = $customer->getBooks();
        foreach ($books as $book) {
            $bookArray[] = [
                'id' => $book->getId(),
                'bookingDate' => $book->getBookingDate(),
                'customer name' => $book->getCustomer()->getName(),
                'room' => $book->getRoom()->getId(),
            ];
        }
        return new JsonResponse($bookArray);
    }

    public function getBook ($id) {
        $book = $this->getDoctrine()
            ->getRepository(Book::class)
            ->find($id);
        if (!$book){
            return new Response('Book not found');
        }
        $bookArray = [
            'id' => $book->getId(),
            'bookingDate' => $book->getBookingDate(),
            'customer name' => $book->getCustomer()->getName(),
            'room' => $book->getRoom()->getId(),
        ];
        return new JsonResponse($bookArray);
    }

    public function createBook (Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $book = new Book();
        $book->setBookingDate($request->request->get('type'));
        $room = $this->getDoctrine()
            ->getRepository(Room::class)
            ->find($request->request->get('roomId'));
        $book->setRoom($room);
        $customer = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->find($request->request->get('customerId'));
        $book->setCustomer($customer);
        $entityManager->persist($book);
        $entityManager->flush();
        return new Response('Book has been created id: '.$book->getId());
    }

    public function patchBook ($id, Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $book = $this->getDoctrine()
            ->getRepository(Book::class)
            ->find($id);
        if (!$book) {
            return new Response('Book not found');
        } else {
            $book->setBookingDate($request->request->get('type'));
            $room = $this->getDoctrine()
                ->getRepository(Room::class)
                ->find($request->request->get('roomId'));
            $book->setRoom($room);
            $customer = $this->getDoctrine()
                ->getRepository(Customer::class)
                ->find($request->request->get('customerId'));
            $book->setCustomer($customer);
            $entityManager->flush();
            return new Response('Book has been updated id: ' . $book->getId());
        }
    }

    public function deleteBook ($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);
        if (!$book) return new Response('Book not found');
        $entityManager->remove($book);
        $entityManager->flush();
        return new Response('Book with id '.$id.' has been deleted');
    }
}
