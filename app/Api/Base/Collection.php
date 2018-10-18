<?php
/**
 * Created by PhpStorm.
 * User: valentinanokhin
 * Date: 17.10.2018
 * Time: 13:37
 */

namespace App\Api\Base;


class Collection implements \App\Api\Interfaces\Collections\Collection
{
    protected $items = array();

    /**
     * Проверка на пустоту
     * @return boolean
     */
    public function isEmpty()
    {
        return ! count($this->items);
    }

    /**
     * Добавляет элемент в коллекцию
     * @param array $item
     * @return Collection
     */
    public function push($item)
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * Записи в виде индексированного массива
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }
}