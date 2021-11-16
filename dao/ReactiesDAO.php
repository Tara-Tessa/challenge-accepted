<?php

require_once(__DIR__ . '/DAO.php');

class ReactiesDAO extends DAO
{

    function countReactiesByTip($tip_id, $reactie_id)
    {
        $sql = "SELECT COUNT(*) as `amount` FROM `tip_reacties` WHERE `tip_id` = :tip_id AND `reactie_id` = :reactie_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':tip_id', $tip_id);
        $stmt->bindValue(':reactie_id', $reactie_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function sortByPopular()
    {
        $sql = "SELECT `tip_id`, COUNT(`tip_id`) AS `value_occurrence` FROM `tip_reacties` GROUP BY `tip_id` ORDER BY `value_occurrence` DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    function sortByPopularLim3()
    {
        $sql = "SELECT `tip_id`, COUNT(`tip_id`) AS `value_occurrence` FROM `tip_reacties` GROUP BY `tip_id` ORDER BY `value_occurrence` DESC LIMIT 3";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }
}
