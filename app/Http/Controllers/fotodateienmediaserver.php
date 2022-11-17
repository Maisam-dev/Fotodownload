<?php

namespace App\Http\Controllers;
require_once 'public\classes\fotoc.php';
use fotoC;
use Illuminate\Http\Request;

class fotodateienmediaserver extends Controller
{

    public function getfoto($art ,$prm){

        $length=1;


        if (substr($prm,14,1)== 'A'){
            $length = 22;

        }else{
            $length = 27;

        }

        $fotoObj = new fotoC();
        $Foto_ID=null;
        $ret=null;
        if ( strlen($prm) >=  $length){
            $Foto_ID = substr($prm,0,$length);
        }else die ('falches Linke ');

        // Expression Patterns
        // && preg_match("/[0-9]{1}[0-9|A-Z|a-z]{21}/", $Foto_ID)===1
        if (isset($Foto_ID) &&!empty ($Foto_ID) && preg_match_all("/[0-9|a-z_|A-Z_]/",$Foto_ID) === strlen($Foto_ID)) { // für sicherheit wird die eingabe vom user gepruft
            $pfad = $fotoObj->getFotoPfad($Foto_ID);
            if ($pfad) {
                $DateiName = explode('\\',$pfad);
                $type = $fotoObj->getFotoType( $pfad);
                $contentType = $fotoObj->getcontentType($type);
                if($contentType){
                    if (file_exists($pfad)){
                        if($art==='download'){
                            header("Content-Type:  $contentType");
                            header("Content-Disposition: attachment; filename={$DateiName[count($DateiName)-1]}");
                        }elseif($art==='preview'){
                            header("Content-Type:  $contentType");
                        }
                        $fotoObj->readFoto($type,$pfad);
                    }else{
                        echo 'File is not exists';
                    }
                }else{
                    echo 'Art von der Datei ist nicht unterstützt';
                }
            }else{
                echo "das Bild ist nicht in der Datenbank definiert";
            }
        }//if Expression Patterns
else {
    echo "illega ID";
}
    }//getfoto

}//class
