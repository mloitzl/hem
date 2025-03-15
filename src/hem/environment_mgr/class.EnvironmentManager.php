<?php

class EnvironmentManager extends PHPApplication
{
  
  function run()
  {
    //    global $globals, $HOME_APP;
    global $HOME_APP, $HOME_APP_LABEL;
    
    
    // TODO: Add right
    if($this->auth_handler_->checkRight(MANAGE_ENVIRONMENTS))
      {
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
	$this->HeuristicSetManagerDriver();
      }
    else
      {
	header("Location: $HOME_APP");
      }
  }


  function HeuristicSetManagerDriver()
  {
    global $PHP_SELF;

    // A Form has been subitted, check which one
    $form = $this->getPostRequestField('form_id', null);
    $answer = $this->getPostRequestField('Yes', null);

    //    $this->dumpArray($_REQUEST);

    if(!is_null($form))
      {
	switch ($form)
	  {
	  case 'confirm':
	    if(!is_null($answer))
	      $this->doDeleteEnvironment();
	    break;
	  case 'addEnvironment':
	    // Submit button hit, we're ready
	    if(!is_null($this->getPostRequestField('Submit', null)))
	      {
		$this->doAddEnvironment();
	      }
	    // Add fields Button pressed, go back
	    elseif(!is_null($this->getPostRequestField('AddFields', null)))
	      {
		$this->doAddEnvironment();
		$data = $this->getPostRequestField('data', null);
		$add_fields = $this->getPostRequestField('addFields', null);
		header("Location: ".$PHP_SELF."?cmd=addEnvironment&envid=".$data['envId']."&addFields=".$add_fields);
	      }
	    elseif(!is_null($this->getPostRequestField('delete_value', null)))
	      {
		// Save changes made in the form
		$this->doAddEnvironment();
		// Do Deletion
		$this->doDeleteAttribute();
		$data = $this->getPostRequestField('data', null);
		// Adding fields at deletion button is a bit confusing
		// Changed default value from number of fields to add to one, 
		// which causes that one has to write value into the field, 
		// which is a bit more complicated
		$add_fields = $this->getPostRequestField('addFields', null);
		header("Location: ".$PHP_SELF."?cmd=addEnvironment&envid=".$data['envId']."&addFields=".$add_fields);
	      }
	    break;
	  default:
	    break;
	  }
	
      }

    // Url Parameters, no form submitted, or form data already processed    
    if(!is_null($cmd = $this->getGetRequestField('cmd', null)))
      {
	switch ($cmd)
	  {
	  case 'addEnvironment':
	    $this->addEnvironmentDriver();
	    break;
	  case 'deleteEnvironment':
	    $this->getConfirmation();
	    break;
	  default:
	    $this->debug('Calling HeuristicSet Overview');
	    $this->environmentOverview();
	    break;
	  }
      }
    else
      {
	$this->environmentOverview();
      }

  }


  function doAddEnvironment()
  {
    global $LANGUAGES;

    $this->debug("doAddEnvironment()");

    //    $this->dumpArray($_REQUEST);
    
    $data = $this->getPostRequestField('data', null);

    $dummy_env = & new Environment($data['envId'], $this->dbi_);
    if(!is_null($data))
      {
	$dummy_env->storeEnvironment($data);
      }
    else
      {
	// TODO: Error Handling
	// $dummy_heur_set->getError() or sth.
      }
    
  }
  
  function doDeleteAttribute()
  {
    if(!is_null($delete_value = $this->getPostRequestField('delete_value', null)))
      {
	$id = array_keys($delete_value);
	$value_object = & new EnvironmentAttribute($id[0], $this->dbi_);
	$value_object->removeAttribute();
      }
  }


  function doDeleteEnvironment()
  {
    //    $this->dumpArray($_REQUEST);

    $envid = $this->getPostRequestField('envid', null);

    $this->debug("Deleteing EnvId: $envid ");

    if(!is_null($envid))
      {
	$env_to_delete = & new Environment($envid, $this->dbi_);
	if($env_to_delete->init_ok_)
	  {
	    $env_to_delete->removeEnvironment();
	  }
      }
  }


  function addEnvironmentDriver()
  {
    global $ADD_ENVIRONMENT_TEMPLATE;

    $this->showScreen($ADD_ENVIRONMENT_TEMPLATE, 'displayAddEnvironmentForm', $this->getAppName());
  }


