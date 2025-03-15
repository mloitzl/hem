<?php

class ProjectManager extends PHPApplication
{
  
  function run()
  {
    global $globals;

    global $HOME_APP, $HOME_APP_LABEL;
    
    // Set Application Title and Breadcrumbs
    $this->app_name_ = $this->getLabelText("APPLICATION_TITLE");
    $this->app_breadcrumbs_[] = Array(
				      'url' => $HOME_APP,
				      'label' => $HOME_APP_LABEL[$this->language_],
				      );
    
    $this->app_breadcrumbs_[] = Array(
				      'url' => $_SERVER['SCRIPT_NAME'],
				      'label' => $this->getLabelText("APPLICATION_TITLE"),
				      );
    
    
    $this->projectManagerDriver();
    
  }


  function projectManagerDriver()
  {
    // A Form has been subitted, check 

    $this->debug( $this->getPostRequestField('form_id', null));
    if(!is_null($form = $this->getPostRequestField('form_id', null)))
      {
	switch ($form)
	  {
	  case 'add_project':
	    // Submit button hit, we're ready
	    if(!is_null($this->getPostRequestField('Submit', null)))
	      {
		$this->doAddProject();
		header("Location: ".$_SERVER['PHP_SELF']);
	      }
	    if(!is_null($this->getPostRequestField('inviteButton',null)))
	      {
		$pid = $this->doAddProject();
		$this->debug("Location: ".$_SERVER['PHP_SELF']."?cmd=addProj&pid=$pid");
		header("Location: ".$_SERVER['PHP_SELF']."?cmd=addProj&pid=$pid");
	      }
	    break;
	  case 'import_project':
	    $stats = $this->doImportProject();
	    $this->displayResultsDriver($stats);
	    break;
	  case 'delete_project':
	    $this->doDeleteProject();
	    break;
	  default:
	    break;
	  }
      }


    // Url Parameters, no form submitted, or form data already processed    
    else if(!is_null($cmd = $this->getGetRequestField('cmd', null)))
      {
	switch ($cmd)
	  {
	  case 'addProject':
	    $this->addProjectDriver();
	    break;
	  case 'importProject':
	    $this->importProjectDriver();
	    break;
	  case 'exportProject':
	    $this->exportProjectDriver();
	    break;
	  case 'deleteProject':
	    $this->deleteProjectDriver();
	    break;
	  default:
	    $this->debug('Calliung projectOverview');
	    $this->projectOverview();
	    break;
	  }
      }
    else
      {
	$this->projectOverview();
      }

  }


  function doAddProject()
  {

    $this->debug("doAddProject()");

    $available_users = (array)$this->getPostRequestField('availableUsers', 'nix');
    $project_users = (array)$this->getPostRequestField('projectUsers', 'nix');

    //    $this->dumpArray($available_users);
    //    $this->dumpArray($project_users);

    //    $this->dumpArray($_REQUEST);


    $project_data = $this->getPostRequestField('project', null);
    if(!is_null($project_data['pId'])) 
      {
	$proj =& new Project($project_data['pId'], $this->dbi_);
	$proj->storeProject($project_data);
      }
    // Add / Update Project Data
    /*    
    if($proj->init_ok_)
      {
	$this->debug("Updating Project Information");
	$data = array(
		      'pId' => $project['pId'],
		      'pName' => $project['pName'],
		      'pDescription' => $project['pDescription'],
		      'heurSetId' => $project['heurSetId'],
		      'pPhase' => $project['pPhase'],
		      'schemeId' => $project['schemeId'],
		      'envId' => $project['envId'],
		      );
	$proj->updateData($data);
	
      }
    else
      {
	$this->debug("Adding Project Information");
	$data = array(
		      'pId' => $project['pId'],
		      'pName' => $project['pName'],
		      'pDescription' => $project['pDescription'],
		      'heurSetId' => $project['heurSetId'],
		      'pPhase' => $project['pPhase'],
		      'schemeId' => $project['schemeId'],
		      'envId' => $project['envId'],
		      'pAdded' => date('YmdHms',time()),
		      );
	$proj->addData($data);
	// Re init the Project Object, so that the data is valid for later use
	$proj->init();
      }
  
    */
    // Add / Update Users assigned to Project
    $project_users = $this->getPostRequestField('projectUsers', null);
    if(!is_null($project_users))
      {
	$current_users = $proj->getAllUserIdsFromProject();
	if($current_users)
	  {
	    while($current_user_id = array_pop($current_users))
	      {
		$proj->removeUserFromProject($current_user_id);
	      }
	  }
	while($new_user_id = array_pop($project_users))
	  {
	    $proj->addUserToProject($new_user_id);
	  }
      }
    
    return $project_data['pId'];
  }


