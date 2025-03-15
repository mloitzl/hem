<?php

class RatingCollector extends PHPApplication
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
    global $PHP_SELF;
    
    $cmd = $this->getGetRequestField('cmd', null);

    $form = $this->getPostRequestField('form_id', null);

    // Action to take after adding the finding
    $action = $this->getPostRequestField('action', null);

    //Url to redirect user to, afterwards
    $url = $this->getPostRequestField('url', null);
    
    $data = $this->getPostRequestField('data', null);
    $project_id = $data['pId'];

    $this->debug($action);

    switch ($form)
      {
      case 'add_ratings':
	$this->doAddRatings();
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
	    header("Location: $PHP_SELF");
	  }
	elseif($action == 'add_another')
	  {
	    //	echo "$PHP_SELF?cmd=evaluate&pid=$project_id";
	    header("Location: $PHP_SELF?cmd=viewFindings&pid=$project_id");
	  }
      }
    switch ($cmd)
      {
      case 'rate':
	$this->findingOverview();
	break;
      default:
	$this->projectOverview();
	break;
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

    $row=0;
    for($i=0; $i < sizeof($project_ids); $i++)
      {
	$current_project =& new Project($project_ids[$i], $this->dbi_);
	if($current_project->getProjectPhase() == '3')
	  {

	    $tpl->setCurrentBlock('project_block');
	    $tpl->setVar(array(
			       'BG_CLASS' => ($row%2?'odd':'even'),
			       'PROJECT_TITLE' =>$translation->getTranslation($current_project->pNameId, $this->language_),
			       'PROJECT_DESCRIPTION' =>$translation->getTranslation($current_project->pDescriptionId, $this->language_),
			       'RATE_URL' => $PHP_SELF."?cmd=rate&pid=$current_project->pId",
			       'LABEL_RATE' => $this->getLabelText('LABEL_RATE'),
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
    
    $project_id = $this->getGetRequestField('pid', null);
    $dummy_finding = & new Finding(0, $this->dbi_);

    $uid = $this->getGetRequestField('uid', null);

    if(!is_null($uid) && $this->auth_handler_->checkRight(CHANGE_OTHER_RATINGS))
      {
	//	$tpl->setVar('USER_ID', $uid);
      }
    else
      {
	$uid = $this->user_->user_id_;
	//	$tpl->setVar('USER_ID', $uid);
      }

    if(!is_null($project_id))
      {
	$this->debug("Project ID ok");
	$translation = & new Translation(0, $this->dbi_);

	$project = & new Project($project_id, $this->dbi_);
	$tpl->setVar('PROJECT_TITLE', $translation->getTranslation($project->pNameId, $this->language_));
	
	$this->app_breadcrumbs_[] = Array(
					  'label' => $translation->getTranslation($project->pNameId, $this->language_),
					  );



	$users_project_finding_ids = $dummy_finding->getAllFindingIds($project_id, null, 'fOrder', 'ASC', null, 'Y');

	$ratingscheme_object = & new RatingScheme($project->schemeId, $this->dbi_);
	
	$associated_scale_ids = $ratingscheme_object->getAssociatedScaleIds($ratingscheme_object->schemeId);
	
	$translation = & new Translation(0, $this->dbi_);

	if(!empty($users_project_finding_ids))
	  {
	    $this->debug("Manager merged findings");
	    $tpl->setVar(array(
			       'LABEL_FINDING_TEXT' => $this->getLabelText('LABEL_FINDING_TEXT'),
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

	    foreach($associated_scale_ids as $key => $scale_id)
	      {
		
		$current_scale = & new RatingScale($scale_id, $this->dbi_);
		$tpl->setCurrentBlock('scale_header_block');
		$tpl->setVar(array(
				   'SCALE_TITLE' =>$translation->getTranslation($current_scale->scaleTitleId, $this->language_),
				   )
			     );
		$tpl->parseCurrentBlock();
	      }
	
	    for($i= 0; $i < sizeof($users_project_finding_ids); $i++)
	      {
		$current_finding = & new Finding($users_project_finding_ids[$i], $this->dbi_);
		
		if(!empty($current_finding->heurId))
		  {
		    $heuristic = & new Heuristic($current_finding->heurId, $this->dbi_);
		    
		    $tpl->setCurrentBlock('heuristic_block');
		    $tpl->setVar(array(
				       'FINDING_HEURISTIC' => $translation->getTranslation($heuristic->hTitleId, $this->language_),
				       )
				 );
		    
		    $tpl->parseCurrentBlock();
		  }
		
		$dummy_screenshot = & new Screenshot(0, $this->dbi_);
		$screenshot_ids = $dummy_screenshot->getScreenshotIds($current_finding->fId);
		
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
		
		foreach($associated_scale_ids as $key => $scale_id)
		  {
		    $current_finding_ratings = $current_finding->getRatingsForFinding($uid, $current_finding->fId);
		    //		$this->dumpArray($current_finding_ratings);
		    
		    $current_scale = & new RatingScale($scale_id, $this->dbi_);
		    
		    $dummy_scale_value_object = & new RatingScaleValue(0, $this->dbi_);
		    $filter = array('scaleId' => array( 'name' => 'scaleId', 'op' => '=', 'value' => "$current_scale->scaleId", 'cond' => ''));
		    $value_ids = $dummy_scale_value_object->getAllRatingScaleValueIds($filter, 'scaleValue', 'ASC');
		    //		$this->dumpArray($value_ids);
		    
		    foreach($value_ids as $key => $current_value_id)
		      {
			$current_scale_value_object = & new RatingScaleValue($current_value_id, $this->dbi_);
			
			$tpl->setCurrentBlock('rating_scale_option');
			$tpl->setVar(array(
					   'SCALE_VALUE_ID' => $current_scale_value_object->scaleValueId ,
					   'SCALE_VALUE_TEXT' => $translation->getTranslation($current_scale_value_object->scaleValueCaptionId , $this->language_),
					   )
				     );
			if($current_finding_ratings[$current_scale->scaleId] == $current_scale_value_object->scaleValueId)
			  $tpl->setVar('SCALE_VALUE_SELECTED', "selected=\"selected\"");
			$tpl->parseCurrentBlock();
		      }
		    
		    $tpl->setCurrentBlock('rating_scheme_block');
		    $tpl->setVar(array(
				       'FINDING_ID' => $current_finding->fId,
				       'SCALE_ID' => $current_scale->scaleId,
				       )
				 );
		    
		    $tpl->parseCurrentBlock();
		  }
	    
		$tpl->setCurrentBlock('finding_block');
		$tpl->setVar(array(
				   'BG_CLASS' => ($i%2?'odd':'even'),
				   'FINDING_TITLE' =>$current_finding->fText,
				   )
			     );
		$tpl->parseCurrentBlock();
	      }
	    
	    $tpl->setCurrentBlock('main_block');

	    $tpl->setVar(array(
			       'SUBMIT_BUTTON' => $this->getLabelText('SUBMIT_BUTTON'),
			       'CANCEL_BUTTON' => $this->getLabelText('CANCEL_BUTTON'),
			       'RETURN_URL' => $this->getGetRequestField('url', null),
			       'FORM_METHOD' => 'POST',
			       'FORM_ACTION' => $PHP_SELF,
			       'USER_ID' => $uid,
			       )
			 );
	    $tpl->parseCurrentBlock();
	  }
	else
	  {
	    $tpl->setCurrentBlock('no_findings_block');
	    $tpl->setVar(array(
			       'MESSAGE_NO_FINDINGS' => $this->getMessageText('MESSAGE_NO_FINDINGS'),
			       )
			 );
	    $tpl->parseCurrentBlock();
	  }
	
      }

    $tpl->setVar('RATINGS_TITLE', $this->getLabelText('FINDINGS_TITLE'));
    
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


  function doAddRatings()
  {
    //    $this->dumpArray($_REQUEST);

    $ratings = $this->getPostRequestField('ratings', null);
    
    $uid = $this->getPostRequestField('uid', null);

    // check if user is trying to fool us with a wrong user id
    if($uid !== $this->user_->user_id_ && !$this->auth_handler_->checkRight(CHANGE_OTHER_RATINGS))
      $uid = $this->user_->user_id_;
    
    if(!is_null($ratings) && !is_null($uid))
      {
	$finding = & new Finding(0, $this->dbi_);
	$finding->storeRatings($uid, $ratings);
      }
    else
      {
	// TODO: Report the error
      }
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
