<?php

class PartidaModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function getRandomQuestionByCategory($categoryId)
    {
        $categoryId = intval($categoryId);
        $sql = "SELECT * FROM question WHERE category_id = $categoryId ORDER BY RAND() LIMIT 1";
        return $this->conexion->query($sql);
    }
}