  function addProjectDriver()
  {
    global $ADD_PROJECT_TEMPLATE;

    $this->showScreen($ADD_PROJECT_TEMPLATE, 'displayAddProjectForm', $this->getAppName());
  }


  function displayAddProjectForm(& $tpl)
  {
    global $LANGUAGES;

    if(!is_null($proj_id = $this->getGetRequestField('pid', null)))
      {
	$this->debug('ProjectId:'. $proj_id);
	$proj_to_change = & new Project($proj_id, $this->dbi_);
	if($proj_to_change->hasError())
	  {
	    $this->debug("Project Error:".$proj_to_change->getError());
	  }
      }
    else
      {
	$proj_to_change = & new Project(0, $this->dbi_);
      }

    $message_text = '';
    if($this->hasSessionMessages() == TRUE)
      {
	$messages = $this->getAllSessionMessages();
	while($msg = array_pop($messages))
	  {
	    $message_text.=$msg;
	  }
      }

    $tpl->setCurrentBlock('main_block');

    // Display a list of all available users
    $tpl->setVar('LABEL_AVAILABLE_USERS', $this->getLabelText('LABEL_AVAILABLE_USERS'));
    $users = $this->admin_auth_handler_->getAllUsers();
    while($current_user = array_pop($users))
      {
	// 	    <a href='?uid={USER_ID}'>{USER_FIRST_NAME} {USER_LAST_NAME} ({USER_NAME}) </a>
	$current_user_object =& new User($current_user['user_id'], $this->dbi_);

	if(!is_null($proj_id) &&  $proj_to_change->isuserInProject($current_user['user_id']))
	  {}
	else
	  {
	    $tpl->setCurrentBlock('available_user_block');
	    $tpl->setVar(array(
			       'USER_ID' => $current_user['user_id'],
			       'USER_FIRST_NAME' => $current_user_object->first_name,
			       'USER_LAST_NAME' => $current_user_object->last_name,
			       //			       'USER_NAME' => $current_user['user_name']
			       )
			 );
	    $tpl->parseCurrentBlock();
	  }
      }

    //Display Users from Project if available
    $tpl->setVar('LABEL_PROJECT_USERS', $this->getLabelText('LABEL_PROJECT_USERS'));
    if(!is_null($proj_id))
      {
	$proj_users = $proj_to_change->getAllUserIdsFromProject();

	if($proj_users)
	  {
	    while($current_user_id = array_pop($proj_users))
	      {
		$current_user_object =& new User($current_user_id, $this->dbi_);
		
		$tpl->setCurrentBlock('project_user_block');
		$tpl->setVar(array(
				   'USER_ID' => $current_user_id,
				   'USER_FIRST_NAME' => $current_user_object->first_name,
				   'USER_LAST_NAME' => $current_user_object->last_name,
				   //			       'USER_NAME' => $current_user['user_name']
				   )
			     );
		$tpl->parseCurrentBlock();
	      }
	  }

      }


    // Set Project Title and Description Titles
    $tpl->setVar(
		 array(
		       'LABEL_PROJECT_TITLE' => $this->getLabelText('LABEL_PROJECT_TITLE'),
		       'LABEL_PROJECT_DESC' => $this->getLabelText('LABEL_PROJECT_DESC'),
		       )
		 );
    // Set Project Id
    if(!is_null($proj_id))
      $tpl->setVar('PROJECT_ID', $proj_to_change->pId);
    else
      $tpl->setVar('PROJECT_ID', $this->getUniqueId());
    

    $generated_title_translation_id = $this->getUniqueId();
    $generated_description_translation_id = $this->getUniqueId();

    $translation = & new Translation(0, $this->dbi_);
    // Set title and messages
    foreach ( $LANGUAGES as $key => $val )
      {
		$tpl->setCurrentBlock('title_language_header_block');
		$tpl->setVar(array(
			   	'LABEL_TITLE_LANGUAGE' => $this->getLabelText('LABEL_'.$val),
			   	)
		     	);
		$tpl->parseCurrentBlock();

		$tpl->setCurrentBlock('title_language_block');
		$tpl->setVar('TITLE_LANGUAGE_CODE', $val);
		
		if($proj_to_change->init_ok_)
	  	{
		    $tpl->setVar(array(
			       	'PROJECT_TITLE' => $translation->getTranslation($proj_to_change->pNameId, $val),
			       	'PROJECT_TITLE_ID' => $proj_to_change->pNameId,
			       	)
			 	);
	  	}
		else
	  	{
		    $tpl->setVar(array(
			       	'PROJECT_TITLE_ID' => $generated_title_translation_id,
			       	)
			 	);
	  	}
		$tpl->parseCurrentBlock();

		$tpl->setCurrentBlock('description_language_header_block');
		$tpl->setVar(array(
			   	'LABEL_DESCRIPTION_LANGUAGE' => $this->getLabelText('LABEL_'.$val),
			   	)
		     	);
		$tpl->parseCurrentBlock();

		
		$tpl->setCurrentBlock('description_language_block');

		$tpl->setVar('TITLE_LANGUAGE_CODE', $val);
		if($proj_to_change->init_ok_)
	  	{	
		    $tpl->setVar(array(
			       	'PROJECT_DESCRIPTION' => $translation->getTranslation($proj_to_change->pDescriptionId, $val),
			       	'PROJECT_DESCRIPTION_ID' => $proj_to_change->pDescriptionId,
			       	)
			 	);
	  	}
		else
	  	{
		    $tpl->setVar(array(
			       	'PROJECT_DESCRIPTION_ID' => $generated_description_translation_id,
			       	)
			 	);
	  	}
		$tpl->parseCurrentBlock();
	
      }
    
    
    
	if(!is_null($proj_id))
      {
		$tpl->setVar('ADD_PROJECT_TITLE', $this->getLabelText('CHANGE_PROJECT_TITLE'));
		$this->app_name_ = $this->getLabelText('CHANGE_PROJECT_TITLE');
		$this->app_breadcrumbs_[] = Array(
					  'url' => $_SERVER['REQUEST_URI'],
					  'label' => $translation->getTranslation($proj_to_change->pNameId, $this->language_),
					  );
	$this->app_breadcrumbs_[] = Array(
					  'label' => $this->getLabelText("CHANGE_PROJECT_TITLE"),
					  );
      }
    else
      {
	$tpl->setVar('ADD_PROJECT_TITLE', $this->getLabelText('ADD_PROJECT_TITLE'));
	$this->app_name_ = $this->getLabelText('ADD_PROJECT_TITLE');
	$this->app_breadcrumbs_[] = Array(
					  'label' => $this->getLabelText("ADD_PROJECT_TITLE"),
					  );
      }
    
    $tpl->setVar(array(
		       'MESSAGES' => $message_text
		       )
		 );
	
    
    
    // Set Heuristik Sets
    $tpl->setVar(array(
		       'LABEL_HEUR_SET_TITLE' => $this->getLabelText('LABEL_HEUR_SET_TITLE'),
		       'LABEL_NO_HEURS' => $this->getLabelText('LABEL_NO_HEURS')
		       )
		 );
    
    $heur_set = new HeuristicSet(0, $this->dbi_);

    $translator = & new Translation(0, $this->dbi_);
    

   $all_heur_set_ids = $heur_set->getHeuristicSetIds();

   if(is_array($all_heur_set_ids))
     {
       if($proj_id)
	 {
	   $this->debug('Project Heuristiks:'. $proj_to_change->heurSetId);
	 }
       while($current_set_id = array_pop($all_heur_set_ids))
	 {
	   $current_set = & new HeuristicSet($current_set_id, $this->dbi_);
	   $tpl->setCurrentBlock('heur_set_block');
	   $tpl->setVar(array(
			      'HEUR_SET_ID' => $current_set->hSetId,
			      'HEUR_SET_NAME' => $translator->getTranslation($current_set->hSetTitleId, $this->language_),
			      )
			);
	   if($proj_id && $current_set->hSetId == $proj_to_change->heurSetId)
	     $tpl->setVar( 'SELECTED', 'selected');
	   //	   $this->dumpArray($current_set);
	   $tpl->parseCurrentBlock();
	 }
       
     }


    // Set Environment
   $tpl->setVar(array(
		      'LABEL_ENV_TITLE' => $this->getLabelText('LABEL_ENV_TITLE'),
		      )
		);
   
   $pseudo_env =& new Environment(0, $this->dbi_);
    
   $all_envs = $pseudo_env->getEnvironmentIds();
   if(is_array($all_envs))
     {
       if($proj_id)
	 {
	   $this->debug('Project Environment:'. $proj_to_change->envId);
	 }
       while($current_env = array_pop($all_envs))
	 {
	   $current_env_object = & new Environment($current_env, $this->dbi_);
	   $tpl->setCurrentBlock('env_block');
	   $tpl->setVar(array(
			      'ENV_ID' => $current_env_object->envId,
			      'ENV_NAME' => $translator->getTranslation($current_env_object->envTitleId, $this->language_),
			      )
			);
	   if($proj_id && $current_env_object->envId == $proj_to_change->envId)
	     $tpl->setVar( 'SELECTED', 'selected');
	   //	   $this->dumpArray($current_set);
	   $tpl->parseCurrentBlock();
	 }
       
     }

   // Display Rating Schemes
   $tpl->setVar(array(
		      'LABEL_SCHEME_TITLE' => $this->getLabelText('LABEL_SCHEME_TITLE'),
		      )
		);
   
   $pseudo_scheme =& new RatingScheme(0, $this->dbi_);
    
   $all_scheme_ids = $pseudo_scheme->getAllRatingSchemeIds();
   if(is_array($all_scheme_ids))
     {
       $translation = & new Translation(0, $this->dbi_);
       if($proj_id)
	 {
	   $this->debug('Project Environment:'. $proj_to_change->schemeId);
	 }
       while($current_scheme_id = array_pop($all_scheme_ids))
	 {
	   $current_scheme_object = & new RatingScheme($current_scheme_id, $this->dbi_);
	   $tpl->setCurrentBlock('scheme_block');
	   $tpl->setVar(array(
			      'SCHEME_ID' => $current_scheme_object->schemeId,
			      'SCHEME_NAME' => $translation->getTranslation($current_scheme_object->schemeTitleId, $this->language_),
			      )
			);
	   if($proj_id && $current_scheme_object->schemeId == $proj_to_change->schemeId)
	     $tpl->setVar( 'SELECTED', 'selected');

	   $tpl->parseCurrentBlock();
	 }
     }   




   //Set Phases
   $tpl->setVar('LABEL_PROJECT_PHASE', $this->getLabelText('LABEL_PROJECT_PHASE'));
   $tpl->setCurrentBlock('phase_block');
   $tpl->setVar(array(
		      'PHASE_VALUE' => PROJ_NOT_STARTED,
		      'PHASE_TITLE' => $this->getLabelText('LABEL_NOT_STARTED')
		      )
		);  
   if(!is_null($proj_id) && ($proj_to_change->pPhase == PROJ_NOT_STARTED))
     $tpl->setVar('SELECTED', 'selected');
   $tpl->parseCurrentBlock();

   $tpl->setCurrentBlock('phase_block');
   $tpl->setVar(array(
		      'PHASE_VALUE' => PROJ_EVALUATE,
		      'PHASE_TITLE' => $this->getLabelText('LABEL_EVALUATE')
		      )
		);
   if(!is_null($proj_id) && ($proj_to_change->pPhase == PROJ_EVALUATE))
     $tpl->setVar('SELECTED', 'selected');
   $tpl->parseCurrentBlock();

   $tpl->setCurrentBlock('phase_block');
   $tpl->setVar(array(
		      'PHASE_VALUE' => PROJ_MERGE,
		      'PHASE_TITLE' => $this->getLabelText('LABEL_MERGE')
		      )
		);
   if(!is_null($proj_id) && ($proj_to_change->pPhase == PROJ_MERGE))
     $tpl->setVar('SELECTED', 'selected');
   $tpl->parseCurrentBlock();

   $tpl->setCurrentBlock('phase_block');
   $tpl->setVar(array(
		      'PHASE_VALUE' => PROJ_RATE,
		      'PHASE_TITLE' => $this->getLabelText('LABEL_RATE')
		      )
		);
   if(!is_null($proj_id) && ($proj_to_change->pPhase == PROJ_RATE))
     $tpl->setVar('SELECTED', 'selected');
   $tpl->parseCurrentBlock();

  $tpl->setCurrentBlock('phase_block');
   $tpl->setVar(array(
		      'PHASE_VALUE' => PROJ_FINISHED,
		      'PHASE_TITLE' => $this->getLabelText('LABEL_FINISHED')
		      )
		);
   if(!is_null($proj_id) && ($proj_to_change->pPhase == PROJ_FINISHED))
     $tpl->setVar('SELECTED', 'selected');
   $tpl->parseCurrentBlock();

    //Set Buttons
    $tpl->setVar(array(
		       'LABEL_INVITE_BUTTON' => $this->getLabelText('LABEL_INVITE_BUTTON'),
		       'LABEL_SUBMIT_BUTTON' => $this->getLabelText('SUBMIT_BUTTON'),
		       'LABEL_CANCEL_BUTTON' => $this->getLabelText('CANCEL_BUTTON')
		       )
		 );

    //Set Hidden Fields
    $tpl->setVar(array(
		       'FORM_ACTION' => $this->getFQAN('run.ProjectManager.php')
		       )
		 );

    $tpl->parseCurrentBlock();

    return TRUE;
  }


