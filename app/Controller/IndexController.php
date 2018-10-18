<?php
/**
 * Created by PhpStorm.
 * User: valentinanokhin
 * Date: 17.10.2018
 * Time: 14:27
 */

namespace App\Controller;

use App\Api\Base\Model;
use App\Helpers\Converter;
use App\Helpers\Parser;
use App\Model\Action;

class IndexController
{
    protected $dbConfig;
    protected $dbController;
    protected $converter;
    protected $parser;
    protected $tableName;
    protected $csvHeader;

    /**
     * IndexController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->dbConfig = include(__DIR__.'/../../config/db_config.php');
        $this->tableName = 'actions';

        // Init data base
        $this->dbController = new DbController($this->dbConfig);

        // Init helpers
        $this->converter = new Converter();
        $this->parser = new Parser();
    }

    /**
     * @return Action
     */
    public function random()
    {
        $action = new Action($this->dbController);
        /** @var Action $action */
        $action = $action->getRandomItem();

        // Change status
        $action->status = ($action->status == 'On') ? 'Off' : 'On';

        // Save model
        $action->save();

        // Reverse view type
        $action = $this->converter->reverseFormat($action);

        return $action;
    }

    /**
     * @return Action
     */
    public function export()
    {
        // Exist table
        if ($this->dbController->isTableExists($this->tableName))
            die('Таблица уже существует');

        // Create table
        $create_query = include(__DIR__."/../../scripts/files/query_create_table_actions.php");
        if ( ! $this->dbController->query($create_query) )
            die("Не удалось создать таблицу: (" . $this->dbController->errno . ") " . $this->dbController->error);

        // Parse csv
        $itemsByCsv = $this->parser->csvToArray('actions.csv', 1000, ';');
        $this->csvHeader = $itemsByCsv['header'];
        if ( isset($itemsByCsv['actions']) && count($itemsByCsv['actions']) ) {
            /** @var \App\Api\Base\Collection $actions */
            $actions = $this->converter->toActionModels($itemsByCsv['actions']);
        } else
            die('Данные не получены');

        // Is empty collection
        if($actions->isEmpty())
            die('Коллекция пуста');

        // Save collection to db
        /** @var Action $action */
        foreach ($actions->getItems() as $action) {
            $action->db = $this->dbController;
            $action->insert();
        }

        return $action;
    }

    /**
     * Выводит акцию
     * @param Model $action
     */
    public function single($action)
    {
        echo('<hr>');

        // Print header
        if ( !empty($this->csvHeader) )
            echo implode(";", $this->csvHeader),'<br>';
        else
            echo implode(";", $action->getAttributes()),'<br>';

        // Print body
        echo implode(";", $action->toAssoc()),'<br>';

        echo('<hr>');
    }

    /**
     * Выводит акции и генерирует url
     */
    public function actions()
    {
        $action = new Action($this->dbController);
        $actions = $action->getAll();

        if ($actions->isEmpty())
            echo 'Акции отсутствуют';

        echo 'Генерация ссылок на акции: <br>';
        echo '<ul>';
        foreach ($actions->getItems() as $action)
        {
            // Transform
            $name = $this->converter->transform($action['name']);
            $name = $this->converter->translit($name);

            $link = $action['action_id'] .'/'. $name;

            echo '<li>',$action['id'],') '.$link.'</li>';
        }
        echo '<ul>';
    }

}