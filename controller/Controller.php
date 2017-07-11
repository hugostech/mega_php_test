<?php
/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/07/17
 * Time: 12:34 PM
 */

namespace hugo;


class Controller
{
    /*
     * inject data into html*/
    public function view($path,$data=array()){
        $path = 'view/'.str_replace('.','/',$path).'.html';
        if (file_exists($path)){
            $html = file_get_contents($path);

            foreach ($data as $key=>$value){

                $html = str_replace('@{{'.$key.'}}',$value,$html);
            }
            return $html;
        }else{
            return 'Error in View Method';
        }
    }
}