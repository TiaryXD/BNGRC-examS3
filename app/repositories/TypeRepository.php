<?php

namespace app\repositories;

use PDO;

class TypeRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get_type()
    {
        return $this->pdo
            ->query("SELECT * FROM types ORDER BY nom")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}
