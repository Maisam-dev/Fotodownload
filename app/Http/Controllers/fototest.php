<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
require_once 'public\classes\fotoc.php';
use fotoC;

class fototest extends Controller
{
    public function fototest( Request $request ,$prm = null)
    {
        $html='';
        $msg='';
        $fotoObj = new fotoC();
        $fotoInfo = array();
        $data = array();
        $aufrufNummer='';
     if($request->isMethod('get')){
             if($prm && strlen($prm)>=22){
                 $aufrufNummer=substr($prm,0,22);
                 goto go;
             }
        }else {//post

         if (isset($_FILES) && !empty($_FILES['Foto']['name'])) {
             $type = $fotoObj->getFotoType($_FILES['Foto']['name']);
             if ($type){





                     $fotoInfo= $fotoObj->getFotoInfo($_FILES['Foto']['tmp_name']);



             }else $msg= "Type Foto ist nicht definiert \n ";

         }//if


         if (isset($_POST['FotoName']) && !empty($_POST['FotoName'])){

             if(strlen($_POST['FotoName']) >= 22){
                 $aufrufNummer= substr(trim($_POST['FotoName']) ,0,22);
              go:   $pfad= $fotoObj->getFotoPfad($aufrufNummer);
                 if ($pfad){
                     $type=$fotoObj->getFotoType($pfad);
                     if (file_exists($pfad)){
                         $foto = $fotoObj->createFoto($type,$pfad);
                         if ($foto){

                             $fotoInfo=$fotoObj->getFotoInfo($pfad);


                         }else $msg= "type ist nicht verfügbar \n";
                     }else $msg="die Datei ist nicht im Server verfügbar \n";
                 }else $msg= "Foto ist nicht in der Datenbank \n ";

             }
         }//
         if(isset($fotoInfo)&& !empty($fotoInfo)){
          $html='
<div width="100%">
    <table width="100%">
        <thead width="100%">
        <tr width="100%">
            <th width="15%">&nbsp;</th>
            <th colspan="2"><p><B>Foto Info</B></p></th>

        </tr>
        <tr></tr>
        <tr>
            <th width="15%"><p><B>Datei Name:</B></p></th>
            <th widg="85%" colspan="3"><p><B>'.$fotoInfo['name'].'</B></p></th>
        </tr>
        <tr></tr>
        </thead>
        <tbody>
        <tr>
            <td  width="15%"><p><B>Farbtiefe:</B></p></td>
            <td  width="35%"><p>'.$fotoInfo['kanal']*$fotoInfo['bits'].' (Bit)</p></td>
            <td  width="15%"><p><B>Foto Type:</B></p></td>
            <td  width="35%"><p>'. $fotoInfo['type'] .'</p></td>
        </tr>
        <tr>
            <td><p><B>Kanal Zahl:</B></p></td>
            <td><p>'.$fotoInfo['kanal'].'</p></td>
            <td><p><B>Bits</B></p></td>
            <td><p>'.$fotoInfo['bits'].' per Kanal</p></td>
        </tr>
        <tr>
            <td><p><B>Bereit</B></p></td>
            <td><p>'. $fotoInfo['width'].' px</p></td>
            <td><p><B>Höhe</B></p></td>
            <td> <p>'.$fotoInfo['height'].' px</p></td>
        </tr>
         <tr>
            <td><p><B>Breite Auflösung </B></p></td>
            <td> <p>'. $fotoInfo['xppi'].' (ppi)</p></td>
            <td><p><B>höhe Auflösung</B></p></td>
            <td><p>'. $fotoInfo['yppi'].' (ppi)</p></td>
        </tr>
         <tr>
            <td><p><B>Datei Size </B></p></td>
            <td> <p>'. $fotoInfo['size'].' (B)</p></td>
            <td><p><B>Auflösung Unit</B></p></td>
            <td><p>'. $fotoInfo['unit'].' </p></td>
        </tr>
        </tbody>
    </table>
</div>
';
   }//if
     }
        $data=['msg'=>$msg,'html'=>$html,'fotoInfo'=>$fotoInfo];
        return view ('fototest',$data);

    }//fototest
}//class
