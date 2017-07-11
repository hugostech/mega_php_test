<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/07/17
 * Time: 1:06 PM
 */

namespace hugo;

include_once 'model/Model.php';
include_once 'model/Translated_strings.php';
include_once 'model/Broken_strings.php';

class English_strings extends Model
{
    protected $db_table = 'english_strings';

    protected $schema = <<<SCHEMA
        CREATE TABLE IF NOT EXISTS `english_strings` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT, `timestamp` int(10) unsigned NOT NULL,
        `text` text NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB
SCHEMA;

    /*
     * save string to database*/
    public function save_string($input){
        $data = [
            'text'=>$input,
            'timestamp'=>time()
        ];
        return $this->insert($data);
    }

    /*
     * find string by id and list all translated string for this string*/
    public function translated_strings($id){
        $query = 'select * from translated_strings';
        $query .= ' where originalstringid='.$id;
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQL_ASSOC);
    }

    /*
    * find string by id and list all translated string for this string*/
    public function broken_string($id){
        $query = 'select * from broken_strings';
        $query .= ' where status=1 and originalstringid='.$id;
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQL_ASSOC);
    }
}