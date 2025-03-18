<?php

class EnvironmentCollector extends PHPApplication
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
    
    $this->environmentCollectorDriver();

  }


  function environmentCollectorDriver()
  {
    $cmd = $this->getGetRequestField('cmd', null);

    $form = $this->getPostRequestField('form_id', null);

    $url = $this->getPostRequestField('url', null);

    switch ($form)
      {
      case 'addEnvironmentData':
	$this->doAddEnvironmentData();
	if(!is_null($url))
	  header("Location: $url");
	break;
      }


    switch ($cmd)
      {
      case 'addEnvironmentData':
	$this->addEnvironmentData();
	break;
      default:
	$this->projectOverview();
	break;
      }

  }


  function doAddEnvironmentData()
  {
    $this->debug("doAddEnvironementData()");
    $data = $this->getPostRequestField('data', null);
    //    $project_id = $this->getPostRequestField('project_id', null);

    //    $this->dumpArray($_REQUEST);
    //    $this->dumpArray($project_id);

    if(!is_null($data))
      {
	$data_obj = new EnvironmentData(0, $this->dbi_);
	
	$data_obj->storeAttributeDataArray($data);
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

     if(!empty($project_ids))
      {
	$row=0;
	for($i=0; $i < sizeof($project_ids); $i++)
	  {
	    $current_project =& new Project($project_ids[$i], $this->dbi_);
	    
	    if($current_project->getProjectPhase() <= '3' )
	      {
		$tpl->setVar(array(
				   'EDIT_URL' => $PHP_SELF."?cmd=addEnvironmentData&pid=$current_project->pId",
				   'LABEL_EDIT' => $this->getLabelText('LABEL_EDIT'),
				   )
			     );
		
		$tpl->setCurrentBlock('project_block');
		$tpl->setVar(array(
				   'BG_CLASS' => ($row%2?'odd':'even'),
				   'PROJECT_TITLE' => $translation->getTranslation($current_project->pNameId, $this->language_),
				   'PROJECT_DESCRIPTION' => $translation->getTranslation($current_project->pDescriptionId, $this->language_),
				   )
			     );
		
		$tpl->parseCurrentBlock();
		$row++;
	      }
	    
	  }
      }

    $tpl->setVar(array(
		       'PROJECTS_TITLE' => $this->getLabelText('PROJECTS_TITLE'),
		       'LABEL_TITLE' => $this->getLabelText('LABEL_TITLE'),
		       )
		 );

    return TRUE;
  }


  function addEnvironmentData()
  {
    global $ADD_ENVIRONMENT_DATA_TEMPLATE;

    $this->showScreen($ADD_ENVIRONMENT_DATA_TEMPLATE, 'displayAddEnvironmentDataForm', $this->getAppName());
  }


  function displayAddEnvironmentDataForm(& $tpl)
  {
    global $PHP_SELF;

    $tpl->setVar('ADD_ENVIRONMENT_DATA_TITLE', $this->getLabelText('ADD_ENVIRONMENT_DATA_TITLE'));

    $this->app_breadcrumbs_[] = Array(
				      'url' => $_SERVER['REQUEST_URI'], 
				      'label' => $this->getLabelText('ADD_ENVIRONMENT_DATA_TITLE'),
				      );

    $url = $this->getGetRequestField('url', null);
    $project_id = $this->getGetRequestField('pid', null);
    $uid = $this->getGetRequestField('uid', null);

    $translation = & new Translation(0, $this->dbi_);

    if(!is_null($uid) && $this->auth_handler_->checkRight(CHANGE_OTHER_ENVIRONMENTS))
      {
	$user_id = $uid;
      }
    else
      {
	$user_id = $this->user_->user_id_;
      }

    if(!is_null($project_id))
      {
	$project_object = & new Project($project_id, $this->dbi_);
	$this->app_breadcrumbs_[] = Array(
					  'label' => $translation->getTranslation($project_object->pNameId, $this->language_),
					  );
	
	if($project_object->init_ok_)
	  {
	    $env_object = & new Environment($project_object->envId, $this->dbi_);

	    $env_data_obj = &new EnvironmentData(0, $this->dbi_);
	    $env_data = $env_data_obj->getAttributeDataForUserAndProject($user_id, $project_id);
	    $environment = $env_object->getEnvironment();
	    //	    $this->dumpArray($environment);

	    $attribute_ids = array_keys($environment['attributes']);
	    $attributes = $environment['attributes'];

	    foreach($attribute_ids as $current_attribute_id)
	      {
		if($attributes[$current_attribute_id]['envAttributeType'] == 'select')
		  {
		    // Not Implemented Yet!
		    //		    $select_values = explode(",", $attributes[$current_attribute_id]['envAttributeValues']);
		    //		    $tpl->setCurrentBlock('drop_down_block');

		    //		    for($i=0;$i<sizeof($select_values); $i++)
		    //		      {
		    //			$tpl->setCurrentBlock('drop_down_option_block');
		    //			$tpl->setVar(array(
		    //					   'DROP_DOWN_OPTION_VALUE' =>addslashes($select_values[$i]),
		    //					   'DROP_DOWN_OPTION_TEXT' =>$select_values[$i],
		    //					   )
		    //				     );
			//			if($env_set->init_ok_ && $env_set->environment_data_[$current_attribute_object->envAttributeId] == $value_array[$i])
			//			  $tpl->setVar('DROP_DOWN_OPTION_SELECTED', 'selected=\"selected\"');
			//			$tpl->parseCurrentBlock();
		    //		      }
		    
		    //		    $tpl->setVar(array(
		    //				       'DROP_DOWN_FIELD_NAME' =>'data['.$attributes[$current_attribute_id]['envAttributeId'].']',
		    //				       'DROP_DOWN_FIELD_LABEL' => $attributes[$current_attribute_id]['title_translation'][$this->language_],
		    //				       )
		    //				 );
		  }
		elseif( $attributes[$current_attribute_id]['envAttributeType'] == 'text')
		  {
		    if(!isset($env_data[$current_attribute_id]))
		      {
			$env_data[$current_attribute_id]['envDataId'] = $this->getUniqueId();
			$env_data[$current_attribute_id]['envAttributeData'] = '';
		      }

		    $tpl->setCurrentBlock('text_field_block');
		    $tpl->setVar(array(
				       'TEXT_FIELD_NAME' =>'data['.$attributes[$current_attribute_id]['envAttributeId'].']',
				       'TEXT_FIELD_LABEL' => $attributes[$current_attribute_id]['title_translation'][$this->language_],
				       'ATTRIBUTE_ID' => $current_attribute_id,
				       'TEXT_FIELD_VALUE' => $env_data[$current_attribute_id]['envAttributeData'],
				       'PROJECT_ID' => $project_id,
				       'ENV_DATA_ID' => $env_data[$current_attribute_id]['envDataId'],
				       'USER_ID' => $user_id,
				       )
				 );
		  }

		$tpl->parseCurrentBlock();
	    
	      }
	    $tpl->setVar(array(
			       'PROJECT_ID' => $project_id,
			       'FORM_ACTION' => $PHP_SELF,
			       'LABEL_SUBMIT_BUTTON' => $this->getlabelText('SUBMIT_BUTTON'),
			       'LABEL_CANCEL_BUTTON' => $this->getlabelText('CANCEL_BUTTON'),
			       )
			 );
	    if(!is_null($url))
	      $tpl->setVar('RETURN_URL', $url);
	  }
      }

    return TRUE;
  }


}
?>