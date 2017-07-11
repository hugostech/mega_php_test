<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/07/17
 * Time: 12:49 PM
 */

namespace hugo;

include_once 'model/Model.php';

class Translated_strings extends Model
{
    protected $db_table = 'translated_strings';

    protected $schema = <<<SCHEMA
        CREATE TABLE IF NOT EXISTS `translated_strings` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `timestamp` int(10) unsigned NOT NULL,
        `originalstringid` int(10) unsigned NOT NULL,
        `translatedtext` text NOT NULL,
        `langcode` char(2) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `originalstringid` (`originalstringid`,`langcode`)
        ) ENGINE=InnoDB

SCHEMA;

    public function save_translatedtext($translatedtext,$originalstringid,$langcode){
        $timestamp = time();
        $this->insert(compact('translatedtext','originalstringid','langcode','timestamp'));
    }

    public function update_translatedtext($translatedtext,$id){
        $timestamp = time();

        $this->update(compact('translatedtext','timestamp'),$id);
    }
}