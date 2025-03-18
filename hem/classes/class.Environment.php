<?php
// This class aggregates a number of attributess
require_once 'class.EnvironmentAttribute.php';

// This class aggregates a Translator
require_once 'class.Translation.php';

class Environment extends DBObject
{

  /**
   * The name of the table with the data
   *
   * @access private
   * @var    string
   */
  var $table_ = 'environment';

  /**
   * The fields of the table
   *
   * @access private
   * @var    array
   */
  var $table_fields_ = array(
			     'envId' => 'text',
			     'envTitleId' => 'text',
			     'envDescriptionId' => 'text'
			     );
  
  /**
   * The Primary Key of the project table
   *
   * @access private
   * @var    string
   */
  var $table_primary_key_ = 'envId';

  
 /**#@+
  * Properties of the object defined @see $table_fields_
  *
  * @var string
  */
  var $envId = null;
  var $envTitleId = null;
  var $envDescriptionId = null;
 /**#@-*/


  /**
   * Constructor
   *
   *
   *
   * @param int Project to instantiate
   * @param object Database handle
   */
  function Environment($env_id = null, & $dbh)
  {
    global $ENVIRONMENT_TABLE, $LANGUAGES;

    $this->dbh_ = $dbh;

    if(isset($LANGUAGES) && is_array($LANGUAGES))
      {
	$this->languages_ = $LANGUAGES;
	$this->translator_ = & new Translation(0, $this->dbh_);
      }
    else
      {
	$this->error_ = 'Environment(): No languages defined, or not in correct format';
      }

    $this->table_ = (!empty($ENVIRONMENT_TABLE)) ? $ENVIRONMENT_TABLE : $this->table_ ;

    DBObject::DBObject($env_id, $this->dbh_);
  }


  function getEnvironment()
  {
    if(!$this->init_ok_)
      $this->init();

    $attribute_array = array();

    // Get Attribute ids belonging to this Environment
    $dummy_attribute = & new EnvironmentAttribute(0, $this->dbh_);
    $attribute_ids = $dummy_attribute->getAttributeIds(
						       array(
							     'envId' => $this->id_,
							     ),
						       'envOrder'
						       );
    if(is_array($attribute_ids))
      {
	foreach($attribute_ids as $key => $current_attribute_id)
	  {
	    $current_attribute_object = & new EnvironmentAttribute($current_attribute_id, $this->dbh_);
	    $attribute_array[$current_attribute_id] = $current_attribute_object->getAttribute();
	  }
      }

    // Assemble return Array
    $return_array = array(
			  'envId' => $this->id_,
			  'title_translation' => array(
						       'trans_id' => $this->envTitleId,
						       ),
			  'description_translation' => array(
						       'trans_id' => $this->envDescriptionId,
						       ),
			  );

    // Add Translations to return Array
    if(!$return_array['title_translation'] = $this->translator_->getTranslationArray($this->envTitleId))
      $this->error_ = $this->translator_->getError();
    if(!$return_array['description_translation'] = $this->translator_->getTranslationArray($this->envDescriptionId))
      $this->error_ = $this->translator_->getError();
    
    // Add Attributes to return Array
    $return_array['attributes'] = $attribute_array;

    return $return_array;
  }




  /**
   * Stores an Environment
   * 
   * Removes all EnvironmentAttributes from the Environment
   * and stores and assigns the new Attributes with the 
   * Environment afterwards
   * It also stores Description and Title Translations of the Environment
   *
   * @param array Associative Array with the data
   * @return boolean TRUE on success, FALSE otherwise
   */
  function storeEnvironment($data)
  {
    if(is_array($data))
      {
	// Remove all Attributes from Environment
	$this->removeAttributesFromEnvironment();
	
	// Store new Attributes
	if(isset($data['attributes']))
	  $attributes = $data['attributes'];
	else
	  $attributes = array();

	$dummy_attribute = & new EnvironmentAttribute(0, $this->dbh_);
	foreach ($attributes as $key => $current_attribute)
	  {
	    // Fragile Data, lets be sure they are set correctly
	    // an error here and the Attribute will never be found again
	    $current_attribute['envId'] = $data['envId'];
	    $dummy_attribute->storeAttribute($current_attribute);	    
	  }

	// Store Environment
	$title_translations = $data['title_translation'];
	$description_translations = $data['description_translation'];
	
	$data = array(
		      'envId' => $data['envId'],
		      );
	
	// Store Title Translations
	if(is_array($title_translations))
	  {
	    $this->translator_->storeTranslationArray($title_translations);
	    $data['envTitleId'] = $title_translations['trans_id'];
	  }
	else
	  {
	    $this->error_ = "storeEnvironment(): Title Translations not given, or not in correct format";
	    return FALSE;
	  }

	// Store Description Translations
	if(is_array($description_translations))
	  {
	    $this->translator_->storeTranslationArray($description_translations);
	    $data['envDescriptionId'] = $description_translations['trans_id'];
	  }
	else
	  {
	    $this->error_ = "storeEnvironment(): Description Translations not given, or not in correct format";
	    return FALSE;
	  }
	
	// Store Environment Data
	if($this->init_ok_)
	  $this->updateData($data);
	else
	  $this->addData($data);
	
	// Data of Object is now dirty, needs to be reloaded from Database
	$this->init_ok_ = FALSE;
      }
    else
      {
	$this->error_ = "storeEnvironment(): data is not an array, or malformed";
	return FALSE;
      }
  }


