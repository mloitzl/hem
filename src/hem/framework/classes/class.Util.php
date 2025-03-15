<?php



class Util
{


  function getmicrotime()
  {
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
  }
  



  function dumpArray($array)
  {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
  }


  function LoadJPEG ($img_name = null) 
  {
    if (ImageTypes() & IMG_JPEG)
      {
	$im = @ImageCreateFromJPEG ($img_name);
	if ($im) 
	  {
	    return $im;
	  }
	else
	  {
	    $error_string = "Error opening image $img_name";
	  }
      }
    else
      {
	$error_string = "Image Type JPG not supported";
      }

    $im = ImageCreate (150, 30);
    $bgc = ImageColorAllocate ($im, 255, 255, 255);
    $tc = ImageColorAllocate ($im, 0, 0, 0);
    ImageFilledRectangle ($im, 0, 0, 150, 30, $bgc); 
    ImageString($im, 1, 5, 5, $error_string, $tc); 

    return $im;
  }

  function LoadPNG ($img_name = null) 
  {
    if (ImageTypes() & IMG_PNG)
      {
	$im = @ImageCreateFromPNG ($img_name);
	if ($im) 
	  {
	    return $im;
	  }
	else
	  {
	    $error_string = "Error opening image $img_name";
	  }
      }
    else
      {
	$error_string = "Image Type PNG not supported";
      }

    $im = ImageCreate (200, 30);
    $bgc = ImageColorAllocate ($im, 255, 255, 255);
    $tc = ImageColorAllocate ($im, 0, 0, 0);
    ImageFilledRectangle ($im, 0, 0, 150, 30, $bgc); 
    ImageString($im, 3, 5, 5, $error_string, $tc); 

    return $im;
  }


  function LoadGIF ($img_name = null) 
  {
    if (ImageTypes() & IMG_GIF)
      {
	$im = @ImageCreateFromGIF ($img_name);
	if ($im) 
	  {
	    return $im;
	  }
	else
	  {
	    $error_string = "Error opening image $img_name";
	  }
      }
    else
      {
	$error_string = "Image Type GIF not supported";
      }

    $im = ImageCreate (150, 30);
    $bgc = ImageColorAllocate ($im, 255, 255, 255);
    $tc = ImageColorAllocate ($im, 0, 0, 0);
    ImageFilledRectangle ($im, 0, 0, 150, 30, $bgc); 
    ImageString($im, 1, 5, 5, $error_string, $tc); 

    return $im;
  }

  function LoadWBMP ($img_name = null) 
  {
    if (ImageTypes() & IMG_WBMP)
      {
	$im = @ImageCreateFromWBMP ($img_name);
	if ($im) 
	  {
	    return $im;
	  }
	else
	  {
	    $error_string = "Error opening image $img_name";
	  }
      }
    else
      {
	$error_string = "Image Type WBMP not supported";
      }

    $im = ImageCreate (150, 30);
    $bgc = ImageColorAllocate ($im, 255, 255, 255);
    $tc = ImageColorAllocate ($im, 0, 0, 0);
    ImageFilledRectangle ($im, 0, 0, 150, 30, $bgc); 
    ImageString($im, 1, 5, 5, $error_string, $tc); 

    return $im;
  }
  
  
  
  
  /**
   * Returns a unique id of $length, or 32, if no length given
   *
   * taken from http://www.zend.com/codex.php?id=308&single=1
   *
   * @param int lenght lenght of the id
   * @param string pool pool of characters used for creation
   */

  function getUniqueId($length=32, $pool="")
  {
    // set pool of possible char 
    if($pool == ""){
      //      $pool = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $pool = "abcdefghijklmnopqrstuvwxyz"; 
      $pool .= "0123456789";
    }// end if
    mt_srand ((double) microtime() * 1000000);
    $unique_id = "";
    for ($index = 0; $index < $length; $index++) {
      $unique_id .= substr($pool, (mt_rand()%(strlen($pool))), 1);
    }// end for
    return($unique_id);
  }// end get_unique_id
  
}



?>
