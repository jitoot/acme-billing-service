<?php

declare(strict_types=1);

namespace App\Models;

class Product
{
    /**
     * Product name
     *
     * @var string
     */
    private string $name;

    /**
     * Constructor
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get product name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}