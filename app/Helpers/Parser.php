<?php
/**
 * Created by PhpStorm.
 * User: valentinanokhin
 * Date: 17.10.2018
 * Time: 14:39
 */

namespace App\Helpers;

class Parser
{
    public function csvToArray($file_name, $length=1000, $delimiter=';')
    {
        $header = array();
        $actions = array();

        $row = 1;
        if (($handle = fopen(__DIR__.'/../../scripts/files/'.$file_name, "r")) !== FALSE)
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
}