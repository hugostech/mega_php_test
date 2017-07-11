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
include_once 'config/app.php';
include_once 'trait/helper.php';

class AppController extends Controller
{
    use \utility;
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

                            <input type="hidden" name="originalstringid" value=%u>
                            <input type="hidden" name="translated_string_id" value=%u>
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

    public function run($request){
        $originaltext = $request['original_text'];
        $english_string = new English_strings();
        $original_id = $english_string->save_string($originaltext);
        $this->randomTranslate($originaltext,$original_id);
        return $this->index();
    }

    public function update($request){
        $translated_string = new Translated_strings();
        $translated_string->update_translatedtext($request['translatedtext'],$request['translated_string_id']);
        return $this->index();
    }

    public function randomTranslate($string,$original_id){
        $tags = $this->detectTags($string);
        $googleList = $this->getGoogleSupportList();
        shuffle($googleList);
        $translated = new Translated_strings();
        foreach (range(0,4) as $i){
            $translated_string = $this->translate($string,$googleList[$i]);
            if($this->matchTags($translated_string,$tags)){
                $translated->save_translatedtext($translated_string,$original_id,$googleList[$i]);
            }else{

            }

        }
    }

    /*
     * check the tags if being changed*/
    public function matchTags($string,$tags){
        foreach ($tags as $tag) {
            $tem = str_replace($tag, '', $string);
            if ($tem == $string) {
                return false;
            }
            $string = $tem;
        }
        return true;
    }

    /*
     * find the tags in string*/
    public function detectTags($string){

        //ignore %1
        preg_match_all('/%\d*\b/', $string,$matches1);

        //ignore tag []
        preg_match_all('/\[.*?\]/', $string,$matches2);

        $result = array_merge_recursive($matches1[0],$matches2[0]);

        return array_unique($result);
    }

    /*
     * translate string by using google api*/
    public function translate($string,$langcode){


        $params = [
            'source' => 'en',
            'target' => $langcode,
            'q' => $string,
            'format'=>'text'
        ];
        global $config;
        $url = 'https://www.googleapis.com/language/translate/v2?key='.$config['google_api_key'];
        $result = $this->getContentByPostData($params,$url);
        $result = json_decode($result,true);
        $result = html_entity_decode($result['data']['translations'][0]['translatedText']);

        return $result;
    }

    /*
     * grab google translate support language*/
    public function getGoogleSupportList(){
        global $config;
        $url = 'https://translation.googleapis.com/language/translate/v2/languages?key='.$config['google_api_key'];
        $data = json_decode($this->getContent($url),true);
        $data = $data['data']['languages'];
        $list = array();

        foreach ($data as $line){
            $list[] = $line['language'];
        }
        return $list;
    }
}