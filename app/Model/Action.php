<?php
/**
 * Created by PhpStorm.
 * User: valentinanokhin
 * Date: 17.10.2018
 * Time: 12:43
 */

namespace App\Model;
use App\Api\Base\Model;

class Action extends Model
{
    protected $table_name = 'actions';
    protected $attributes = array('action_id', 'name', 'date_start', 'date_end', 'status');

    public $id;
    public $action_id;
    public $name;
    public $date_start;
    public $date_end;
    public $status;

    /**
     * Рандомная запись
     * @return Model
     */
    public function getRandomItem()
    {
        $query = $this->db->query("SELECT * FROM ".$this->db->escape_string($this->table_name)." ORDER BY rand() LIMIT 1");
        $item = $query->fetch_row();
        $id = array_shift($item);
        $item = $this->combineProperties($item);
        $this->setParams($id, $item);
        return $this;
    }

}