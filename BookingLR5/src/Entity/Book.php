<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $bookingDate;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="books")
     */
    private $room;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="books")
     */
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookingDate(): ?string
    {
        return $this->bookingDate;
    }

    public function setBookingDate(?string $bookingDate): self
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
