<?php

class RatingSchemeManager extends PHPApplication
{
  
  function run()
  {
    //    global $globals, $HOME_APP;
    global $HOME_APP, $HOME_APP_LABEL;

    if($this->auth_handler_->checkRight(MANAGE_RATINGSCHEMES))
      {
	$this->app_name_ = $this->getLabelText("APPLICATION_TITLE");
	$this->app_breadcrumbs_[] = Array(
					  'url' => $HOME_APP,
					  'label' => $HOME_APP_LABEL[$this->language_],
					  );
	
	$this->app_breadcrumbs_[] = Array(
					  'url' => $_SERVER['SCRIPT_NAME'],
					  'label' => $this->getLabelText("APPLICATION_TITLE"),
					  );

	$this->RatingSchemeManagerDriver();
      }
    else
      {
	header("Location: $HOME_APP");
      }
  }


  function RatingSchemeManagerDriver()
  {
    global $PHP_SELF;

    // A Form has been subitted, check which one
    $form = $this->getPostRequestField('form_id', null);
    $answer = $this->getPostRequestField('Yes', null);

    if(!is_null($form))
      {
	switch ($form)
	  {
	  case 'addRatingScheme':
	    // Submit button hit, we're ready
	    if(!is_null($this->getPostRequestField('Submit', null)))
	      {
		$this->doAddRatingScheme();
	      }
	    break;
	  case 'confirm':
	    if(!is_null($answer))
	      $this->doDeleteScheme();
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
	  case 'addRatingScheme':
	    $this->addRatingSchemeDriver();
	    break;
	  case 'deleteRatingScheme':
	    $this->getConfirmation();
	    break;
	  default:
	    $this->debug('Calling RatingScheme Overview');
	    $this->ratingSchemeOverview();
	    break;
	  }
      }
    else
      {
	$this->ratingSchemeOverview();
      }

  }


  function doAddRatingScheme()
  {
    global $LANGUAGES;

    $this->debug("doAddRatingScheme()");
    //    $this->dumpArray($_REQUEST);

    $scheme_data = $this->getPostRequestField('scheme', null);
    $data = $this->getPostRequestField('data', null);
    

    if(!is_null($scheme_data['scheme_id']))
      $scheme = & new RatingScheme($scheme_data['scheme_id'], $this->dbi_);
    else
      $scheme = & new RatingScheme(0, $this->dbi_);
    
    $translation = & new Translation(0, $this->dbi_);
    
    if($scheme->init_ok_)
      {
	$this->debug("Updating Scheme");
	// Update Title
	foreach( $LANGUAGES as $key => $language_code)
	  {
	    $translation->updateTranslation(
					    $scheme_data['translation']['trans_id'],
					    $language_code, 
					    $scheme_data['translation'][$language_code]
					    );
	    $this->debug("Scheme Text: " 
			 .$scheme_data['translation'][$language_code]." "
			 .$language_code." "
			 .$scheme_data['translation']['trans_id']);
	  }

	$data = array(
		      'schemeId' => $scheme_data['scheme_id'],
		      'schemeTitleId' => $scheme_data['translation']['trans_id'],
		      'schemeResultOperation' => $scheme_data['schemeResultOperation'],
		      );
	$scheme->updateData($data);
      }
    else
      {
	$this->debug("Adding Scheme");
	// Add Title
	
	$scheme_data = $this->getPostRequestField('scheme', null);
	
	$translation_id = $this->getUniqueId();
	
	foreach( $LANGUAGES as $key => $language_code)
	  {
	    $translation->addTranslation(
					 $translation_id,
					 $language_code, 
					 $scheme_data['translation'][$language_code]
					 );
	    $this->debug("Scheme Text: " 
			 .$translation_id." "
			 .$language_code." "
			 .$scheme_data['translation']['trans_id']);
	  }	
	
	$scheme = & new RatingScheme(0, $this->dbi_);
	
	$data = array(
		      'schemeId' => $scheme_data['scheme_id'],
		      'schemeTitleId' => $translation_id,
		      'schemeResultOperation' => $scheme_data['schemeResultOperation'],
		      );

	$scheme->addData($data);
			      
	// A new Rating Scheme has no Values!!!
	// Adding a Value is already an Scheme Update!
      }

    // Update Associated Scales
    $scale_data = $this->getPostRequestField('scales', null);

    // Remove all associated Scales
    $scheme->deleteAssociatedScaleIds($scheme_data['scheme_id']);    

    if(!is_null($scale_data) && is_array($scale_data))
      {
	$scale_ids = array_keys($scale_data);

	// Set associated Scales
	$scheme->setAssociatedScaleIds($scheme_data['scheme_id'], $scale_ids);
      }
  }
  
