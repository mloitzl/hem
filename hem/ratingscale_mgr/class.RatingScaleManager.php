<?php

class RatingscaleManager extends PHPApplication
{
  
  function run()
  {
    //    global $globals, $HOME_APP;
    global $HOME_APP, $HOME_APP_LABEL;


    if($this->auth_handler_->checkRight(MANAGE_RATINGSCALES))
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

	$this->RatingScaleManagerDriver();
      }
    else
      {
	header("Location: $HOME_APP");
      }
  }


  function RatingScaleManagerDriver()
  {
    global $PHP_SELF;

    // A Form has been subitted, check which one
    $form = $this->getPostRequestField('form_id', null);
    $answer = $this->getPostRequestField('Yes', null);

    if(!is_null($form))
      {
	switch ($form)
	  {
	  case 'confirm':
	    if(!is_null($answer))
	      $this->doDeleteScale();
	    break;
	  case 'addRatingScale':
	    // Submit button hit, we're ready
	    if(!is_null($this->getPostRequestField('Submit', null)))
	      {
		$this->doAddRatingScale();
	      }
	    // Add fields Button pressed, go back
	    elseif(!is_null($this->getPostRequestField('AddFields', null)))
	      {
		$this->doAddRatingScale();
		$scale = $this->getPostRequestField('scale', null);
		$add_fields = $this->getPostRequestField('addFields', null);
		header("Location: ".$PHP_SELF."?cmd=addRatingScale&sid=".$scale['scale_id']."&addFields=".$add_fields);
	      }
	    elseif(!is_null($this->getPostRequestField('delete_value', null)))
	      {
		// Save changes made in the form
		$this->doAddRatingScale();
		// Do Deletion
		$this->doDeleteValue();
		$scale = $this->getPostRequestField('scale', null);
		// Adding fields at deletion button is a bit confusing
		// Changed default value from number of fields to add to one, 
		// which causes that one has to write value into the field, 
		// which is a bit more complicated
		$add_fields = $this->getPostRequestField('addFields', null);
		header("Location: ".$PHP_SELF."?cmd=addRatingScale&sid=".$scale['scale_id']."&addFields=".$add_fields);
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
	  case 'addRatingScale':
	    $this->addRatingScaleDriver();
	    break;
	  case 'deleteRatingScale':
	    $this->getConfirmation();
	    break;
	  default:
	    $this->debug('Calling RatingScale Overview');
	    $this->ratingScaleOverview();
	    break;
	  }
      }
    else
      {
	$this->ratingScaleOverview();
      }

  }


  function doAddRatingScale()
  {
    global $LANGUAGES;

    $this->debug("doAddRatingScale()");
    //    $this->dumpArray($_REQUEST);

    $scale_data = $this->getPostRequestField('scale', null);
    $data = $this->getPostRequestField('data', null);
    
    //    $this->dumpArray($scale_data['translation']);


    if(!is_null($scale_data['scale_id']))
      $scale = & new RatingScale($scale_data['scale_id'], $this->dbi_);
    else
      $scale = & new RatingScale(0, $this->dbi_);

    $translation = & new Translation(0, $this->dbi_);
    
    if($scale->init_ok_)
      {
	$this->debug("Updating Scale");
	// Update Title
	foreach( $LANGUAGES as $key => $language_code)
	  {
	    $translation->updateTranslation(
					    $scale_data['translation']['trans_id'],
					    $language_code, 
					    $scale_data['translation'][$language_code]
					    );
	    $this->debug("Scale Text: " 
			 .$scale_data['translation'][$language_code]." "
			 .$language_code." "
			 .$scale_data['translation']['trans_id']);
	  }	

	// Update / Add Values
	if(is_array($data))
	  {
	    foreach( $data as $scale_value_id => $scale_value_data)
	      {
		$current_scale_value_object =& new RatingScaleValue($scale_value_id, $this->dbi_);
		// Scale Value exists
		if($current_scale_value_object->init_ok_)
		  {
		    // Update Translation
		    foreach( $LANGUAGES as $key => $language_code)
		      {
			$translation->updateTranslation(
							$scale_value_data['translation']['trans_id'],
							$language_code, 
							$scale_value_data['translation'][$language_code]
							);
			$this->debug("Text: ".$scale_value_data['translation'][$language_code]);
			$scale_value_data['scaleValueId'] = $scale_value_id;
			$scale_value_data['scaleValue'] = $scale_value_data['scalevalue'];
			$current_scale_value_object->updateData($scale_value_data);
		      }
		  }
		// Scale Value does'nt exist
		else
		  {
		    // Create Translation
		    foreach( $LANGUAGES as $key => $language_code)
		      {
			if(!( empty($scale_value_data['scalevalue']) && empty($scale_value_data['translation'][$language_code]) ) )
			  {
			    $translation->addTranslation(
							 $scale_value_data['translation']['trans_id'],
							 $language_code, 
							 $scale_value_data['translation'][$language_code]
							 );
			    $scale_value_data['scaleValueId'] = $scale_value_id;
			    $scale_value_data['scaleValue'] = $scale_value_data['scalevalue'];
			    $scale_value_data['scaleValueCaptionId'] = $scale_value_data['translation']['trans_id'];
			    $scale_value_data['scaleId'] = $scale->scaleId;
			    $current_scale_value_object->addData($scale_value_data);
			  }
			else
			  {
			    $this->addSessionMessage('VALUE_TEXT_FIELDS_EMPTY');
			  }
		      }
		  }
	      }
	  }
      }
    else
      {
	$this->debug("Adding Scale");
	// Add Title

	$scale_data = $this->getPostRequestField('scale', null);

	$translation_id = $this->getUniqueId();

	foreach( $LANGUAGES as $key => $language_code)
	  {
	    $translation->addTranslation(
					 $translation_id,
					 $language_code, 
					 $scale_data['translation'][$language_code]
					 );
	    $this->debug("Scale Text: " 
			 .$translation_id." "
			 .$language_code." "
			 .$scale_data['translation']['trans_id']);
	  }	

	$scale = & new RatingScale(0, $this->dbi_);

	$data = array(
		      'scaleId' => $scale_data['scale_id'],
		      'scaleTitleId' => $translation_id,
		      );

	$scale->addData($data);
			      
	// A new Rating Scale has no Values!!!
	// Adding a Value is already an Scale Update!
      }
  }
  
  function doDeleteValue()
  {
    if(!is_null($delete_value = $this->getPostRequestField('delete_value', null)))
      {
	$id = array_keys($delete_value);
	$value_object = & new RatingScaleValue($id[0], $this->dbi_);
	$value_object->deleteData();
      }
  }


  function doDeleteScale()
  {
    //    $this->dumpArray($_REQUEST);

    $sid = $this->getPostRequestField('sid', null);

    if(!is_null($sid))
      {
	$scale_to_delete = & new RatingScale($sid, $this->dbi_);
	if($scale_to_delete->init_ok_)
	  {
	    $translation = & new Translation(0, $this->dbi_);
	    $translation->removeTranslation($scale_to_delete->scaleTitleId);
	    
	    $dummy_scale_value = & new RatingScaleValue(0, $this->dbi_);
	    $filter = array('scaleId' => array( 'name' => 'scaleId', 'op' => '=', 'value' => "$scale_to_delete->scaleId", 'cond' => ''));
	    $value_ids = $dummy_scale_value->getAllRatingScaleValueIds($filter);

	    foreach($value_ids as $key => $id)
	      {
		$value_object = & new RatingScaleValue($id, $this->dbi_);
		
		$translation->removeTranslation($value_object->scaleValueCaptionId);
		$value_object->deleteData();
	      }
	    
	    $scale_to_delete->deleteData();
	  }
      }
  }


  function addRatingScaleDriver()
  {
    global $ADD_RATINGSCALE_TEMPLATE;

    $this->showScreen($ADD_RATINGSCALE_TEMPLATE, 'displayAddRatingscaleForm', $this->getAppName());
  }


  function displayAddRatingScaleForm(& $tpl)
  {
    global $LANGUAGES, $PHP_SELF;

    if(!is_null($scale_id = $this->getGetRequestField('sid', null)))
      {
	$this->debug('ScaleId:'. $scale_id);
	$scale_to_change = & new RatingScale($scale_id, $this->dbi_);
      }
    else
      $scale_to_change = & new RatingScale(0, $this->dbi_);

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
		       'LABEL_VALUE' => $this->getLabelText('LABEL_VALUE'),
		       )
		 );
    

    // Set Table Captions
    $tpl->setVar(array(
		       'LABEL_RATINGSCALE_TITLE' => $this->getLabelText('LABEL_RATINGSCALE_TITLE'),
		       'LABEL_RATINGSCALE_VALUES' => $this->getLabelText('LABEL_RATINGSCALE_VALUES'),
		       )
		 );
    // Build Form
    foreach ( $LANGUAGES as $key => $val )
      {
	$tpl->setCurrentBlock('title_language_block');
	$tpl->setVar('TITLE_LANGUAGE_CODE', $val);

	if($scale_to_change->init_ok_)
	  {
	    $tpl->setVar(array(
			       'RATINGSCALE_TITLE' => $translation->getTranslation($scale_to_change->scaleTitleId, $val),
			       'RATINGSCALE_TITLE_ID' => $scale_to_change->scaleTitleId,
			       )
			 );
	  }
	$this->debug("Scale to Change TitleId: $scale_to_change->scaleTitleId");
	$tpl->parseCurrentBlock();
      }

    if($scale_to_change->init_ok_)
      {
	$dummy_scale_object = & new RatingScaleValue(0, $this->dbi_);
	$filter = array('scaleId' => array( 'name' => 'scaleId', 'op' => '=', 'value' => "$scale_to_change->scaleId", 'cond' => ''));
	$value_ids = $dummy_scale_object->getAllRatingScaleValueIds($filter, 'scaleValue', 'ASC');

	if(is_array($value_ids))
	  {
	    foreach ( $value_ids as $key => $val )
	      {
		$current_value_object = & new RatingScaleValue($val, $this->dbi_);
		
		foreach ( $LANGUAGES as $lang_key => $lang_val )
		  {
		    $tpl->setCurrentBlock('value_language_block');
		    $tpl->setVar(array(
				       'RATINGSCALEVALUE_TITLE' => $translation->getTranslation($current_value_object->scaleValueCaptionId, $lang_val),
				       'VALUE_TRANSLATION_ID' => $current_value_object->scaleValueCaptionId,
				       'SCALE_VALUE_ID' => $val,
				       'VALUE_LANGUAGE_CODE' => $lang_val
				       )
				 );
		    $tpl->parseCurrentBlock();
		  }

		$tpl->setCurrentBlock('value_block');
		$tpl->setVar(array(
				   'SCALE_VALUE_ID' => $val,
				   'RATINGSCALE_VALUE' =>$current_value_object->scaleValue,
				   'LABEL_DELETE' => $this->getLabelText('LABEL_DELETE'),
				   )
			     );
		$tpl->parseCurrentBlock();		
	      }
	  }
      }
    else
      {


      }
    

    $additional_fields = $this->getGetRequestField('addFields', null);
    
    if(!is_null($additional_fields))
      {
	for($i=0; $i < $additional_fields; $i++)
	  {
	    $this->debug("AddFields:". $additional_fields);
	    $new_trans_id = $this->getUniqueId();
	    $new_scale_value_id = $this->getUniqueId();
	    foreach ( $LANGUAGES as $lang_key => $lang_val )
	      {
		$tpl->setCurrentBlock('value_language_block');
		$tpl->setVar(array(
				   'VALUE_TRANSLATION_ID' => $new_trans_id,
				   'SCALE_VALUE_ID' => $new_scale_value_id,
				   'VALUE_LANGUAGE_CODE' => $lang_val
				   )
			     );
		$tpl->parseCurrentBlock();
	      }
	    
	    $tpl->setCurrentBlock('value_block');
	    $tpl->setVar(array(
			       'SCALE_VALUE_ID' => $new_scale_value_id,
			       'LABEL_DELETE' => $this->getLabelText('LABEL_DELETE'),
			       )
			 );
	    $tpl->parseCurrentBlock();	
	  }
      }
    
    
    // Set Additional Fields
    $tpl->setVar(array(
		       'LABEL_ADD_VALUE_FIELDS' => $this->getLabelText('LABEL_ADD_VALUE_FIELDS'),
		       )
		 );
    

    // Set title and messages
    if($scale_to_change->init_ok_)
      {
	$tpl->setVar(array(
			   'ADD_RATINGSCALE_TITLE' => $this->getLabelText('CHANGE_RATINGSCALE_TITLE'),
			   'RATINGSCALE_ID' => $scale_to_change->scaleId,
			   )
		     );
	$this->app_name_ = $this->getLabelText('CHANGE_RATINGSCALE_TITLE');		     
	$this->app_breadcrumbs_[] = Array(
					  'url' => $_SERVER['SCRIPT_NAME'],
					  'label' => $this->getLabelText('CHANGE_RATINGSCALE_TITLE'),
					  );
	$this->app_breadcrumbs_[] = Array(
					  'label' => $translation->getTranslation($scale_to_change->scaleTitleId, $this->language_),
					  );
      }
    else
      {
	$tpl->setVar(array(
			   'ADD_RATINGSCALE_TITLE' => $this->getLabelText('ADD_RATINGSCALE_TITLE'),
			   'RATINGSCALE_ID' => $this->getUniqueId(),
			   )
		     );
	$this->app_name_ = $this->getLabelText('ADD_RATINGSCALE_TITLE');		     
	$this->app_breadcrumbs_[] = Array(
					  'url' => $_SERVER['SCRIPT_NAME'],
					  'label' => $this->getLabelText('ADD_RATINGSCALE_TITLE'),
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


  function ratingScaleOverview()
  {
    global $RATINGSCALE_OVERVIEW_TEMPLATE;
    // TODO: Handle submitted data

    $this->debug('RatingscaleOverview');

    $this->showScreen($RATINGSCALE_OVERVIEW_TEMPLATE, 'displayRatingscaleOverview', $this->getAppName());
  }


  function displayRatingScaleOverview(& $tpl)
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

    $scale =& new RatingScale(0, $this->dbi_);

    $scale_ids = $scale->getAllRatingScaleIds();

    $translation = & new Translation(0, $this->dbi_);

    if(is_array($scale_ids))
      {
	$i=0;
	while($current_scale_id = array_pop($scale_ids))
	  {
	    $current_scale_object =& new RatingScale($current_scale_id, $this->dbi_);

	    $tpl->setCurrentBlock('scale_block');
	    $tpl->setVar(array(
			       'BG_CLASS' => ($i%2?'odd':'even'),
			       'RATINGSCALE_TITLE' => $translation->getTranslation($current_scale_object->scaleTitleId, $this->language_),
			       'EDIT_URL' => $_SERVER['PHP_SELF']."?cmd=addRatingScale&sid=".$current_scale_object->scaleId,
			       'LABEL_CHANGE_RATINGSCALE' => $this->getlabelText('LABEL_CHANGE_RATINGSCALE'),
			       'DELETE_URL' => $_SERVER['PHP_SELF']."?cmd=deleteRatingScale&sid=".$current_scale_object->scaleId,
			       'LABEL_DELETE_RATINGSCALE' => $this->getlabelText('LABEL_DELETE_RATINGSCALE'),
			       )
			 );
	    
	    $tpl->parseCurrentBlock();
	    $i++;
	  }

      }

    $tpl->setCurrentBlock('main_block');

    $tpl->setVar(array(
		       'LABEL_ADD_RATINGSCALE' => $this->getLabelText('LABEL_ADD_RATINGSCALE'), 
		       'LABEL_TITLE' => $this->getLabelText('LABEL_TITLE'), 
		       'LABEL_OPERATIONS' => $this->getLabelText('LABEL_OPERATIONS'), 
		       'ADD_URL' => $_SERVER['PHP_SELF']."?cmd=addRatingScale"
		       )
		 );

    $tpl->setVar('RATINGSCALE_OVERVIEW_TITLE', $this->getLabelText('RATINGSCALE_OVERVIEW_TITLE'));

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

    if(!is_null($cmd) && $cmd == 'deleteRatingScale')
      {
	$tpl->setVar(array(
			   'CONFIRM_MESSAGE' => $this->getMessageText('CONFIRM_MESSAGE')
			   )
		     );

	if(!is_null($sid))
	  {
	    $scale_to_delete = & new RatingScale($sid, $this->dbi_);
	    $tpl->setVar(array(
			       'SCALE_TITLE' => $translation->getTranslation($scale_to_delete->scaleTitleId, $this->language_),
			       )
			 );
	    $tpl->setVar(array(
			       'SCALE_ID' => $sid
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

}
?>