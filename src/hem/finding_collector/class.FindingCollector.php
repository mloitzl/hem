<?php

class FindingCollector extends PHPApplication
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
    
    $this->findingCollectorDriver();

    }
  
  function findingCollectorDriver()
  {
    global $PHP_SELF, $HOME_APP;
    
    $cmd = $this->getGetRequestField('cmd', null);

    $form = $this->getPostRequestField('form_id', null);

    $answer = $this->getPostRequestField('Yes', null);

    // Action to take after adding the finding
    $action = $this->getPostRequestField('action', null);

    //Url to redirect user to, afterwards
    $url = $this->getPostRequestField('url', null);
    
    $data = $this->getPostRequestField('data', null);
    $project_id = $data['pId'];

    $this->debug($action);

    switch ($form)
      {
      case 'add_finding':
	//	$this->dumpArray($_REQUEST);
	$this->doChangeFinding();
	break;
      case 'confirm':
	if(!is_null($answer))
	  $this->doDeleteFinding();
	break;
      default:
	break;
      }

    if(!is_null($url))
      {
	header("Location: $url");
      }
    else
      {
	if($action == 'discontinue')
	  {
	    header("Location: $HOME_APP");
	  }
	elseif($action == 'add_another')
	  {
	    //	echo "$PHP_SELF?cmd=evaluate&pid=$project_id";
	    header("Location: $PHP_SELF?cmd=viewFindings&pid=$project_id");
	  }
      }
    switch ($cmd)
      {
      case 'addFinding':
	$this->addFindingDriver();
	break;
      case 'editFinding':
	$this->addFindingDriver();
	break;
      case 'viewFindings':
	$this->findingOverview();
	break;
      case 'deleteFinding':
	$this->getConfirmation();
	break;
      case 'moveDown':
	$this->moveDownFinding();
	$this->doRedirect();
	break;
      case 'moveUp':
	$this->moveUpFinding();
	$this->doRedirect();
	break;
      default:
	$this->projectOverview();
	break;
      }
  }
  
  function addFindingDriver()
  {
    $this->debug("Evaluation Driver");
    global $CHANGE_FINDING_TEMPLATE;
    //    global $EVALUATION_TEMPLATE;
    //    $this->showScreen($EVALUATION_TEMPLATE, 'displayAddFindingForm', $this->getAppName());
    $this->showScreen($CHANGE_FINDING_TEMPLATE, 'displayAddFindingForm', $this->getAppName());
  }

  function displayAddFindingForm(& $tpl)
  {
    global $PHP_SELF, $FULLSIZE_IMAGE_POPUP;

    $translation = & new Translation(0, $this->dbi_);

    $project_id = $this->getGetRequestField('pid', null);

    // Url to return afterwards, primarly for admin changes
    $url = $this->getGetRequestField('url', null);

    // If we change a finding a finding id is given
    $finding_id = $this->getGetRequestField('fid', null);
    $this->debug("Editing Finding " . $finding_id);

    if(!is_null($finding_id))
      $finding_to_change = & new Finding($finding_id, $this->dbi_);

    // Get the project id, when we change a finding
    if(is_null($project_id) && isset($finding_to_change) && $finding_to_change->init_ok_)
      $project_id = $finding_to_change->pId;

    // Were do we insert the finding
    $insert_after = $this->getGetRequestField('after', null);

    // Insert at the end, if nothing is given
    if(is_null($insert_after))
      {
	$dummy_finding = & new Finding(0, $this->dbi_);
	$insert_after = $dummy_finding->getLastFindingId($this->user_->auth_user_id, $project_id);
      }


    if(!is_null($project_id))
      {
	$project = & new Project($project_id, $this->dbi_);
	
	$this->app_breadcrumbs_[] = Array(
					  'url' => 'finding_collector/run.FindingCollector.php?cmd=viewFindings&pid='.$project_id,
					  'label' => $translation->getTranslation($project->pNameId, $this->language_),
					  );    

	// TODO: Clean up if it works out
	//	$project_users = $project->getAllUserIdsFromProject();
	//	if(in_array($this->user_->user_id_, $project_users))
	if($project->isUserInProject($this->user_->user_id_) || $this->auth_handler_->checkRight(CHANGE_OTHER_FINDINGS))
	  {
	    if($project->init_ok_ && ( $project->getProjectPhase() == '1' || $this->auth_handler_->checkRight(CHANGE_OTHER_FINDINGS) ))
	      {
		// TODO: Check bug: displays on labeled form and one unlabeled in some projects
		//		$tpl->setCurrentBlock('main_block');
		
		$tpl->setVar(array(
				   'LABEL_FINDING_TEXT' => $this->getLabelText('LABEL_FINDING_TEXT'),
				   'LABEL_POSITIVE' => $this->getLabelText('LABEL_POSITIVE'),
				   )
			     );

		if(!is_null($finding_id))
		  {
		    $tpl->setVar(array(
				       'VALUE_FINDING_TEXT' => $finding_to_change->fText,
				       'FINDING_ID' =>$finding_to_change->fId,
				       )
				 );
		    
		    if($finding_to_change->fPositive == 'Y')
		      $tpl->setVar('POSITIVE_CHECKED', "checked=\"checked\"");
		  }
		
		
		if(!empty($project->heurSetId))
		  {
		    $heur_set = & new HeuristicSet($project->heurSetId, $this->dbi_);
		    $heur_set_data = $heur_set->getHeuristicSet();
		    $heuristics = $heur_set_data['heuristics'];
		    
		    $tpl->setCurrentBlock('heuristic_drop_down_block');
		    
		    $tpl->setVar(array(
				       'LABEL_HEURISTIC' => $this->getLabelText('LABEL_HEURISTIC'),
				       'SELECT_NAME_HEURISTIC' => 'data[heurId]',
				       'LABEL_HEURISTIC_HELP' => $this->getLabelText('LABEL_HEURISTIC_HELP'),
				       'HEURISTIC_HELP_URL' => 'heuristicset_mgr/run.HeuristicSetManager.php?heuristicHelp=1&sid='.$project->heurSetId,
				       )
				 );
		    
		    //		    for($i=0; $i < sizeof($heuristics); $i++)
		      foreach($heuristics as $heur_key => $current_heuristic)
		      {
			$tpl->setCurrentBlock('heuristic_drop_down_option_block');
			$tpl->setVar(array(
					   'OPTION_VALUE_HEURISTIC' => $heuristics[$heur_key]['hId'],
					   'OPTION_TEXT_HEURISTIC' => $translation->getTranslation($heuristics[$heur_key]['title_translation']['trans_id'], $this->language_),
					   )
				     );
			if(!is_null($finding_id) && $finding_to_change->heurId == $heuristics[$heur_key]['hId'])
			  $tpl->setVar('HEURISTIC_SELECTED', "selected=\"selected\"");
			
			$tpl->parseCurrentBlock();
		      }
		    $tpl->parseCurrentBlock();
		  }
		
		if(!is_null($finding_id))
		  {
		    $dummy_screenshot = & new Screenshot(0, $this->dbi_);
		    $screenshot_ids = $dummy_screenshot->getScreenshotIds($finding_to_change->fId);
		    
		    if(empty($screenshot_ids['annotated']))
		      {
			$tpl->setCurrentBlock('annotated_screenshot_upload_block');
			$tpl->setVar('LABEL_ANNOTATED_SCREENSHOT', $this->getLabelText('LABEL_ANNOTATED_SCREENSHOT'));
			$tpl->parseCurrentBlock();
		      }
		    
		    if(empty($screenshot_ids['fullsize']))
		      {
			$tpl->setCurrentBlock('fullsize_screenshot_upload_block');
			$tpl->setVar('LABEL_FULLSIZE_SCREENSHOT', $this->getLabelText('LABEL_FULLSIZE_SCREENSHOT'));
			$tpl->parseCurrentBlock();
		      }
		    
		    if(!empty($screenshot_ids['annotated']))
		      {
			$tpl->setCurrentBlock('annotated_screenshot_block');
			$tpl->setVar(array(
					   'LABEL_ANNOTATED_SCREENSHOT' => $this->getLabelText('LABEL_ANNOTATED_SCREENSHOT'),
					   'ANNOTATED_SCREENSHOT_URL' => 'run.displayImage.php?tn=1&bid='.$screenshot_ids['annotated'],
					   'ANNOTATED_SCREENSHOT_FULLSIZE_URL' => 'run.displayImage.php?bid='.$screenshot_ids['annotated'],
					   'LABEL_DELETE_ANNOTATED_SCREENSHOT' => $this->getLabelText('LABEL_DELETE_ANNOTATED_SCREENSHOT'),
					   'ANNOTATED_ID' =>$screenshot_ids['annotated'],
					   )
				     );
			if($FULLSIZE_IMAGE_POPUP)
			  $tpl->setVar('TARGET', "target=\"_blank\"");
			$tpl->parseCurrentBlock();
		      }
		    
		    if(!empty($screenshot_ids['fullsize']))
		      {
			$tpl->setCurrentBlock('fullsize_screenshot_block');
			$tpl->setVar(array(
					   'LABEL_FULLSIZE_SCREENSHOT' => $this->getLabelText('LABEL_FULLSIZE_SCREENSHOT'),
					   'FULLSIZE_SCREENSHOT_URL' => 'run.displayImage.php?tn=1&bid='.$screenshot_ids['fullsize'],
					   'FULLSIZE_SCREENSHOT_FULLSIZE_URL' => 'run.displayImage.php?bid='.$screenshot_ids['fullsize'],
					   'LABEL_DELETE_FULLSIZE_SCREENSHOT' => $this->getLabelText('LABEL_DELETE_FULLSIZE_SCREENSHOT'),
					   'FULLSIZE_ID' =>$screenshot_ids['fullsize'],
					   )
				     );
			if($FULLSIZE_IMAGE_POPUP)
			  $tpl->setVar('TARGET', "target=\"_blank\"");
			$tpl->parseCurrentBlock();
		      }
		  }
		else
		  {
		    $tpl->setCurrentBlock('annotated_screenshot_upload_block');
		    $tpl->setVar('LABEL_ANNOTATED_SCREENSHOT', $this->getLabelText('LABEL_ANNOTATED_SCREENSHOT'));
		    $tpl->parseCurrentBlock();
		    
		    $tpl->setCurrentBlock('fullsize_screenshot_upload_block');
		    $tpl->setVar('LABEL_FULLSIZE_SCREENSHOT', $this->getLabelText('LABEL_FULLSIZE_SCREENSHOT'));
		    $tpl->parseCurrentBlock();
		  }


		if(!is_null($finding_id))
		  $tpl->setvar('SUBMIT_BUTTON', $this->getLabelText('CHANGE_BUTTON'));
		else
		  $tpl->setVar('SUBMIT_BUTTON', $this->getLabelText('SUBMIT_BUTTON'));
		
		$tpl->setVar(array(
				   'CANCEL_BUTTON' => $this->getLabelText('CANCEL_BUTTON'),
				   'PROJECT_ID' => $project_id,
				   'INSERT_AFTER' =>$insert_after,
				   )
			     );
		  

		// Return url given, dont ask what to do afterwards
		if(!is_null($url))
		  {
		    $tpl->setVar('RETURN_URL', $url);
		  }
		// no return url given, ask what user wnats to do after add/change op.
		else
		  {
		    $tpl->setCurrentBlock('after_action_block');
		    if(!is_null($finding_id))
		      {
			$tpl->setVar('LABEL_AFTERSUBMIT_ACTION', $this->getLabelText('LABEL_AFTERCHANGE_ACTION'));
		      }
		    else
		      {
			$tpl->setVar('LABEL_AFTERSUBMIT_ACTION', $this->getLabelText('LABEL_AFTERSUBMIT_ACTION'));
		      }

		    $tpl->setVar(array(
				       'LABEL_ADD_ANOTHER_FINDING' => $this->getLabelText('LABEL_ADD_ANOTHER_FINDING'),
				       'LABEL_DISCONTINUE_EVALUATION' => $this->getLabelText('LABEL_DISCONTINUE_EVALUATION'),
				       )
				 );
		    $tpl->parseCurrentBlock();
		    
		  }
	      }
	    else
	      {
		$this->addSessionMessage('DONT_EVALUATE_THIS_PROJECT');
	      }
	  }
	else
	  {
	    $this->addSessionMessage('NOT_ASSIGNED_TO_PROJECT');
	  }
	
      }
    else
      {
	$this->addSessionMessage('NO_PROJECT_GIVEN');
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


    if(!is_null($finding_id))
      {
	$tpl->setVar('ADD_FINDING_TITLE', $this->getLabelText('CHANGE_FINDING_TITLE'));
	$this->app_name_ = $this->getLabelText('CHANGE_FINDING_TITLE');
	$this->app_breadcrumbs_[] = Array(
					  'label' => $this->getLabelText('CHANGE_FINDING_TITLE'),
					  );    
      }
    else
      {
	$tpl->setVar('ADD_FINDING_TITLE', $this->getLabelText('ADD_FINDING_TITLE'));
	$this->app_name_ = $this->getLabelText('ADD_FINDING_TITLE');
	$this->app_breadcrumbs_[] = Array(
					  'label' => $this->getLabelText('ADD_FINDING_TITLE'),
					  );   
      }

    $tpl->setVar(array(
		       //		       'EVALUATION_TITLE' => $this->getLabelText('EVALUATION_TITLE'),
		       'MESSAGES' => $message_text,
		       'FORM_ACTION' => $PHP_SELF,
		       )
		 );
    
    return TRUE;
  }


  function doChangeFinding()
  {
    $this->debug("doChangeFinding()");
    $data = $this->getPostRequestField('data', null);
    $finding_id = $this->getPostRequestField('fid', null);
    
    if(isset($data['is_positive']) && $data['is_positive'] == 'on')
      $data['fPositive'] = 'Y';
    else
      $data['fPositive'] = 'N';

    $finding = & new Finding($finding_id, $this->dbi_);


    // We are changing, change the edited stamp
    if($finding->init_ok_)
      {
	$data['fLastEditedTimestamp'] = date('YmdHms',time());
	$data['fId'] = $finding_id;
	$finding->updateData($data);
      }
    // We are adding
    else
      {
	$data['fId'] = $this->dbi_->getUniqueId();
	$data['fTimestamp'] = date('YmdHms',time());
	$data['fLastEditedTimestamp'] = '00000000000000';
	$data['uId'] = $this->user_->user_id_;
	$data['fManagerFinding'] = 'N';

	if(!isset($data['heurId']))
	  $data['heurId'] = '';

	//	$this->dumpArray($data);
	$finding->addFinding($data);

	//	$finding->addData($data);
      }
    

    $util = & new Util();
    $screenshot = & new Screenshot(0, $this->dbi_, $util);

    //    $this->dumpArray($_FILES);

    if(isset($_FILES['annotated_screenshot']) && empty($_FILES['annotated_screenshot']['error']))
      {
	$annotated_screenshot_data['sId'] = $this->dbi_->getUniqueId();
	$annotated_screenshot_data['fId'] = $data['fId'];
	$annotated_screenshot_data['sKind'] = 'annotated';
	$screenshot->addImage($annotated_screenshot_data, $_FILES['annotated_screenshot']);
      }
    if(isset($_FILES['fullsize_screenshot']) && empty($_FILES['fullsize_screenshot']['error']))
      {
	$fullsize_screenshot_data['sId'] = $this->dbi_->getUniqueId();
	$fullsize_screenshot_data['fId'] = $data['fId'];
	$fullsize_screenshot_data['sKind'] = 'fullsize';
	$screenshot->addImage($fullsize_screenshot_data, $_FILES['fullsize_screenshot']);
      }

    $delete_annotated = $this->getPostRequestField('deleteAnnotatedScreenshot', null);
    $delete_fullsize = $this->getPostRequestField('deleteFullsizeScreenshot', null);

    if(!is_null($delete_annotated))
      {
	$annotated_id = $this->getPostRequestField('annotated_id', null);
	$screenshot = & new Screenshot($annotated_id, $this->dbi_);
	if($screenshot->init_ok_)
	  $screenshot->deleteImage();
      }
    if(!is_null($delete_fullsize))
      {
	$fullsize_id = $this->getPostRequestField('fullsize_id', null);
	$screenshot = & new Screenshot($fullsize_id, $this->dbi_);
	if($screenshot->init_ok_)
	  $screenshot->deleteImage();
      }
  }



  function projectOverview()
  {
    global $PROJECT_OVERVIEW_TEMPLATE;

    $this->showScreen($PROJECT_OVERVIEW_TEMPLATE, 'displayProjectOverview', $this->getAppName());
  }


  function displayProjectOverview(& $tpl)
  {
    global $PHP_SELF;

    $translation = & new Translation(0, $this->dbi_);

    $dummy_project = & new Project(0, $this->dbi_);
    $project_ids = $dummy_project->getProjectIdsForUser($this->user_->user_id_);

    $dummy_finding = & new Finding(0, $this->dbi_);

    $row=0;
    for($i=0; $i < sizeof($project_ids); $i++)
      {
	$current_project =& new Project($project_ids[$i], $this->dbi_);
	if($current_project->getProjectPhase() == '1')
	  {

	    /*	    $this->debug("Project $project_ids[$i]");
	    $this->debugArray($dummy_finding->getAllFindingIds($current_project->pId, $this->user_->user_id_));
	    $this->debug(sizeof($dummy_finding->getAllFindingIds($current_project->pId, $this->user_->user_id_)));
	    $this->debug("________");*/

	    //	    $project_findings = $dummy_finding->getAllFindingIds($current_project->pId, $this->user_->user_id_);

	    //	    if(!empty($project_findings))
	    //	      {
	    //		$tpl->setCurrentBlock('finding_overview_block');
		$tpl->setVar(array(
				   'FINDING_OVERVIEW_URL' => $PHP_SELF."?cmd=viewFindings&pid=$current_project->pId",
				   'LABEL_FINDING_OVERVIEW' => $this->getLabelText('LABEL_FINDING_OVERVIEW'),
				   )
			     );
		//		$tpl->parseCurrentBlock();
		//	      }
	    $tpl->setCurrentBlock('project_block');
	    $tpl->setVar(array(
			       'BG_CLASS' => ($row%2?'odd':'even'),
			       'PROJECT_TITLE' => $translation->getTranslation($current_project->pNameId, $this->language_),
			       'PROJECT_DESCRIPTION' => $translation->getTranslation($current_project->pDescriptionId, $this->language_),
			       'EVALUATE_URL' => $PHP_SELF."?cmd=addFinding&pid=$current_project->pId",
			       'LABEL_EVALUATE' => $this->getLabelText('LABEL_EVALUATE'),
			       )
			 );
	    $tpl->parseCurrentBlock();
	    $row++;
	  }
	
      }


    $tpl->setVar(array(
		       'PROJECTS_TITLE' => $this->getLabelText('PROJECTS_TITLE'),
		       'LABEL_TITLE' => $this->getLabelText('LABEL_TITLE'),
		       )
		 );

    return TRUE;
  }


  function findingOverview()
  {
    global $FINDING_OVERVIEW_TEMPLATE;

    $this->showScreen($FINDING_OVERVIEW_TEMPLATE, 'displayFindingOverview', $this->getAppName());
  }


  function displayFindingOverview(& $tpl)
  {
    global $PHP_SELF, $REL_APP_ROOT, $FULLSIZE_IMAGE_POPUP, $TABLE_HEADING_COLOR, $TABLE_ROW_COLOR_1, $TABLE_ROW_COLOR_2;

	$this->app_name_ = $this->getLabelText('LIST_OF_FINDINGS_TITLE');

    $project_id = $this->getGetRequestField('pid', null);
    $dummy_finding = & new Finding(0, $this->dbi_);
    $translation = & new Translation(0, $this->dbi_);

    if(!is_null($project_id))
      {
	$project = & new Project($project_id, $this->dbi_);
	$tpl->setVar('PROJECT_TITLE', $translation->getTranslation($project->pNameId, $this->language_));
	
	$users_project_finding_ids = $dummy_finding->getAllFindingIds($project_id, $this->user_->user_id_, 'fOrder', 'ASC', null, 'N', null);

	if(!empty($users_project_finding_ids))
	  {
	    $tpl->setVar(array(
			       'LABEL_FINDING_TEXT' => $this->getLabelText('LABEL_FINDING_TEXT'),
			       'LABEL_POSITIVE' => $this->getLabelText('LABEL_POSITIVE'),
			       'LABEL_FINDING_OPERATIONS' => $this->getLabelText('LABEL_FINDING_OPERATIONS'),
			       'LABEL_ANNOTATED_SCREENSHOT' => $this->getLabelText('LABEL_ANNOTATED_SCREENSHOT'),
			       'LABEL_FULLSIZE_SCREENSHOT' => $this->getLabelText('LABEL_FULLSIZE_SCREENSHOT'),
			       'TABLE_HEADING_COLOR' => $TABLE_HEADING_COLOR,
			       )
			 );

	    if($project->heurSetId)
	      {
		$tpl->setCurrentBlock('heuristic_title_block');
		$tpl->setVar(array(
				   'LABEL_HEURISTIC' => $this->getLabelText('LABEL_HEURISTIC'),
				   )
			     );
		$tpl->parseCurrentBlock();
	      }
	  }	
	
	if($users_project_finding_ids)
	  {
	    for($i= 0; $i < sizeof($users_project_finding_ids); $i++)
	      {
		$current_finding = & new Finding($users_project_finding_ids[$i], $this->dbi_);
		
		if(!empty($current_finding->heurId))
		  {
		    $heuristic = & new Heuristic($current_finding->heurId, $this->dbi_);
		    //		$this->dumpArray($heuristic->data_array_);
		    $tpl->setCurrentBlock('heuristic_block');
		    $tpl->setVar(array(
				       'FINDING_HEURISTIC' => $translation->getTranslation($heuristic->hTitleId, $this->language_),
				       )
				 );
		    
		    $tpl->parseCurrentBlock();
		  }
		
		
		$dummy_screenshot = & new Screenshot(0, $this->dbi_);
		$screenshot_ids = $dummy_screenshot->getScreenshotIds($current_finding->fId);
		/*
		 if(empty($screenshot_ids['annotated']) && empty($screenshot_ids['fullsize']))
		 {
		 $tpl->setCurrentBlock('no_screenshot_block');
		 $tpl->setVar('ADD_SCREENSHOTS' , $this->getMessageText('ADD_SCREENSHOTS'));
		 $tpl->parseCurrentBlock();
		 }
		*/
		if(!empty($screenshot_ids['annotated']))
		  {
		    $tpl->setCurrentBlock('annotated_screenshot_block');
		    $tpl->setVar(array(
				       'ANNOTATED_SCREENSHOT_URL' => 'run.displayImage.php?tn=1&bid='.$screenshot_ids['annotated'],
				       'ANNOTATED_SCREENSHOT_FULLSIZE_URL' => 'run.displayImage.php?bid='.$screenshot_ids['annotated'],
				       )
				 );
		    if($FULLSIZE_IMAGE_POPUP)
		      $tpl->setVar('TARGET', "target=\"_blank\"");
		    $tpl->parseCurrentBlock();
		  }
		
		if(!empty($screenshot_ids['fullsize']))
		  {
		    $tpl->setCurrentBlock('fullsize_screenshot_block');
		    $tpl->setVar(array(
				       'FULLSIZE_SCREENSHOT_URL' => 'run.displayImage.php?tn=1&bid='.$screenshot_ids['fullsize'],
				       'FULLSIZE_SCREENSHOT_FULLSIZE_URL' => 'run.displayImage.php?bid='.$screenshot_ids['fullsize'],
				       )
				 );
		    if($FULLSIZE_IMAGE_POPUP)
		      $tpl->setVar('TARGET', "target=\"_blank\"");
		    $tpl->parseCurrentBlock();
		  }
		
		if($current_finding->getPredecessorFindingId($current_finding->fId))
		  {
		    $tpl->setCurrentBlock('up_block');
		    $tpl->setVar(array(
				       'UP_URL' => $PHP_SELF."?cmd=moveUp&fid=$current_finding->fId&url=".urlencode($_SERVER['REQUEST_URI']),
				       'LABEL_UP' =>$this->getLabelText('LABEL_UP'),
				       )
				 );				   
		    $tpl->parseCurrentBlock();
		  }
		if($current_finding->getSuccessorFindingId($current_finding->fId))
		  {
		    $tpl->setCurrentBlock('down_block');
		    $tpl->setVar(array(
				       'DOWN_URL' => $PHP_SELF."?cmd=moveDown&fid=$current_finding->fId&url=".urlencode($_SERVER['REQUEST_URI']),
				       'LABEL_DOWN' =>$this->getLabelText('LABEL_DOWN'),
				       )
				 );				   
		    $tpl->parseCurrentBlock();
		  }
		
		if($current_finding->fPositive == 'Y')
		  $positive_string = $this->getLabelText('LABEL_IS_POSITIVE');
		else
		  $positive_string = $this->getLabelText('LABEL_IS_NEGATIVE');
		
		$tpl->setCurrentBlock('finding_block');
		$tpl->setVar(array(
				   'BG_CLASS' => ($i%2?'odd':'even'),
				   'FINDING_TITLE' => $current_finding->fText,
				   'IS_POSITIVE' => $positive_string,
				   'EDIT_URL' => $PHP_SELF."?cmd=editFinding&fid=$current_finding->fId&url=".urlencode($_SERVER['REQUEST_URI']),
				   'LABEL_EDIT' => $this->getLabelText('LABEL_EDIT'),
				   'DELETE_URL' => $PHP_SELF."?cmd=deleteFinding&fid=$current_finding->fId&url=".urlencode($_SERVER['REQUEST_URI']),
				   'LABEL_DELETE' => $this->getLabelText('LABEL_DELETE'),
				   )
			     );
		$tpl->setVar(array(
				   'LABEL_INSERT' => $this->getLabelText('LABEL_INSERT'),
				   'INSERT_URL' => $PHP_SELF."?cmd=addFinding&pid=$project_id&after=$current_finding->fId",
				   )
			     );
		
		$tpl->parseCurrentBlock();
	      }
	  }
	else
	  {
	    $tpl->setCurrentBlock('no_finding_block');
	    $tpl->setVar(array(
			       'NO_FINDINGS_MESSAGE' => $this->getMessageText('NO_FINDINGS_MESSAGE'),
			       'LABEL_ADD_FINDING' => $this->getLabelText('LABEL_ADD_FINDING'),
			       'ADD_FINDING_URL' => $PHP_SELF."?cmd=addFinding&pid=$project_id",
			       )
			 );
	    $tpl->parseCurrentBlock();
	  }
	$this->app_breadcrumbs_[] = Array(
					  'label' => $translation->getTranslation($project->pNameId, $this->language_),
					  );    
      }

    $tpl->setVar(array(
		       'FINDINGS_TITLE' => $this->getLabelText('FINDINGS_TITLE'),
		       )
		 );
    
    return TRUE;
  }



  function doDeleteFinding()
  {
    $this->debug("doDeleteFinding() called");
    $id = $this->getPostRequestField('id', null);

    if(!is_null($id))
      {
	$finding_to_delete = & new Finding($id, $this->dbi_);
	$dummy_screenshot = & new Screenshot(0, $this->dbi_);
	
	$screenshots = $dummy_screenshot->getScreenshotIds($id);
	if(!empty($screenshots))
	  {
	    $ann_screenshot = & new Screenshot($screenshots['annotated'], $this->dbi_); 
	    $ful_screenshot = & new Screenshot($screenshots['fullsize'], $this->dbi_); 

	    if($ann_screenshot->init_ok_)
	      $ann_screenshot->deleteImage();
	    if($ful_screenshot->init_ok_)
	      $ful_screenshot->deleteImage();
	  }
	
	if($finding_to_delete->init_ok_)
	  {
	    $finding_order = $finding_to_delete->fOrder;
	    //	    $finding_to_delete->deleteData();
	    $finding_to_delete->deleteFinding();
	    $this->addSessionMessage('DELETED');	    
	  }
	//	$this->dumpArray($screenshots);
      }
    else
      $this->addSessionMessage('NOT_DELETED');
    //    $this->dumpArray($_REQUEST);
    
  }


  function moveUpFinding()
  {
    $fid = $this->getGetRequestField('fid', null);

    if(!is_null($fid))
      {
	$this->debug($fid);
	$finding = & new Finding($fid, $this->dbi_);
	$finding->swapFindings($finding->fId, $finding->getPredecessorFindingId($finding->fId));
      }
  }



  function moveDownFinding()
  {

    $fid = $this->getGetRequestField('fid', null);

    if(!is_null($fid))
      {
	$this->debug($fid);
	$finding = & new Finding($fid, $this->dbi_);
	$finding->swapFindings($finding->fId, $finding->getSuccessorFindingId($finding->fId));
       }
  }


  function getConfirmation()
    {
      global $CONFIRMATION_TEMPLATE;
      
      $this->showScreen($CONFIRMATION_TEMPLATE, 'displayConfirmationForm', $this->getAppName());
    }

  function displayConfirmationForm(& $tpl)
    {
      $cmd = $this->getGetRequestField('cmd', null);
      $finding_id = $this->getGetRequestField('fid', null);
      $url = $this->getGetRequestField('url', null);
      
      if(!is_null($cmd) && $cmd == 'deleteFinding')
	{
	  $tpl->setVar('CONFIRM_MESSAGE', $this->getMessageText('CONFIRM_MESSAGE'));
	  
	  if(!is_null($finding_id))
	    {
	      $finding_to_delete = & new Finding($finding_id, $this->dbi_);
	      $this->app_breadcrumbs_[] = Array(
						'label' => $this->getLabelText('CONFIRM_TITLE'),
						);    
	      $tpl->setVar(array(
				 'TITLE' => $finding_to_delete->fText,
				 'ID' => $finding_id,
				 'RETURN_URL' => $url,
				 )
			   );
	    }
	  $tpl->setVar(array(
			     'LABEL_YES' => $this->getLabelText('LABEL_YES'),
			     'LABEL_NO' => $this->getLabelText('LABEL_NO')
			     )
		       );
	}

      $tpl->setVar(array(
			 'FORM_ACTION' => $_SERVER['PHP_SELF'],
			 'CONFIRM_TITLE' => $this->getLabelText('CONFIRM_TITLE')
			 )
		   );
      
      return TRUE;
    }


  function doRedirect()
  {
    $url = $this->getGetRequestField('url', null);

    if(!is_null($url))
      {
	header("Location: $url");
      }
    
  }


}




?>
