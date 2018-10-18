<?php
/**
 * Created by PhpStorm.
 * User: valentinanokhin
 * Date: 17.10.2018
 * Time: 13:25
 */

namespace App\Api\Base;

use App\Api\Interfaces\Models\Model as ModelInterface;
use App\Controller\DbController;


class Model implements ModelInterface
{

    protected $table_name = '';
    protected $attributes = array();
    protected $id;
    public $db;

    /**
     * Model constructor.
     * @param DbController $db
     * @param array $opt
     */
    public function __construct($db = null)
    {
        if ($db)
            $this->db = $db;
    }

    /**
     * Установка атрибутов модели
     * @param $attr
     * @return boolean
     */
    public function setAttributes($attr)
    {
        $this->attributes = $attr;
    }

    /**
     * Получить атрибуты модели
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Все записи
     * @return Collection
     */
    public function getAll()
    {
        $res = $this->db->query("SELECT * FROM `".$this->table_name."`");
        $items = $res->fetch_all();

        $collection = new Collection();

        foreach ($items as $item) {
            $id = array_shift($item);
            $item = $this->combineProperties($item);
            $item['id'] = $id;
            $collection->push($item);
        }

        return $collection;
    }

    /**
     * Записи в виде индексированного массива
     * @return array
     */
    public function toArray()
    {
        $arr = array();
        foreach ($this->attributes as $attribute) {
            $arr[] = $this->$attribute;
        }
        return $arr;
    }

    /**
     * Записи в виде ассоциативного массива
     * @return array
     */
    public function toAssoc()
    {
        $arr = array();
        foreach ($this->attributes as $attribute) {
            $arr[$attribute] = $this->$attribute;
        }
        return $arr;
    }

    /**
     * Сохраняет модель в базу
     * @return boolean
     */
    public function save()
    {
        $item = $this->toAssoc();
        $id = array_shift($item);

        $sqlSets = array();
        foreach ($item as $key => $value)
            $sqlSets[] = "`" . $key . "` = '" . $value . "'";

        if(!$this->db->query("UPDATE '".$this->table_name."' SET ".implode(",", $sqlSets)."'  WHERE `id`='".$id."'"))
            return true;
        else
            return false;
    }

    /**
     * Создает модель в базе
     * @return boolean
     */
    public function insert()
    {
        $fields_compose = "(`".implode("`,`", $this->attributes)."`)";
        $values_compose = "('".implode("','", $this->toArray())."')";

        return $this->db->query("INSERT INTO `".$this->table_name."` " .$fields_compose." VALUES ".$values_compose);
    }

    /**
     * Устанавливает параметры модели
     * @param integer $id
     * @param array $params
     * @return Model
     */
    public function setParams($id, $params)
    {
        $this->id = intval($id);
        foreach ($params as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }

    /**
     * Устанавливает ключи к значениям
     * @param array $item
     * @return array
     */
    public function combineProperties($item)
    {
        $item = array_combine($this->attributes, $item);
        return $item;
    }
}