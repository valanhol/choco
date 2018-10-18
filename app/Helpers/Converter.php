<?php
/**
 * Created by PhpStorm.
 * User: valentinanokhin
 * Date: 17.10.2018
 * Time: 18:35
 */

namespace App\Helpers;

use App\Api\Base\Collection;
use App\Model\Action;

class Converter
{
    /**
     * @param $arr
     * @return Collection
     */
    public function toActionModels($arr)
    {

        $collection = new Collection();

        foreach($arr as $index => $action_data)
        {
            $action = new Action();

            $action_data = array_slice($action_data, 0, count($action->getAttributes()));

            // Combine keys with data
            $action_data = array_combine($action->getAttributes(), array_values($action_data));

            // Convert data types
            $action_data['action_id'] = intval($action_data['action_id']);
            $action_data['date_start'] = strtotime($action_data['date_start']); // So standard date format d-m-Y

            // Set properties
            $action->setParams(0, $action_data);

            // Push in collection
            $collection->push($action);
        }

        return $collection;
    }

    /**
     * @param Action $action
     * @return Action
     */
    public function reverseFormat($action)
    {
        // Convert data types
        $action->action_id = intval($action->action_id);
        $action->date_start = strtotime($action->date_start); // So standard date format d-m-Y

        return $action;
    }

    /**
     * Преобразует кирилицу в латиницу
     * @param string $title
     * @return string
     */
    public function translit($title)
    {
        $mapping = array(   "а" => "a",  "ый" => "iy", "ые" => "ie",
                            "б" => "b",  "в"  => "v",  "г" => "g",
                            "д" => "d",   "е" => "e",  "ё" => "yo",
                            "ж" => "zh",  "з" => "z",  "и" => "i",
                            "й" => "y",   "к" => "k",  "л" => "l",
                            "м" => "m",   "н" => "n",  "о" => "o",
                            "п" => "p",   "р" => "r",  "с" => "s",
                            "т" => "t",   "у" => "u",  "ф" => "f",
                            "х" => "kh",  "ц" => "ts", "ч" => "ch",
                            "ш" => "sh",  "щ" => "shch", "ь" => "",
                            "ы" => "y",   "ъ" => "",    "э" => "e",
                            "ю" => "yu",  "я" => "ya", "йо" => "yo",
                            "ї" => "yi",  "і" => "i",  "є" => "ye",
                            "ґ" => "g"
        );

        return strtr($title, $mapping);
    }

    /**
     * @param $title
     * @return string
     */
    public function transform($title)
    {
        // [Название акции]
        $title = mb_strtolower($title);

        // все знаки препинания и пробелы заменяются на "-"
        $title = preg_replace('#[[:punct:]\s]#', '-', $title);

        //в конце или в начале строки получается "-", то этот знак (знаки, если их несколько) удаляется
        $title = preg_replace('/^\-{1,}|\-$/','', $title);

        //  2 и больше "-" подряд преобразовываются в один;
        $title = preg_replace('/\-{2,}/', '-', $title);

        return $title;
    }
}