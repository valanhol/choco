<?php
/**
 * Created by PhpStorm.
 * User: valentinanokhin
 * Date: 17.10.2018
 * Time: 12:53
 */
namespace App\Api\Interfaces\Collections;

interface Collection
{
    /**
     * Проверка на пустоту
     * @return boolean
     */
    public function isEmpty();

    /**
     * Добавляет элемент в коллекцию
     * @param $item array
     * @return Collection
     */
    public function push($item);

    /**
     * Записи в виде индексированного массива
     * @return array
     */
    public function getItems();
}