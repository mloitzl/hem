<?php


class Screenshot extends DBObject
{

  /**
   * The name of the table with the image data
   *
   * @access private
   * @var    string
   */
  var $table_ = 'screenshots';

  /**
   * The id of the Image
   *
   * @access private
   * @var    int
   */
  var $id_ = null;

  /**
   * The fields of the image table
   *
   * @access private
   * @var    array
   */
  var $table_fields_ = array(
			     'sId' => 'text',
			     'sFileName' => 'text',
			     'sName' => 'text',
			     'sMimeType' => 'text',
			     'sFileSize' => 'text',
			     'sKind' => 'text',
			     'fId' => 'text',
			     );
  
  /**
   * The Primary Key of the image table
   *
   * @access private
   * @var    string
   */
  var $table_primary_key_ = 'sId';

  /**
   * Flag if Image with id was initialized correctly
   *
   * @access protected
   * @var    string
   */
  var $init_ok_ = FALSE;

  /**
   * Holds an associative array with the image data
   *
   * @access private
   * @var    array
   */
  var $data_array_ = array();
  
  /**
   * Holds the ratio for the thumbnail size
   *
   * @access private
   * @var    float
   */
  var $thumbnail_ratio_ = 0.33;

  /**
   * Holds the maximum height for the thumbnail
   *
   * @access private
   * @var    int
   */
  var $thumbnail_max_height_ = null;

  /**
   * Holds the maximum width for the thumbnail
   *
   * @access private
   * @var    int
   */
  var $thumbnail_max_width_ = null;

  var $thumbnail_prefix_ = null;  

  var $generate_thumbnails_ = FALSE;

  var $thumbnail_ = FALSE;

  var $watermark_ = FALSE;


 /**#@+
  * Properties of the object defined see $category_table_fields_
  *
  * @var string
  */
  var $sId = null;
  var $sFileName = null;
  var $sName = null;
  var $sMimeType = null;
  var $sFileSize = null;
  var $sKind = null;
  var $fId = null;
 /**#@-*/



  function Screenshot($image_id = null, $dbh = null, $util = null, $thumbnail = FALSE, $watermark = FALSE)
  {
    global $IMAGE_DB_DIR, $IMAGE_TABLE;
    global $THUMBNAIL_MAX_HEIGHT, $THUMBNAIL_MAX_WIDTH, $THUMBNAIL_RATIO;
    global $THUMBNAIL_PREFIX, $GENERATE_THUMBNAILS;
    global $WATERMARK_IMAGE;
    global $ANNOTATED_SCREENSHOT_WIDTH;

    DBObject::DBObject($image_id, $dbh);

    if(!is_null($dbh))
      $this->dbh_ = & $dbh;
    if(!is_null($image_id))
      $this->image_id_ = $image_id;
    if(!is_null($util))
      $this->util_ = & $util;

    if(isset($IMAGE_DB_DIR))
      $this->image_db_dir_ = $IMAGE_DB_DIR;
    if(isset($IMAGE_TABLE))
      $this->table_ = $IMAGE_TABLE;

    if(isset($ANNOTATED_SCREENSHOT_WIDTH))
      $this->annotated_screenshot_width_ = $ANNOTATED_SCREENSHOT_WIDTH;
    else
      $this->annotated_screenshot_width_ = '600';      

    if(isset($ANNOTATED_SCREENSHOT_HEIGHT))
      $this->annotated_screenshot_height_ = $ANNOTATED_SCREENSHOT_HEIGHT;
    else
      $this->annotated_screenshot_height_ = '400';      


    if(isset($THUMBNAIL_RATIO))
      $this->thumbnail_ratio_ = $THUMBNAIL_RATIO;
    elseif(isset($THUMBNAIL_MAX_HEIGHT))
      $this->thumbnail_max_height_ = $THUMBNAIL_MAX_HEIGHT;
    elseif(isset($THUMBNAIL_MAX_WIDTH))
      $this->thumbnail_max_width_ = $THUMBNAIL_MAX_WIDTH;

    if(isset($THUMBNAIL_PREFIX))
    	$this->thumbnail_prefix_ = $THUMBNAIL_PREFIX;
    
    if(isset($GENERATE_THUMBNAILS))
      $this->generate_thumbnails_ = $GENERATE_THUMBNAILS;

    if(isset($WATERMARK_IMAGE))
      $this->watermark_image_ = $WATERMARK_IMAGE;
    
    
    if($thumbnail == '1')
      {
	$this->thumbnail_ = TRUE;
	if(isset($THUMBNAIL_RATIO))
	  $this->thumbnail_ratio_ = $THUMBNAIL_RATIO;
	elseif(isset($THUMBNAIL_MAX_HEIGHT))
	  $this->thumbnail_max_height_ = $THUMBNAIL_MAX_HEIGHT;
	elseif(isset($THUMBNAIL_MAX_WIDTH))
	  $this->thumbnail_max_width_ = $THUMBNAIL_MAX_WIDTH;
      }
    // TODO: work out this hack
    elseif($thumbnail == '2')
      {
	$this->thumbnail_ = TRUE;
	//	$this->thumbnail_ratio_
	if(isset($THUMBNAIL_RATIO))
	  $this->thumbnail_ratio_ = $THUMBNAIL_RATIO/2;
	elseif(isset($THUMBNAIL_MAX_HEIGHT))
	  $this->thumbnail_max_height_ = $THUMBNAIL_MAX_HEIGHT/2;
	elseif(isset($THUMBNAIL_MAX_WIDTH))
	  $this->thumbnail_max_width_ = $THUMBNAIL_MAX_WIDTH/2;
      }

    if($watermark)
      {
	$this->watermark_ = TRUE;
      }

    $this->init();
    if(file_exists($this->image_db_dir_ . "/" . $this->thumbnail_prefix_ . $this->sFileName))
      {
	//	echo $this->image_db_dir_ . "/" . $this->thumbnail_prefix_ . $this->binFileName." exists<br/>";
      }
  }