  function projectOverview()
  {
    global $PROJECT_OVERVIEW_TEMPLATE;
    // TODO: Handle submitted data

    $this->debug('projectOverview');

    $this->showScreen($PROJECT_OVERVIEW_TEMPLATE, 'displayProjectOverview', $this->getAppName());

  }


  function displayProjectOverview(& $tpl)
  {
    
    $message_text = '';
    if($this->hasSessionMessages() == TRUE)
      {
	$messages = $this->getAllSessionMessages();
	while($msg = array_pop($messages))
	  {
	    $message_text.=$msg;
	  }
      }    

    $proj =& new Project(0, $this->dbi_);

    $project_ids = $proj->getAllProjectIds();

    if($project_ids)
      {
	$i = 0;
	while($current_proj_id = array_pop($project_ids))
	  {
	    $current_project_object =& new Project($current_proj_id, $this->dbi_);
	    $translation = & new Translation(0, $this->dbi_);

	    $tpl->setCurrentBlock('project_block');
	    $tpl->setVar(array(
			       'BG_CLASS' => ($i%2?'odd':'even'),
			       'PROJECT_TITLE' => $translation->getTranslation($current_project_object->pNameId, $this->language_),
			       'PROJECT_DESCRIPTION' => $translation->getTranslation($current_project_object->pDescriptionId, $this->language_),
			       'EDIT_URL' => $_SERVER['PHP_SELF']."?cmd=addProject&pid=".$current_project_object->pId,
			       'DELETE_URL' => $_SERVER['PHP_SELF']."?cmd=deleteProject&pid=".$current_project_object->pId,
			       'EXPORT_URL' => $_SERVER['PHP_SELF']."?cmd=exportProject&pid=".$current_project_object->pId,
			       'LABEL_CHANGE_PROJECT' => $this->getLabelText('LABEL_CHANGE_PROJECT'),
			       'LABEL_DELETE_PROJECT' => $this->getLabelText('LABEL_DELETE_PROJECT'),
			       'LABEL_EXPORT_PROJECT' => $this->getLabelText('LABEL_EXPORT_PROJECT'),
			       'PROJECT_PHASE' => $this->getLabelText($current_project_object->getPhaseLabelById($current_project_object->pPhase))
			       )
			 );
	    
	    $tpl->parseCurrentBlock();
	    $i++;
	  }

      }


    $tpl->setCurrentBlock('main_block');
    $tpl->setVar(array(
		       'LABEL_PROJECT_TITLE' => $this->getLabelText('LABEL_PROJECT_TITLE'), 
		       'LABEL_PROJECT_PHASE' => $this->getLabelText('LABEL_PROJECT_PHASE'), 
		       'LABEL_OPERATIONS' => $this->getLabelText('LABEL_OPERATIONS'), 
		       'LABEL_ADD_PROJECT' => $this->getLabelText('LABEL_ADD_PROJECT'), 
		       'ADD_URL' => $_SERVER['PHP_SELF']."?cmd=addProject",
		       'LABEL_IMPORT_PROJECT' => $this->getLabelText('LABEL_IMPORT_PROJECT'),
		       'IMPORT_URL' => $_SERVER['PHP_SELF']."?cmd=importProject",
		       )
		 );
    $tpl->setVar('PROJECT_OVERVIEW_TITLE', $this->getLabelText('PROJECT_OVERVIEW_TITLE'));
    $tpl->setVar(array(
		       'MESSAGES' => $message_text
		       )
		 );



    $tpl->parseCurrentBlock();

    return TRUE;
  }