  function removeEnvironment($env_id = null)
  {
    if(is_null($env_id))
      {
	$env_id = $this->id_;
      }
    if(!is_null($env_id))
      {
	//	$this->toggleQueryDebug();
	$this->removeAttributesFromEnvironment();
	$this->translator_->removeTranslation($this->envTitleId);
	$this->translator_->removeTranslation($this->envDescriptionId);
	
	$this->deleteData();
      }
    else
      {
	$this->error_ = "removeEnvironment(): No valid Id given";
	return FALSE;
      }
  }


  function removeAttributesFromEnvironment()
  {
    $env = $this->getEnvironment();
    $old_attribute_ids = array_keys($env['attributes']);
    
    foreach($old_attribute_ids as $key => $current_attribute_id_to_remove)
      {
	$current_attribute_to_remove = & new EnvironmentAttribute($current_attribute_id_to_remove, $this->dbh_);
	$current_attribute_to_remove->removeAttribute();
      }
  }


  /**
   * Returns all Available Environment Ids
   *
   * Returns all Envrionment Ids, that match the filter
   * 2 Usages:
   * 1: $filter = array(
   *          'fieldname' => array('op' => '>', 'value' => 'dummy', 'cond' => ''),
   *          'fieldname' => array('op' => '<', 'value' => 'dummy2', 'cond' => 'OR'),
   *          );
   * 2: $filter = array(
   *          'fieldname' => 'value',
   *          'another_fieldname' => 'another_value',
   *          );
   * Filter implementation taken From LiveUser (AdminAuth::getUsers())   
   *
   * @param array filter
   * @return array Environment ids
   */
  function getEnvironmentIds($filters = array(), $order_column = null, $order_direction = 'ASC')
  {
    $where = '';
    $order = '';

    if (sizeof($filters) > 0) 
      {
	$where = ' WHERE';
	foreach ($filters as $f => $v) 
	  {
	    if (is_array($v)) 
	      {
		$cond = ' ' . $v['cond'];
		$where .= ' ' . $v['name'] . $v['op'] . $this->dbh_->quote($v['value']) . $cond;
	      } 
	    else 
	      {
		$cond = ' AND';
		$where .= " $f=". $this->dbh_->quote($v) . "" . $cond;
	      }
	  }
	$where = substr($where, 0, -(strlen($cond)));
      }

    if(!is_null($order_column))
      {
	$order = " ORDER BY $order_column ";
	if($order_direction !== 'ASC')
	  $order.= 'DESC';
      }

    $query = "SELECT $this->table_primary_key_ FROM $this->table_ ";

    $query.= $where;
    $query.= $order;
    
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
	    $this->setError('No Environments found');
	    return FALSE;
	  }
      }
  }


  function exportEnvironmentData()
  {
    $return_array = Array();
    if($this->init_ok_)
      {
	$env_data = $this->getData();

	$return_array[0]['data'] = $env_data;
	$return_array[0]['translations']['envTitleId'] = $this->translator_->exportTranslation($env_data['envTitleId']);
	$return_array[0]['translations']['envDescriptionId'] = $this->translator_->exportTranslation($env_data['envDescriptionId']);
  

      }

    return $return_array;
  }



  function exportEnvironmentAttributeData()
  {
    $return_array = Array();
    if($this->init_ok_)
      {
	$dummy_attribute = & new EnvironmentAttribute(0, $this->dbh_);
	$attribute_ids = $dummy_attribute->getAttributeIds(
							   array(
								 'envId' => $this->id_,
								 ),
							   'envOrder'
							   );
	if(is_array($attribute_ids))
	  {
	    $i = 0;
	    foreach($attribute_ids as $key => $current_attribute_id)
	      {
		$current_attribute_object = & new EnvironmentAttribute($current_attribute_id, $this->dbh_);
		$current_attribute_data = $current_attribute_object->getData();

		$return_array[$i]['data'] = $current_attribute_data;
		$return_array[$i]['translations']['envAttributeNameId'] = $this->translator_->exportTranslation($current_attribute_data['envAttributeNameId']);
		$i++;
	      }
	  }	
      }

    return $return_array;
  }


}
?>