  function addImage($data = null, $file_env = null)
  {

    if(!is_null($data) && !is_null($file_env))
      {
	$error = FALSE;

	$file_type_array = explode('/',  $file_env['type']);
	$file_suffix = $file_type_array[1];

	if(!isset($data['sId']))
	  $file_attributes['sId'] = $this->util_->getUniqueId();
	else
	  $file_attributes['sId'] = $data['sId'];
	
	$file_attributes['sFileName'] = $file_attributes['sId'] . "." . $file_suffix;
	$file_attributes['sName'] = $file_env['name'];
	$file_attributes['sMimeType'] = $file_env['type'];
	$file_attributes['sFileSize'] = $file_env['size'];
	$file_attributes['sKind'] = $data['sKind'];
	$file_attributes['fId'] = $data['fId'];
	
	$tmp_file_name = $file_env['tmp_name'];
	$new_file_uri =  $this->image_db_dir_ . "/" . $file_attributes['sFileName'];
	
	//	$this->util_->dumpArray($file_attributes);
	//	$this->util_->dumpArray($file_env);



	   
	if(isset($file_env['origin']) && $file_env['origin'] == 'ftp')
	  {
	    // copy file normally
	    if(copy($tmp_file_name, $new_file_uri))
	      {
		if(!unlink($tmp_file_name))
		  {// Report the error
		    echo "Could not unlink file: $tmp_file_name <br/>"; 
		  }
		else
		  $error = FALSE;
	      }
	    else
	      {
		echo "Could not copy file: $tmp_file_name to $new_file_uri<br/>"; 
		$error = TRUE;
	      }
	  }
	else
	  {
	    if(!move_uploaded_file($tmp_file_name, $new_file_uri))
	      {
		echo "Could move uploaded file: $tmp_file_name to $new_file_uri<br/>"; 
		$error = TRUE;
	      }
	  }

	if(!$error && $this->generate_thumbnails_)
	  {
	    $thumbnail_uri = $this->image_db_dir_ . "/" . $this->thumbnail_prefix_ . $file_attributes['sId'] . "." . $file_suffix;
	    if($this->writeThumbnail($new_file_uri, $thumbnail_uri, $file_attributes['sMimeType']))
	      {
		echo "Could not write thumbnail: $thumbnail_uri<br/>"; 
		$error = TRUE;
	      }
	  }

	if(!$error)
	  if($this->addData($file_attributes) == TRUE)
	    return TRUE;
	  else
	    {
	      echo $this->getError();
	      return FALSE;
	    }
	else
	  return FALSE;
      }
      else return FALSE;
  }


