<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/07/17
 * Time: 2:22 PM
 */

namespace hugo;
include_once 'model/Model.php';

class Broken_strings extends Model
{
    protected $db_table = 'broken_strings';

    protected $schema = <<<SCHEMA
        CREATE TABLE IF NOT EXISTS `broken_strings` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `timestamp` int(10) unsigned NOT NULL,
        `originalstringid` int(10) unsigned NOT NULL,
        `brokentext` text NOT NULL,
        `tags` text,
        `status` int(2),
        `langcode` char(2) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `originalstringid` (`originalstringid`,`langcode`)
        ) ENGINE=InnoDB

SCHEMA;

    public function save_brokentranslatedtext($brokentext,$originalstringid,$langcode,$tags){
        $timestamp = time();
        $status = 1;
        $tags = htmlspecialchars($tags);
        $this->insert(compact('brokentext','originalstringid','langcode','timestamp','status','tags'));
    }

    public function update_text($brokentext,$id){
        $timestamp = time();

        $this->update(compact('brokentext','timestamp'),$id);
    }

    public function delete($id){
        $timestamp = time();
        $status = 0;
        $this->update(compact('status','timestamp'),$id);
    }

}