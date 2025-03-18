<?

/* 
 * Multilanguage Error Handler class
 * from M.J.Kabir
 * adapted by Martin Loitzl
 */


define ('ERROR_HANDLER_LOADED', TRUE);

class ErrorHandler extends Handler
{

  var $version_ = '1.0.1';
  // V.1.0.1  07.06.04 : New Handler Superclass

  function ErrorHandler($params=null)
  {
    Handler::Handler($params);

    //    global $DEFAULT_LANGUAGE;

    //    $this->language_ = $DEFAULT_LANGUAGE;

    //$this->caller_class_ = (!empty($params['caller'])) ? $params['caller'] : null ;
    //$this->error_message_ = array();

    $this->error_file_ = (!empty($params['file'])) ? $params['file'] : null ;
    
    return $this->loadErrorCode();
  }


  function alert($code = null, $flag = null)
  {
    $msg = $this->getMessage($code);
    if(!strlen($msg))
      {
	$msg = $code;
      }
    if ($flag == null) 
      {
	echo "<script>alert('$msg');history.go(-1);</script>";
      }
    else if (!strcmp( $flag, 'close' )) 
      {
	echo "<script>alert('$msg');window.close();</script>";
      }
    else 
      {
	echo "<script>alert('$msg');</script>";
      }
  }

  // Now in Superclass!
  /*  function getErrorMessage($code = null)
  {
    if(isset($code))
      {
	// works only for associative Arrays!
	if(is_array($code))
	  {
	    $out = array();
	    foreach ($code as $entry)
	      {
		array_push($out, $this->error_message_[$entry]);
	      }
	    return $out;
	  }
	else 
	  {
	    return (!empty($this->error_message_[$code])) ? $this->error_message_[$code] : null;
	  }
      }
    else 
      {
	return (!empty($this->error_message_['MISSING'])) ? $this->error_message_['MISSING'] : null;
      }
      }*/


  function loadErrorCode()
  {
    if(!is_null($this->error_file_) && is_file($this->error_file_))
      {
	require_once $this->error_file_;
	while (list($key, $value) = each ($ERRORS[$this->language_]))
	  {
	    $this->messages_[$key] = $value;
	  }
	return TRUE;
      }
    else
      return FALSE;
    /*	require_once ERROR_FILE;
     if(isset($ERRORS[$this->language_]) && is_array($ERRORS[$this->language_]))
     {
     while (list($key, $value) = each ($ERRORS[$this->language_]))
     {
     $this->messages_[$key] = $value;
     }
     return TRUE;
     }
     else
     return FALSE;*/
  }


  function apiVersion()
  {
    return $this->version_;
  }

}



?>