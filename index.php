<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/07/17
 * Time: 12:19 PM
 */
include_once 'controller/AppController.php';

$controller = new \hugo\AppController();

if (isset($_GET['route'])){

}else{
    echo $controller->index();
}