  function writeReportScreenshot($exported_file_path = null)
  {
    if(!is_null($exported_file_path))
      {
	$im = $this->loadImage();

	$img_width = (int)imageSX($im);
	$img_height = (int)imageSY($im);


	if($img_width < $img_height)
	  {
	    $report_width = $this->annotated_screenshot_height_ / imageSY($im) * imageSX($im);
	    $report_height = $this->annotated_screenshot_height_;
	  }
	else
	  {
	    $report_width = $this->annotated_screenshot_width_;
	    $report_height = $this->annotated_screenshot_width_ / imageSX($im) * imageSY($im);
	  }
	$report_image = ImageCreateTrueColor($report_width,$report_height);
	imagecopyresized($report_image,$im,0,0,0,0,$report_width,$report_height,$img_width,$img_height);

	$this->writeImage($report_image, $exported_file_path, $this->sMimeType);
      }
  }

  function writeOriginalScreenshot($exported_file_path = null)
  {
    if(!is_null($exported_file_path))
      {
	$im = $this->loadImage();
	
	$this->writeImage($im, $exported_file_path, $this->sMimeType);
      }
  }


  function writeThumbnail($original_file = null, $thumbnail_file = null, $mime_type = null)
  {
    if(!is_null($original_file) && !is_null($thumbnail_file) && !is_null($mime_type))
      {
	switch ($mime_type)
	  {
	  case "image/jpeg":
	    $im = $this->util_->loadJPEG($original_file);
	    break;
	  case 'image/png':
	    $im = $this->util_->loadPNG($original_file);
	    break;
	  case 'image/gif':
	    $im = $this->util_->loadGIF($original_file);      
	    break;
	  default:
	    return FALSE;
	    break;
	  }
	
	
	$img_width = imageSX($im);
	$img_height = imageSY($im);
	
	if(!is_null($this->thumbnail_max_width_))
	  {
	    $tn_width = $this->thumbnail_max_width_;
	    $tn_height = (int)($img_height*$this->thumbnail_max_width_/$img_width);
	  }
	else if(!is_null($this->thumbnail_max_height_))
	  {
	    $tn_width = (int)($img_width*$this->thumbnail_max_height_/$img_height);
	    $tn_height = $this->thumbnail_max_height_;
	  }
	else
	  {
	    $tn_width = (int)($img_width * $this->thumbnail_ratio_);
	    $tn_height = (int)($img_height * $this->thumbnail_ratio_);
	  }
	
	$tn = ImageCreateTrueColor($tn_width,$tn_height);
	imagecopyresized($tn,$im,0,0,0,0,$tn_width,$tn_height,$img_width,$img_height);

	switch ($mime_type)
	  {
	  case "image/jpeg":
	    $this->util_->writeJPEG($tn, $thumbnail_file);
	    break;
	  case 'image/png':
	    $this->util_->writePNG($tn, $thumbnail_file);
	    break;
	  case 'image/gif':
	    $this->util_->writeGIF($tn, $thumbnail_file);      
	    break;
	  default:
	    return FALSE;
	    break;
	  }
	
      }
    else
      return FALSE;
  }
  
  function writeImage($image_object, $image_path, $mime_type)
  {
    switch ($mime_type)
      {
      case "image/jpeg":
	$this->util_->writeJPEG($image_object, $image_path);
	break;
      case 'image/png':
	$this->util_->writePNG($image_object, $image_path);
	break;
      case 'image/gif':
	$this->util_->writeGIF($image_object, $image_path);      
	break;
      default:
	return FALSE;
	break;
      }
  }



