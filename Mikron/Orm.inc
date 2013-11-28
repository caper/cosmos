<?php
  
class Mikron_Orm {

    public static function updateSchemas() {
        $xmlUrl = M_WWW_ROOT.'/schema/main.xml'; // XML feed file/URL
        $xmlStr = file_get_contents($xmlUrl);
        $xmlObj = simplexml_load_string($xmlStr);
        echo '<pre>';
        echo $xmlObj->getName().'<br />';
        foreach($xmlObj->attributes() as $key => $value) {
            echo "{$key} => {$value}<br />";
        }
        echo '</pre>';
    }

}
