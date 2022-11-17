<?php
class fotoC
{
   /*` function _construct (){

    }
*/
    /**
     * @param $img
     * @return string
     */
    function FotoResize($img){

        if(imagesx($img)< 1136 or imagesy ($img) < 640 ){

            return 'Das Bild ist klein. ';// return imagescale($img,1136,640);

        }else if (imagesx($img)> 2880 or imagesy ($img) > 1800 ){

            return 'Das Bild ist groß ';// return imagescale($img,2880,1800);

        }else {

            return 'Das Bild ist passt ';

        }
    }//FotoResize


    /**
     * @param $fotoCode
     * @return bool|string
     */
    function getFotoPfad($Foto_ID)
    {
        $ret=null;
        if (!empty($Foto_ID)) {
            try {
                $row = \App\Models\fotodateienmediaserver::where('aufruf',$Foto_ID)->select('Aufruf','DateiPfad','DateinameNeu')->get();
                if ($row){
                    if (count($row) > 1) die ('Bitte informieren Sie die EDV Abteilung'); // für sicherheit wird mehere Datensatzt nicht erlaubt
                    if (strcasecmp($row[0]['Aufruf'], $Foto_ID) === 0) {// für sicherheit wird noch ein mal prüfen

                        $ret = $row[0]['DateiPfad'] . '\\' . $row[0]['DateinameNeu'];
                        $tempARR = explode('\\', $ret);
                        $tempARR[0] = 'D:\GS1 Dokumente';
                        $ret = implode('\\', $tempARR);
                        $ret= utf8_encode($ret);
                    }
                }
                else {
                    return  $ret;
                }
            } catch (Exception $e) {
                return false; // für sicherheit wird kein Exception geworfen
            }
        }// if
        return $ret;
    }//getpfad

    /**
     * @param $pfad
     * @return mixed
     */
    function getFotoType($pfad){
        $typArr = explode('.',$pfad);
        return $typArr[count($typArr)-1];
    }

    /**
     * @param $typ
     * @param $pfad
     * @return mixed
     */

    function createFoto($typ,$pfad){

        switch ($typ){
            case 'png':
                return imagecreatefrompng($pfad);

            case 'jpg' :
            case 'jfif' :
            return imagecreatefromjpeg($pfad);

            case 'gif':
                return imagecreatefromgif($pfad);

            case 'bmp':
                return imagecreatefrombmp($pfad);
            case 'tif':
                return exif_read_data($pfad);
            default:
                return false;
        }
    }//createFoto

    /**
     * @param $pfad
     * @return array|false
     */
    function getFotoInfo($pfad){
        $fotoInfo= array();

        if ( !file_exists($pfad)) return false;
        try {

            $header=exif_read_data($pfad);
        }catch (Exception $e){
            return false;
        }
        $FotoSizeArr =  getimagesize($pfad);


        $fotoInfo['width']= $FotoSizeArr[0];
        $fotoInfo['height']= $FotoSizeArr[1];

        $fotoInfo['bits']=  (isset($FotoSizeArr['bits'] )&& !empty($FotoSizeArr['bits']))?$FotoSizeArr['bits']:( ( isset( $header['BitsPerSample'] )&& !empty($header['BitsPerSample'][0])  )?$header['BitsPerSample'][0]:0 );
        $fotoInfo['kanal']= (isset($FotoSizeArr['channels'] )&& !empty($FotoSizeArr['channels']))?$FotoSizeArr['channels']:(( isset( $header['SamplesPerPixel'] )&& !empty($header['SamplesPerPixel'])  )?$header['SamplesPerPixel']:0 );
        $fotoInfo['type']= (isset($FotoSizeArr['mime'])&& !empty($FotoSizeArr['mime']))?$FotoSizeArr['mime']:"";
        $fotoInfo['name']=$header['FileName'];
        $fotoInfo['size']= (isset($header['FileSize'])&& !empty($header['FileSize']))?$header['FileSize']:$FotoSizeArr[3];
        $fotoInfo['unit'] = (isset($header['ResolutionUnit'])&& !empty($header['ResolutionUnit']))?$header['ResolutionUnit']:(( isset( $FotoSizeArr[2]  )&& !empty( $FotoSizeArr[2]  )  )?$FotoSizeArr[2] :"") ;
        /////begin read ppi


        //width Resolution
        if (isset ($header['XResolution']) && !empty( $header['XResolution']  )   ){

            $tmp=explode("/",$header['XResolution']);
            if($tmp[1]!="") {
                $fotoInfo['xppi']=($tmp[0]/$tmp[1]);
            }
            else if($tmp[0]!="") {
                $fotoInfo['xppi']= $tmp[0];
            }
            else {
                $fotoInfo['xppi']="keine Angaben zu";
            }
        }else
            $fotoInfo['xppi']="keine Angaben zu";

        //height Resolution
        if(isset ($header['YResolution']) && !empty( $header['YResolution']  )){

            $tmp=explode("/",$header['YResolution']);
            if($tmp[1]!="") {
                $fotoInfo['yppi']=($tmp[0]/$tmp[1]);
            }
            else if($tmp[0]!="") {
                $fotoInfo['yppi']= $tmp[0];
            }
            else {
                $fotoInfo['yppi']="keine Angaben zu";
            }
        }
        else
            $fotoInfo['yppi']="keine Angaben zu";
        return $fotoInfo;
    }// getFotoInfo

    /**
     * @param $type
     * @return false|string
     */
    function getcontentType($type){

        switch ($type){
            case 'png':
                return 'image/png';
            case 'jpg' :
            case 'jfif' :
                return 'image/jpeg';
            case 'gif':
                return 'image/gif';
            case 'bmp':
                return 'image/bmp';
            case 'tiff':
            case 'tif':
                return 'image/tiff';
            case 'pdf':
                return 'application/pdf';
            default:
                return false;
        }
    }


    /**
     * @param $typ
     * @param $pfad
     */
    function readFoto($type,$pfad){

        switch ($type){
            case 'png':
                readfile($pfad);
                //$img = imagecreatefrompng($pfad);
                //imagepng($img);
                //$img =FotoResize($img); // richtige Bildgrösse einstellen
                //imagedestroy($img);
                break;
            case 'jpg' :
            case 'jfif' :
                readfile($pfad);
                // $img = imagecreatefromjpeg($pfad);
                //$img =FotoResize($img);
                //imagejpeg($img);
                //imagedestroy($img);
                break;
            case 'gif':
                readfile($pfad);
                //$img = imagecreatefromgif($pfad);
                //$img =FotoResize($img);
                //imagegif($img);
                //imagedestroy($img);
                break;
            case 'bmp':
                readfile($pfad);
                //$img = imagecreatefrombmp($pfad);
                //$img =FotoResize($img);
                //imagebmp($img);
                ///imagedestroy($img);
                break;
            case 'pdf':
                readfile($pfad);
                break;
            case 'tif':
                readfile($pfad);
                break;
            default:
                echo 'Bild der Art ist nicht unterstützen';
        }
    }//readfoto


}//class
