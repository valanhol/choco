<?php
/**
 * Created by PhpStorm.
 * User: valentinanokhin
 * Date: 17.10.2018
 * Time: 12:48
 */
namespace App\Api\Interfaces\Models;

interface Model
{
    /**
     * Установка атрибутов модели
     * @param $attr
     * @return boolean
     */
    public function setAttributes($attr);

    /**
     * Получить атрибуты модели
     * @return array
     */
    public function getAttributes();

    /**
     * Все записи
     */
    public function getAll();

    /**
     * Записи в виде индексированного массива
     */
    public function toArray();

    /**
     * Записи в виде ассоциативного массива
     */
    public function toAssoc();

    /**
     * Устанавливает параметры модели
     * @param $id
     * @param array $params
     * @return boolean
     */
    public function setParams($id, $params);

    /**
     * Сохраняет модель в базу
     * @return boolean
     */
    public function save();

    /**
     * Создает модель в базе
     * @return boolean
     */
    public function insert();

    /**
     * @param array $item
     */
    public function combineProperties($item);
}