  function displayAddEnvironmentForm(& $tpl)
  {
    global $LANGUAGES, $PHP_SELF;

    if(!is_null($env_id = $this->getGetRequestField('envid', null)))
      {
	$this->debug('EnvId:'. $env_id);
	$env_to_change = & new Environment($env_id, $this->dbi_);
	$env_data = $env_to_change->getEnvironment();
	//	$this->dumpArray($set_data);
      }
    else
      $env_to_change = & new Environment(0, $this->dbi_);

    $message_text = '';
    if($this->hasSessionMessages() == TRUE)
      {
	$messages = $this->getAllSessionMessages();
	while($msg = array_pop($messages))
	  {
	    $message_text.=$msg;
	  }
      }

    $generated_title_translation_id = $this->getUniqueId();
    $generated_values_translation_id = $this->getUniqueId();

    // Set Labels for Labels for Title and Values Block
    foreach ( $LANGUAGES as $key => $val )
      {
		$tpl->setCurrentBlock('title_language_header_block');
		$tpl->setVar(array(
			   	'LABEL_TITLE_LANGUAGE' => $this->getLabelText('LABEL_'.$val),
			   	)
		     	);
		$tpl->parseCurrentBlock();


		$tpl->setCurrentBlock('description_language_header_block');
		$tpl->setVar(array(
			   	'LABEL_DESCRIPTION_LANGUAGE' => $this->getLabelText('LABEL_'.$val),
			   	)
		     	);
		$tpl->parseCurrentBlock();



		$tpl->setCurrentBlock('value_language_header_block');
		$tpl->setVar(array(
			   	'LABEL_VALUE_LANGUAGE' => $this->getLabelText('LABEL_'.$val),
			   	)
		     	);
		$tpl->parseCurrentBlock();

      	}


    // Set Value Header Label
    $tpl->setVar(array(
		       'LABEL_ORDER' => $this->getLabelText('LABEL_ORDER'),
		       )
		 );
    

    // Set Table Captions
    $tpl->setVar(array(
		       'LABEL_ENVIRONMENT_TITLE' => $this->getLabelText('LABEL_ENVIRONMENT_TITLE'),
		       'LABEL_ENVIRONMENT_DESCRIPTION' => $this->getLabelText('LABEL_ENVIRONMENT_DESCRIPTION'),
		       'LABEL_ENVIRONMENT_VALUES' => $this->getLabelText('LABEL_ENVIRONMENT_VALUES'),
		       )
		 );
    // Build Form ------

    // Build Title part
    foreach ( $LANGUAGES as $key => $val )
      {
	$tpl->setCurrentBlock('title_language_block');
	$tpl->setVar('TITLE_LANGUAGE_CODE', $val);

	if($env_to_change->init_ok_)
	  {
	    $tpl->setVar(array(
			       'ENVIRONMENT_TITLE' => $env_data['title_translation'][$val],
			       'ENVIRONMENT_TITLE_ID' => $env_data['title_translation']['trans_id'],
			       )
			 );
	  }
	else
	  {
	    // Generate Ids if none given
	    $tpl->setVar(array(
			       'ENVIRONMENT_TITLE_ID' => $this->getUniqueId(),
			       )
			 );
	  }
	$tpl->parseCurrentBlock();
      }

    // Build Description part
    foreach ( $LANGUAGES as $key => $val )
      {
	$tpl->setCurrentBlock('description_language_block');
	$tpl->setVar('TITLE_LANGUAGE_CODE', $val);

	if($env_to_change->init_ok_)
	  {
	    $tpl->setVar(array(
			       'ENVIRONMENT_DESCRIPTION' => $env_data['description_translation'][$val],
			       'ENVIRONMENT_DESCRIPTION_ID' => $env_data['description_translation']['trans_id'],
			       )
			 );
	  }
	else
	  {
	    // Generate Ids if none given
	    $tpl->setVar(array(
			       'ENVIRONMENT_DESCRIPTION_ID' => $this->getUniqueId(),
			       )
			 );
	  }
	$tpl->parseCurrentBlock();
      }


    $last_order_number = 0;
    // Build, form for Heuristics
    if($env_to_change->init_ok_)
      {
	$attributes = $env_data['attributes'];

	if(is_array($attributes))
	  {
	    foreach ( $attributes as $key => $current_attribute )
	      {
		foreach ( $LANGUAGES as $lang_key => $lang_val )
		  {
		    $tpl->setCurrentBlock('value_title_language_block');
		    $tpl->setVar(array(
				       'ATTRIBUTE_TITLE' => $current_attribute['title_translation'][$lang_val],
				       'VALUE_TRANSLATION_ID' => $current_attribute['title_translation']['trans_id'],
				       'ATTRIBUTE_ID' => $key,
				       'VALUE_LANGUAGE_CODE' => $lang_val
				       )
				 );
		    $tpl->parseCurrentBlock();
		  }

		$tpl->setCurrentBlock('value_title_block');
		$tpl->setVar(array(
				   'ATTRIBUTE_ID' => $key,
				   'ATTRIBUTE_ORDER' =>$current_attribute['envOrder'],
				   'ENVIRONMENT_ID' =>$current_attribute['envId'],
				   'LABEL_DELETE' => $this->getLabelText('LABEL_DELETE'),
				   )
			     );
		$last_order_number = $current_attribute['envOrder'];
		$tpl->parseCurrentBlock();		

	      }

	  }
      }

    $additional_fields = $this->getGetRequestField('addFields', null);
    
    if(!is_null($additional_fields))
      {
	for($i=0; $i < $additional_fields; $i++)
	  {
	    $this->debug("AddFields:". $additional_fields);
	    $new_title_trans_id = $this->getUniqueId();
	    $new_description_trans_id = $this->getUniqueId();
	    $new_attribute_id = $this->getUniqueId();




		foreach ( $LANGUAGES as $lang_key => $lang_val )
		  {
		    $tpl->setCurrentBlock('value_title_language_block');
		    $tpl->setVar(array(
				       'VALUE_TRANSLATION_ID' => $new_title_trans_id,
				       'ATTRIBUTE_ID' => $new_attribute_id,
				       'VALUE_LANGUAGE_CODE' => $lang_val
				       )
				 );
		    $tpl->parseCurrentBlock();
		  }


		$tpl->setCurrentBlock('value_title_block');
		$tpl->setVar(array(
				   'ATTRIBUTE_ID' => $new_attribute_id,
				   'ATTRIBUTE_ORDER' =>++$last_order_number,
				   'ENVIRONMENT_ID' =>$env_to_change->envId,
				   'LABEL_DELETE' => $this->getLabelText('LABEL_DELETE'),
				   )
			     );
		$tpl->parseCurrentBlock();		
	  }
      }
        
    // Set Additional Fields
    $tpl->setVar(array(
		       'LABEL_ADD_ENVIRONMENT_FIELDS' => $this->getLabelText('LABEL_ADD_ENVIRONMENT_FIELDS'),
		       )
		 );
    

    // Set title and messages
    if($env_to_change->init_ok_)
      {
	$tpl->setVar(array(
			   'ADD_ENVIRONMENT_TITLE' => $this->getLabelText('CHANGE_ENVIRONMENT_TITLE'),
			   'ENVIRONMENT_ID' => $env_to_change->envId,
			   )
		     );
	$this->app_name_ = $this->getLabelText('CHANGE_ENVIRONMENT_TITLE');
	$this->app_breadcrumbs_[] = Array(
					  'url' => $_SERVER['REQUEST_URI'],
					  'label' => $this->getLabelText('CHANGE_ENVIRONMENT_TITLE'),
					  );
	$this->app_breadcrumbs_[] = Array(
					  'label' => $env_data['title_translation'][$this->language_],
					  );
      }
    else
      {
	$tpl->setVar(array(
			   'ADD_ENVIRONMENT_TITLE' => $this->getLabelText('ADD_ENVIRONMENT_TITLE'),
			   'ENVIRONMENT_ID' => $this->getUniqueId(),
			   )
		     );
	$this->app_name_ = $this->getLabelText('ADD_ENVIRONMENT_TITLE');		     
	
	$this->app_breadcrumbs_[] = Array(
					  'url' => $_SERVER['REQUEST_URI'],
					  'label' => $this->getLabelText('ADD_ENVIRONMENT_TITLE'),
					  );
      }


    $tpl->setCurrentBlock('main_block');

    $tpl->setVar(array(
		       'MESSAGES' => $message_text
		       )
		 );

    $tpl->setVar(array(
		       'LABEL_SUBMIT_BUTTON' => $this->getLabelText('SUBMIT_BUTTON'),
		       'LABEL_CANCEL_BUTTON' => $this->getLabelText('CANCEL_BUTTON')
		       )
		 );

    //Set Hidden Fields
    $tpl->setVar(array(
		       'FORM_ACTION' => $PHP_SELF,
		       'FORM_METHOD' => 'POST',
		       )
		 );

    $tpl->parseCurrentBlock();

    return TRUE;
  }


