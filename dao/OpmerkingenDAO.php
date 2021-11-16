<?php

require_once(__DIR__ . '/DAO.php');

class OpmerkingenDAO extends DAO
{

    function selectOpmerkingenbyId($id)
    {
        $sql = "SELECT * FROM `opmerkingen` WHERE `tip_id`=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