  function doDeleteScheme()
  {
    //    $this->dumpArray($_REQUEST);

    $sid = $this->getPostRequestField('sid', null);

    if(!is_null($sid))
      {
	$scheme_to_delete = & new RatingScheme($sid, $this->dbi_);
	if($scheme_to_delete->init_ok_)
	  {
	    $translation = & new Translation(0, $this->dbi_);
	    $translation->removeTranslation($scheme_to_delete->schemeTitleId);
	    
	    if($scheme_to_delete->deleteAssociatedScaleIds($sid))
	      $scheme_to_delete->deleteData();
	  }
      }
  }

  function addRatingSchemeDriver()
  {
    global $ADD_RATINGSCHEME_TEMPLATE;

    $this->showScreen($ADD_RATINGSCHEME_TEMPLATE, 'displayAddRatingSchemeForm', $this->getAppName());
  }


  function displayAddRatingSchemeForm(& $tpl)
  {
    global $LANGUAGES, $PHP_SELF;

    if(!is_null($scheme_id = $this->getGetRequestField('sid', null)))
      {
	$this->debug('SchemeId:'. $scheme_id);
	$scheme_to_change = & new RatingScheme($scheme_id, $this->dbi_);
      }
    else
      $scheme_to_change = & new RatingScheme(0, $this->dbi_);

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


    // Set Table Captions
    $tpl->setVar(array(
		       'LABEL_RATINGSCHEME_TITLE' => $this->getLabelText('LABEL_RATINGSCHEME_TITLE'),
		       'LABEL_ASSOCIATED_RATINGSCALES' => $this->getLabelText('LABEL_ASSOCIATED_RATINGSCALES'),
		       'LABEL_AVAILABLE_RATINGSCALES' => $this->getLabelText('LABEL_AVAILABLE_RATINGSCALES'),
		       )
		 );
    // Build Form
    foreach ( $LANGUAGES as $key => $val )
      {
	$tpl->setCurrentBlock('title_language_block');
	$tpl->setVar('TITLE_LANGUAGE_CODE', $val);

	if($scheme_to_change->init_ok_)
	  {
	    $tpl->setVar(array(
			       'RATINGSCHEME_TITLE' => $translation->getTranslation($scheme_to_change->schemeTitleId, $val),
			       'RATINGSCHEME_TITLE_ID' => $scheme_to_change->schemeTitleId,
			       )
			 );
	  }
	$this->debug("Scheme to Change TitleId: $scheme_to_change->schemeTitleId");
	$tpl->parseCurrentBlock();
      }

    // Show associated Scales
    if($scheme_to_change->init_ok_)
      {
	$associated_scale_ids = $scheme_to_change->getAssociatedScaleIds($scheme_to_change->schemeId);
	
	if(is_array($associated_scale_ids))
	  {
	    //	    $this->dumpArray($associated_scale_ids);
	    while($current_scale_id = array_pop($associated_scale_ids))
	      {
		$current_scale = & new RatingScale($current_scale_id, $this->dbi_);
		
		$tpl->setCurrentBlock('associated_scale_hidden_field');
		$tpl->setVar(array(
				   'SCALE_ID' =>$current_scale_id,
				   )
			     );
		$tpl->parseCurrentBlock();
		
		
		$tpl->setCurrentBlock('associated_scale_row');
		$tpl->setVar(array(
				   'SCALE_ID'=> $current_scale_id,
				   'SCALE_TITLE' => $translation->getTranslation($current_scale->scaleTitleId, $this->language_),
				   )
			     );
		$tpl->parseCurrentBlock();
	      }
	  }
      }
    
    
    // Show available Scales
    $dummy_scale = & new RatingScale(0, $this->dbi_);
    $available_scale_ids = $dummy_scale->getAllRatingScaleIds();

    if(is_array($available_scale_ids))
      {
	//	$this->dumpArray($available_scale_ids);

	while($current_scale_id = array_pop($available_scale_ids))
	  {
	    $current_scale_object = & new RatingScale($current_scale_id, $this->dbi_);
	    
	    $tpl->setCurrentBlock('available_scale_row');
	    $tpl->setVar(array(
			       'AVAILABLE_SCALE_ID'=> $current_scale_id,
			       'AVAILABLE_SCALE_TITLE' => $translation->getTranslation($current_scale_object->scaleTitleId, $this->language_),
			       )
			 );
	    $tpl->parseCurrentBlock();	    
	  }

      }

    // Display Result Operation Drop Down
    $tpl->setvar(array(
		       'LABEL_RESULT' => $this->getLabelText('LABEL_RESULT'),
		       'LABEL_SUM' => $this->getLabelText('LABEL_SUM'),
		       'LABEL_MULT' => $this->getLabelText('LABEL_MULT'),
		       'LABEL_AVERAGE' => $this->getLabelText('LABEL_AVERAGE'),
		       )
		 );

    if($scheme_to_change->init_ok_)
      {
	if($scheme_to_change->schemeResultOperation == 'sum')
	  $tpl->setVar('RESULT_SUM_SELECTED', 'selected=\"selected\"');
	if($scheme_to_change->schemeResultOperation == 'mult')
	  $tpl->setVar('RESULT_MULT_SELECTED', 'selected=\"selected\"');
	if($scheme_to_change->schemeResultOperation == 'av')
	  $tpl->setVar('RESULT_AV_SELECTED', 'selected=\"selected\"');

      }


    // Set title and messages
    if($scheme_to_change->init_ok_)
      {
	$tpl->setVar(array(
			   'ADD_RATINGSCHEME_TITLE' => $this->getLabelText('CHANGE_RATINGSCHEME_TITLE'),
			   'RATINGSCHEME_ID' => $scheme_to_change->schemeId,
			   )
		     );
	$this->app_name_ = 	$this->getLabelText('CHANGE_RATINGSCHEME_TITLE');	  
	$this->app_breadcrumbs_[] = Array(
					  'url' => $_SERVER['REQUEST_URI'],
					  'label' => $this->getLabelText('CHANGE_RATINGSCHEME_TITLE'),
					  );
	$this->app_breadcrumbs_[] = Array(
					  'label' => $translation->getTranslation($scheme_to_change->schemeTitleId, $this->language_),
					  );
      }
    else
      {
	$tpl->setVar(array(
			   'ADD_RATINGSCHEME_TITLE' => $this->getLabelText('ADD_RATINGSCHEME_TITLE'),
			   'RATINGSCHEME_ID' => $this->getUniqueId(),
			   )
		     );
	$this->app_name_ = 	$this->getLabelText('ADD_RATINGSCHEME_TITLE');		     
	$this->app_breadcrumbs_[] = Array(
					  'label' => $this->getLabelText('ADD_RATINGSCHEME_TITLE'),
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


  function ratingSchemeOverview()
  {
    global $RATINGSCHEME_OVERVIEW_TEMPLATE;

    $this->debug('RatingSchemeOverview');

    $this->showScreen($RATINGSCHEME_OVERVIEW_TEMPLATE, 'displayRatingSchemeOverview', $this->getAppName());
  }


  function displayRatingSchemeOverview(& $tpl)
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

    $scheme =& new RatingScheme(0, $this->dbi_);

    $scheme_ids = $scheme->getAllRatingSchemeIds();

    //    $this->dumpArray($scheme_ids);

    $translation = & new Translation(0, $this->dbi_);

    if(is_array($scheme_ids))
      {
	$i=0;
	while($current_scheme_id = array_pop($scheme_ids))
	  {
	    $current_scheme_object =& new RatingScheme($current_scheme_id, $this->dbi_);

	    $tpl->setCurrentBlock('scheme_block');
	    $tpl->setVar(array(
			       'BG_CLASS' => ($i%2?'odd':'even'),
			       'RATINGSCHEME_TITLE' => $translation->getTranslation($current_scheme_object->schemeTitleId, $this->language_),
			       'EDIT_URL' => $_SERVER['PHP_SELF']."?cmd=addRatingScheme&sid=".$current_scheme_object->schemeId,
			       'LABEL_CHANGE_RATINGSCHEME' => $this->getlabelText('LABEL_CHANGE_RATINGSCHEME'),
			       'DELETE_URL' => $_SERVER['PHP_SELF']."?cmd=deleteRatingScheme&sid=".$current_scheme_object->schemeId,
			       'LABEL_DELETE_RATINGSCHEME' => $this->getlabelText('LABEL_DELETE_RATINGSCHEME'),
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
		       'LABEL_ADD_RATINGSCHEME' => $this->getLabelText('LABEL_ADD_RATINGSCHEME'), 
		       'ADD_URL' => $_SERVER['PHP_SELF']."?cmd=addRatingScheme"
		       )
		 );
    
    $tpl->setVar('RATINGSCHEME_OVERVIEW_TITLE', $this->getLabelText('RATINGSCHEME_OVERVIEW_TITLE'));

    $tpl->setVar(array(
		       'MESSAGES' => $message_text
		       )
		 );
    
    $tpl->parseCurrentBlock();


    $this->debug($this->getLabelText('RATINGSCHEME_OVERVIEW_TITLE'));


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

    if(!is_null($cmd) && $cmd == 'deleteRatingScheme')
      {
	$tpl->setVar(array(
			   'CONFIRM_MESSAGE' => $this->getMessageText('CONFIRM_MESSAGE')
			   )
		     );

	if(!is_null($sid))
	  {
	    $scheme_to_delete = & new RatingScheme($sid, $this->dbi_);
	    $tpl->setVar(array(
			       'SCHEME_TITLE' => $translation->getTranslation($scheme_to_delete->schemeTitleId, $this->language_),
			       )
			 );
	    $tpl->setVar(array(
			       'SCHEME_ID' => $sid
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
		       'CONFIRM_TITLE' => $this->getLabelText('CONFIRM_TITLE')
		       )
		 );
	$this->app_name_ = 	$this->getLabelText('CONFIRM_TITLE');
    $tpl->setVar(array(
		       'MESSAGES' => $message_text
		       )
		 );


    
    return TRUE;
  }

}
?>