  function exportProjectDriver()
  {
    $this->debug('ExportProjectDriver');

    if(!is_null($proj_id = $this->getGetRequestField('pid', null)))
      {
	$project_object = & new Project($proj_id, $this->dbi_);

	//	$this->dumpArray($project_object->exportProject($this->admin_auth_handler_));
	$project_object->getProjectExportAsZipFile($project_object->exportProject($this->admin_auth_handler_));
      }
  }




  function doImportProject()
  {
    $project_object = & new Project(0, $this->dbi_);

    if(isset($_FILES['project_archive']))
      {
	$stats = $project_object->importProjectFromZipFile($_FILES['project_archive'], $this->admin_auth_handler_);
      }
    else
      $stats['result'] = 0;


    return $stats;
  }



  function displayResultsDriver($stats)
  {
    $this->debug('displayResultsDriver');

    global $PROJECT_IMPORT_RESULTS_TEMPLATE;

    $this->import_stats_ = $stats;

    $this->showScreen($PROJECT_IMPORT_RESULTS_TEMPLATE, 'displayImportProjectResults', $this->getAppName());
  }


  function displayImportProjectResults(& $tpl)
  {
    
    //    $this->dumpArray($this->import_stats_);
    
    if($this->import_stats_['result'])
      $tpl->setVar(array(
			 'LABEL_NUMBER_OF_OK_QUERIES' => $this->getLabelText('LABEL_NUMBER_OF_OK_QUERIES'),
			 'NUMBER_OF_OK_QUERIES' => $this->import_stats_['queries']['ok'],
			 'LABEL_NUMBER_OF_KEPT_QUERIES' => $this->getLabelText('LABEL_NUMBER_OF_KEPT_QUERIES'),
			 'NUMBER_OF_KEPT_QUERIES' => ((int)$this->import_stats_['queries']['number'] - (int)$this->import_stats_['queries']['ok']),
			 'PROJECT_IMPORT_STATUS' => $this->getLabelText('PROJECT_IMPORT_STATUS_OK'),
			 'PROJECT_IMPORT_STATUS' => $this->getLabelText('PROJECT_IMPORT_STATUS_OK'),
			 )
		   );
    else
      $tpl->setVar('PROJECT_IMPORT_STATUS', $this->getLabelText('PROJECT_IMPORT_STATUS_NOTOK'));
    
    $this->app_breadcrumbs_[] = Array(
				      'label' =>  $this->getLabelText('PROJECT_IMPORT_RESULT_TITLE'),
				      );

    $tpl->setVar(array(
		       'PROJECT_IMPORT_RESULT_TITLE' => $this->getLabelText('PROJECT_IMPORT_RESULT_TITLE'),
		       )
		 );

    return TRUE;
  }

