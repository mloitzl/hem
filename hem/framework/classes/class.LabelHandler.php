<?

/* 
 * Multilanguage Message Handler class
 * from Martin Loitzl
 */


define ('LABEL_HANDLER_LOADED', TRUE);

class LabelHandler extends Handler
{

  var $version_ = '1.0.2';
  // V.1.0.1  07.06.04 : New Handler Superclass

  function LabelHandler($params=null)
  {
    Handler::Handler($params);
    $this->label_file_ = (!empty($params['file'])) ? $params['file'] : null ;
    return $this->loadLabelCode();
  }


  function write($code = null, $flag = null)
  {
    $msg = $this->getMessage($code);
    if(!strlen($msg))
      {
	$msg = $code;
      }
    if ($flag == null) 
      {
	return "$msg";
      }
  }


  function loadLabelCode()
  {
    //    global $LABELS;

    if(!is_null($this->label_file_))
      {
	require_once $this->label_file_;
	while (list($key, $value) = each ($LABELS[$this->language_]))
	  {
	    $this->messages_[$key] = $value;
	  }
	return TRUE;
      }
    else
      return FALSE;
  }


  function apiVersion()
  {
    return $this->version_;
  }

}



?>