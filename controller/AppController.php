<?php

/**
 * Created by PhpStorm.
 * User: Hugo
 * Date: 11/07/17
 * Time: 12:33 PM
 */

namespace hugo;

include_once 'controller/Controller.php';
include_once 'model/English_strings.php';

class AppController extends Controller
{
    public function index(){
        $data = $this->generateTranslatedData();
        return $this->view('index',compact('data'));
    }

    /*
     * to do: list original text, langcode and translated text table set data*/
    public function generateTranslatedData(){
        $format = <<<TABLE
                <tr>
                    <form method="post" action="?route=update">
                        <td>%s</td>
                        <td>%s</td>
                        <td><input type="text" name="translatedtext" value="%s" class="form-control"></td>
                        <td>

                            <input type="hidden" name="originalstringid" value="%u">
                            <input type="hidden" name="translated_string_id" value=%u">
                            <input type="hidden" name="langcode" value="%s">
                            <input type="submit" value="Edit" class="btn btn-sm btn-primary">
                        </td>
                    </form>

                </tr>
TABLE;

        $data = '';
        $englist_strings = new English_strings();
        $strings = $englist_strings->all();
        foreach ($strings as $string){
            $tanslated_strings = $englist_strings->translated_strings($string['id']);
            foreach ($tanslated_strings as $tanslated_string){
                $data .= sprintf($format,$string['text'],$tanslated_string['langcode'],$tanslated_string['translatedtext'],$string['id'],$tanslated_string['id'],$tanslated_string['langcode']);
            }
        }
        return $data;
    }
}