  function importProjectDriver()
  {
    $this->debug('ImportProjectDriver');

    global $PROJECT_IMPORT_FORM_TEMPLATE;

    $this->showScreen($PROJECT_IMPORT_FORM_TEMPLATE, 'displayImportProjectForm', $this->getAppName());
  }


  
  function displayImportProjectForm(& $tpl)
  {
    global $PHP_SELF;
    
    
    $this->app_breadcrumbs_[] = Array(
				      'label' =>  $this->getLabelText('PROJECT_IMPORT_FORM_TITLE'),
				      );

    $tpl->setVar(array(
		       'PROJECT_IMPORT_FORM_TITLE' => $this->getLabelText('PROJECT_IMPORT_FORM_TITLE'),
		       'LABEL_ARCHIVE' => $this->getLabelText('LABEL_ARCHIVE'),
		       'LABEL_MAX_UPLOAD_FILESIZE' => $this->getLabelText('LABEL_MAX_UPLOAD_FILESIZE'),
		       'LABEL_DONOTUNZIP' => $this->getLabelText('LABEL_DONOTUNZIP'),
		       'VALUE_MAX_UPLOAD_FILESIZE' => ini_get('post_max_size'),
		       'FORM_ACTION' => $PHP_SELF,
		       'FORM_METHOD' => 'POST',
		       'UPLOAD_BUTTON' => $this->getLabelText('UPLOAD_BUTTON'),
		       'BACK_BUTTON' => $this->getLabelText('BACK_BUTTON'),
		       )
		 ); 
    //		       'VALUE_MAX_UPLOAD_FILESIZE' => ini_get('upload_max_filesize'),    
    
    return TRUE;
  }




