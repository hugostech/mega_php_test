<?php

/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/07/17
 * Time: 12:33 PM
 */

namespace hugo;

include_once 'controller/Controller.php';

class AppController extends Controller
{
    public function index(){
        $data = $this->generateTranslatedData();
        return $this->view('index',compact('data'));
    }

    /*
     * to do: list original text, langcode and translated text table set data*/
    public function generateTranslatedData(){
        return 'Error';
    }
}