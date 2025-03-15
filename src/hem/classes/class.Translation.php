<?php
  /**
   * Class for managing Translations
   *
   *
   * @author Martin Loitzl, martin@loitzl.com
   *
   */

class Translation extends DBObject
{

  /**
   * The name of the table with the translation data
   *
   * @access private
   * @var    string
   */
  var $table_ = 'translation';

  /**
   * The fields of the project table
   *
   * @access private
   * @var    array
   */
  var $table_fields_ = array(
			     'tId' => 'text',
			     'tLanguage' => 'text',
			     'tString' => 'text',
			     );
  
  /**
   * The Primary Key of the translation table
   *
   * @access private
   * @var    string
   */
  var $table_primary_key_ = 'tId';


  /**
   * The languages that we (have to) know here
   *
   * @access private
   * @var    string
   */
  var $languages_ = null;

  var $query_debug_ = FALSE;
  

 /**#@+
  * Properties of the object defined @see $table_fields_
  *
  * @var string
  */
  var $tId = null;
  var $tLanguage = null;
  var $tString = null;
 /**#@-*/


  /**
   * Constructor
   *
   *
   *
   * @param int Transaltion to instantiate
   * @param object Database handle
   */

  function Translation($trans_id, & $dbh)
  {
    global $TRANSLATION_TABLE, $LANGUAGES;

    $this->dbh_ = $dbh;

    if(isset($TRANSLATION_TABLE)) $this->table_ = $TRANSLATION_TABLE;
    if(isset($LANGUAGES))
      $this->languages_ = $LANGUAGES;

    DBObject::DBObject($trans_id, $dbh);
  }

  function translationExists($trans_id = null, $languagecode = null)
  {
    if(!is_null($trans_id))
      {
	$query = "SELECT tId FROM $this->table_ WHERE tId = '$trans_id' AND tLanguage = '$languagecode'";

	if($this->query_debug_) echo $query."<br />";

	$result = $this->dbh_->query($query);
	if($this->dbh_->hasError())
	  {
	    $this->setError($this->dbh_->getError());
	    $this->dbh_->resetError();
	    return FALSE;
	  }
	elseif($result->numRows() > 0)
	  {
	    $row = $result->fetchRow();
	    return $row->tId;
	  }
	else
	  {
	    return FALSE;
	  }
      }
    else
      {
	return FALSE;
      }

  }

  function getTranslation($trans_id = null, $languagecode)
  {
    $query = "SELECT tString FROM $this->table_ WHERE tId = '$trans_id' AND tLanguage = '$languagecode'";
    
    if($this->query_debug_) echo $query."<br />";

    $result = $this->dbh_->query($query);
    if($this->dbh_->hasError())
      {
	$this->setError($this->dbh_->getError());
	$this->dbh_->resetError();
	return FALSE;
      }
    elseif($result->numRows() > 0)
      {
	$row = $result->fetchRow();
	return htmlspecialchars($row->tString);
      }

    return FALSE;
  }

  function getTranslationArray($trans_id)
  {
    if(!is_null($this->languages_))
      {
	$return_array = array();
		       
	foreach($this->languages_ as $key => $language_code)
	  {
	    $return_array[$language_code] = $this->getTranslation($trans_id, $language_code);
	  }
	$return_array['trans_id'] = $trans_id;

	return $return_array;
      }
    else
      {
	$this->error_ = "getTranslationArray(): I do not know any language (set global \$LANGUAGES)";
	return FALSE;
      }
  }


  function addTranslation($trans_id = null, $languagecode, $text = null)
  {
    // TODO: Check if this enables bugs
    //    if(is_null($trans_id) && $this->init_ok_)
    //      $trans_id = $this->id_;

    if(!is_null($trans_id))
      {
	$data = array(
		      'tId' => $trans_id,
		      'tLanguage' => $languagecode,
		      'tString' => $text,
		      );

	$this->addData($data);
      }
    else
      {
	// $this->setError();
	return FALSE;
      }
  }



  function storeTranslationArray($translation_array)
  {
    if(is_array($translation_array))
      {
	foreach( $this->languages_ as $key => $language_code )
	  {
	    $translation_id = $translation_array['trans_id'];
	      //	    if($this->getTranslation($translation_id, $language_code))
	    if($this->translationExists($translation_id, $language_code))
	      {
		$this->updateTranslation($translation_id, $language_code, $translation_array[$language_code]);
	      }
	    elseif(!is_null($translation_array[$language_code]))
	      {
		$this->addTranslation($translation_id, $language_code, $translation_array[$language_code]);
	      } 
	  }
      }
    else
      {
	$this->error_ = "storeTranslationArray(): translation array is no array, or does not have the correct form";
	return FALSE;
      }
  }


  function updateTranslation($trans_id = null, $languagecode, $text = null)
  {
    if(is_null($trans_id) && $this->init_ok_)
      $trans_id = $this->id_;

    if(!is_null($trans_id))
      {
	$data = array(
		      'tId' => $trans_id,
		      'tLanguage' => $languagecode,
		      'tString' => $text,
		      );

	$set_values = array();
	//	while(list($k, $v) = each($data))
	foreach($data as $k => $v)
	  {
	    $v = $this->dbh_->quote($v);
	    $set_values[] = "$k = $v";
	  }
	
	$query = "UPDATE $this->table_ SET ".implode(", ", $set_values)." WHERE $this->table_primary_key_  = '".$data[$this->table_primary_key_]."' AND tLanguage = '$languagecode'";
	

	if($this->query_debug_) echo $query."<br />";


	$result = $this->dbh_->query($query);
	if($this->dbh_->hasError())
	  {
	    $this->setError($this->dbh_->getError());
	    $this->dbh_->resetError();
	    return FALSE;
	  }
	else
	  return TRUE;
      }
    else
      {
	// $this->setError();
	return FALSE;
      }
  }


  function removeTranslation($tid = null)
  {
    if(!is_null($tid))
      {
	$query = "DELETE FROM $this->table_ WHERE $this->table_primary_key_ = '$tid'";

	if($this->query_debug_) echo $query."<br />";

	$result = $this->dbh_->query($query);

	if($this->dbh_->hasError())
	  {
	    $this->setError($this->dbh_->getError());
	    $this->dbh_->resetError();
	    return FALSE;
	  }
	else
	  return TRUE;
      }
    else
      return FALSE;
  }


  function exportTranslation($tid = null)
  {
    $return_array = Array();

    if(!is_null($tid))
      {
	$query = "SELECT * FROM $this->table_ WHERE $this->table_primary_key_ = '$tid'";
	
	if($this->query_debug_) echo $query."<br />";


	$result = $this->dbh_->query($query);

	if($this->dbh_->hasError())
	  {
	    $this->setError($this->dbh_->getError());
	    $this->dbh_->resetError();
	    return FALSE;
	  }
	else
	  {
	    while($result->fetchInto($row, DB_FETCHMODE_ASSOC))
	      {
		$return_array[] = $row;

	      }
	  }
      }
    
    return $return_array;

  }


}

?>