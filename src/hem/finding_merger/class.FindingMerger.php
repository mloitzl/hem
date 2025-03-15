<?php

class FindingMerger extends PHPApplication
{
  
  function run()
    {
      //    global $globals;
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
      
      if($this->auth_handler_->checkRight(MERGE_FINDINGS))
	$this->findingMergerDriver();
      else
	{
	  $this->addSessionMessage('DO_NOT_MERGE_FINDINGS');
	  $this->redirectToHomeApp();
	}
      
    }
  
  function findingMergerDriver()
  {
    global $PHP_SELF;
    
    $cmd = $this->getGetRequestField('cmd', null);

    $form = $this->getPostRequestField('form_id', null);

    $answer = $this->getPostRequestField('Yes', null);

    // Action to take after adding the finding
    $action = $this->getPostRequestField('action', null);
    
    $data = $this->getPostRequestField('data', null);
    //    $project_id = $data['pId'];

    $this->debug($action);

    switch ($form)
      {
      case 'merge_finding':
	$this->doMergeFinding();
	$this->doRedirect();
	break;
      case 'choose_screenshot':
	$this->doChooseScreenshot();
	$this->doRedirect();
	break;
      case 'confirm':
	if(!is_null($answer))
	  {
	    $this->doDeleteFinding();
	    $this->doRedirect();
	  }
	break;
      default:
	break;
      }

    switch ($cmd)
      {
      case 'merge':
	$this->mergeScreen();
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
      case'chooseScreenshot':
	$this->chooseScreenshot();
	break;
      default:
	$this->projectOverview();
	break;
      }
  }

  function doChooseScreenshot()
  {
    //    $this->dumpArray($_REQUEST);

    $annotated_id = $this->getPostRequestField('annotated', null);
    $fullsize_id = $this->getPostRequestField('fullsize', null);
    $finding_id = $this->getPostRequestField('fid', null);

    if(!is_null($finding_id))
      {
	if(!is_null($annotated_id))
	  {
	    $annotated_screenshot = & new Screenshot($annotated_id, $this->dbi_);
	    $annotated_data = $annotated_screenshot->data_array_;
	    $annotated_data['fId'] = $finding_id;
	    $annotated_data['sId'] = $this->getUniqueId();
	    //	    $this->dumpArray($annotated_data);
	    $annotated_screenshot->addData($annotated_data);
	  }
	if(!is_null($fullsize_id))
	  {
	    $fullsize_screenshot = & new Screenshot($fullsize_id, $this->dbi_);
	    $fullsize_data = $fullsize_screenshot->data_array_;
	    $fullsize_data['fId'] = $finding_id;
	    $fullsize_data['sId'] = $this->getUniqueId();
	    //	    $this->dumpArray($fullsize_data);
	    $fullsize_screenshot->addData($fullsize_data);
	  }
      }

  }


  function chooseScreenshot()
  {
    $this->debug("Choose Screenshot Driver");
    global $CHOOSE_SCREENSHOT_TEMPLATE;
    $this->showScreen($CHOOSE_SCREENSHOT_TEMPLATE, 'displayChooseScreenshot', $this->getAppName());
  }

