<?php
require_once 'class.Translation.php';
require_once 'class.Finding.php';
require_once 'class.EnvironmentData.php';
require_once 'class.User.php';
require_once 'class.RatingScheme.php';
require_once 'class.HeuristicSet.php';


  /**
   * Class for managing HEM Projects
   *
   *
   * @author Martin Loitzl, martin@loitzl.com
   *
   */

class Project extends DBObject
{
  var $counter_ = 0;
  /**
   * The name of the table with the project data
   *
   * @access private
   * @var    string
   */
  var $table_ = 'project';

  /**
   * The fields of the project table
   *
   * @access private
   * @var    array
   */
  var $table_fields_ = array(
			     'pId' => 'text',
			     'pNameId' => 'text',
			     'pDescriptionId' => 'text',
			     'pPhase' => 'text',
			     'heurSetId' => 'text',
			     'envId' => 'text',
			     'schemeId' => 'text',			     
			     'pAdded' => 'text',
			     );
  
  /**
   * The Primary Key of the project table
   *
   * @access private
   * @var    string
   */
  var $table_primary_key_ = 'pId';

  
 /**#@+
  * Properties of the object defined @see $table_fields_
  *
  * @var string
  */
  var $pId = null;
  var $pNameId = null;
  var $pDesriptionId = null;
  var $pPhase = null;
  var $heurSetId = null;
  var $envId = null;
  var $schemeId = null;
  var $pAdded = null;
 /**#@-*/


  /**
   * The Table where Users are assigned to Projects
   *
   * @access private
   * @var string
   */
  var $project_user_table_ = null;

  /**
   * The column in Project User Table, where the user id is stored
   *
   * @access private
   * @var string
   */
  var $project_user_table_user_key_ = 'uId';

  /**
   * Constructor
   *
   *
   *
   * @param int Project to instantiate
   * @param object Database handle
   */

  function Project($project_id = null, & $dbh)
  {
    global $PROJECT_TABLE, $PROJECT_USER_TABLE;

    if(isset($PROJECT_TABLE)) $this->table_ = $PROJECT_TABLE;
    if(isset($PROJECT_USER_TABLE)) $this->project_user_table_ = $PROJECT_USER_TABLE;

    $this->translator_ = & new Translation(0, $dbh);

    DBObject::DBObject($project_id, $dbh);
  }


  function storeProject($data)
  {
    if(!is_null($data) && is_array($data))
      {
	$title_translations = $data['title_translation'];
	$description_translations = $data['description_translation'];
	
	$data = array(
		      'pId' => $data['pId'],
		      'heurSetId' => $data['heurSetId'],
		      'envId' => $data['envId'],
		      'schemeId' => $data['schemeId'],
		      'pPhase' => $data['pPhase'],
		      );

	if(is_array($title_translations))
	  {
	    $this->translator_->storeTranslationArray($title_translations);
	    $data['pNameId'] = $title_translations['trans_id'];
	  }
	else
	  {
	    $this->error_ = "storeHeuristic(): Title Translations not given, or not in correct format";
	    return FALSE;
	  }

	if(is_array($description_translations))
	  {
	    $this->translator_->storeTranslationArray($description_translations);
	    $data['pDescriptionId'] = $description_translations['trans_id'];
	  }
	else
	  {
	    $this->error_ = "storeHeuristic(): Description Translations not given, or not in correct format";
	    return FALSE;
	  }

	if($this->init_ok_)
	  $this->updateData($data);
	else
	  {
	    $data['pAdded'] = date('YmdHms',time());
	    $this->addData($data);
	  }
      }
    else
      {
	$this->error_ = "storeProject(): No data given, or not in correct format";
	return FALSE;
      }
  }





  /**
   * Sets the Project Phase
   *
   * 0: Not started
   * 1: Evaluation Pahse
   * 2: Merge Phase
   * 3: Rating Phase
   * 4: Finished
   *
   * @param int phase id
   * @return boolean TRUE on success
   */
  function setProjectPhase($phase_id = null)
  {
    $data = array(
		  'pId' => $this->id_,
		  'pPhase' => $phase_id
		  );
    if(!is_null($phase_id))
      $this->updateData($data);
    else
      {
	$this->setError('No phase given');
	return FALSE;
      }
    if($this->dbh_->hasError())
      {
	$this->setError($this->dbh_->getError());
	$this->dbh_->resetError();
	return FALSE;
      }
    else
      return TRUE;
  }

  /**
   * Gets the Project Phase
   *
   * 0: Not started
   * 1: Evaluation Pahse
   * 2: Merge Phase
   * 3: Rating Phase
   * 4: Finished
   *
   * @return int Project Phase
   */
  function getProjectPhase()
  {
    // TODO: do it more elegantly, introduce a dirty flag, just call init, if DB data has changed
    $this->init();
    return $this->pPhase;
  }


