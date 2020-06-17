<?php

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends AbstractController
{
    public function getCustomers () {
        $customers = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->findAll();
        if (!$customers){
            return new Response('Customers not found');
        }
        $customersArray = array();

        foreach($customers as $customer) {
            $customersArray[] = array(
                'id' => $customer->getId(),
                'name' => $customer->getName(),
                'phone' => $customer->getPhone(),
                'address' => $customer->getEmail()
            );
        }

        return new JsonResponse($customersArray);
    }

    public function getCustomer ($id) {
        $customer = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->find($id);
        if (!$customer){
            return new Response('Customer not found');
        }
        $customerArray = [
            'id' => $customer->getId(),
            'name' => $customer->getName(),
            'phone' => $customer->getPhone(),
            'email' => $customer->getEmail()
        ];
        return new JsonResponse($customerArray);
    }

    public function createCustomer (Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $customer = new Customer();
        $customer->setName($request->request->get('name'));
        $customer->setPhone($request->request->get('phone'));
        $customer->setEmail($request->request->get('email'));
        $entityManager->persist($customer);
        $entityManager->flush();
        return new Response('Customer has been created id: '.$customer->getId());
    }

    public function patchCustomer ($id, Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $customer = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->find($id);
        if (!$customer) {
            return new Response('Customer not found');
        } else {
            $customer->setName($request->request->get('name'));
            $customer->setPhone($request->request->get('phone'));
            $customer->setEmail($request->request->get('email'));
            $entityManager->flush();
            return new Response('Customer has been updated id: ' . $customer->getId());
        }
    }

    public function deleteCustomer ($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $customer = $entityManager->getRepository(Customer::class)->find($id);
        if (!$customer) return new Response('Customer not found');
        $entityManager->remove($customer);
        $entityManager->flush();
        return new Response('Customer with id '.$id.' has been deleted');
    }
}