  function loadImage()
  {
    $thumbnail_exist = 0;
    if( ($this->thumbnail_ == TRUE) && file_exists($this->image_db_dir_ . "/" . $this->thumbnail_prefix_ . $this->sFileName))
      {
	$file_to_load = $this->image_db_dir_ . "/" . $this->thumbnail_prefix_ . $this->sFileName;
	$thumbnail_exist = 1;
      }
    else
      {
	//	$file_to_load = $this->image_db_dir_ . "/" . $this->thumbnail_prefix_ . $this->sFileName;
	$file_to_load = $this->image_db_dir_ . "/" . $this->sFileName;
      }

    switch ($this->sMimeType)
      {
      case "image/jpeg":
	$im = $this->util_->loadJPEG($file_to_load);
	break;
      case 'image/png':
	$im = $this->util_->loadPNG($file_to_load);
	break;
      case 'image/gif':
	$im = $this->util_->loadGIF($file_to_load);
	break;
      default:
	$im = $this->util_->loadPNG();
	break;
      }
    
    if( ($this->thumbnail_ == TRUE) && ($thumbnail_exist == '0') )
      {
	$img_width = imageSX($im);
	$img_height = imageSY($im);
	
	if(!is_null($this->thumbnail_max_width_))
	  {
	    $tn_width = $this->thumbnail_max_width_;
	    $tn_height = (int)($img_height*$this->thumbnail_max_width_/$img_width);
	  }
	else if(!is_null($this->thumbnail_max_height_))
	  {
	    $tn_width = (int)($img_width*$this->thumbnail_max_height_/$img_height);
	    $tn_height = $this->thumbnail_max_height_;
	  }
	else
	  {
	    $tn_width = (int)($img_width * $this->thumbnail_ratio_);
	    $tn_height = (int)($img_height * $this->thumbnail_ratio_);
	  }
	
	$tn = ImageCreateTrueColor($tn_width,$tn_height);
	imagecopyresized($tn,$im,0,0,0,0,$tn_width,$tn_height,$img_width,$img_height);
	
	$im =& $tn;
	//	ImageDestroy($tn);
      }

    if($this->watermark_ == TRUE)
      {
	$logoImage = $this->util_->LoadPNG($this->watermark_image_);
	ImageAlphaBlending($im, true); 
	
	$logoW = ImageSX($logoImage); 
	$logoH = ImageSY($logoImage); 
	
	$photoW = ImageSX($im);
	$photoH = ImageSY($im);
	
	$logo_max = max($logoW, $logoH);
	$photo_max = max($photoW, $photoH);
	
	$wm_ratio = 0.5;
	$watermark_scale = $photo_max/$logo_max * $wm_ratio;
	
	$watermarkW = (int)($logoW * $watermark_scale);
	$watermarkH = (int)($logoH * $watermark_scale);
	
	$watermark = ImageCreateTrueColor($watermarkW,$watermarkH);
	imagecopyresized($watermark,$logoImage,0,0,0,0,$watermarkW,$watermarkH,$logoW,$logoH);
	
	imagecolortransparent($watermark,imagecolorat($watermark,1,1));
	
	ImageCopyMerge($im, $watermark, ($photoW-$watermarkW) / 2,($photoH-$watermarkH) / 2 , 0, 0, $watermarkW, $watermarkH, 20);
      }

    return $im;
  }
  
  
  function displayImage()
  {
    switch ($this->sMimeType)
      {
      case 'image/jpeg':
	header("Content-type: $this->sMimeType");
	header("Content-Disposition: inline; filename=$this->sName");
	imageJPEG($im = $this->loadImage());
	//	$im = $this->loadImage();
	ImageDestroy($im);
	break;
      case 'image/png':
	header("Content-type: $this->sMimeType");
	header("Content-Disposition: inline; filename=$this->sName");
	imagePNG($im = $this->loadImage());
	ImageDestroy($im);
	break;
      case 'image/gif':
	if(function_exists("imagegif"))
	  {
	    header("Content-type: $this->sMimeType");
	    header("Content-Disposition: inline; filename=$this->sName");
	    imageGIF($im = $this->loadImage());
	    ImageDestroy($im);
	  }
	else
	  {
	    header("Content-type: image/png");
	    header("Content-Disposition: inline; filename=error.png");
	    imagePNG($this->loadImage());
	  }
	break;
      default:
	header("Content-type: image/png");
	header("Content-Disposition: inline; filename=error.png");
	imagePNG($this->loadImage());
	break;    
	}
  }



  // TODO: refactor for HEM
  function getAllImageIds($cat_id = null, $event_id = null, $active = null)
  {
    $id_array = array();

    $query = "SELECT $this->table_primary_key_ FROM $this->table_";

    if($active !== 'Y' && $active !== 'N' )
      $active = null;
      
    $where_clause = array();

    if(!is_null($cat_id))
      array_push($where_clause, "sCat = '$cat_id'");
    if(!is_null($event_id))
      array_push($where_clause, "sEvent = '$event_id'");
    if(!is_null($active))
      array_push($where_clause, "sActive = '$active'");
    
    $where_clause = implode( 'AND ', $where_clause);

    if(!is_null($cat_id) || !is_null($event_id) || !is_null($active))
      {
	$query.= " WHERE ";
	$query.= $where_clause;
      }

    $result = $this->dbh_->query($query);
    
    if( ($result != null) && ($result->numRows() > 0) )
      {
	while($row = $result->fetchRow())
	  {
	    array_push($id_array, $row->{$this->table_primary_key_});
	  }
	return $id_array;
      }
  }