  /**
   * Sets the Heuristic Set to use in Project
   *
   * Use 0, if no heuristics shoul be used in project
   *
   * @param int Id of the Heuristic set
   * @return boolean TRUE on success
   */
  function setHeuristicSet($heur_set_id = null)
  {
    $data = array(
		  'pId' => $this->id_,
		  'heurSetId' => $heur_set_id
		  );
    if(!is_null($heur_set_id))
      $this->updateData($data);
    else
      {
	$this->setError('No Heur Set Id given');
	return FALSE;
      }
    if($this->dbh_->hasError())
      {
	$this->setError($this->dbh_->getError());
	$this->dbh_->resetError();
	return FALSE;
      }
    else
      return TRUE;
  }

  /**
   * Gets the Heuristic Set used in Project
   *
   * 0, if no heuristics is used in project
   *
   * @return int Id of the Heuristic set, 0 if none
   */
  function getHeuristicSet()
  {
    // TODO: do it more elegant, introduce a dirty flag, just call init, if DB data has changed
    $this->init();
    return $this->heurSetId;
  }


  /**
   * Add a user to project
   *
   * Adds the user with the given user id to the project
   *
   * @param int user id to add
   * @return boolean TRUE on success
   */
  function addUserToProject($user_id = null)
  {
    if(!is_null($user_id))
      {
	$query = "INSERT INTO $this->project_user_table_ ($this->table_primary_key_, $this->project_user_table_user_key_) ".
	  "VALUES ('$this->id_', '$user_id')";

	//	echo $query;

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
	$this->setError('No user id given');
	return FALSE;
      }
  }

 /**
   * Remove a user from project
   *
   * Removes the user with the given user id from the project
   *
   * @param int user id to remove
   * @return boolean TRUE on success
   */
  function removeUserFromProject($user_id = null)
  {
    if(!is_null($user_id))
      {
	$query = "DELETE FROM $this->project_user_table_ WHERE $this->table_primary_key_ = '$this->id_' ".
	  "AND $this->project_user_table_user_key_ = '$user_id'";

	//	echo $query;

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
	$this->setError('No user id given');
	return FALSE;
      }
  }

