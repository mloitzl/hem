<?php

class HeuristicSetManager extends PHPApplication
{
  
  function run()
  {
    // global $globals, $HOME_APP;

    global $HOME_APP, $HOME_APP_LABEL;
    
    


    if($this->getGetRequestField('heuristicHelp', null) !== null)
      {
	$this->heuristicHelp();
      }
    else if($this->auth_handler_->checkRight(EDIT_HEURISTIC_SETS))
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
	      $this->doDeleteSet();
	    break;
	  case 'addHeuristicSet':
	    // Submit button hit, we're ready
	    if(!is_null($this->getPostRequestField('Submit', null)))
	      {
		$this->doAddHeuristicSet();
	      }
	    // Add fields Button pressed, go back
	    elseif(!is_null($this->getPostRequestField('AddFields', null)))
	      {
		$this->doAddHeuristicSet();
		$data = $this->getPostRequestField('data', null);
		$add_fields = $this->getPostRequestField('addFields', null);
		header("Location: ".$PHP_SELF."?cmd=addHeuristicSet&sid=".$data['hSetId']."&addFields=".$add_fields);
	      }
	    elseif(!is_null($this->getPostRequestField('delete_value', null)))
	      {
		// Save changes made in the form
		$this->doAddHeuristicSet();
		// Do Deletion
		$this->doDeleteHeuristic();
		$data = $this->getPostRequestField('data', null);
		// Adding fields at deletion button is a bit confusing
		// Changed default value from number of fields to add to one, 
		// which causes that one has to write value into the field, 
		// which is a bit more complicated
		$add_fields = $this->getPostRequestField('addFields', null);
		header("Location: ".$PHP_SELF."?cmd=addHeuristicSet&sid=".$data['hSetId']."&addFields=".$add_fields);
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
	  case 'addHeuristicSet':
	    $this->addHeuristicSetDriver();
	    break;
	  case 'deleteHeuristicSet':
	    $this->getConfirmation();
	    break;
	  default:
	    $this->debug('Calling HeuristicSet Overview');
	    $this->ratingScaleOverview();
	    break;
	  }
      }
    else
      {
	$this->heuristicSetOverview();
      }

  }


  function doAddHeuristicSet()
  {
    global $LANGUAGES;

    $this->debug("doAddHeuristicSet()");

    //    $this->dumpArray($_REQUEST);

    $data = $this->getPostRequestField('data', null);

    $dummy_heur_set = & new HeuristicSet($data['hSetId'], $this->dbi_);
    if(!is_null($data))
      {
	$dummy_heur_set->storeHeuristicSet($data);
      }
    else
      {
	// TODO: Error Handling
      }

    //    echo $dummy_heur_set->getError();

  }
  
  function doDeleteHeuristic()
  {
    if(!is_null($delete_value = $this->getPostRequestField('delete_value', null)))
      {
	$id = array_keys($delete_value);
	$value_object = & new Heuristic($id[0], $this->dbi_);
	$value_object->removeHeuristic();
      }
  }


  function doDeleteSet()
  {
    //    $this->dumpArray($_REQUEST);

    $sid = $this->getPostRequestField('sid', null);

    if(!is_null($sid))
      {
	$set_to_delete = & new HeuristicSet($sid, $this->dbi_);
	if($set_to_delete->init_ok_)
	  {
	    $set_to_delete->removeHeuristicSet();
	  }
      }
  }


  function addHeuristicSetDriver()
  {
    global $ADD_HEURISTICSET_TEMPLATE;

    $this->showScreen($ADD_HEURISTICSET_TEMPLATE, 'displayAddHeuristicSetForm', $this->getAppName());
  }


  function displayAddHeuristicSetForm(& $tpl)
  {
    global $LANGUAGES, $PHP_SELF;

    if(!is_null($set_id = $this->getGetRequestField('sid', null)))
      {
	$this->debug('SetId:'. $set_id);
	$set_to_change = & new HeuristicSet($set_id, $this->dbi_);
	$set_data = $set_to_change->getHeuristicSet();
      }
    else
      $set_to_change = & new HeuristicSet(0, $this->dbi_);

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
		       'LABEL_HEURISTICSET_TITLE' => $this->getLabelText('LABEL_HEURISTICSET_TITLE'),
		       'LABEL_HEURISTICSET_VALUES' => $this->getLabelText('LABEL_HEURISTICSET_VALUES'),
		       )
		 );
    // Build Form ------

    // Build Title part
    foreach ( $LANGUAGES as $key => $val )
      {
	$tpl->setCurrentBlock('title_language_block');
	$tpl->setVar('TITLE_LANGUAGE_CODE', $val);

	if($set_to_change->init_ok_)
	  {
	    $tpl->setVar(array(
			       'HEURISTICSET_TITLE' => $set_data['title_translation'][$val],
			       'HEURISTICSET_TITLE_ID' => $set_data['title_translation']['trans_id'],
			       )
			 );
	  }
	else
	  {
	    // Generate Ids if none given
	    $tpl->setVar(array(
			       'HEURISTICSET_TITLE_ID' => $this->getUniqueId(),
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

	//	if($set_to_change->init_ok_)
	if(isset($set_data['description_translation']) && !is_null($set_data['description_translation'][$val]))
	  {
	    $tpl->setVar(array(
			       'HEURISTICSET_DESCRIPTION' => $set_data['description_translation'][$val],
			       'HEURISTICSET_DESCRIPTION_ID' => $set_data['description_translation']['trans_id'],
			       )
			 );
	  }
	else
	  {
	    // Generate Ids if none given
	    $tpl->setVar(array(
			       'HEURISTICSET_DESCRIPTION_ID' => $this->getUniqueId(),
			       )
			 );
	  }
	$tpl->parseCurrentBlock();
      }


    $last_order_number = 0;
    // Build, form for Heuristics
    if($set_to_change->init_ok_)
      {
	$heuristics = $set_data['heuristics'];

	if(is_array($heuristics))
	  {
	    foreach ( $heuristics as $key => $current_heuristic )
	      {
		foreach ( $LANGUAGES as $lang_key => $lang_val )
		  {
		    $tpl->setCurrentBlock('value_title_language_block');
		    $tpl->setVar(array(
				       'HEURISTIC_TITLE' => $current_heuristic['title_translation'][$lang_val],
				       'VALUE_TRANSLATION_ID' => $current_heuristic['title_translation']['trans_id'],
				       'HEURISTIC_ID' => $key,
				       'VALUE_LANGUAGE_CODE' => $lang_val
				       )
				 );
		    $tpl->parseCurrentBlock();
		  }

		foreach ( $LANGUAGES as $lang_key => $lang_val )
		  {
		    $tpl->setCurrentBlock('value_description_language_block');
		    $tpl->setVar(array(
				       'HEURISTIC_DESCRIPTION' => $current_heuristic['description_translation'][$lang_val],
				       'DESCRIPTION_TRANSLATION_ID' => $current_heuristic['description_translation']['trans_id'],
				       'HEURISTIC_ID' => $key,
				       'VALUE_LANGUAGE_CODE' => $lang_val
				       )
				 );
		    $tpl->parseCurrentBlock();
		  }


		$tpl->setCurrentBlock('value_title_block');
		$tpl->setVar(array(
				   'HEURISTIC_ID' => $key,
				   'HEURISTIC_ORDER' =>$current_heuristic['hOrder'],
				   'HEURISTIC_SET' =>$current_heuristic['hSetId'],
				   'LABEL_DELETE' => $this->getLabelText('LABEL_DELETE'),
				   )
			     );
		$last_order_number = $current_heuristic['hOrder'];
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
	    $new_heuristic_id = $this->getUniqueId();




		foreach ( $LANGUAGES as $lang_key => $lang_val )
		  {
		    $tpl->setCurrentBlock('value_title_language_block');
		    $tpl->setVar(array(
				       'VALUE_TRANSLATION_ID' => $new_title_trans_id,
				       'HEURISTIC_ID' => $new_heuristic_id,
				       'VALUE_LANGUAGE_CODE' => $lang_val
				       )
				 );
		    $tpl->parseCurrentBlock();
		  }

		foreach ( $LANGUAGES as $lang_key => $lang_val )
		  {
		    $tpl->setCurrentBlock('value_description_language_block');
		    $tpl->setVar(array(
				       'DESCRIPTION_TRANSLATION_ID' => $new_description_trans_id,
				       'HEURISTIC_ID' => $new_heuristic_id,
				       'VALUE_LANGUAGE_CODE' => $lang_val
				       )
				 );
		    $tpl->parseCurrentBlock();
		  }


		$tpl->setCurrentBlock('value_title_block');
		$tpl->setVar(array(
				   'HEURISTIC_ID' => $new_heuristic_id,
				   'HEURISTIC_ORDER' =>++$last_order_number,
				   'HEURISTIC_SET' =>$set_to_change->hSetId,
				   'LABEL_DELETE' => $this->getLabelText('LABEL_DELETE'),
				   )
			     );
		$tpl->parseCurrentBlock();		
	  }
      }
        
    // Set Additional Fields
    $tpl->setVar(array(
		       'LABEL_ADD_HEURISTIC_FIELDS' => $this->getLabelText('LABEL_ADD_HEURISTIC_FIELDS'),
		       )
		 );
    

    // Set title and messages
    if($set_to_change->init_ok_)
      {
	$tpl->setVar(array(
			   'ADD_HEURISTICSET_TITLE' => $this->getLabelText('CHANGE_HEURISTICSET_TITLE'),
			   'HEURISTICSET_ID' => $set_to_change->hSetId,
			   )
		     );
	$this->app_name_ = $this->getLabelText('CHANGE_HEURISTICSET_TITLE');
	$this->app_breadcrumbs_[] = Array(
					  'url' => $_SERVER['REQUEST_URI'],
					  'label' => $current_heuristic['title_translation'][$this->language_],
					  );
	$this->app_breadcrumbs_[] = Array(
					  'label' => $this->getLabelText('CHANGE_HEURISTICSET_TITLE'),
					  );
      }
    else
      {
	$tpl->setVar(array(
			   'ADD_HEURISTICSET_TITLE' => $this->getLabelText('ADD_HEURISTICSET_TITLE'),
			   'HEURISTICSET_ID' => $this->getUniqueId(),
			   )
		     );
	$this->app_name_ = $this->getLabelText('ADD_HEURISTICSET_TITLE');
	$this->app_breadcrumbs_[] = Array(
					  'label' => $this->getLabelText('ADD_HEURISTICSET_TITLE'),
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


  function heuristicSetOverview()
  {
    global $HEURISTICSET_OVERVIEW_TEMPLATE;

    $this->showScreen($HEURISTICSET_OVERVIEW_TEMPLATE, 'displayHeuristicSetOverview', $this->getAppName());
  }


  function displayHeuristicSetOverview(& $tpl)
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

    $dummy_set =& new HeuristicSet(0, $this->dbi_);

    $set_ids = $dummy_set->getHeuristicSetIds();

    if(is_array($set_ids))
      {
	$i=0;
	foreach($set_ids as $key => $current_set_id)
	  {
	    $current_set_object =& new HeuristicSet($current_set_id, $this->dbi_);
	    $set_data = $current_set_object->getHeuristicSet();

	    $tpl->setCurrentBlock('set_block');
	    $tpl->setVar(array(
			       'BG_CLASS' => ($i%2?'odd':'even'),
			       'HEURISTICSET_TITLE' => $set_data['title_translation'][$this->language_],
			       'HEURISTICSET_DESCRIPTION'  => $set_data['description_translation'][$this->language_],
			       'EDIT_URL' => $_SERVER['PHP_SELF']."?cmd=addHeuristicSet&sid=".$current_set_object->hSetId,
			       'LABEL_CHANGE_HEURISTICSET' => $this->getlabelText('LABEL_CHANGE_HEURISTICSET'),
			       'DELETE_URL' => $_SERVER['PHP_SELF']."?cmd=deleteHeuristicSet&sid=".$current_set_object->hSetId,
			       'LABEL_DELETE_HEURISTICSET' => $this->getlabelText('LABEL_DELETE_HEURISTICSET'),
			       )
			 );
	    
	    $tpl->parseCurrentBlock();
	    $i++;
	  }

      }

    $tpl->setCurrentBlock('main_block');

    $tpl->setVar(array(
		       'LABEL_TITLE' => $this->getLabelText('LABEL_TITLE'), 
		       'LABEL_OPERATIONS' => $this->getLabelText('LABEL_OPERATIONS'), 
		       'LABEL_ADD_HEURISTICSET' => $this->getLabelText('LABEL_ADD_HEURISTICSET'), 
		       'ADD_URL' => $_SERVER['PHP_SELF']."?cmd=addHeuristicSet"
		       )
		 );

    $tpl->setVar('HEURISTICSET_OVERVIEW_TITLE', $this->getLabelText('HEURISTICSET_OVERVIEW_TITLE'));

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
    $sid = $this->getGetRequestField('sid', null);

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

    if(!is_null($cmd) && $cmd == 'deleteHeuristicSet')
      {
	$tpl->setVar(array(
			   'CONFIRM_MESSAGE' => $this->getMessageText('CONFIRM_MESSAGE')
			   )
		     );

	if(!is_null($sid))
	  {
	    $set_to_delete = & new HeuristicSet($sid, $this->dbi_);
	    $tpl->setVar(array(
			       'SET_TITLE' => $translation->getTranslation($set_to_delete->hSetTitleId, $this->language_),
			       )
			 );
	    $tpl->setVar(array(
			       'SET_ID' => $sid
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
	$this->app_name_ = $this->getLabelText('CONFIRM_TITLE');		      
    
    $tpl->setVar(array(
		       'FORM_ACTION' => $_SERVER['PHP_SELF'],
		       'CONFIRM_TITLE' => $this->getLabelText('CONFIRM_TITLE')
		       )
		 );

    $tpl->setVar(array(
		       'MESSAGES' => $message_text
		       )
		 );


    
    return TRUE;
  }


  function heuristicHelp()
  {
    global $HEURISTICHELP_TEMPLATE;

    $this->showScreen($HEURISTICHELP_TEMPLATE, 'displayHeuristicHelp', $this->getAppName(), TRUE);
  }


  function displayHeuristicHelp(& $tpl)
  {
    $this->debug("Display Heuristic Help");
   
    $heuristic_set_id = $this->getGetRequestField('sid', null);
    

    if(!is_null($heuristic_set_id))
      {

	$heur_set = & new HeuristicSet($heuristic_set_id, $this->dbi_);
	
	
	$heur_set_data = $heur_set->getHeuristicSet();

	//	$this->dumpArray($heur_set_data);


	foreach($heur_set_data['heuristics'] as $current_heur_data)
	  {
	    $tpl->setCurrentBlock('heuristic_block');
	    $tpl->setVar(array(
			       'HEURISTIC_NUMBER' => $current_heur_data['hOrder'],
			       'HEURISTIC_TITLE' =>$current_heur_data['title_translation'][$this->language_],
			       'HEURISTIC_DESCRIPTION' =>$current_heur_data['description_translation'][$this->language_],
			   )
		     );
	    $tpl->parseCurrentBlock();

	  }


	$tpl->setVar(array(
			   'HEURISTIC_SET_TITLE' =>$heur_set_data['title_translation'][$this->language_],
			   'HEURISTIC_SET_DESCRIPTION' =>$heur_set_data['description_translation'][$this->language_],
			   )
		     );

      }

    $tpl->setVar(array(
		       'HEURISTICS_HELP_TITLE' => $this->getLabelText('HEURISTICS_HELP_TITLE'),
		       )
		 );

    return TRUE;
  }

}
?>