  function getScreenshotIds($finding_id)
  {
    $id_array = array();
    
    $query = "SELECT $this->table_primary_key_ FROM $this->table_ WHERE fId = '$finding_id' AND sKind = 'fullsize'";
    
    $result = $this->dbh_->query($query);
    
    if( !$this->dbh_->hasError() && ($result->numRows() > 0) )
      {
	$row = $result->fetchRow();
	$id_array['fullsize'] = $row->{$this->table_primary_key_};
      }
    else
	$id_array['fullsize'] = 0;

    $query = "SELECT $this->table_primary_key_ FROM $this->table_ WHERE fId = '$finding_id' AND sKind = 'annotated'";
    
    $result = $this->dbh_->query($query);
    
    if( !$this->dbh_->hasError() && ($result->numRows() > 0) )
      {
	$row = $result->fetchRow();
	$id_array['annotated'] = $row->{$this->table_primary_key_};
      }
    else
	$id_array['annotated'] = 0;

    return $id_array;
  }
  

  function getAllInactiveImageIds($owner_id = null)
  {
    $id_array = array();

    $query = "SELECT $this->table_primary_key_ FROM $this->table_ WHERE binActive = 'N' ";
    if(!is_null($owner_id))
      {
	$query.= "AND binOwnerId='$owner_id'";
      }

    $result = $this->dbh_->query($query);
    
    if( !$this->dbh_->hasError() && ($result->numRows() > 0) )
      {
	while($row = $result->fetchRow())
	  {
	    array_push($id_array, $row->{$this->table_primary_key_});
	  }
	return $id_array;
      }
    else
      return FALSE;
  }

  function getImageIds($where = null, $value = null, $order_column = null, $order = 'ASC')
  {
    $id_array = array();

    $query = "SELECT $this->table_primary_key_ FROM $this->table_ ";

    if(!is_null($where) && !is_null($value))
      {
	$query.="WHERE $where = '$value' ";
      }

    if(!is_null($order_column) && !is_null($order))
      {
	$query.="ORDER BY $order_column ";
	if($order == 'ASC')
	  $query.= "ASC";
	else
	  $query.= "DESC";
      }

    $result = $this->dbh_->query($query);
    
    if( !$this->dbh_->hasError() && ($result->numRows() > 0) )
      {
	while($row = $result->fetchRow())
	  {
	    array_push($id_array, $row->{$this->table_primary_key_});
	  }
	return $id_array;
      }
    else
      return FALSE;
  }

  function photographerIdUsed($photographer_id)
  {
    $ids = $this->getImageIds('binPhotographerId', $photographer_id);
    if(is_array($ids) && sizeof($ids) > 0)
      return TRUE;
    else
      return FALSE;
  }



  function isOwner($user_id = null)
  {
    if(!is_null($user_id))
      return ($this->binOwnerId == $user_id) ? TRUE : FALSE;
    else
      return FALSE;
  }


  function deleteImage()
  {
    $query = "DELETE FROM $this->table_ WHERE $this->table_primary_key_ = '$this->sId'";

    if(file_exists($this->image_db_dir_ . "/" . $this->sFileName) && !is_dir($this->image_db_dir_ . "/" . $this->sFileName))
      {
	if(!unlink($this->image_db_dir_ . "/" . $this->sFileName))
	  {
	    $this->error_ = "Image File ($this->image_db_dir_/$this->sFileName) was not deleted";
	  }
      }
    if(file_exists($this->image_db_dir_ . "/" . $this->thumbnail_prefix_ . $this->sFileName) && !is_dir($this->image_db_dir_ . "/" . $this->thumbnail_prefix_ . $this->sFileName))
      {
	if(!unlink($this->image_db_dir_ . "/" . $this->thumbnail_prefix_ . $this->sFileName))
	  {
	    $this->error_ = "Image File (".$this->image_db_dir_."/".$this->thumbnail_prefix_ . $this->sFileName.") was not deleted";
	  }      
      }
    $result = $this->dbh_->query($query);
    if($this->dbh_->hasError())
      return FALSE;
    else
      return TRUE; 
  }

  function isActive()
  {
    return (isset($this->binActive) && $this->binActive == 'Y') ? TRUE : FALSE;
  }



  function exportScreenshotData()
  {
    $return_array = Array();

    $return_array[0]['data'] = $this->getData();

    return $return_array;
  }


}
?>