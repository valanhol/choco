<?php
mb_internal_encoding("UTF-8");

exit('Черновик');


require '../vendor/autoload.php';
use App\Controller\DbController;

// Include functions
require_once ('../functions.php');

// Init configurations
$db_config = include('../config/db_config.php');
$table_name = 'actions';
$table_fields = array('action_id', 'name', 'date_start', 'date_end', 'status');

echo('Инитиализация выгрузки... <br>');

// Init data base
$DbController = new DbController($db_config);

// Exist table
if ($DbController->isTableExists($table_name))
    die('Таблица уже существует');

// Create table
echo('Создание таблицы ' . $table_name . '... <br>');
$create_query = include "files/query_create_table_actions.php";
if ( !$DbController->query($create_query) )
    die("Не удалось создать таблицу: (" . $DbController->errno . ") " . $DbController->error);

// Parse csv
echo('CSV файл парсится... <br>');
$arr_csv = csv_to_array('actions.csv', 1000, ';');

// Save to data base
echo('Экспорт данных в базу... <br>');
if ( isset($arr_csv['actions']) && count($arr_csv['actions']) )
{
    // Convert csv data
    $actions = convert_data($table_fields, $arr_csv['actions']);
    $insert_string = compose_insert_data($table_name, $table_fields, $actions);

    if (!$DbController->multi_query($insert_string))
        echo "Не удалось выполнить множественную вставку: (" . $DbController->errno . ") " . $DbController->error;
} else
    die('Данные не получены');


// Get random row of actions
echo('Получаем случайную запись из базы... <br>');
$random_action = $DbController->getRandomRow($table_name);

// Change status
$action_id = array_shift($random_action);
$random_action = array_combine($table_fields, array_values($random_action));

echo('Смена статуса с ' . $random_action['status']);
$random_action['status'] = ($random_action['status'] == 'On') ? 'Off' : 'On';
echo(' на ' . $random_action['status'] . '...');

// Update status
$DbController->updateStatus($action_id, $random_action['status'], $table_name);

// Change print format
$random_action['date_start'] = date('d-m-Y', $random_action['date_start']);

// Print random action
echo('Выводим акцию:<br>');
print_action($random_action);

echo 'Работа завершена.';


/**
 * @param array $table
 * @param array $fields
 * @param array $data
 * @return string
 */
function compose_insert_data($table, $fields, $data)
{
    $fields_compose = "(`".implode("`,`", $fields)."`)";
    $values_compose = array();

    foreach ($data as $data) {
        $data = array_map("format_str", $data);
        $values_compose[] = "(".implode(",", $data).")";
    }

    return "INSERT INTO ".$table.$fields_compose." VALUES ".implode(",", $values_compose);
}

/** Convert csv file to array
 * @param $file_name
 * @param int $length
 * @param string $delimiter
 * @return array
 */
function csv_to_array($file_name, $length=1000, $delimiter=';')
{
    $header = array();
    $actions = array();

    $row = 1;
    if (($handle = fopen('files/'.$file_name, "r")) !== FALSE)
    {
        while (($data = fgetcsv($handle, $length, $delimiter)) !== FALSE)
        {
            // $num полей в строке $row
            $num = count($data);

            // Init storage type
            if($row == 1)
                $header = $data;
            else {
                if (count($header) <= count($data))
                    $actions[] = $data;
                else {
                    echo "Строка " . $row . ") -> имеет не соответствует формату шапки! <br>";
                    echo '<pre>',print_r($data),'</pre>';
                }
            }

            $row++;
        }
        fclose($handle);
    } else {
        return array();
    }

    return compact('header', 'actions');
}

/**
 * Convert actions data into a necessary and readable view.
 * @param array $table_fields
 * @param $actions_data
 * @return array
 */
function convert_data($table_fields, $actions_data)
{
    $converted = array();
    foreach($actions_data as $index => $action_data)
    {
        $action_data = array_slice($action_data, 0, count($table_fields));

        // Combine keys with data
        $action = array_combine($table_fields, array_values($action_data));

        // Convert data types
        $action['action_id'] = intval($action['action_id']);
        $action['date_start'] = strtotime($action['date_start']); // So standard date format d-m-Y

        $converted[] = $action;
    }

    return $converted;
}

/**
 * Formats a string of extra data
 * @param $str
 * @return string
 */
function format_str($str)
{
    global $DbController;

    $str = strip_tags($str);
    $str = trim($str);
    $str = addslashes($str);
    $str = htmlspecialchars($str);
    $str = html_entity_decode($str);
    //$str = $DbController->real_escape_string($str);
    $str = iconv('utf-8', 'utf-8', $str);
    //$str = preg_replace('/[[:word:]]+/u','',$str);

    return "'".$str."'";
}

function print_action($action)
{
    global $arr_csv;

    echo('<hr>');

    // Print header
    echo implode(";", $arr_csv['header']),'<br>';

    // Print body
    echo implode(";", $action),'<br>';

    echo('<hr>');

    return true;
}


