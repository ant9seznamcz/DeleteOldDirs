<?php

/*
function sm_check_date($file)

$file - a file name with path. For example, "DIR001/DATA.TXT".

If the date string in the file is older than current date,- delete the whole directory (the file exists in) and return false;
Otherwise, nothing to do and return true;
*/
function sm_check_date($file){
	$ret_val = true;

	$content_date = @file_get_contents($file);
	$content_date = trim(remove_utf8_bom($content_date));

	if($content_date && strlen($content_date) > 7){
		setlocale(LC_TIME, 'ru_RU.UTF-8');

		$now = time(); 
		$file_date = strtotime($content_date);
		$datediff = $now - $file_date;

		$days = $datediff / (60 * 60 * 24);
		if($days >= 1){//older than the same day
			$the_dir = dirname($file);
			delDir($the_dir);
			$ret_val = false;
		}
	}else{
//		die('no date');
	}

	return $ret_val;
}

function remove_utf8_bom($text)
{
    $bom = pack('H*','EFBBBF');
    $text = preg_replace("/^$bom/", '', $text);
    return $text;
}

function delDir($path){
        if(is_dir($path) == TRUE){
            $rootFolder = scandir($path);
            if(sizeof($rootFolder) > 2){
                foreach($rootFolder as $folder){
                    if($folder != "." && $folder != ".."){
//Pass the subfolder to function
                        delDir($path."/".$folder);
                    }
                }
//On the end of foreach the directory will be cleaned, and you will can use rmdir, to remove it
                rmdir($path);
            }
        }else{
            if(file_exists($path) == TRUE){
                unlink($path);
            }
        }
}

/////////////////////////////////////

//Sample of usage:

$file = 'DIR001/DATA.TXT';
$res = sm_check_date($file);
die("res=".(int)$res);