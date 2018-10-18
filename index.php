<?php
mb_internal_encoding("UTF-8");

require 'vendor/autoload.php';

// Include functions
require_once ('functions.php');

// Init configurations
$db_config = include('config/db_config.php');

$IndexController = new \App\Controller\IndexController();

/**
 * 1. Создать MySQL таблицу под данную структуру файла (Если таблица существует - выдать сообщение);
 * 2. Все данные из CSV-файла экспортировать в созданную таблицу. Данные в поле "Дата начала акции" должны храниться в
 * формате INTEGER;
 */
if ($IndexController->export()) echo 'Данные экспортированны.<br>';

/**
 * 3. После полного экспорта данных в таблицу изменить для одной случайной записи в таблице статус на противоположный и
 * вывести эту запись на экран. При этом данные этой записи должны выводиться в том же виде, в каком они были получены
 * из CSV-файла;
 */
if ($action = $IndexController->random()) {
    $IndexController->single($action);
}

/**
 * 4. После этого вывести на экран список ссылок(из CSV-файла) на все акции построенные по правилам построения URL;
 */
$IndexController->actions();

