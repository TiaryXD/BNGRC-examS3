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

    public function getTypeIdForDon(string $donNom): ?int
    {
        $sql = "
        SELECT type_id
        FROM dons
        WHERE LOWER(TRIM(description)) = LOWER(TRIM(:don))
        ORDER BY id DESC
        LIMIT 1
        ";
        $st = $this->pdo->prepare($sql);
        $st->execute([':don' => $donNom]);
        $row = $st->fetch(\PDO::FETCH_ASSOC);
        return $row ? (int)$row['type_id'] : null;
    }

}