  function displayChooseScreenshot(& $tpl)
  {
    global $PHP_SELF, $FULLSIZE_IMAGE_POPUP;
    
    $finding_id = $this->getGetRequestField('fId', null);
    $finding_object = & new Finding($finding_id, $this->dbi_);

    if($finding_object->init_ok_)
      {
	$translation = & new Translation(0, $this->dbi_);
	$project_object = & new Project($finding_object->pId, $this->dbi_);
	
	$this->app_breadcrumbs_[] = Array(
					  'url' => $PHP_SELF.'?cmd=merge&pid='.$finding_object->pId,
					  'label' => $translation->getTranslation($project_object->pNameId, $this->language_),
					  );

      }

    $associated_evaluator_finding_ids = $finding_object->getAttachedFindingIds($finding_object->fId);

    if(is_array($associated_evaluator_finding_ids) && !empty($associated_evaluator_finding_ids))
      {
	$i = 0;
	while($current_associated_finding_id = array_pop($associated_evaluator_finding_ids))
	  {
	    $current_associated_finding_id = & new Finding($current_associated_finding_id, $this->dbi_);
	    
	    $current_finding_user = & new User($current_associated_finding_id->uId, $this->dbi_);

	    $dummy_screenshot = & new Screenshot(0, $this->dbi_);
	    $screenshot_ids = $dummy_screenshot->getScreenshotIds($current_associated_finding_id->fId);

	    $tpl->setVar('BG_CLASS', ($i%2?'odd':'even'));
	    $i++;

	    if(!empty($screenshot_ids['annotated']))	    
	      {
		$tpl->setCurrentBlock('annotated_screenshot_block');
		$tpl->setVar(array(
				   'ANNOTATED_SCREENSHOT_URL' => 'run.displayImage.php?tn=1&bid='.$screenshot_ids['annotated'],
				   'ANNOTATED_SCREENSHOT_FULLSIZE_URL' => 'run.displayImage.php?bid='.$screenshot_ids['annotated'],
				   'ANNOTATED_ID' =>$screenshot_ids['annotated'],
				   )
			     );
		if($FULLSIZE_IMAGE_POPUP)
		  $tpl->setVar('ANNOTATED_TARGET', "target=\"_blank\"");
		$tpl->parseCurrentBlock();
	      }
	    else
	      {
		$tpl->setCurrentBlock('no_annotated_screenshot_block');
		$tpl->setVar(array(
				   'NO_ANNOTATED_SCREENSHOT_SUBMITTED' => $this->getMessageText('NO_ANNOTATED_SCREENSHOT_SUBMITTED'),
				   )
			     );
		$tpl->parseCurrentBlock();

	      }
	    if(!empty($screenshot_ids['fullsize']))
	      {
		$tpl->setCurrentBlock('fullsize_screenshot_block');
		$tpl->setVar(array(
				   'FULLSIZE_SCREENSHOT_URL' => 'run.displayImage.php?tn=1&bid='.$screenshot_ids['fullsize'],
				   'FULLSIZE_SCREENSHOT_FULLSIZE_URL' => 'run.displayImage.php?bid='.$screenshot_ids['fullsize'],
				   'FULLSIZE_ID' =>$screenshot_ids['fullsize'],
				   )
			     );
		if($FULLSIZE_IMAGE_POPUP)
		  $tpl->setVar('FULLSIZE_TARGET', "target=\"_blank\"");
		$tpl->parseCurrentBlock();
	      }
	    else
	      {
		$tpl->setCurrentBlock('no_fullsize_screenshot_block');
		$tpl->setVar(array(
				   'NO_FULLSIZE_SCREENSHOT_SUBMITTED' => $this->getMessageText('NO_FULLSIZE_SCREENSHOT_SUBMITTED'),
				   )
			     );
		$tpl->parseCurrentBlock();

	      }	    

	    $tpl->setCurrentBlock('screenshot_row_block');
	    
	    $tpl->setVar(array(
			       'FIRST_NAME' =>$current_finding_user->first_name,
			       'LAST_NAME' =>$current_finding_user->last_name,
			       )
			 );
	    $tpl->parseCurrentBlock();
	  }
	$tpl->setVar(array(
			   'LABEL_FULLSIZE_SCREENSHOTS'=> $this->getLabelText('LABEL_FULLSIZE_SCREENSHOTS'),
			   'LABEL_ANNOTATED_SCREENSHOTS'=> $this->getLabelText('LABEL_ANNOTATED_SCREENSHOTS'),
			   )
		     );
      }
    else
      {
	$this->addSessionMessage('NO_ASSOCIATED_FINDINGS');
      }
    


    
    $tpl->setVar(array(
		       'FINDING_ID' => $finding_id,
		       'RETURN_URL' => $this->getGetRequestField('url', null),
		       'CHOOSE_SCREENSHOT_BUTTON' => $this->getLabelText('CHOOSE_SCREENSHOT_BUTTON'),
		       )
		 );

    
    $tpl->setCurrentBlock('main_block');
    
    $message_text = '';
    if($this->hasSessionMessages() == TRUE)
      {
    	$messages = $this->getAllSessionMessages();
    	while($msg = array_pop($messages))
    	  {
    	    $message_text.=$msg;
    	  }
      }

    $this->app_name_ .= " : " . $this->getLabelText('CHOOSE_SCREENSHOT_TITLE');
    $this->app_breadcrumbs_[] = Array(
				      'url' => null,
				      'label' => $this->getLabelText('CHOOSE_SCREENSHOT_TITLE'),
				      );

    //    $this->dumpArray($this->app_breadcrumbs_);

    $tpl->setVar(array(
		       'CHOOSE_SCREENSHOT_TITLE' => $this->getLabelText('CHOOSE_SCREENSHOT_TITLE'),
    		       'MESSAGES' => $message_text,
		       'FORM_ACTION' => $PHP_SELF,
		       'FORM_METHOD' => 'post',
    		       )
    		 );
    $tpl->parseCurrentBlock();
    
    return TRUE;
  }
  