  function deleteProjectDriver()
  {
    global $DELETE_PROJECT_TEMPLATE;
    // TODO: Handle submitted data
    
    $this->debug('deleteProject');
    
    $this->showScreen($DELETE_PROJECT_TEMPLATE, 'deleteProjectConfirmation', $this->getAppName());
  }
  
  
  function deleteProjectConfirmation(& $tpl)
  {

    global $PHP_SELF;

    $pid = $this->getGetRequestField('pid', null);
    if(!is_null($pid))
      {
	$project = & new Project($pid, $this->dbi_);
	
	$translation = & new Translation(0, $this->dbi_);

	$confirmation_message = $this->getMessageText('CONFIRMATION_MESSAGE');
	
	$this->app_breadcrumbs_[] = Array(
					  'label' => $this->getLabelText("DELETE_PROJECT_TITLE"),
					  );
	
	$tpl->setVar(array(
			   'FORM_ACTION' => $PHP_SELF,
			   'FORM_METHOD' => 'POST',
			   'CONFIRMATION_MESSAGE' => $confirmation_message,
			   'PROJECT_NAME' => $translation->getTranslation($project->pNameId, $this->language_),
			   'PROJECT_ID' => $project->pId,
			   'DELETE_PROJECT_TITLE' => $this->getLabelText('DELETE_PROJECT_TITLE'),
			   'LABEL_OK_BUTTON' => $this->getLabelText('LABEL_OK_BUTTON'),
			   'LABEL_NO_BUTTON' => $this->getLabelText('LABEL_NO_BUTTON'),
			   )
		     );
	
      }

    return TRUE;
  }


  function doDeleteProject()
  {
    global $PHP_SELF;

    $pid = $this->getPostRequestField('pid', null);

    $this->debug("Deleteing Project $pid");


    if(!is_null($pid))
      {
	$project = & new Project($pid, $this->dbi_);

	if($project->init_ok_)
	  {
	    $project->deleteProject();
	    $this->addSessionMessage('PROJECT_DELETED');
	  }
	
      }
    else
      $this->addSessionMessage('PROJECT_NOT_DELETED');

    header("Location: $PHP_SELF");
  }

}
?>