  /**
   * Get all Users assigned to project
   *
   * Returns an Array with all user ids that are assigned to the project
   *
   * @return mixed Array with user ids, or FALSE 
   */
  function getAllUserIdsFromProject()
  {
    if($this->init_ok_)
      {
	$query = "SELECT $this->project_user_table_user_key_ FROM $this->project_user_table_ "
	  ."WHERE $this->table_primary_key_ = '$this->id_'";
	
	//	echo $query;
	
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
		    array_push($return_array, $row->{$this->project_user_table_user_key_});
		  }
		return $return_array;
	      }
	    else
	      {
		$this->setError('No users found for this project');
		return FALSE;
	      }
	  }
      }
    else
      {
	$this->setError('Project class not initialized correctly');
	return FALSE;
      }
  }


  /**
   * Returns TRUE if User is assigned to Project
   *
   *
   * @param string user id
   * @return boolean TRUE if user is assigned to project, false otherwise
   */

  function isUserInProject($user_id = null)
  {
    if(!is_null($user_id))
      {
	$users = $this->getAllUserIdsFromProject();

	if($users)
	  {
	    if(in_array($user_id, $users))
	      return TRUE;
	    else
	      return FALSE;
	  }
	else
	  return FALSE;
      }
    else
      {
	$this->setError('isUserInProject(): no user id given');
	return FALSE;
      }
  }
  
  /**
   * Returns all Available Project Ids
   *
   * Returns all Project Ids, that match the filter
   * TODO: Impplement the filter!
   *
   * @param array filter
   * @return array Project ids
   */
  function getAllProjectIds($filter = null)
  {
    if(!is_null($filter))
      {
	// Filter stuff gies here!!
      }
    else
      {
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
		$this->setError('No projects found');
		return FALSE;
	      }
	  }
      }
  }

  function getProjectIdsForUser($user_id = null)
  {
    $query = "SELECT $this->table_primary_key_ FROM $this->project_user_table_ ";

    if(!is_null($user_id))
      $query.= " WHERE $this->project_user_table_user_key_ = '$user_id'";
    
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
	    $this->setError('No users found for this project');
	    return FALSE;
	  }
      }
  }


  function getProjectData()
  {
    global $LANGUAGES;

    $return_array = Array();

    if($this->init_ok_)
      {

	$return_array['title_translation']['trans_id'] = $this->pNameId;
	foreach($LANGUAGES as $current_language)
	  {
	    $return_array['title_translation'][$current_language] = $this->translator_->getTranslation($this->pNameId, $current_language);
	  }

	$return_array['description_translation']['trans_id'] = $this->pDescriptionId;
	foreach($LANGUAGES as $current_language)
	  {
	    $return_array['description_translation'][$current_language] = $this->translator_->getTranslation($this->pDescriptionId, $current_language);
	  }

	$return_array['pId'] = $this->pId;
	$return_array['pPhase'] = $this->pPhase;
	$return_array['heurSetId'] = $this->heurSetId;
	$return_array['envId'] = $this->envId;
	$return_array['schemeId'] = $this->schemeId;
	$return_array['pAdded'] = $this->pAdded;


	return $return_array;
      }
    else
      return FALSE;
  }



  function getPhaseLabelById($id = null)
  {
    switch ($id)
      {
      case '0':
	return 'LABEL_NOT_STARTED';
	break;
      case '1':
	return 'LABEL_EVALUATE';
	break;
      case '2':
	return 'LABEL_MERGE';
	break;
      case '3':
	return 'LABEL_RATE';
	break;
      case '4':
	return 'LABEL_FINISHED';
	break;
      default:
	return 'LABEL_NOT_STARTED';
	break;
      }

  }



  // Export the DB entries for this project
  function exportProject_old(&$admin_auth_handler)
  {

    $query = '';

    // Project Data
    $project_dump = $this->exportProjectData();
    $query.= $this->buildImportQuery($project_dump, 'project');
    
    $project_user_dump = $this->exportProjectUserMapping();
    $query.= $this->buildImportQuery($project_user_dump, 'project_user');

    // Findings
    $dummy_finding =& new Finding(0, $this->dbh_);
    $finding_dump = $dummy_finding->exportFindingData($this->pId);
    $query.= $this->buildImportQuery($finding_dump, 'finding');

    // Finding Evaluator - Manager Mapping
    $finding_mappings_dump = $dummy_finding->exportFindingMapping($this->pId);
    $query.= $this->buildImportQuery($finding_mappings_dump, 'manager_evaluator_finding');

    // Finding Evaluator Ratings
    $finding_ratings_dump = $dummy_finding->exportFindingRatings($this->pId);
    $query.= $this->buildImportQuery($finding_ratings_dump, 'finding_rate');

    // Environment Data
    $dummy_env_data = & new EnvironmentData(0, $this->dbh_);
    $env_data_dump = $dummy_env_data->exportEnvironmentAttributeData($this->pId);
    $query.= $this->buildImportQuery($env_data_dump, 'environment_data');

    // Heuristic Set
    if(!empty($this->heurSetId))
      {
	$dummy_heuristic_set = & new HeuristicSet($this->heurSetId, $this->dbh_);
	$heur_set_data_dump = $dummy_heuristic_set->exportHeuristicSetData();
	$query.= $this->buildImportQuery($heur_set_data_dump, 'heuristic_set');
	
	$heur_data_dump = $dummy_heuristic_set->exportHeuristicData();
	$query.= $this->buildImportQuery($heur_data_dump, 'heuristic');
      }

    // Rating Scheme
    $rating_scheme = & new RatingScheme($this->schemeId, $this->dbh_);
    $scheme_data_dump = $rating_scheme->exportRatingSchemeData();
    $query.= $this->buildImportQuery($scheme_data_dump, 'ratingscheme');

    $associated_scale_data_dump = $rating_scheme->exportAssociatedScaleData();
    $query.= $this->buildImportQuery($associated_scale_data_dump, 'ratingscheme_scale');

    // Rating Scales
    $associated_scale_ids = $rating_scheme->getAssociatedScaleIds();
    foreach($associated_scale_ids as $current_scale_id)
      {
	$current_scale_object = & new RatingScale($current_scale_id, $this->dbh_);
	$current_scale_data_dump = $current_scale_object->exportScaleData();

	$query.= $this->buildImportQuery($current_scale_data_dump, 'rating_scale');

	// Rating Scale Values
	
	$current_scale_values_dump = $current_scale_object->exportScaleValueData();

	$query.= $this->buildImportQuery($current_scale_values_dump, 'rating_scale_value');
      }

    // Environments
    $environment = & new Environment($this->envId, $this->dbh_);
    $environment_data_dump = $environment->exportEnvironmentData();

    $query.= $this->buildImportQuery($environment_data_dump, 'environment');


    $environment_attribute_data_dump = $environment->exportEnvironmentAttributeData();
    $query.= $this->buildImportQuery($environment_attribute_data_dump, 'environment_attributes');



    // Auth Users
    $user_ids = $this->getAllUserIdsFromProject();

    $user_groups = Array();

    foreach($user_ids as $current_user_id)
      {
	$current_user = & new User($current_user_id, $this->dbh_);
	$user_data_dump = $current_user->exportUserData();
	$query.= $this->buildImportQuery($user_data_dump, 'user_attributes');

	if(!is_null($admin_auth_handler))
	  {
	    $groups = $admin_auth_handler->getGroups($current_user_id);
	    /*	    echo "<pre>";
	    print_r($groups);
	    echo "</pre>";
	    */
	    
	    $user_groups[$current_user_id] = array_pop(array_keys($groups));
	    if($admin_auth_handler->userIdExists($current_user_id))
	      {
		$current_auth_data[0]['data'] = array_pop($admin_auth_handler->getUserData($current_user_id)); 
		$query.= $this->buildImportQuery($current_auth_data, 'liveuser_users');
	      }
	  }

      }
    

    // Screenshots
    $dummy_screenshot = & new Screenshot(0, $this->dbh_);
    $finding_ids = $dummy_finding->getAllFindingIds($this->pId);

    $file_names = Array();
    $i = 0;
    foreach($finding_ids as $current_finding_id)
      {
	$current_screenshot_ids = $dummy_screenshot->getScreenshotIds($current_finding_id);
	
	foreach($current_screenshot_ids as $current_screenshot_id)
	  {
	    $current_screenshot = & new Screenshot($current_screenshot_id, $this->dbh_);
	    $current_screenshot_data = $current_screenshot->exportScreenShotData();
	    

	    if(!empty($current_screenshot->sFileName))
	      {
		$file_names[$i]['name'] = $current_screenshot->sFileName;
		$file_names[$i]['path'] = $current_screenshot->image_db_dir_;
		$file_names[$i]['fid'] = $current_finding_id;
		$i++;

		$thumbnail_file = $current_screenshot->image_db_dir_."/".$current_screenshot->thumbnail_prefix_ . $current_screenshot->sFileName;

		//		echo $thumbnail_file."<br>";

		if(file_exists($thumbnail_file))
		  {
		    $file_names[$i]['name'] = $current_screenshot->thumbnail_prefix_ . $current_screenshot->sFileName;
		    $file_names[$i]['path'] = $current_screenshot->image_db_dir_;
		    $file_names[$i]['fid'] = $current_finding_id;
		    $i++;
		  }
	      }

	    $query.= $this->buildImportQuery($current_screenshot_data, 'screenshot');
	  }
      }

    $return_array = Array();

    $return_array['query'] = $query;
    $return_array['user_groups'] = serialize($user_groups);
    $return_array['screenshot_filenames'] = $file_names;

    /*
    echo "<pre>";
    print_r($file_names);
    echo "</pre>";


     print_r(serialize($user_groups));

    $query = str_replace("{PREFIX}", 'test_', $query);

    $query = nl2br($query);

    $query = explode('<br />', $query);
    */

    return $return_array;
  } // OLD!!!


  // Export the DB entries for this project
  function exportProject(&$admin_auth_handler)
  {

    $query = '';

    $export_data_array = Array();
    // Project Data
    $project_dump = $this->exportProjectData();
    $project_dump['table'] = 'project';
    //    $export_data_array[] = serialize($project_dump);
    $export_data_array[] = $project_dump;
    $query.= $this->buildImportQuery($project_dump, 'project');

    
    $project_user_dump = $this->exportProjectUserMapping();
    $project_user_dump['table'] = 'project_user';
    //    $export_data_array[] = serialize($project_user_dump);
    $export_data_array[] = $project_user_dump;
    $query.= $this->buildImportQuery($project_user_dump, 'project_user');

    // Findings
    $dummy_finding =& new Finding(0, $this->dbh_);
    $finding_dump = $dummy_finding->exportFindingData($this->pId);
    $finding_dump['table'] = 'finding';
    //    $export_data_array[] = serialize($finding_dump);
    $export_data_array[] = $finding_dump;
    $query.= $this->buildImportQuery($finding_dump, 'finding');

    // Finding Evaluator - Manager Mapping
    $finding_mappings_dump = $dummy_finding->exportFindingMapping($this->pId);
    $finding_mappings_dump['table'] = 'manager_evaluator_finding';
    //    $export_data_array[] = serialize($finding_mappings_dump);
    $export_data_array[] = $finding_mappings_dump;
    $query.= $this->buildImportQuery($finding_mappings_dump, 'manager_evaluator_finding');

    // Finding Evaluator Ratings
    $finding_ratings_dump = $dummy_finding->exportFindingRatings($this->pId);
    $finding_ratings_dump['table'] = 'finding_rate';
    //    $export_data_array[] = serialize($finding_ratings_dump);
    $export_data_array[] = $finding_ratings_dump;
    $query.= $this->buildImportQuery($finding_ratings_dump, 'finding_rate');

    // Environment Data
    $dummy_env_data = & new EnvironmentData(0, $this->dbh_);
    $env_data_dump = $dummy_env_data->exportEnvironmentAttributeData($this->pId);
    $env_data_dump['table'] = 'environment_data';
    //    $export_data_array[] = serialize($env_data_dump);
    $export_data_array[] = $env_data_dump;
    $query.= $this->buildImportQuery($env_data_dump, 'environment_data');

    // Heuristic Set
    if(!empty($this->heurSetId))
      {
	$dummy_heuristic_set = & new HeuristicSet($this->heurSetId, $this->dbh_);
	$heur_set_data_dump = $dummy_heuristic_set->exportHeuristicSetData();
	$heur_set_data_dump['table'] = 'heuristic_set';
	//	$export_data_array[] = serialize($heur_set_data_dump);
	$export_data_array[] = $heur_set_data_dump;
	$query.= $this->buildImportQuery($heur_set_data_dump, 'heuristic_set');
	
	$heur_data_dump = $dummy_heuristic_set->exportHeuristicData();
	$heur_data_dump['table'] = 'heuristic';
	//	$export_data_array[] = serialize($heur_data_dump);
	$export_data_array[] = $heur_data_dump;
	$query.= $this->buildImportQuery($heur_data_dump, 'heuristic');
      }

    // Rating Scheme
    $rating_scheme = & new RatingScheme($this->schemeId, $this->dbh_);
    $scheme_data_dump = $rating_scheme->exportRatingSchemeData();
    $scheme_data_dump['table'] = 'ratingscheme';
    //    $export_data_array[] = serialize($scheme_data_dump);
    $export_data_array[] = $scheme_data_dump;
    $query.= $this->buildImportQuery($scheme_data_dump, 'ratingscheme');

    $associated_scale_data_dump = $rating_scheme->exportAssociatedScaleData();
    $associated_scale_data_dump['table'] = 'ratingscheme_scale';
    //    $export_data_array[] = serialize($associated_scale_data_dump);
    $export_data_array[] = $associated_scale_data_dump;
    $query.= $this->buildImportQuery($associated_scale_data_dump, 'ratingscheme_scale');

    // Rating Scales
    $associated_scale_ids = $rating_scheme->getAssociatedScaleIds();
    foreach($associated_scale_ids as $current_scale_id)
      {
	$current_scale_object = & new RatingScale($current_scale_id, $this->dbh_);
	$current_scale_data_dump = $current_scale_object->exportScaleData();
	$current_scale_data_dump['table'] = 'rating_scale';
	//	$export_data_array[] = serialize($current_scale_data_dump);
	$export_data_array[] = $current_scale_data_dump;
	$query.= $this->buildImportQuery($current_scale_data_dump, 'rating_scale');

	// Rating Scale Values
	
	$current_scale_values_dump = $current_scale_object->exportScaleValueData();
	$current_scale_values_dump['table'] = 'rating_scale_value';
	//	$export_data_array[] = serialize($current_scale_values_dump);
	$export_data_array[] = $current_scale_values_dump;
	$query.= $this->buildImportQuery($current_scale_values_dump, 'rating_scale_value');
      }

    // Environments
    $environment = & new Environment($this->envId, $this->dbh_);
    $environment_data_dump = $environment->exportEnvironmentData();
    $environment_data_dump['table'] = 'environment';
    //    $export_data_array[] = serialize($environment_data_dump);
    $export_data_array[] = $environment_data_dump;
    $query.= $this->buildImportQuery($environment_data_dump, 'environment');


    $environment_attribute_data_dump = $environment->exportEnvironmentAttributeData();
    $environment_attribute_data_dump['table'] = 'environment_attributes';
    //    $export_data_array[] = serialize($environment_attribute_data_dump);
    $export_data_array[] = $environment_attribute_data_dump;
    $query.= $this->buildImportQuery($environment_attribute_data_dump, 'environment_attributes');



    // Auth Users
    $user_ids = $this->getAllUserIdsFromProject();

    $user_groups = Array();

    foreach($user_ids as $current_user_id)
      {
	$current_user = & new User($current_user_id, $this->dbh_);
	$user_data_dump = $current_user->exportUserData();
	$user_data_dump['table'] = 'user_attributes';
	//	$export_data_array[] = serialize($user_data_dump);
	$export_data_array[] = $user_data_dump;
	$query.= $this->buildImportQuery($user_data_dump, 'user_attributes');

	if(!is_null($admin_auth_handler))
	  {
	    $groups = $admin_auth_handler->getGroups($current_user_id);
	    /*	    echo "<pre>";
	    print_r($groups);
	    echo "</pre>";
	    */
	    
	    $user_groups[$current_user_id] = array_pop(array_keys($groups));
	    if($admin_auth_handler->userIdExists($current_user_id))
	      {
		$current_auth_data[0]['data'] = array_pop($admin_auth_handler->getUserData($current_user_id)); 
		$current_auth_data['table'] = 'liveuser_users';
		$export_data_array[] = $current_auth_data;
		$query.= $this->buildImportQuery($current_auth_data, 'liveuser_users');
	      }
	  }

      }
    

    // Screenshots
    $dummy_screenshot = & new Screenshot(0, $this->dbh_);
    $finding_ids = $dummy_finding->getAllFindingIds($this->pId);

    $file_names = Array();
    $i = 0;
    foreach($finding_ids as $current_finding_id)
      {
	$current_screenshot_ids = $dummy_screenshot->getScreenshotIds($current_finding_id);
	
	foreach($current_screenshot_ids as $current_screenshot_id)
	  {
	    $current_screenshot = & new Screenshot($current_screenshot_id, $this->dbh_);
	    $current_screenshot_data = $current_screenshot->exportScreenShotData();
	    

	    if(!empty($current_screenshot->sFileName))
	      {
		$file_names[$i]['name'] = $current_screenshot->sFileName;
		$file_names[$i]['path'] = $current_screenshot->image_db_dir_;
		$file_names[$i]['fid'] = $current_finding_id;
		$i++;

		$thumbnail_file = $current_screenshot->image_db_dir_."/".$current_screenshot->thumbnail_prefix_ . $current_screenshot->sFileName;

		//		echo $thumbnail_file."<br>";

		if(file_exists($thumbnail_file))
		  {
		    $file_names[$i]['name'] = $current_screenshot->thumbnail_prefix_ . $current_screenshot->sFileName;
		    $file_names[$i]['path'] = $current_screenshot->image_db_dir_;
		    $file_names[$i]['fid'] = $current_finding_id;
		    $i++;
		  }
	      }
	    $current_screenshot_data['table'] = 'screenshot';
	    //	    $export_data_array[] = serialize($current_screenshot_data);
	    $export_data_array[] = $current_screenshot_data;
	    $query.= $this->buildImportQuery($current_screenshot_data, 'screenshot');
	  }
      }

    $return_array = Array();
    /*    $db_data_dump ='';
    foreach($export_data_array as $current_data_array)
      {
	$db_data_dump.=$current_data_array."\n";
	}*/

    $return_array['data'] = serialize($export_data_array);
    //    $return_array['data'] = $db_data_dump;
    //    $return_array['query'] = $query;
    $return_array['user_groups'] = serialize($user_groups);
    $return_array['screenshot_filenames'] = $file_names;

    /*
    echo "<pre>";
    print_r($export_data_array);
    echo "</pre>";
    */
    //    echo $this->counter_;

    /*
    echo "<pre>";
    print_r($file_names);
    echo "</pre>";


     print_r(serialize($user_groups));

    $query = str_replace("{PREFIX}", 'test_', $query);

    $query = nl2br($query);

    $query = explode('<br />', $query);
    */

    return $return_array;
  }


  function getProjectExportAsZipFile($data_array)
  {

    /*    echo "<pre>";
    print_r($data_array);
    echo "</pre>";
    */ 
    require_once "File/Archive.php"; 
    $reader = File_archive::readMulti();
    
    $reader->addSource(File_Archive::readMemory($this->pId, "PROJECT"));
    $reader->addSource(File_Archive::readMemory(md5($this->pId), "PROJECT.md5"));
    $reader->addSource(File_Archive::readMemory(addslashes($data_array['user_groups']), "USER_GROUPS"));
    $reader->addSource(File_Archive::readMemory(md5(addslashes($data_array['user_groups'])), "USER_GROUPS.md5"));
    $reader->addSource(File_Archive::readMemory($data_array['data'], "DATA"));
    //    $reader->addSource(File_Archive::readMemory($data_array['query'], "DATA"));
    $reader->addSource(File_Archive::readMemory(md5($data_array['data']), "DATA.md5"));
    //    $reader->addSource(File_Archive::readMemory(md5($data_array['query']), "DATA.md5"));
    
    // Screenshots
    foreach($data_array['screenshot_filenames'] as $current_file_name)
      {
	$current_screenshot_uri = $current_file_name['path'] . "/" . $current_file_name['name'];
	if(file_exists($current_screenshot_uri))
	  $reader->addSource(File_Archive::read($current_screenshot_uri,  'images/' . $current_file_name['name']));
	//	echo "Added $current_screenshot_uri<br>";
      }

    /*
    while($reader->next())
      {
	
	echo $reader->getFilename()."<br>";
      }
    */

    $filename = "export_" . $this->pId . ".zip";
    
    
    $reader->extract( 
		     File_Archive::toArchive(
					     $filename,
					     File_Archive::toOutput()
					     ) 
		     );
    
   }

  function importProjectFromZipFile( $file_env, & $admin_auth_handler)
  {
    global $DB_PREFIX;

    $stats = Array();
    
    //    print_r($file_env);
    
    //    move_uploaded_file($file_env['tmp_name'], "/srv/www/vhosts/hem.dnsalias.org/htdocs/temp".$file_env['tmp_name']);
    
    //    $file_env['tmp_name'] = "/srv/www/vhosts/hem.dnsalias.org/htdocs/temp".$file_env['tmp_name'];

    if(!$file_env['error'] && file_exists($file_env['tmp_name']))
      {
	require_once "File/Archive.php"; 
	
	$source = File_Archive::readArchive('zip', File_Archive::read($file_env['tmp_name']));
	
	while($source->next())
	  {
	    $data[$source->getFilename()] = $source->getData();
	  }

	$stats['result'] = 1;
	
	$file_error = 0;
	if(md5($data['PROJECT']) !== $data['PROJECT.md5'])
	  {
	    echo "File  PROJECT corrupted";
	    $file_error = 1;
	  }
	if(md5($data['DATA']) !== $data['DATA.md5'])
	  {
	    echo "File DATA corrupted";
	    $file_error = 1;
	  }
	if(md5($data['USER_GROUPS']) !== $data['USER_GROUPS.md5'])
	  {
	    echo "File USER_GROUPS corrupted";
	    $file_error = 1;
	  }
	
	if(!$file_error)
	  {
	    // SQL Data
	    $sql_data = $data['DATA'];
	    //	    $sql_data = str_replace("{PREFIX}", $DB_PREFIX, $sql_data);
	    
	    //	    $sql_data = nl2br($sql_data);
	    //	    $data_array = explode(';', $sql_data);
	    //	    $data_array = explode('\n', $sql_data);
	    
	    $sql_stats = $this->importProjectData($sql_data);
	    
	    $stats['queries']['ok'] = $sql_stats['successful'];
	    $stats['queries']['number'] = $sql_stats['all'];
	    
	    
	    // User groups
	    $user_groups_array = unserialize(stripslashes($data['USER_GROUPS']));

	    foreach($user_groups_array as $user_id => $group)
	      {
		$groups = array();
		$i = 0;
		while($i < $group)
		  {
		    array_push($groups, $i+1);
		    $i++;
		  }
		$admin_auth_handler->addPermUser($user_id);
		$admin_auth_handler->updateGroupMemberShip($user_id, $groups);
	      }
	    
	    /*
	    echo "<pre>";
	    print_r($user_groups_array);
	    echo "</pre>";
	    */
	    // Images
	    foreach($data as $current_uri => $current_item)
	      {
		if(strstr($current_uri, "images/"))
		  {
		    $filename = str_replace("images/", "", $current_uri);
		    
		    $dummy_screenshot = & new Screenshot(0, $this->dbh_);
		    $filename = $dummy_screenshot->image_db_dir_ . "/" . $filename;
		    if(!file_exists($filename))
		      {
			if($fp=fopen($filename, "w"))
			  fwrite($fp, $current_item);
		      }
		  }
	      }
	  }
      }
    else
      $stats['error'] = "FILE_UPLOAD_ERROR";
    
    return $stats;
  }
  

  function importProjectData_old($query_array)
  {
    $successful_queries = 0;

    if(is_array($query_array))
      {
	foreach($query_array as $current_query)
	  {
	    if(isset($_ENV['LANG']) && stristr($_ENV['LANG'], 'utf-8'))
	      {
		// Server is configured utf-8
		if(!$this->isUTF8($current_query))
		  $current_query = utf8_encode($current_query);
	      }
	    else
	      {
		// Server has some other charset, decode UTF-8
		if($this->isUTF8($current_query))
		  $current_query = utf8_decode($current_query);
	      }

	    $return = $this->dbh_->query($current_query);
	    if(!$this->dbh_->hasError())
	       $successful_queries++;
	    else
	      {
		echo "Error: <pre>";
		print_r($this->dbh_->getError());
		$this->dbh_->resetError();
		echo $current_query;
		echo "</pre>";
	      }
	  }
      }

    return $successful_queries;
  } // OLD


  function importProjectData($data)
  {
    global $DB_PREFIX;

    $stats['successful'] = 0;
    $stats['all'] = 0;

    $data_array = unserialize($data);

    if(is_array($data_array))
      {
	foreach($data_array as $current_data_array)
	  {
	    /*	    echo "Current deserialized Array:<pre>";
	    print_r($current_data_array);
	    echo "</pre>";
	    */

	    $query_array = $this->buildImportQueryArray($current_data_array, $current_data_array['table']);

	    /*
	    echo "Current generated query array:<pre>";
	    print_r($query_array);
	    echo "</pre>";
	    */

	    foreach($query_array as $current_query)
	      {
		if(isset($_ENV['LANG']) && stristr($_ENV['LANG'], 'utf-8'))
		  {
		    // Server is configured utf-8
		    if(!$this->isUTF8($current_query))
		      $current_query = utf8_encode($current_query);
		  }
		else
		  {
		    // Server has some other charset, decode UTF-8
		    if($this->isUTF8($current_query))
		      $current_query = utf8_decode($current_query);
		  }

		$current_query = str_replace("{PREFIX}", $DB_PREFIX, $current_query);
		
		$return = $this->dbh_->query($current_query);

		//		echo $current_query."<br />";

		$stats['all']++;
		
		if(!$this->dbh_->hasError())
		  $stats['successful']++;
		else
		  {
		    //		    echo "Error: <pre>";
		    //		    print_r($this->dbh_->getError());
		    $this->dbh_->resetError();
		    //		    echo $current_query;
		    //		    echo "</pre>";
		  }
	      }
	  }
      }
    return $stats;
  }
  
  function isUTF8($string)
  {
    if(!empty($string))
      {
	if (is_array($string))
	  {
	    $enc = implode('', $string);
	    return @!((ord($enc[0]) != 239) && (ord($enc[1]) != 187) && (ord($enc[2]) != 191));
	  }
	else
	  {
	    return (utf8_encode(utf8_decode($string)) == $string);
	  }   
      }
    return FALSE;
  }
  


  // Export Function for current Project Data
  function exportProjectData()
  {
    $return_array = Array();
    
    if($this->init_ok_)
      {
	$return_array[0]['data'] = $this->getData();
      	$return_array[0]['translations']['pName'] = $this->translator_->exportTranslation($return_array[0]['data']['pNameId']);
      	$return_array[0]['translations']['pDescription'] = $this->translator_->exportTranslation($return_array[0]['data']['pDescriptionId']);
      }
    
    return $return_array;
  }


  function exportProjectUserMapping()
  {
    $return_array = Array();
    
    if($this->init_ok_)
      {
	$user_ids = $this->getAllUserIdsFromProject();

	foreach($user_ids as $current_user_id)
	  {
	    $return_array[]['data'] = Array(
					    'pId' => $this->pId,
					    'uId' => $current_user_id,
					    );
	  }
      }

    return $return_array;
  }
  
  function buildImportQuery($data_hash, $table)
  {
    $this->counter_++;

    $query = '';
    /*
    echo "<pre>";
    print_r($data_hash);
    echo "</pre>";
    */
    if(!empty($data_hash) && is_array($data_hash))
      {
	while($current_row = array_pop($data_hash))
	  {
	    if(isset($current_row['data']))
	      $query.= $this->buildImportDataQuery($current_row['data'], $table);
	    if(isset($current_row['translations']))
	      $query.= $this->buildImportTranslationQuery($current_row['translations']);
	  }
      }

    return $query;
  }

  function buildImportQueryArray($data_hash, $table)
  {
    $this->counter_++;

    $query = Array();
    
    /*    echo "<pre>";
    print_r($data_hash);
    echo "</pre>";*/
    
    if(!empty($data_hash) && is_array($data_hash))
      {
	while($current_row = array_pop($data_hash))
	  {
	    if(isset($current_row['data']) && is_array($current_row['data']))
	      $query[] = $this->buildImportDataQuery($current_row['data'], $table);
	    if(isset($current_row['translations']))
	      {
		$translation_query_array =  $this->buildImportTranslationQueryArray($current_row['translations']);
		/*		echo "buildImportTranslationQueryArray returned: <pre>";
		print_r($translation_query_array);
		echo "</pre>";*/
		if(is_array($translation_query_array))
		  $query = array_merge($query, $translation_query_array);
	      }
	  }
      }

    return $query;
  }
  


  function buildImportTranslationQueryArray($translation_hash)
  {
    $query_array = Array();
    $query_string = '';

    if(!empty($translation_hash) && is_array($translation_hash))
      {

	foreach($translation_hash as $current_translation_hash)
	  {
	    //	    print_r($current_translation_hash);
	    while($current_language = array_pop($current_translation_hash))
	      {
		$fields = Array();
		$values = Array();
		foreach($current_language as $field => $value)
		  {
		    $fields[] = $field;
		    $values[] = $this->dbh_->quote($value);
		  }
		
		
		$field_string = "(".implode(', ', $fields).")";
		
		$value_string = "(".implode(', ', $values).")";
		
	        $query_array[] = "INSERT INTO {PREFIX}translation $field_string VALUES $value_string\n";
	      }
	  }

	return $query_array;
      }
    else
      return FALSE;

  }

  function buildImportTranslationQuery($translation_hash)
  {
    $query_string = '';

    if(!empty($translation_hash) && is_array($translation_hash))
      {

	foreach($translation_hash as $current_translation_hash)
	  {
	    //	    print_r($current_translation_hash);
	    while($current_language = array_pop($current_translation_hash))
	      {
		$fields = Array();
		$values = Array();
		foreach($current_language as $field => $value)
		  {
		    $fields[] = $field;
		    $values[] = "'".addslashes($value)."'";
		  }
		
		
		$field_string = "(".implode(', ', $fields).")";
		
		$value_string = "(".implode(', ', $values).")";
		
	        $query_string.= "INSERT INTO {PREFIX}translation $field_string VALUES $value_string\n";
	      }
	  }

	return $query_string;
      }
    else
      return FALSE;

  }


  function buildImportDataQuery($data_hash, $table)
  {
    $fields = Array();
    $values = Array();

    if(is_array($data_hash) && !empty($data_hash))
      {
	foreach($data_hash as $field => $value)
	  {
	    $fields[] = $field;
	    $values[] = $this->dbh_->quote($value);
	  }
    

	$field_string = "(".implode(', ', $fields).")";
	
	$value_string = "(".implode(', ', $values).")";
	
	return "INSERT INTO {PREFIX}$table $field_string VALUES $value_string\n";

      }
    else
      return FALSE;
  }



  /**
   * Removes a project
   *
   * Delete all project data, findings, ratings
   *
   * TODO: implement me
   *
   * @return FALSE
   */
  function deleteProject()
  {
    $dummy_finding =& new Finding(0, $this->dbh_);

    // Screenshots
    $dummy_screenshot = & new Screenshot(0, $this->dbh_);
    $finding_ids = $dummy_finding->getAllFindingIds($this->pId);

    foreach($finding_ids as $current_finding_id)
      {
	$current_screenshot_ids = $dummy_screenshot->getScreenshotIds($current_finding_id);

	//	echo "<pre>";
	//	print_r($current_screenshot_ids);
	//	echo "</pre>";

	foreach($current_screenshot_ids as $current_screenshot_id)
	  {
	    //	    echo "Deleting: $current_screenshot_id<br>";
	    $current_screenshot = & new Screenshot($current_screenshot_id, $this->dbh_);
	    $current_screenshot->deleteImage();
	  }
      }
    

    // Environment Data
    $dummy_env_data = & new EnvironmentData(0, $this->dbh_);
    $env_data_dump = $dummy_env_data->deleteAttributeData($this->pId);


    // Findings
    $dummy_finding->deleteFindingData($this->pId);


    // Project Data
    $user_ids = $this->getAllUserIdsFromProject();
    foreach($user_ids as $current_user_id)
      {
	$this->removeUserFromProject($current_user_id);
      }

    $this->translator_->removeTranslation($this->pNameId);
    $this->translator_->removeTranslation($this->pDescriptionId);

    $this->deleteData();


      
    $this->setError('deleteProject is not implemented for now');
    return FALSE;
  }

}
?>