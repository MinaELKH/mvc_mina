<?php
namespace App\model;
use App\config\DataBaseManager;

class Admin extends User
{
    private DataBaseManager $dbManager;

    public function __construct(int $id, string $name, string $email, DataBaseManager $dbManager)
    {
        parent::__construct($id, $name, $email);
        $this->dbManager = $dbManager;
    }

}