  function doMergeFinding()
  {
    $data = $this->getPostRequestField('data', null);

    $attached_findings = $this->getPostRequestField('findings', null);

    $detach_screenshots = $this->getPostRequestField('detach', null);

    //    $this->dumpArray($_REQUEST);

    if(!is_null($data))
      {
	$finding_to_change = & new Finding($data['fId'], $this->dbi_);

	// detach Screenshots if checked
	if(!is_null($detach_screenshots) && $detach_screenshots == 'yes')
	  {
	    $dummy_screenshot = & new Screenshot(0, $this->dbi_);
	    $screenshot_ids = $dummy_screenshot->getScreenshotIds($finding_to_change->fId);	    
	    if(!empty($screenshot_ids['annotated']))
	      {
		$screenshot = & new Screenshot($screenshot_ids['annotated'], $this->dbi_);
		$screenshot->deleteData();
	      }
	    if(!empty($screenshot_ids['fullsize']))
	      {
		$screenshot = & new Screenshot($screenshot_ids['fullsize'], $this->dbi_);
		$screenshot->deleteData();
	      }
	  }

	if(isset($data['is_positive']) && $data['is_positive'] == 'on')
	  $data['fPositive'] = 'Y';
	else
	  $data['fPositive'] = 'N';
	
	if($finding_to_change->init_ok_)
	  {
	    // TODO: We are changing --> check later
	    $data['fLastEditedTimestamp'] = date('YmdHms',time());

	    $finding_to_change->updateData($data);
	    $finding_to_change->detachAllFindingsFromManagerFinding($data['fId']);
	    if(is_array($attached_findings))
	      $finding_to_change->attachFindingsToManagerFinding($data['fId'], array_keys($attached_findings));
	  }
	else
	  {
	    $data['fId'] = $this->getUniqueId();
	    $data['fManagerFinding'] = 'Y';
	    $data['uId'] = $this->user_->auth_user_id;
	    $data['fTimestamp'] = date('YmdHms',time());
	    $data['fLastEditedTimestamp'] = date('YmdHms',time());

	    $finding_to_change->addFinding($data);
	    if(!is_null($attached_findings))
	      $finding_to_change->attachFindingsToManagerFinding($data['fId'], array_keys($attached_findings));
	  }
      }
  }
  
  function mergeScreen()
  {
    $this->debug("Merge Findings Driver");
    global $MERGE_TEMPLATE;
    $this->showScreen($MERGE_TEMPLATE, 'displayMergeScreen', $this->getAppName());
  }

