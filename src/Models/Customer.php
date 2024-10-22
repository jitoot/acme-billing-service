<?php

declare(strict_types=1);

namespace App\Models;

class Customer
{
    /**
     * Customer ID
     *
     * @var string
     */
    private string $id;

    /**
     * First name
     *
     * @var string
     */
    private string $firstName;

    /**
     * Last name
     *
     * @var string
     */
    private string $lastName;

    /**
     * Email
     *
     * @var string
     */
    private string $email;

    /**
     * Constructor
     *
     * @param string $id
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     */
    public function __construct(string $id, string $firstName, string $lastName, string $email)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    /**
     * Get ID
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get first name
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Get last name
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Get full name
     *
     * @return string
     */
    public function getFullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}