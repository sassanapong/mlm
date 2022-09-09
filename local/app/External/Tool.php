<?php
namespace App\External;
use DOMDocument;
use DOMXPath;
use Carbon\Carbon;
use Image;
//namespace App\Http\Traits;
trait Tool
{
    public static function sp_value($id)
    {
        $new_id = explode('/',$id);
        return $new_id ;
    }
    public static function sp_email($id)
    {
        $new_email = explode(',',$id);
        return $new_email ;
    }
    public static function check_empty($id)
    {
        if ($id == null) {
            return 'readonly' ;
        } else {
            return ;
        }
    }
    public static function upload_picture($path,$imgwidth,$filename,$request_file)
    {
        if (!empty($request_file)) {
            $pic_name = ' ';
            $image = $request_file;
            $series = explode('.',$image->getClientOriginalName());
            $len = sizeof($series) -1;
            $pic_name  = date('dmYHis').$filename.'.'.$series[$len];
            list($width, $height) = getimagesize($image->getRealPath());
            $image_resize = Image::make($image->getRealPath());
            if ($width > $imgwidth) {
                $image_resize->resize($imgwidth, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $image_resize->save($path.$pic_name);
            return $pic_name ;
        }
    }
    public static function convert_full_date($strDate){
        $strYear = date("Y",strtotime($strDate))+543;
        $strMonth= date("n",strtotime($strDate));
        $strDay= date("j",strtotime($strDate));
        $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return $strDay.' '.$strMonthThai.' '.$strYear;
      }
    public static function number_show($id)
    {
        $new_id = explode('/',$id);
        if ($new_id[1] == '-') {
            $new_first = explode('.',$new_id[0]);
            if (count($new_first) <= 1) {
                return number_format($new_id[0],0) ;
            }else{
                return number_format($new_id[0],3) ;
            }
        } else {
            $new_first = explode('.',$new_id[0]);
            if (count($new_first) <= 1) {
                $first = number_format($new_id[0],0) ;
            }else{
                $first = number_format($new_id[0],3) ;
            }
            $new_seccon = explode('.',$new_id[1]);
            if (count($new_seccon) <= 1) {
                $seccon = number_format($new_id[1],0) ;
            }else{
                $seccon = number_format($new_id[1],3) ;
            }
            // return $id ;
            return $first.'/'.$seccon ;
        }
    }
    public static function number_show_detail($id)
    {
        $new_id = explode('/',$id);
        if ($new_id[1] == '-') {
            $new_first = explode('.',$new_id[0]);
            if (count($new_first) <= 1) {
                $value = number_format($new_id[0],0).' / -' ;
            }else{
                $value = number_format($new_id[0],2).' / -'  ;
            }
            return $value ;
        } else {
            $new_first = explode('.',$new_id[0]);
            if (count($new_first) <= 1) {
                $first = number_format($new_id[0],0) ;
            }else{
                $first = number_format($new_id[0],2) ;
            }
            $new_seccon = explode('.',$new_id[1]);
            if (count($new_seccon) <= 1) {
                $seccon = number_format($new_id[1],0) ;
            }else{
                $seccon = number_format($new_id[1],2) ;
            }
            return $first.' / '.$seccon ;
        }
    }
    public static function number_show_detail_v2($id)
    {
        $new_id = explode('/',$id);
        if ($new_id[1] == '-') {
            $new_first = explode('.',$new_id[0]);
            if (count($new_first) <= 1) {
                $value = number_format($new_id[0],0).' to -' ;
            }else{
                $value = number_format($new_id[0],2).' to -'  ;
            }
            return $value ;
        } else {
            $new_first = explode('.',$new_id[0]);
            if (count($new_first) <= 1) {
                $first = number_format($new_id[0],0).' °C' ;
            }else{
                $first = number_format($new_id[0],2).' °C' ;
            }
            $new_seccon = explode('.',$new_id[1]);
            if (count($new_seccon) <= 1) {
                $seccon = number_format($new_id[1],0).' °C' ;
            }else{
                $seccon = number_format($new_id[1],2).' °C' ;
            }
            return $first.' to '.$seccon ;
        }
    }
    public static function settrade()
    {
        $cache_time 	= 600; // 10 min
        $cache_file 	= 'settrade.txt';
        $timedif 		= (time() - @filemtime($cache_file));

        if (!file_exists($cache_file) || $timedif > $cache_time) {
            if ($f = @fopen($cache_file, 'w')) {
                $ch = curl_init("https://www.settrade.com/C04_01_stock_quote_p1.jsp?txtSymbol=KKC&ssoPageId=9&selectPage=1");
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $html = curl_exec($ch);
                curl_close($ch);
                //echo $html; exit;
                $html_dom 	= new DOMDocument();
                @$html_dom->loadHTML($html);
                $x_path 	= new DOMXPath($html_dom);
                $dom_path 	= '//*[@id="maincontent"]/div/div[1]/div/div[1]/div[1]/div/div[2]';
                $settrade['setUpdate'] 		= trim($x_path->query($dom_path.'/div[4]/div/div[1]/div[1]/span')->item(0)->nodeValue);
                $settrade['setPrice'] 		= trim($x_path->query($dom_path.'/div[5]/div[1]/div/div[1]/div[3]/h1')->item(0)->nodeValue);
                $settrade['setChange'] 		= trim($x_path->query($dom_path.'/div[5]/div[1]/div/div[2]/div[2]/h1')->item(0)->nodeValue);
                $settrade['setVolume'] 		= trim($x_path->query($dom_path.'/div[5]/div[2]/div/div[2]/table/tr[1]/td[2]')->item(0)->nodeValue);
                $settrade['setPrior'] 		= trim($x_path->query($dom_path.'/div[5]/div[2]/div/div[1]/table/tr[1]/td[2]')->item(0)->nodeValue);
                $settrade['setOpen'] 		= trim($x_path->query($dom_path.'/div[5]/div[2]/div/div[1]/table/tr[2]/td[2]')->item(0)->nodeValue);
                $settrade['setHigh'] 		= trim($x_path->query($dom_path.'/div[5]/div[2]/div/div[1]/table/tr[3]/td[2]')->item(0)->nodeValue);
                $settrade['setLastChange'] 	= trim($x_path->query($dom_path.'/div[5]/div[1]/div/div[2]/div[1]/h1')->item(0)->nodeValue);
                $settrade['setLastPercent'] = trim($x_path->query($dom_path.'/div[5]/div[1]/div/div[2]/div[2]/h1')->item(0)->nodeValue);
                // $settrade['setLow']         = trim($x_path->query($dom_path.'/div[5]/div[2]/div/div[1]/table/tbody/tr[4]/td[2]')->item(0)->nodeValue);
                fwrite ($f, json_encode($settrade));
                fclose($f);
            }else{
                $settrade = array();
            }
        }else{
            if( file_exists($cache_file) ){
                $f = fopen($cache_file,'r');
                $settrade = json_decode(fread($f, filesize($cache_file)),true);
                fclose($f);
            }else{
                $settrade = array();
            }
        }
        return $settrade ;
    }
    public static function convert_date($id)
    {
        $new_id = explode(' ',$id);
        $new_date = explode('/',$new_id[0]);
        $format_date = $new_date[2].'-'.$new_date[1].'-'.$new_date[0];
        return Carbon::parse($format_date)->format('d F Y') ;
    }
}