  function environmentOverview()
  {
    global $ENVIRONMENT_OVERVIEW_TEMPLATE;

    $this->showScreen($ENVIRONMENT_OVERVIEW_TEMPLATE, 'displayEnvironmentOverview', $this->getAppName());
  }


  function displayEnvironmentOverview(& $tpl)
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

    $dummy_env =& new Environment(0, $this->dbi_);

    $env_ids = $dummy_env->getEnvironmentIds();

    if(is_array($env_ids))
      {
	$i=0;
	foreach($env_ids as $key => $current_env_id)
	  {
	    $current_env_object =& new Environment($current_env_id, $this->dbi_);
	    $env_data = $current_env_object->getEnvironment();

	    $tpl->setCurrentBlock('set_block');
	    $tpl->setVar(array(
			       'BG_CLASS' => ($i%2?'odd':'even'),
			       'ENVIRONMENT_TITLE' => $env_data['title_translation'][$this->language_],
			       'EDIT_URL' => $_SERVER['PHP_SELF']."?cmd=addEnvironment&envid=".$current_env_object->envId,
			       'LABEL_CHANGE_ENVIRONMENT' => $this->getlabelText('LABEL_CHANGE_ENVIRONMENT'),
			       'DELETE_URL' => $_SERVER['PHP_SELF']."?cmd=deleteEnvironment&envid=".$current_env_object->envId,
			       'LABEL_DELETE_ENVIRONMENT' => $this->getlabelText('LABEL_DELETE_ENVIRONMENT'),
			       )
			 );
	    
	    $tpl->parseCurrentBlock();
	    $i++;
	  }
      }

