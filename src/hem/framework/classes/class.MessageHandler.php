<?

/* 
 * Multilanguage Message Handler class
 * from Martin Loitzl
 */


define ('MESSAGE_HANDLER_LOADED', TRUE);

class MessageHandler extends Handler
{

  var $version_ = '1.0.2';
  // V.1.0.1  07.06.04 : New Handler Superclass
  // V.1.0.2  02.10.04 : Changed loadingmechanism of files 

  function MessageHandler($params=null)
  {
    Handler::Handler($params);
    $this->message_file_ = (!empty($params['file'])) ? $params['file'] : null ;

    return $this->loadMessageCode();
  }


  function write($code = null, $flag = null)
  {
    $msg = $this->getMessage($code);
    if(!strlen($msg))
      {
	$msg = $code;
      }
    // TODO: Add some severity Information, e.g. colored style!
    if ($flag == null) 
      {
	echo "$msg<br/>";
      }
  }


  function loadMessageCode()
  {
	/*	if(isset($MESSAGES[$this->language_]) && is_array($MESSAGES[$this->language_]))
	  {
	    while (list($key, $value) = each ($MESSAGES[$this->language_]))
	      {
		$this->messages_[$key] = $value;
	      }
	    return TRUE;
	    }*/

    //    echo $this->message_file_;

    if(!is_null($this->message_file_) && file_exists($this->message_file_))
      {
	require_once $this->message_file_;
	while (list($key, $value) = each($MESSAGES[$this->language_]))
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