  function displayMergeScreen(& $tpl)
  {
    global $PHP_SELF, $FULLSIZE_IMAGE_POPUP, $TABLE_ROW_COLOR_1, $TABLE_ROW_COLOR_2, $TABLE_HEADING_COLOR;

    $project_id = $this->getGetRequestField('pid', null);
    $finding_id = $this->getGetRequestField('editFindingId', null);
    $show_screenshots = $this->getRequestField('HEM_screenshots', 0);

    $translation = & new Translation(0, $this->dbi_);

    $project = & new Project($project_id, $this->dbi_);
    if($project->init_ok_)
      {
	$this->app_name_ .= ": ".$translation->getTranslation($project->pNameId, $this->language_);

	$user_ids = $project->getAllUserIdsFromProject();

	// The Merge Finding Form
	$dummy_finding = & new Finding(0, $this->dbi_);

	// change this when changing is implemented
	$finding_to_change = & new Finding($finding_id, $this->dbi_);

	$tpl->setVar(array(
			   'FORM_METHOD' => 'post',
			   'FORM_ACTION' => $PHP_SELF,
			   'RETURN_URL' => urlencode($_SERVER['REQUEST_URI']),
			   )
		     );

	if(!is_null($finding_id))
	  {
	    $tpl->setVar(array(
			       'FINDING_ID' => $finding_to_change->fId,
			       'FINDING_TEXT' => $finding_to_change->fText,
			       'SUBMIT_BUTTON' => $this->getLabelText('CHANGE_BUTTON'),
			       )
			 );


	    $dummy_screenshot = & new Screenshot(0, $this->dbi_);
	    $screenshot_ids = $dummy_screenshot->getScreenshotIds($finding_to_change->fId);

	    if(!empty($screenshot_ids['annotated']) || !empty($screenshot_ids['fullsize']))
	      {
		$tpl->setVar(array(
				   'LABEL_DETACH_SCREENSHOTS' => $this->getLabelText('LABEL_DETACH_SCREENSHOTS'),
				   )
			     );

		if(!empty($screenshot_ids['annotated']))
		  {
		    $tpl->setCurrentBlock('manager_annotated_screenshot_block');
		    $tpl->setVar(array(
				       'DETACH_ANNOTATED_SCREENSHOT_URL' => 'run.displayImage.php?tn=1&bid='.$screenshot_ids['annotated'],
				       'DETACH_ANNOTATED_SCREENSHOT_FULLSIZE_URL' => 'run.displayImage.php?bid='.$screenshot_ids['annotated'],
				       )
				 );
		    if($FULLSIZE_IMAGE_POPUP)
		      $tpl->setVar('DETACH_TARGET', "target=\"_blank\"");
		    $tpl->parseCurrentBlock();
		  }
		
		if(!empty($screenshot_ids['fullsize']))
		  {
		    $tpl->setCurrentBlock('manager_fullsize_screenshot_block');
		    $tpl->setVar(array(
				       'DETACH_FULLSIZE_SCREENSHOT_URL' => 'run.displayImage.php?tn=1&bid='.$screenshot_ids['fullsize'],
				       'DETACH_FULLSIZE_SCREENSHOT_FULLSIZE_URL' => 'run.displayImage.php?bid='.$screenshot_ids['fullsize'],
				       )
				 );
		    if($FULLSIZE_IMAGE_POPUP)
		      $tpl->setVar('DETACH_TARGET', "target=\"_blank\"");
		    $tpl->parseCurrentBlock();
		  }
	      }
 	  }
	else
	  {
	    $tpl->setVar(array(
			       'SUBMIT_BUTTON' => $this->getLabelText('SUBMIT_BUTTON'),
			       )
			 );
	  }

	$tpl->setVar(array(
			   'LABEL_FINDING_DESCRIPTION' => $this->getLabelText('LABEL_FINDING_DESCRIPTION'),
			   'LABEL_POSITIVE' => $this->getLabelText('LABEL_POSITIVE'),
			   'PROJECT_ID' => $project->pId,
			   'INSERT_AFTER' => $this->getGetRequestField('after', null),
			   )
		     );

	if($finding_to_change->fPositive == 'Y')
	  $tpl->setVar('POSITIVE_CHECKED', "checked=\"checked\"");

	if(!empty($project->heurSetId))
	  {
	    $heur_set = & new HeuristicSet($project->heurSetId, $this->dbi_);
	    $heur_set_data = $heur_set->getHeuristicSet();
	    $heuristics = $heur_set_data['heuristics'];
	    //	    $heur_set = & new HeuristicSet($project->heurSetId, $this->dbi_);
	    //	    $heuristics = $heur_set->getAllHeuristics();
	    
	    //	    for($i=0; $i < sizeof($heuristics); $i++)
	    foreach($heuristics as $heur_key => $current_heuristic)
	      {
		$tpl->setCurrentBlock('heuristic_drop_down_option_block');
		$tpl->setVar(array(
				   'OPTION_VALUE_HEURISTIC' => $heuristics[$heur_key]['hId'],
				   'OPTION_TEXT_HEURISTIC' => $translation->getTranslation($heuristics[$heur_key]['title_translation']['trans_id'], $this->language_),
				   )
			     );
		if(isset($finding_id) && !is_null($finding_id) && $finding_to_change->heurId == $heuristics[$heur_key]['hId'])
		  $tpl->setVar('HEURISTIC_SELECTED', "selected=\"selected\"");
		
		$tpl->parseCurrentBlock();
	      }
	    $tpl->setCurrentBlock('heuristic_drop_down_block');
	    
	    $tpl->setVar(array(
			       'LABEL_HEURISTIC' => $this->getLabelText('LABEL_HEURISTIC'),
			       'LABEL_HEURISTIC_HELP' => $this->getLabelText('LABEL_HEURISTIC_HELP'),
			       'HEURISTIC_HELP_URL' => 'heuristicset_mgr/run.HeuristicSetManager.php?heuristicHelp=1&sid='.$project->heurSetId,

			       )
			 );
	    
	    $tpl->parseCurrentBlock();
	  }

	// Attached Finding Title
	$tpl->setVar(array(
			   'LABEL_ATTACHED_FINDINGS' => $this->getLabelText('LABEL_ATTACHED_FINDINGS'),
			   )
		     );
	

	// Display associated Findings
	if($finding_to_change->init_ok_)
	  {
	    $attached_finding_ids = $finding_to_change->getAttachedFindingIds($finding_to_change->fId);
	    
	    if(is_array($attached_finding_ids))
	      {
		foreach($attached_finding_ids as $current_finding_id)
		  {
		    $current_finding = & new Finding($current_finding_id, $this->dbi_);
		    
		    $tpl->setCurrentBlock('evaluator_hidden_field');
		    $tpl->setVar(array(
				       'EVALUATOR_FINDING_ID' =>$current_finding_id,
				       )
				 );
		    $tpl->parseCurrentBlock();
		  }
	      }
	  }
	

	// Display Manager Findings Tab
	//	$manager_finding_ids = $dummy_finding->getAllFindingIds($project_id, $this->user_->user_id_, 'fOrder', 'ASC', null, 'Y');
	$manager_finding_ids = $dummy_finding->getAllFindingIds($project_id, null, 'fOrder', 'ASC', null, 'Y');


	if(!empty($manager_finding_ids))
	  {

	    // Table Headers
	    $tpl->setVar(array(
			       'LABEL_FINDING_TEXT' => $this->getLabelText('LABEL_FINDING_TEXT'),
			       'LABEL_MANAGER_POSITIVE' => $this->getLabelText('LABEL_MANAGER_POSITIVE'),
			       'LABEL_FINDING_OPERATIONS' => $this->getLabelText('LABEL_FINDING_OPERATIONS'),
			       'LABEL_ANNOTATED_SCREENSHOT' => $this->getLabelText('LABEL_ANNOTATED_SCREENSHOT'),
			       'LABEL_FULLSIZE_SCREENSHOT' => $this->getLabelText('LABEL_FULLSIZE_SCREENSHOT'),
			       'TABLE_HEADING_COLOR' => $TABLE_HEADING_COLOR,
			       )
			 );

	    if($project->heurSetId)
	      {
		$tpl->setCurrentBlock('manager_heuristic_title_block');
		$tpl->setVar(array(
				   'LABEL_HEURISTIC' => $this->getLabelText('LABEL_HEURISTIC'),
				   )
			     );
		$tpl->parseCurrentBlock();
	      }
	  }	
	
	// Table Rows
	if(!empty($manager_finding_ids))
	  {
	    for($i= 0; $i < sizeof($manager_finding_ids); $i++)
	      {
		$current_finding = & new Finding($manager_finding_ids[$i], $this->dbi_);
		
		if(!empty($current_finding->heurId))
		  {
		    $heuristic = & new Heuristic($current_finding->heurId, $this->dbi_);
		    
		    $tpl->setCurrentBlock('manager_heuristic_block');
		    $tpl->setVar(array(
				       'FINDING_HEURISTIC' => $translation->getTranslation($heuristic->hTitleId, $this->language_),
				       )
				 );
		    
		    $tpl->parseCurrentBlock();
		  }
		
		
		
		// Screenshots
		$dummy_screenshot = & new Screenshot(0, $this->dbi_);
		$screenshot_ids = $dummy_screenshot->getScreenshotIds($current_finding->fId);
		$current_attached_finding_ids = $current_finding->getAttachedFindingIds($current_finding->fId);
		
		
		if(!empty($screenshot_ids['annotated'])  && $show_screenshots)
		  {
		    $tpl->setCurrentBlock('manager_annotated_screenshot_block');
		    $tpl->setVar(array(
				       'ANNOTATED_SCREENSHOT_URL' => 'run.displayImage.php?tn=1&bid='.$screenshot_ids['annotated'],
				       'ANNOTATED_SCREENSHOT_FULLSIZE_URL' => 'run.displayImage.php?bid='.$screenshot_ids['annotated'],
				       )
				 );
		    if($FULLSIZE_IMAGE_POPUP)
		      $tpl->setVar('TARGET', "target=\"_blank\"");
		    $tpl->parseCurrentBlock();
		  }
		elseif(!empty($current_attached_finding_ids) && empty($screenshot_ids['annotated']))
		  {
		    $tpl->setCurrentBlock('manager_choose_annotated_screenshot_block');
		    $tpl->setVar(array(
				       'URL_CHOOSE_ANNOTATED_SCREENSHOT' => $PHP_SELF
				       ."?cmd=chooseScreenshot&fId=$current_finding->fId&url="
				       .urlencode($_SERVER['REQUEST_URI']),
				       'LABEL_CHOOSE_ANNOTATED_SCREENSHOT' => $this->getLabelText('LABEL_CHOOSE_ANNOTATED_SCREENSHOT'),
				       )
				 );
		    $tpl->parseCurrentBlock();
		  }
		
		if(!empty($screenshot_ids['fullsize']) && $show_screenshots)
		  {
		    $tpl->setCurrentBlock('manager_fullsize_screenshot_block');
		    $tpl->setVar(array(
				       'FULLSIZE_SCREENSHOT_URL' => 'run.displayImage.php?tn=1&bid='.$screenshot_ids['fullsize'],
				       'FULLSIZE_SCREENSHOT_FULLSIZE_URL' => 'run.displayImage.php?bid='.$screenshot_ids['fullsize'],
				       )
				 );
		    if($FULLSIZE_IMAGE_POPUP)
		      $tpl->setVar('TARGET', "target=\"_blank\"");
		    $tpl->parseCurrentBlock();
		  }
		elseif(!empty($current_attached_finding_ids) && empty($screenshot_ids['annotated']))
		  {
		    $tpl->setCurrentBlock('manager_choose_fullsize_screenshot_block');
		    $tpl->setVar(array(
				       'URL_CHOOSE_FULLSIZE_SCREENSHOT' =>$PHP_SELF
				       ."?cmd=chooseScreenshot&fId=$current_finding->fId&url="
				       .urlencode($_SERVER['REQUEST_URI']),
				       'LABEL_CHOOSE_FULLSIZE_SCREENSHOT' => $this->getLabelText('LABEL_CHOOSE_FULLSIZE_SCREENSHOT'),
				       )
				 );
		    $tpl->parseCurrentBlock();
		  }
		
		// Operations
		if($current_finding->getPredecessorFindingId($current_finding->fId))
		  {
		    $tpl->setCurrentBlock('manager_up_block');
		    $tpl->setVar(array(
				       'UP_URL' => $PHP_SELF."?cmd=moveUp&fid=$current_finding->fId&url=".urlencode($_SERVER['REQUEST_URI']),
				       'LABEL_UP' =>$this->getLabelText('LABEL_UP'),
				       )
				 );				   
		    $tpl->parseCurrentBlock();
		  }
		if($current_finding->getSuccessorFindingId($current_finding->fId))
		  {
		    $tpl->setCurrentBlock('manager_down_block');
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
		
		// Finding Data
		$tpl->setCurrentBlock('manager_finding_block');
		$tpl->setVar(array(
				   'BG_CLASS' => ($i%2?'odd':'even'),
				   'FINDING_TITLE' => $current_finding->fText,
				   'IS_POSITIVE' => $positive_string,
				   'EDIT_URL' => $PHP_SELF."?cmd=merge&editFindingId=$current_finding->fId&pid=$project_id",
				   'LABEL_EDIT' => $this->getLabelText('LABEL_EDIT'),
				   'DELETE_URL' => $PHP_SELF."?cmd=deleteFinding&fid=$current_finding->fId&url=".urlencode($_SERVER['REQUEST_URI']),
				   'LABEL_DELETE' => $this->getLabelText('LABEL_DELETE'),
				   )
			     );
		$tpl->setVar(array(
				   'LABEL_INSERT' => $this->getLabelText('LABEL_INSERT'),
				   'INSERT_URL' => $PHP_SELF."?cmd=merge&pid=$project_id&after=$current_finding->fId",
				   )
			     );
		
		$tpl->parseCurrentBlock();
		
	      }
	  }
	$tpl->setCurrentBlock('manager_tab_block');
	$tpl->setVar(array(
			   'LABEL_AGGREGATED_FINDINGS' => $this->getLabelText('LABEL_AGGREGATED_FINDINGS'),
			   )
		     );
	$tpl->parseCurrentBlock();
	




	



	// Display Evaluator Tabs
	for($i=0; $i < sizeof($user_ids); $i++)
	  {
	    $current_user = & new User($user_ids[$i], $this->dbi_);
	    
	    $users_project_finding_ids = $dummy_finding->getAllFindingIds($project_id, $user_ids[$i], 'fOrder', 'ASC', null, 'N', null);
	    
	    $this->debug("Evaluator ".$user_ids[$i]." has ". sizeof($users_project_finding_ids). " findings");
	    
	    // Table Header
	    if(!empty($users_project_finding_ids))
	      {
		$tpl->setVar(array(
				   'LABEL_FINDING_TEXT' => $this->getLabelText('LABEL_FINDING_TEXT'),
				   'LABEL_EVALUATOR_POSITIVE' => $this->getLabelText('LABEL_EVALUATOR_POSITIVE'),
				   'LABEL_FINDING_OPERATIONS' => $this->getLabelText('LABEL_FINDING_OPERATIONS'),
				   'LABEL_ANNOTATED_SCREENSHOT' => $this->getLabelText('LABEL_ANNOTATED_SCREENSHOT'),
				   'LABEL_FULLSIZE_SCREENSHOT' => $this->getLabelText('LABEL_FULLSIZE_SCREENSHOT'),
				   )
			     );
		
		if($project->heurSetId)
		  {
		    $tpl->setCurrentBlock('evaluator_heuristic_title_block');
		    $tpl->setVar(array(
				       'LABEL_HEURISTIC' => $this->getLabelText('LABEL_HEURISTIC'),
				       )
				 );
		    $tpl->parseCurrentBlock();
		  }
	      }	
	    
	    // Table Rows
	    if(!empty($users_project_finding_ids))
	      {
		for($j= 0; $j < sizeof($users_project_finding_ids); $j++)
		  {
		    $current_finding = & new Finding($users_project_finding_ids[$j], $this->dbi_);
		    
		    if(!empty($current_finding->heurId))
		      {
			$heuristic = & new Heuristic($current_finding->heurId, $this->dbi_);
			
			$tpl->setCurrentBlock('evaluator_heuristic_block');
			$tpl->setVar(array(
					   'FINDING_HEURISTIC' => $translation->getTranslation($heuristic->hTitleId, $this->language_),
					   )
				     );
			
			$tpl->parseCurrentBlock();
		      }
		    
		    // Screenshots
		    $dummy_screenshot = & new Screenshot(0, $this->dbi_);
		    $screenshot_ids = $dummy_screenshot->getScreenshotIds($current_finding->fId);
		    
		    if(!empty($screenshot_ids['annotated'])  && $show_screenshots)
		      {
			$tpl->setCurrentBlock('evaluator_annotated_screenshot_block');
			$tpl->setVar(array(
					   'ANNOTATED_SCREENSHOT_URL' => 'run.displayImage.php?tn=1&bid='.$screenshot_ids['annotated'],
					   'ANNOTATED_SCREENSHOT_FULLSIZE_URL' => 'run.displayImage.php?bid='.$screenshot_ids['annotated'],
					   )
				     );
			if($FULLSIZE_IMAGE_POPUP)
			  $tpl->setVar('TARGET', "target=\"_blank\"");
			$tpl->parseCurrentBlock();
		      }
		    
		    if(!empty($screenshot_ids['fullsize']) && $show_screenshots)
		      {
			$tpl->setCurrentBlock('evaluator_fullsize_screenshot_block');
			$tpl->setVar(array(
					   'FULLSIZE_SCREENSHOT_URL' => 'run.displayImage.php?tn=1&bid='.$screenshot_ids['fullsize'],
					   'FULLSIZE_SCREENSHOT_FULLSIZE_URL' => 'run.displayImage.php?bid='.$screenshot_ids['fullsize'],
					   )
				     );
			if($FULLSIZE_IMAGE_POPUP)
			  $tpl->setVar('TARGET', "target=\"_blank\"");
			$tpl->parseCurrentBlock();
		      }
		    
		    if($current_finding->fPositive == 'Y')
		      $positive_string = $this->getLabelText('LABEL_IS_POSITIVE');
		    else
		      $positive_string = $this->getLabelText('LABEL_IS_NEGATIVE');
		    
		    // Setup Javascript array that holds original style classes 
		    if($current_finding->findingMerged())
		      {
			$tpl->setVar('ROW_STYLE_JAVASCRIPT_CODE', '<script type="text/javascript">original_row_classes_["'.$current_finding->fId.'"] = "merged" </script>');
		      }
		    else
		      {
			$tpl->setVar('ROW_STYLE_JAVASCRIPT_CODE', '<script type="text/javascript">original_row_classes_["'.$current_finding->fId.'"] = "unmerged" </script>');
		      }



		    // set finding row styles
		    if($finding_to_change->init_ok_ && is_array($attached_finding_ids) && in_array($current_finding->fId, $attached_finding_ids))
		      $tpl->setVar('ROW_STYLE_CLASS', 'selected');
		    elseif($current_finding->findingMerged())
		      $tpl->setVar('ROW_STYLE_CLASS', 'merged');
		    else
		      $tpl->setVar('ROW_STYLE_CLASS', 'unmerged');		      

		    if($finding_to_change->init_ok_ && is_array($attached_finding_ids) && in_array($current_finding->fId, $attached_finding_ids))
		      {
			$tpl->setVar(array(
					   'LABEL_APPEND' => $this->getLabelText('LABEL_DETACH'),
					   'ICON_APPEND' => 'templates/icons/button_detach.png',
					   'APPEND_URL' => "javascript:detachFinding('$current_finding->fId', '" . $this->getLabelText('LABEL_DETACH') . "', '" . $this->getLabelText('LABEL_APPEND') . "');",
					   )
				     );
			
		      }
		    else
		      {
			$tpl->setVar(array(
					   'LABEL_APPEND' => $this->getLabelText('LABEL_APPEND'),
					   'ICON_APPEND' => 'templates/icons/button_attach.png',
					   'APPEND_URL' => "javascript:attachFinding('$current_finding->fId', '" . $this->getLabelText('LABEL_DETACH') . "', '" . $this->getLabelText('LABEL_APPEND') . "');",
					   )
				     );
		      }
		    
		    
		    $tpl->setCurrentBlock('evaluator_finding_block');
		    $tpl->setVar(array(
				       'FINDING_TITLE' =>$current_finding->fText,
				       'ROW_ID' => "row_" . $current_finding->fId,
				       'IS_POSITIVE' => $positive_string,
				       'EDIT_URL' => 'finding_collector/run.FindingCollector.php'."?cmd=editFinding&fid=$current_finding->fId&url=".urlencode($_SERVER['REQUEST_URI']),
				       'LABEL_EDIT' => $this->getLabelText('LABEL_EDIT'),
				       'DELETE_URL' => 'finding_collector/run.FindingCollector.php'."?cmd=deleteFinding&fid=$current_finding->fId&url=".urlencode($_SERVER['REQUEST_URI']),
				       'LABEL_DELETE' => $this->getLabelText('LABEL_DELETE'),
				       'LABEL_USETEXT' => $this->getLabelText('LABEL_USETEXT'),
				       'USETEXT_URL' =>
				       "javascript:appendTextToObject('f_text',"
				       ."'".$current_finding->fText."');"
				       )
				 );
		    
		    $tpl->parseCurrentBlock();
		  }
	      }
	    
	    $tpl->setCurrentBlock('evaluator_tab_block');
	    
	    $tpl->setVar(array(
			       'FIRST_NAME' => $current_user->first_name,
			       'LAST_NAME' => $current_user->last_name,
			       'NUMBER' => $i,
			       )
			 );
	    $tpl->parseCurrentBlock();
	  }
	// End of Evaluator Tabs
	$tpl->setCurrentBlock('main_block');
	$tpl->parseCurrentBlock();
      }
    else
      {
	$this->addSessionMessage('NO_PROJECT_GIVEN');
      }
    
    
    $tpl->setCurrentBlock('main_block');

    $message_text = '';
    if($this->hasSessionMessages() == TRUE)
      {
    	$messages = $this->getAllSessionMessages();
    	while($msg = array_pop($messages))
    	  {
    	    $message_text.=$msg;
    	  }
      }
    $tpl->setVar(array(
    		       'MERGE_WINDOW_TITLE' => $this->getLabelText('MERGE_WINDOW_TITLE'),
    		       'MESSAGES' => $message_text,
    		       )
    		 );

    $this->app_breadcrumbs_[] = Array(
				      'label' => $this->getLabelText('MERGE_WINDOW_TITLE'),
				      );
    $tpl->parseCurrentBlock();

    return TRUE;
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
    $project_ids = $dummy_project->getAllProjectIds();

    $dummy_finding = & new Finding(0, $this->dbi_);

    $row = 0;
    for($i=0; $i < sizeof($project_ids); $i++)
      {
	$current_project =& new Project($project_ids[$i], $this->dbi_);
	if($current_project->getProjectPhase() == '2')
	  {
	    $tpl->setCurrentBlock('project_block');
	    $tpl->setVar(array(
			       'BG_CLASS' => ($row%2?'odd':'even'),
			       'PROJECT_TITLE' =>$translation->getTranslation($current_project->pNameId, $this->language_),
			       'MERGE_FINDINGS_URL' => $PHP_SELF."?cmd=merge&pid=$current_project->pId",
			       'LABEL_MERGE_FINDINGS' => $this->getLabelText('LABEL_MERGE_FINDINGS'),
			       'MERGE_FINDINGS_URL_SCREENSHOTS' => $PHP_SELF."?cmd=merge&pid=$current_project->pId&showScreenshots=1",
			       'LABEL_MERGE_FINDINGS_SCREENSHOTS' => $this->getLabelText('LABEL_MERGE_FINDINGS_SCREENSHOTS'),
			       )
			 );

	    $tpl->parseCurrentBlock();
	    $row++;
	  }
      }


    $tpl->setVar(array(
		       'LABEL_TITLE' => $this->getLabelText('LABEL_TITLE'),
		       'PROJECTS_TITLE' => $this->getLabelText('PROJECTS_TITLE'),
		       'LABEL_MERGE_WITH_SCREENSHOTS' => $this->getLabelText('LABEL_MERGE_WITH_SCREENSHOTS'),
		       'SCREENSHOTS_CHECKED' => (!is_null($this->getRequestField('HEM_screenshots', null))?'checked="checked"':''),
		       )
		 );

    return TRUE;
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
      
      if(!is_null($cmd) && $cmd == 'deleteFinding')
	{
	  $tpl->setVar('CONFIRM_MESSAGE', $this->getMessageText('CONFIRM_MESSAGE'));
	  
	  if(!is_null($finding_id))
	    {
	      $finding_to_delete = & new Finding($finding_id, $this->dbi_);
	      $tpl->setVar(array(
				 'TITLE' => $finding_to_delete->fText,
				 'ID' => $finding_id
				 )
			   );
	    }
	  $tpl->setVar(array(
			     'LABEL_YES' => $this->getLabelText('LABEL_YES'),
			     'LABEL_NO' => $this->getLabelText('LABEL_NO')
			     )
		       );
	}

      $this->app_breadcrumbs_[] = Array(
					'label' => $this->getLabelText('CONFIRM_TITLE'),
					);

      $tpl->setVar(array(
			 'FORM_ACTION' => $_SERVER['PHP_SELF'],
			 'CONFIRM_TITLE' => $this->getLabelText('CONFIRM_TITLE'),
			 'RETURN_URL' => $this->getGetRequestField('url', null),
			 )
		   );
      
      return TRUE;
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
	      $ann_screenshot->deleteData();
	    if($ful_screenshot->init_ok_)
	      $ful_screenshot->deleteData();
	  }
	
	if($finding_to_delete->init_ok_)
	  {
	    $finding_to_delete->detachAllFindingsFromManagerFinding($finding_to_delete->fId);
	    $finding_order = $finding_to_delete->fOrder;
	    $finding_to_delete->deleteFinding();
	    $this->addSessionMessage('DELETED');	    
	  }
      }
    else
      $this->addSessionMessage('NOT_DELETED');
  }

  function doRedirect()
  {
    $url = $this->getGetRequestField('url', null);

    if(is_null($url))
      $url = $this->getPostRequestField('url', null);

    if(!is_null($url))
      {
	header("Location: ".urldecode($url));
      }
    
  }

}




?>
