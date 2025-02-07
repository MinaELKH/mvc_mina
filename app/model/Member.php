<?php

namespace App\Model;

use App\config\DataBaseManager;
use Exception;

abstract class Member extends User
{
    private ?DataBaseManager $db;
    private  int $archived;
    private  int $suspended;


    public function __construct(
        ?DataBaseManager $db,
        ?int $id_user = null,
        ?string $name_full = null,
        ?string $email = null,
        ?string $role = null,
        ?string $avatar = null,
        int $suspended = 0,
        int $archived = 0,

    ) {
        parent::__construct($id_user, $name_full, $email, $role, $avatar);
        $this->db = $db;
        $this->suspended = $suspended;
        $this->archived = $archived;
    }


    public function __get($att)
    {
        if (property_exists($this, $att)) {
            return $this->$att ?? null;
        }
        throw new Exception("Undefined property: " . $att);
    }

    public function delete(): bool
    {
        $cond = "id_user";
        $param = $this->id_user;
        return $this->db->delete("users", $cond, $param);
    }
    public function archived(): bool
    {
        $data = [
            "archived" => 1
        ];
        $whereColumn = "id_user";
        $whereValue = $this->id_user;

        return $this->db->update("users", $data, $whereColumn, $whereValue);
    }

    public function suspended(): bool
    {
        $data = [
            "suspended" => 1
        ];
        $whereColumn = "id_user";
        $whereValue = $this->id_user;

        return $this->db->update("users", $data, $whereColumn, $whereValue);
    }

    public function activited(): bool
    {
        $data = [
            "suspended" => 0
        ];
        $whereColumn = "id_user";
        $whereValue = $this->id_user;

        return $this->db->update("users", $data, $whereColumn, $whereValue);
    }

    abstract public function getAll();

}
