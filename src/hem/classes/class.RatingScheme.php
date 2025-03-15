<?php
// This class aggreagates a Translator
require_once 'class.Translation.php';

  /**
   * Class for managing Rating Schemes
   *
   *
   * @author Martin Loitzl, martin@loitzl.com
   *
   */

class RatingScheme extends DBObject
{

  /**
   * The name of the table with the Rating Scheme data
   *
   * @access private
   * @var    string
   */
  var $table_ = 'ratingscheme';



  /**
   * The name of the table wich links Rating Scheme data with Scales
   *
   * @access private
   * @var    string
   */
  var $scheme_scale_table_ = 'ratingscheme_scale';

  /**
   * The fields of the RatingScheme table
   *
   * @access private
   * @var    array
   */
  var $table_fields_ = array(
			     'schemeId' => 'text',
			     'schemeTitleId' => 'text',
			     'schemeResultOperation' => 'text',
			     );

  /**
   * The Primary Key of the RatingScheme table
   *
   * @access private
   * @var    string
   */
  var $table_primary_key_ = 'schemeId';

  
 /**#@+
  * Properties of the object defined @see $table_fields_
  *
  * @var string
  */
  var $schemeId = null;
  var $schemeTitleId = null;
  var $schemeResultOperation = null;
 /**#@-*/


  /**
   * Constructor
   *
   *
   *
   * @param int RatingScheme to instantiate
   * @param object Database handle
   */

  function RatingScheme($scheme_id, & $dbh)
  {
    global $RATINGSCHEME_TABLE, $RATINGSCHEME_SCALE_TABLE, $LANGUAGES;

    if(isset($LANGUAGES) && is_array($LANGUAGES))
      {
	$this->languages_ = $LANGUAGES;
	$this->translator_ = & new Translation(0, $dbh);
      }
    else
      {
	$this->error_ = 'RatingScale(): No languages defined, or not in correct format';
      }

    if(isset($RATINGSCHEME_TABLE)) $this->table_ = $RATINGSCHEME_TABLE;
    if(isset($RATINGSCHEME_SCALE_TABLE)) $this->scheme_scale_table_ = $RATINGSCHEME_SCALE_TABLE;

    DBObject::DBObject($scheme_id, $dbh);
  }


  function getRatingScheme()
  {
    if(!$this->init_ok_)
      $this->init();

    $return_array = array(
			  'schemeId' => $this->id_,
			  'title_translation' => array(
						       'trans_id' => $this->schemeTitleId,
						       ),
			  'schemeResultOperation' => $this->schemeResultOperation,
			  );

    // Add Translations to return Array
    if(!$return_array['title_translation'] = $this->translator_->getTranslationArray($this->schemeTitleId))
      $this->error_ = $this->translator_->getError();
    
    return $return_array;
  }


  /**
   * Returns all Available RatingScheme Ids
   *
   * Returns all RatingScheme Ids, that match the filter
   * TODO: Implement the filter!
   *
   * @param array filter
   * @return array RatingScheme ids
   */
  function getAllRatingSchemeIds($filter = null)
  {
    if(!is_null($filter))
      {
	// Filter stuff goes here!!
      }

    $query = "SELECT $this->table_primary_key_ FROM $this->table_ ";
    
    $result = $this->dbh_->query($query);
    if($this->dbh_->hasError())
      {
	$this->setError($this->dbh_->getError());
	$this->dbh_->resetError();
	return FALSE;
      }
    else
      {
	if($result->numRows() > 0)
	  {
	    $return_array = array();
	    while($row = $result->fetchRow())
	      {
		array_push($return_array, $row->{$this->table_primary_key_});
	      }
	    return $return_array;
	  }
	else
	  {
	    $this->setError('No schemes found');
	    return FALSE;
	  }
      }
  }



  function getAssociatedScaleIds($scheme_id = null)
  {
    if(is_null($scheme_id))
      $scheme_id = $this->id_;

    $query = "SELECT scaleId FROM $this->scheme_scale_table_ WHERE $this->table_primary_key_ = '$scheme_id'";

    $result = $this->dbh_->query($query);
    if($this->dbh_->hasError())
      {
	$this->setError($this->dbh_->getError());
	$this->dbh_->resetError();
	return FALSE;
      }
    else
      {
	if($result->numRows() > 0)
	  {
	    $return_array = array();
	    while($row = $result->fetchRow())
	      {
		array_push($return_array, $row->scaleId);
	      }
	    return $return_array;
	  }
	else
	  {
	    $this->setError('No scales for this scheme found');
	    return FALSE;
	  }
      }
  }
  

  function deleteAssociatedScaleIds($scheme_id)
  {

    $query = "DELETE FROM $this->scheme_scale_table_ WHERE $this->table_primary_key_ = '$scheme_id'";

    $result = $this->dbh_->query($query);
    if($this->dbh_->hasError())
      {
	$this->setError($this->dbh_->getError());
	$this->dbh_->resetError();
	return FALSE;
      }
    else
      {
	return TRUE;
      }
  }


  function setAssociatedScaleIds($scheme_id, $scale_ids)
  {

    if(is_array($scale_ids))
      {
	while($current_scale_id = array_pop($scale_ids))
	  {
	    $query = "INSERT INTO $this->scheme_scale_table_ (schemeId, scaleId) VALUES ('$scheme_id', '$current_scale_id')";

	    $result = $this->dbh_->query($query);
	    if($this->dbh_->hasError())
	      {
		$this->setError($this->dbh_->getError());
		$this->dbh_->resetError();
	      }
	  }
	if($this->hasError())
	  return FALSE;
	else
	  return TRUE;
      }
    else
      return FALSE;

  }


  function exportRatingSchemeData()
  {
    $return_array = Array();

    if($this->init_ok_)
      {
	$scheme_data = $this->getData();

	$return_array[0]['data'] = $scheme_data;
	$return_array[0]['translations']['schemeTitleId'] = $this->translator_->exportTranslation($scheme_data['schemeTitleId']);
      }

    return $return_array;
  }


  function exportAssociatedScaleData()
  {
    $return_array = Array();
    if($this->init_ok_)
      {
	$scale_ids = $this->getAssociatedScaleIds();
	$i = 0;
	foreach($scale_ids as $current_scale_id)
	  {
	    $return_array[$i]['data']['scaleId'] = $current_scale_id;
	    $return_array[$i]['data']['schemeId'] = $this->schemeId;
	    $i++;
	  }
      }

    return $return_array;
  }

}
?>