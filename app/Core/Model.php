<?php
namespace App\Core;

class Model
{
    protected $db; // mysqli connection

    public function __construct()
    {
        // Use the singleton Database connection
        $this->db = Database::getConnection();
    }
}
