<?php

class Home extends PHPApplication
{
  
  function run()
    {
    global $globals;
    global $HOME_APP, $HOME_APP_LABEL;

    // Set Application Title
    $this->app_name_ = $this->getLabelText("APPLICATION_TITLE");
    $this->app_breadcrumbs_[] = Array(
				      'label' => $HOME_APP_LABEL[$this->language_],
				      );

    $this->homeDriver();

  }

  function homeDriver()
  {
    global $PHP_SELF;

    $cmd = $this->getGetRequestField('cmd', null);

    $form = $this->getPostRequestField('form_id', null);

    $answer = $this->getPostRequestField('Yes', null);

    // Action to take after adding the finding
    $action = $this->getPostRequestField('action', null);
    
    switch ($form)
      {
      case 'yxz':
	break;
      default:
	break;
      }

    switch ($cmd)
      {
      default:
	$this->homeScreen();
	break;
      }
  }



  function homeScreen()
  {
    $this->debug("Home Driver");
    global $HOME_SCREEN_TEMPLATE;
    $this->showScreen($HOME_SCREEN_TEMPLATE, 'displayHomeScreen', $this->getAppName());
  }

  function displayHomeScreen(& $tpl)
  {
    global $PHP_SELF, $FULLSIZE_IMAGE_POPUP, $TABLE_ROW_COLOR_1, $TABLE_ROW_COLOR_2, $TABLE_HEADING_COLOR;
    

    $message_text = '';
    if($this->hasSessionMessages() == TRUE)
      {
	$messages = $this->getAllSessionMessages();
    	while($msg = array_pop($messages))
    	  {
    	    $message_text.=$msg;
    	  }
      }

    $dummy_project = & new Project(0, $this->dbi_);
    $project_ids = $dummy_project->getProjectIdsForUser($this->user_->user_id_);
    $translation = & new Translation(0, $this->dbi_);


    if($project_ids)
      {
	foreach($project_ids as $current_project_id)
	  {
	    $current_project_object = & new Project($current_project_id, $this->dbi_);

	    if($current_project_object->pPhase < 4)
	      {
		$tpl->setCurrentBlock('task_block');
		$tpl->setVar(array(
				   'TASK_MESSAGE' => $this->getMessageText('TASK_ENTER_ENVIRONMENT'),
				   'TASK_URL' => 'environment_collector/run.EnvironmentCollector.php?cmd=addEnvironmentData&pid='.$current_project_object->pId."&url=".urlencode($this->getSelfUrl()),
				   )
			     );
		$tpl->parseCurrentBlock();
		
		
		switch($current_project_object->pPhase)
		  {
		  case 0:
		    break;
		  case 1:
		    $tpl->setCurrentBlock('task_block');
		    $tpl->setVar(array(
				       'TASK_MESSAGE' => $this->getMessageText('TASK_EVALUATE'),
				       'TASK_URL' => 'finding_collector/run.FindingCollector.php?cmd=viewFindings&pid='.$current_project_object->pId,
				       'LABEL_IAM_FINISHED' => $this->getLabelText('LABEL_IAM_FINISHED'),
				       'IAM_FINISHED_URL' => $this->getSelfUrl(),
				       )
				 );
		    $tpl->parseCurrentBlock();
		    break;
		  case 2:
		    break;
		  case 3:
		    $tpl->setCurrentBlock('task_block');
		    $tpl->setVar(array(
				       'TASK_MESSAGE' => $this->getMessageText('TASK_RATE'),
				       'TASK_URL' => 'rating_collector/run.RatingCollector.php?cmd=rate&pid='.$current_project_object->pId."&url=".urlencode($this->getSelfUrl()),
				       'LABEL_IAM_FINISHED' => $this->getLabelText('LABEL_IAM_FINISHED'),
				       'IAM_FINISHED_URL' => $this->getSelfUrl(),
				       )
				 );
		    $tpl->parseCurrentBlock();
		    break;
		  default:
		    break;
		  }

		$tpl->setCurrentBlock('tasks_block');
		$tpl->setVar('LABEL_CURRENT_TASKS', $this->getLabelText('LABEL_CURRENT_TASKS'));
		$tpl->parseCurrentBlock();
		
	      }


	    $tpl->setCurrentBlock('project_block');
	    $tpl->setVar(array(
			       'PROJECT_TITLE' => $translation->getTranslation($current_project_object->pNameId, $this->language_),
			       'PROJECT_PHASE' =>  $this->getLabelText($current_project_object->getPhaseLabelById($current_project_object->pPhase)),
			       )
			 );
	    $tpl->parseCurrentBlock();
	    
	  }
	$tpl->setCurrentBlock('has_tasks_block');
	$tpl->setVar(array(
			   'LABEL_PROJECT_TITLE' => $this->getLabelText('LABEL_PROJECT_TITLE'),
			   'LABEL_PROJECT_PHASE' => $this->getLabelText('LABEL_PROJECT_PHASE'),
			   'TASKS_MESSAGE' => $this->getMessageText('TASKS_MESSAGE'),
			   )
		     );
	$tpl->parseCurrentBlock();
      }
    else
      {
	$tpl->setCurrentBlock('has_no_tasks_block');
	$tpl->setVar('NO_TASKS_MESSAGE', $this->getMessageText('NO_TASKS_MESSAGE'));
	$tpl->parseCurrentBlock();

      }
    

    $tpl->setVar(array(
    		       'HOME_TITLE' => $this->getLabelText('HOME_TITLE'),
    		       'MESSAGES' => $message_text,
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
