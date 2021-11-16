<?php

require_once(__DIR__ . '/DAO.php');

class TipsDAO extends DAO
{

    function selectTips()
    {
        $sql = 'SELECT * FROM `tips` ORDER BY `id` DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    function selectTipsById($id)
    {
        $sql = "SELECT * FROM `tips` WHERE `id`=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function tipToevoegen($data)
    {
        $errors = $this->validate($data);
        if (empty($errors)) {
            $sql = "INSERT INTO `tips`(titel, tag, tip, auteur, afbeelding) VALUES(:titel, :tag, :tip, :auteur, :afbeelding)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':titel', $data['titel']);
            $stmt->bindValue(':tag', $data['tag']);
            $stmt->bindValue(':tip', $data['tip']);
            $stmt->bindValue(':auteur', $data['auteur']);
            $stmt->bindValue(':afbeelding', $data['afbeelding']);
            if ($stmt->execute()) {
                $lastId = $this->pdo->lastInsertId();
                return $this->selectTipsById($lastId);
            }
        }
    }

    function addFavorite($data)
    {
        $sql = "UPDATE `tips` SET `favorieten`=:favorite WHERE `id`=:id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(":favorite", $data['favorite']);
        $statement->bindValue(":id", $data['id']);
        $statement->execute();
        if ($statement->execute()) {
            return $this->selectTipsbyId($data['id']);
        }
    }

    function insertReaction($data)
    {
        $sql = "INSERT INTO `tip_reacties` (tip_id, reactie_id) VALUES(:tip_id, :reactie_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':tip_id', $data['tip_id']);
        $stmt->bindValue(':reactie_id', $data['reaction_id']);
        if ($stmt->execute()) {
            return $this->countReactionsByType($data['reaction_id'], $data['tip_id']);
        }
    }

    function countReactionsByType($type, $id)
    {
        $sql = "SELECT COUNT(*) as `aantal` FROM `tip_reacties` WHERE `reactie_id`=:reactie_id AND `tip_id`=:tip_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':reactie_id', $type);
        $stmt->bindValue(':tip_id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function insertOpmerking($data)
    {
        $sql = "INSERT INTO `opmerkingen` (tip_id, opmerking) VALUES(:tip_id, :opmerking)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':tip_id', $data['tip_id']);
        $stmt->bindValue(':opmerking', $data['opmerking']);
        if ($stmt->execute()) {
            return $this->selectTipsById($this->pdo->lastInsertId());
        }
    }

    function searchTips($titel)
    {
        $sql = "SELECT * FROM `tips` WHERE `titel` LIKE :titel ORDER BY `id` DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':titel', '%' . $titel . '%');
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }


    function filterOnTag($tag)
    {
        $sql = "SELECT * FROM `tips` WHERE `tag`=:tag";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':tag', $tag);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    function filterOnFavorites()
    {
        $sql = "SELECT * FROM `tips` WHERE `favorieten`= 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    /* function insertIcoon($data)
    {
        $sql = "INSERT INTO `reacties` (naam, icoon) VALUES(:naam, :icoon)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':naam', $data['naam']);
        $stmt->bindValue(':icoon', $data['icoon']);
        if ($stmt->execute()) {
            return $this->selectTipsById($this->pdo->lastInsertId());
        }
    } */

    public function validate($data)
    {
        $errors = [];

        if (empty($data['titel'])) {
            $errors['titel'] = 'Gelieve een titel in te vullen';
        }
        if (empty($data['tag'])) {
            $errors['tag'] = 'Gelieve een tag in te vullen';
        }
        if (empty($data['tip'])) {
            $errors['tip'] = 'Gelieve een tip in te vullen';
        }
        return $errors;
    }
}
