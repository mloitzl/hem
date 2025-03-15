<?

/**
 * Constant for checking if file is included
 */
define('DBI_LOADED', TRUE);



/**
 * Include the API we're abstracting here
 */

// TODO: check invocation of PEAR::DB.php
require_once 'DB.php';


/**
 * Database abstraction class
 *
 * This is a class for abstracting the API from the package PEAR::DB
 * @author M.J.Kabir
 * @author Martin Loitzl <martin@loitzl.com>
 * @version 1.0.0
 *
 *
 * 2004-06-09: added $this->connected_ = FALSE; in line 86
 */

class DBI 
{
  /**
   * Version number 
   *
   * @access private
   * @var string
   */
  var $version_ = "1.0.0";

  /**
   * Constructor of the class
   *
   * @param string $DB_URL DSN of the Database to connect to
   */

  function DBI($DB_URL) 
  {

    $this->db_url_ = $DB_URL;

    $this->connect();

    if($this->connected_) 
      {
		$this->dbh_->setFetchMode(DB_FETCHMODE_OBJECT);
      }
  }
  

  /*
   * DODO: Doc!!
   *
   */
  function connect() 
  {

    $status = $this->dbh_ = DB::connect($this->db_url_);

    if(PEAR::isError($status))
      {    
		$this->connected_ = FALSE;
		$this->error_ = $status->getMessage().", ".$status->getUserInfo();
      }
    else
      {
		$this->connected_ = TRUE;
      }

    return $this->connected_ ;
  }

  /*
   * DODO: Doc!!
   *
   */
  function isConnected() 
  {
    return $this->connected_;
  }
  
  /*
   * DODO: Doc!!
   *
   */
  function disconnect()
  {
    if (isset($this->dbh_))
      {
	$this->dbh_->disconnect();
	$this->connected_ = FALSE;
	return TRUE;
      }
    else
      {
	return FALSE;
      }
  }

  /*
   * DODO: Doc!!
   *
   */
  function setFetchMode($mode = null)
  {
    if(!is_null($mode)) $this->dbh_->setFetchMode($mode);
  }

  
  /*
   * DODO: Doc!!
   *
   */
  function query($statement)
  {
    $result = $this->dbh_->query($statement);
    
    if(PEAR::isError($result)) 
      {
	$this->setError($result->getMessage().", ".$result->toString());
	return FALSE;
      }
    else 
      {
	return $result;
      }
    
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
  
  
   /*
   * DODO: Doc!!
   *
   */
  function setError($msg = null) 
  {
    global $TABLE_DOES_NOT_EXIST, $TABLE_UNKNOWN_ERROR;
    $this->error_ = $msg;
    
    if(strpos($msg, 'no such table'))
      {
	$this->error_type_ = $TABLE_DOES_NOT_EXIST;
      }
    else 
      {
	$this->error_type_ = $TABLE_UNKNOWN_ERROR;
      }
  }
  

  function hasError()
  {
    return $this->isError();
  }


  /*
   * DODO: Doc!!
   *
   */
  function isError()
  {
    return (!empty($this->error_)) ? TRUE : FALSE ;
  }
  
  /*
   * DODO: Doc!!
   *
   */
  function isErrorType($type = null) 
  {
    return ($this->error_type_ == $type) ? TRUE : FALSE ;
  }
  
  /*
   * DODO: Doc!!
   *
   */
  function getError()
  {
    return $this->error_;
  }
  
  function resetError()
  {
    $this->error_ = null;
    $this->error_type_ = null;
  }

  function getDump($var)
  {
    $dump = "<pre>";
    $dump.= var_export($var);
    $dump.= "</pre>";

    return $dump;
  }

  function dump($var)
  {
    echo $this->getDump($var); 
  }


  /*
   * DODO: Doc!!
   *
   */
  function quote($str)
  {
    return $this->dbh_->quoteSmart($str);
    // Different DB Systems has different quoting styles --> quoteSmart from PEAR::DB fixes that issue
    //    return "'" . $str . "'";
  }
  
  /*
   * DODO: Doc!!
   *
   */
  function apiVersion()
  {
    return $this->version_;
  }
}