    $tpl->setCurrentBlock('main_block');

    $tpl->setVar(array(
		       'LABEL_ADD_ENVIRONMENT' => $this->getLabelText('LABEL_ADD_ENVIRONMENT'),
		       'LABEL_TITLE' => $this->getLabelText('LABEL_TITLE'), 
		       'LABEL_OPERATIONS' => $this->getLabelText('LABEL_OPERATIONS'), 
		       'ADD_URL' => $_SERVER['PHP_SELF']."?cmd=addEnvironment"
		       )
		 );

    $tpl->setVar('ENVIRONMENT_OVERVIEW_TITLE', $this->getLabelText('ENVIRONMENT_OVERVIEW_TITLE'));

    $tpl->setVar(array(
		       'MESSAGES' => $message_text
		       )
		 );

    $tpl->parseCurrentBlock();

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
    $envid = $this->getGetRequestField('envid', null);

    $message_text = '';
    if($this->hasSessionMessages() == TRUE)
      {
	$messages = $this->getAllSessionMessages();
	while($msg = array_pop($messages))
	  {
	    $message_text.=$msg;
	  }
      }    

    $translation = & new Translation(0, $this->dbi_);

    if(!is_null($cmd) && $cmd == 'deleteEnvironment')
      {
	$tpl->setVar(array(
			   'CONFIRM_MESSAGE' => $this->getMessageText('CONFIRM_MESSAGE')
			   )
		     );

	if(!is_null($envid))
	  {
	    $env_to_delete = & new Environment($envid, $this->dbi_);
	    $tpl->setVar(array(
			       'ENVIRONMENT_TITLE' => $translation->getTranslation($env_to_delete->envTitleId, $this->language_),
			       )
			 );
	    $tpl->setVar(array(
			       'ENVIRONMENT_ID' => $envid
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
				      'label' => $this->getLabelText('CONFIRM_TITLE')
				      );
    
    $tpl->setVar(array(
		       'FORM_ACTION' => $_SERVER['PHP_SELF'],
		       'CONFIRM_TITLE' => $this->getLabelText('CONFIRM_TITLE')
		       )
		 );

	$this->app_name_ = $this->getLabelText('CONFIRM_TITLE');
    $tpl->setVar(array(
		       'MESSAGES' => $message_text
		       )
		 );


    
    return TRUE;
  }

}
?>