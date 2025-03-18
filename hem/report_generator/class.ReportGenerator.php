<?php

class ReportGenerator extends PHPApplication
{
  
  function run()
  {
    //    global $globals, $HOME_APP;
    global $HOME_APP, $HOME_APP_LABEL;
        
    $this->number_of_findings_to_analyse_ = 5;

    if($this->auth_handler_->checkRight(MANAGE_REPORTS))
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
	$this->ReportGeneratorDriver();
      }
    else
      {
	header("Location: $HOME_APP");
      }
  }


  function ReportGeneratorDriver()
  {
    global $PHP_SELF;

    // Url Parameters, no form submitted, or form data already processed    
    if(!is_null($cmd = $this->getGetRequestField('cmd', null)))
      {
	switch ($cmd)
	  {
	  case 'previewReport':
	    $this->previewReportDriver();
	    break;
	  case 'writeReport':
	    $this->writeReport();
	    break;
	  case 'downloadReport':
	    $this->downloadReport();
	    break;
	  default:
	    $this->debug('Calling Report Overview');
	    $this->reportOverview();
	    break;
	  }
      }
    else
      {
	$this->reportOverview();
      }

  }


  function previewReportDriver()
  {
    global $PREVIEW_REPORT_TEMPLATE;

    $this->showScreen($PREVIEW_REPORT_TEMPLATE, 'displayPreviewReport', $this->getAppName());
  }


  function displayPreviewReport(& $tpl, $export = FALSE)
  {
    global $LANGUAGES, $PHP_SELF, $REPORT_LABEL_FILE;

    $language_code = $this->getGetRequestField('lang', null);
    if(is_null($language_code))
      {
	$language_code = $this->language_;
      }
    else
      $language_code = strtoupper($language_code);

    $report_label_handler = & new LabelHandler(
					       array(
						     'name' => $this->app_name_,
						     'language' => $language_code,
						     'file' => $REPORT_LABEL_FILE,
						     )
					       );
   
    $project_id = $this->getGetRequestField('pid', null); 

    $message_text = '';
    if($this->hasSessionMessages() == TRUE)
      {
	$messages = $this->getAllSessionMessages();
	while($msg = array_pop($messages))
	  {
	    $message_text.=$msg;
	  }
      }


    if(!is_null($project_id))
      {
	$figure_counter = 0;

	$report_helper =  & new ReportHelper($project_id, $this->dbi_);
	
	$project_users = $report_helper->getUserFullNames();

	// Title Page: Evaluators

	foreach($project_users as $user_id => $current_user)
	  {
	    $tpl->setCurrentBlock('title_evaluator_block');
	    $tpl->setVar(array(
			       'EVALUATOR_FIRST_NAME' => $current_user['first_name'],
			       'EVALUATOR_LAST_NAME' => $current_user['last_name'],
			       )
			 );
	    $tpl->parseCurrentBlock();
	  }

	// Chapter 1: General Text
	$tpl->setVar('CHAPTER_1_GENERAL', $report_label_handler->getMessage('CHAPTER_1_GENERAL'));


	// Chapter 2: Evaluation Environment
	
	$tpl->setVar('CHAPTER_2_GENERAL', $report_label_handler->getMessage('CHAPTER_2_GENERAL'));


	 foreach($project_users as $user_id => $current_user)
	   {
	     $tpl->setCurrentBlock('environment_evaluator_block');
	     $tpl->setVar(array(
				'EVALUATOR_FIRST_NAME' => $current_user['first_name'],
				'EVALUATOR_LAST_NAME' => $current_user['last_name'],
				)
			  );
	     $tpl->parseCurrentBlock();
	   }

	 $env_attributes = $report_helper->getEnvironmentAttributes($language_code);
	 $env_values = $report_helper->getUsersEnvironment();

	 foreach($env_attributes as $attribute_id => $attribute_title)
	   {
	     foreach($project_users as $user_id => $current_user)
	       {
		 $tpl->setCurrentBlock('attribute_evaluator_block');
		 if(isset($env_values[$user_id][$attribute_id]))
		   $tpl->setVar('ATTRIBUTE_VALUE', $env_values[$user_id][$attribute_id]['envAttributeData']);
		 else
		   $tpl->setVar('ATTRIBUTE_VALUE', $report_label_handler->getMessage('NOT_SET_BY_USER'));

		 $tpl->parseCurrentBlock();
	       }
	     $tpl->setCurrentBlock('environment_attribute_block');
	     $tpl->setVar('ATTRIBUTE_TITLE', $attribute_title);
	     $tpl->parseCurrentBlock();
	   }

	 // Chapter 3: Positive Impressions


	 $positives = $report_helper->getAggregatedPositiveFinding();

	 $positives_counter = 0;

	 if($positives)
	   {
	     foreach($positives as $finding_id => $finding)
	       {
		 $positives_counter++; 

		 // Both annotated and fullsize screenshots are available
		 if(!empty($positives[$finding_id]['screenshots']['annotated']) && !empty($positives[$finding_id]['screenshots']['fullsize']))
		   {
		     $annotated_screenshot_object = & new Screenshot($positives[$finding_id]['screenshots']['annotated'], $this->dbi_);
		     $fullsize_screenshot_object = & new Screenshot($positives[$finding_id]['screenshots']['fullsize'], $this->dbi_);
		     $figure_counter++;

		     if(!$export)
		       {
			 $tpl->setCurrentBlock('positive_link_block');
			 $tpl->setVar(array(
					    'FIGURE_ANNOTATED_URL' => 'run.displayImage.php?tn=1&bid='.$positives[$finding_id]['screenshots']['annotated'],
					    'FIGURE_FULLSIZE_URL' =>'run.displayImage.php?bid='.$positives[$finding_id]['screenshots']['fullsize'],
					    )
				      );
			 $tpl->parseCurrentBlock();
		       }
		     else
		       {
			 $tpl->setCurrentBlock('positive_link_block');
			 $tpl->setVar(array(
					    'FIGURE_ANNOTATED_URL' => 'images/'.$figure_counter.'_a_'.$annotated_screenshot_object->sName,
					    'FIGURE_FULLSIZE_URL' => 'images/'.$figure_counter.'_f_'.$fullsize_screenshot_object->sName,
					    )
				      );
			 $tpl->parseCurrentBlock();
		       }
		     $tpl->setCurrentBlock('positive_screenshot_block');
		     $tpl->setVar(array(
					    'LABEL_FIGURE' => $report_label_handler->getMessage('LABEL_FIGURE'),
					    'FIGURE_NUMBER' => $figure_counter,
					    'FIGURE_CAPTION' => $annotated_screenshot_object->sName,
					    )
				  );
		     $tpl->parseCurrentBlock();
		   }
		 // Only the fullsize Screenshot is given
		 else if(!empty($positives[$finding_id]['screenshots']['fullsize']))
		   {
		     $screenshot_object = & new Screenshot($positives[$finding_id]['screenshots']['fullsize'], $this->dbi_);
		     $figure_counter++;

		     if(!$export)
		       {
			 $tpl->setCurrentBlock('positive_link_block');
			 $tpl->setVar(array(
					    'FIGURE_ANNOTATED_URL' => 'run.displayImage.php?tn=1&bid='.$positives[$finding_id]['screenshots']['fullsize'],
					    'FIGURE_FULLSIZE_URL' =>'run.displayImage.php?bid='.$positives[$finding_id]['screenshots']['fullsize'],
					    )
				      );
			 $tpl->parseCurrentBlock();
		       }
		     else
		       {
			 $tpl->setCurrentBlock('positive_link_block');
			 $tpl->setVar(array(
					    'FIGURE_ANNOTATED_URL' => 'images/'.$figure_counter.'_a_'.$screenshot_object->sName,
					    'FIGURE_FULLSIZE_URL' => 'images/'.$figure_counter.'_f_'.$screenshot_object->sName,
					    )
				      );
			 $tpl->parseCurrentBlock();
		       }
		     $tpl->setCurrentBlock('positive_screenshot_block');
		     $tpl->setVar(array(
					    'LABEL_FIGURE' => $report_label_handler->getMessage('LABEL_FIGURE'),
					    'FIGURE_NUMBER' => $figure_counter,
					    'FIGURE_CAPTION' => $screenshot_object->sName,
					    )
				  );
		     $tpl->parseCurrentBlock();
		   }
		 // Only the annotated Screenshot is given
		 else if(!empty($positives[$finding_id]['screenshots']['annotated']))
		   {
		     $screenshot_object = & new Screenshot($positives[$finding_id]['screenshots']['annotated'], $this->dbi_);
		     $figure_counter++;

		     if(!$export)
		       {
			 $tpl->setCurrentBlock('positive_no_link_block');
			 $tpl->setVar(array(
					    'FIGURE_ANNOTATED_URL' => 'run.displayImage.php?tn=1&bid='.$positives[$finding_id]['screenshots']['annotated'],
					    )
				      );
			 $tpl->parseCurrentBlock();
		       }
		     else
		       {
			 $tpl->setCurrentBlock('positive_no_link_block');
			 $tpl->setVar(array(
					    'FIGURE_ANNOTATED_URL' => 'images/'.$figure_counter.'_a_'.$screenshot_object->sName,
					    )
				      );
			 $tpl->parseCurrentBlock();
		       }
		     $tpl->setCurrentBlock('positive_screenshot_block');
		     $tpl->setVar(array(
					    'LABEL_FIGURE' => $report_label_handler->getMessage('LABEL_FIGURE'),
					    'FIGURE_NUMBER' => $figure_counter,
					    'FIGURE_CAPTION' => $screenshot_object->sName,
					    )
				  );
		     $tpl->parseCurrentBlock();
		   }





		 $tpl->setCurrentBlock('positive_block');
		 $tpl->setVar(array(
				    'POSTITIVE_SUBCHAPTER_NUMBER' => $positives_counter,
				    'POSITIVE_SUBCHAPTER_TITLE' => substr($finding['finding'], 0, 20)."...",
				    'POSITIVE_TEXT' => $finding['finding'],
				    )
			      );
		 $tpl->parseCurrentBlock();
	       }
	   }

	 //Chapter 3: General Text
	 $tpl->setVar('CHAPTER_3_GENERAL', $report_label_handler->getMessage('CHAPTER_3_GENERAL'));

	 //	$this->dumpArray($positives);


	// Chapter 4: Main Problems
	$findings = $report_helper->getAggregatedFindingsOrdered($language_code);
	//	$this->dumpArray($findings);

	$finding_counter = 0;
	
	if($findings)
	  {
	    foreach($findings['sorted_index'] as $finding_id => $finding_rating)
	      {
		$finding_counter++;

		// Both annotated and fullsize screenshots are available
		if(!empty($findings[$finding_id]['screenshots']['annotated']) && !empty($findings[$finding_id]['screenshots']['fullsize']))
		  {
		    $annotated_screenshot_object = & new Screenshot($findings[$finding_id]['screenshots']['annotated'], $this->dbi_);
		    $fullsize_screenshot_object = & new Screenshot($findings[$finding_id]['screenshots']['fullsize'], $this->dbi_);
		    $figure_counter++;

		    if(!$export)
		      {
			$tpl->setCurrentBlock('screenshot_link_block');
			$tpl->setVar(array(
					   'FIGURE_ANNOTATED_URL' => 'run.displayImage.php?tn=1&bid='.$findings[$finding_id]['screenshots']['annotated'],
					   'FIGURE_FULLSIZE_URL' =>'run.displayImage.php?bid='.$findings[$finding_id]['screenshots']['fullsize'],
					   )
				     );
			$tpl->parseCurrentBlock();
		      }
		    else
		      {
			$tpl->setCurrentBlock('screenshot_link_block');
			$tpl->setVar(array(
					   'FIGURE_ANNOTATED_URL' => 'images/'.$figure_counter.'_a_'.$annotated_screenshot_object->sName,
					   'FIGURE_FULLSIZE_URL' => 'images/'.$figure_counter.'_f_'.$fullsize_screenshot_object->sName,
					   )
				     );
			$tpl->parseCurrentBlock();
		      }
		    $tpl->setCurrentBlock('finding_screenshot_block');
		    $tpl->setVar(array(
					   'LABEL_FIGURE' => $report_label_handler->getMessage('LABEL_FIGURE'),
					   'FIGURE_NUMBER' => $figure_counter,
					   'FIGURE_CAPTION' => $annotated_screenshot_object->sName,
					   )
				 );
		    $tpl->parseCurrentBlock();
		  }
		// Only the fullsize Screenshot is given
		else if(!empty($findings[$finding_id]['screenshots']['fullsize']))
		  {
		    $screenshot_object = & new Screenshot($findings[$finding_id]['screenshots']['fullsize'], $this->dbi_);
		    $figure_counter++;

		    if(!$export)
		      {
			$tpl->setCurrentBlock('screenshot_link_block');
			$tpl->setVar(array(
					   'FIGURE_ANNOTATED_URL' => 'run.displayImage.php?tn=1&bid='.$findings[$finding_id]['screenshots']['fullsize'],
					   'FIGURE_FULLSIZE_URL' =>'run.displayImage.php?bid='.$findings[$finding_id]['screenshots']['fullsize'],
					   )
				     );
			$tpl->parseCurrentBlock();
		      }
		    else
		      {
			$tpl->setCurrentBlock('screenshot_link_block');
			$tpl->setVar(array(
					   'FIGURE_ANNOTATED_URL' => 'images/'.$figure_counter.'_a_'.$screenshot_object->sName,
					   'FIGURE_FULLSIZE_URL' => 'images/'.$figure_counter.'_f_'.$screenshot_object->sName,
					   )
				     );
			$tpl->parseCurrentBlock();
		      }
		    $tpl->setCurrentBlock('finding_screenshot_block');
		    $tpl->setVar(array(
					   'LABEL_FIGURE' => $report_label_handler->getMessage('LABEL_FIGURE'),
					   'FIGURE_NUMBER' => $figure_counter,
					   'FIGURE_CAPTION' => $screenshot_object->sName,
					   )
				 );
		    $tpl->parseCurrentBlock();
		  }
		// Only the annotated Screenshot is given
		else if(!empty($findings[$finding_id]['screenshots']['annotated']))
		  {
		    $screenshot_object = & new Screenshot($findings[$finding_id]['screenshots']['annotated'], $this->dbi_);
		    $figure_counter++;

		    if(!$export)
		      {
			$tpl->setCurrentBlock('screenshot_no_link_block');
			$tpl->setVar(array(
					   'FIGURE_ANNOTATED_URL' => 'run.displayImage.php?tn=1&bid='.$findings[$finding_id]['screenshots']['annotated'],
					   )
				     );
			$tpl->parseCurrentBlock();
		      }
		    else
		      {
			$tpl->setCurrentBlock('screenshot_no_link_block');
			$tpl->setVar(array(
					   'FIGURE_ANNOTATED_URL' => 'images/'.$figure_counter.'_a_'.$screenshot_object->sName,
					   )
				     );
			$tpl->parseCurrentBlock();
		      }
		    $tpl->setCurrentBlock('finding_screenshot_block');
		    $tpl->setVar(array(
					   'LABEL_FIGURE' => $report_label_handler->getMessage('LABEL_FIGURE'),
					   'FIGURE_NUMBER' => $figure_counter,
					   'FIGURE_CAPTION' => $screenshot_object->sName,
					   )
				 );
		    $tpl->parseCurrentBlock();
		  }


		$tpl->setCurrentBlock('main_problem_block');
		$tpl->setVar(array(
				   'MAIN_PROBLEM_SUBCHAPTER_NUMBER' => $finding_counter,
				   'MAIN_PROBLEM_SUBCHAPTER_TITLE' => substr($findings[$finding_id]['finding'], 0, 20)."...",
				   'MAIN_PROBLEM_TEXT' => $findings[$finding_id]['finding'],
				   )
			     );
		$tpl->parseCurrentBlock();



		if($finding_counter > $this->number_of_findings_to_analyse_)
		  break;
	      }
	  }



	$tpl->setVar('CHAPTER_4_GENERAL', $report_label_handler->getMessage('CHAPTER_4_GENERAL'));



	// Chapter 5: List of Problems found

	$user_initials = $report_helper->getUserInitials();
	
	foreach($project_users as $user_id => $current_user)
	  {
	    $tpl->setCurrentBlock('initials_evaluator_block');
	    $tpl->setVar(array(
			       'INITIAL_VALUE' => $user_initials[$user_id],
			       'EVALUATOR_FIRST_NAME' => $current_user['first_name'],
			       'EVALUATOR_LAST_NAME' => $current_user['last_name'],
			       )
			 );
	    $tpl->parseCurrentBlock();
	  }

	$rating_scheme = $report_helper->getRatingScheme($language_code);

	//	$this->dumpArray($rating_scheme);

	if(isset($rating_scheme['scale_ids']))
	  {
	    foreach($rating_scheme['scale_ids'] as $scale_id)
	      {
		$scale_data = $report_helper->getRatingScale($language_code, $scale_id);

		if(isset($scale_data['values']))
		  {
		    foreach($scale_data['values'] as $scale_value)
		      {
			$tpl->setCurrentBlock('scale_value_block');
			$tpl->setVar(array(
					   'SCALE_VALUE' => $scale_value['value'],
					   'SCALE_VALUE_TITLE' => $scale_value['value_title'],
					   )
				     );
			$tpl->parseCurrentBlock();
		      }

		  }


		$tpl->setCurrentBlock('scale_block');
		$tpl->setVar(array(
				   'SCALE_TITLE' => $scale_data['scale_title'],
				   'LABEL_MEANING' => $report_label_handler->getMessage('LABEL_MEANING'),
				   'LABEL_AVERAGE_SHORT' => $report_label_handler->getMessage('LABEL_AVERAGE_SHORT'),
				   'LABEL_AVERAGE_LONG' => $report_label_handler->getMessage('LABEL_AVERAGE_LONG'),
				   )
			     );
		$tpl->parseCurrentBlock();
		//		$this->dumpArray($scale_data);
	      }

	  }

	// Findings
	// $findings already used in chapter 4!

	if(is_array($findings) && sizeof($findings) > 1)
	  {


	    // Table Headers 1st row

	    //	    echo $report_helper->project_->heurSetId."<br>";
	    if($report_helper->project_->heurSetId)
	      {
		$tpl->setCurrentBlock('heuristic_header_block');
		$tpl->setVar('LABEL_HEURISTIC', $report_label_handler->getMessage('LABEL_HEURISTIC'));
		$tpl->parseCurrentBlock();

	      }

	    $tpl->setCurrentBlock('summary_header_block');
	    $tpl->setVar(array(
			       'HEADER_COL_NAME' => $report_label_handler->getMessage('LABEL_FOUND_BY'), 
			       'NUMBER_OF_EVALUATORS' => sizeof($user_initials),
			       )
			 );
	    $tpl->parseCurrentBlock();
	    
	    foreach($rating_scheme['scale_ids'] as $scale_id)
	      {
		$scale_data = $report_helper->getRatingScale($language_code, $scale_id);

		$tpl->setCurrentBlock('summary_header_block');
		$tpl->setVar(array(
				   'HEADER_COL_NAME' => $scale_data['scale_title'], 
				   'NUMBER_OF_EVALUATORS' => (sizeof($user_initials) + 1),
				   )
			     );
		$tpl->parseCurrentBlock();
	      }


	    if(sizeof($rating_scheme['scale_ids']) > 1)
	      {
		$tpl->setCurrentBlock('summary_header_block');
		$tpl->setVar(array(
				   'HEADER_COL_NAME' => $rating_scheme['title'], 
				   'NUMBER_OF_EVALUATORS' => 1,
				   )
			     );
		$tpl->parseCurrentBlock();
	      }
	    

	    // Table Headers 2nd row

	    $tpl->setVar(array(
			       'LABEL_FINDING_TEXT' => $report_label_handler->getMessage('LABEL_FINDING_TEXT'),
			       'LABEL_POSITIVE' => $report_label_handler->getMessage('LABEL_POSITIVE'),
			       )
			 );


	    foreach($user_initials as $user_id => $user_initial)
	      {
		$tpl->setCurrentBlock('finding_header_initials_evaluator_block');
		$tpl->setVar('FINDING_HEADER_INITIAL',  $user_initial);
		$tpl->parseCurrentBlock();
	      }
	    
	    foreach($rating_scheme['scale_ids'] as $scale_id)
	      {
		foreach($user_initials as $user_id => $user_initial)
		  {
		    $tpl->setCurrentBlock('finding_header_initials_evaluator_block');
		    $tpl->setVar('FINDING_HEADER_INITIAL',  $user_initial);
		    $tpl->parseCurrentBlock();
		  }
		$tpl->setCurrentBlock('finding_header_initials_evaluator_block');
		$tpl->setVar('FINDING_HEADER_INITIAL',  $report_label_handler->getMessage('LABEL_AVERAGE_SHORT'));
		$tpl->parseCurrentBlock();
	      }
	    
	    //	    $this->dumpArray($rating_scheme['scale_ids']);

	    if(sizeof($rating_scheme['scale_ids']) > 1)
	      {
		switch ($rating_scheme['result_operation'])
		  {
		  case 'mult':
		    $scheme_string = $report_label_handler->getMessage('LABEL_PRODUCT');
		    break;
		  case 'av':
		    $scheme_string = $report_label_handler->getMessage('LABEL_AVERAGE');
		    break;
		  default:
		    $scheme_string = $report_label_handler->getMessage('LABEL_SUM');
		  }
		
		$tpl->setCurrentBlock('finding_header_initials_evaluator_block');
		$tpl->setVar('FINDING_HEADER_INITIAL',  $scheme_string);
		$tpl->parseCurrentBlock();
	      }

	    // Table Data
	    foreach($findings['sorted_index'] as $current_finding_id => $current_rating)
	      {

		// Heuristic Part
		if($report_helper->project_->heurSetId)
		  {
		    $tpl->setCurrentBlock('found_by_block');
		    $tpl->setVar('HEURISTIC_TEXT', $findings[$current_finding_id]['heuristic']);
		    $tpl->parseCurrentBlock();
		  }

		

		// "Found-by" Part
		foreach($user_initials as $user_id => $user_initial)
		  {
		    $tpl->setCurrentBlock('found_by_block');

		    if(in_array($user_id ,$findings[$current_finding_id]['evaluator_ids']))
		      $tpl->setVar('FOUND_BY_TEXT',  $report_label_handler->getMessage('LABEL_FOUND_SHORT'));
		    else
		      $tpl->setVar('FOUND_BY_TEXT',  '&nbsp;');

		    $tpl->parseCurrentBlock();
		  }		

		// Rating Scales
		if(isset($rating_scheme['scale_ids']))
		  {
		    foreach($rating_scheme['scale_ids'] as $scale_id)
		      {
			foreach($user_initials as $user_id => $user_initial)
			  {
			    $tpl->setCurrentBlock('rating_block');
			    if(isset($findings[$current_finding_id]['ratings'][$scale_id][$user_id]))
			      $tpl->setVar('RATING_TEXT', $findings[$current_finding_id]['ratings'][$scale_id][$user_id]);
			    else
			      $tpl->setVar('RATING_TEXT', '&nbsp;');
			    $tpl->parseCurrentBlock();
			  }
			// Average Rating
			$tpl->setCurrentBlock('rating_block');
			if(isset($findings[$current_finding_id]['ratings']['overall'][$scale_id]))
			  {
			    $tpl->setVar('RATING_TEXT', sprintf("%01.2f", $findings[$current_finding_id]['ratings']['overall'][$scale_id]));
			  }
			else
			  $tpl->setVar('RATING_TEXT', '&nbsp;');

			$tpl->parseCurrentBlock();
		      }
		    if(sizeof($rating_scheme['scale_ids']) > 1)
		      {
			$tpl->setCurrentBlock('rating_block');
			if(isset($findings[$current_finding_id]['final_rating']))
			  $tpl->setVar('RATING_TEXT', sprintf("%01.2f", $findings[$current_finding_id]['final_rating']));
			else
			  $tpl->setVar('RATING_TEXT', '&nbsp;');

			$tpl->parseCurrentBlock();
		      }

		  }
		
		$tpl->setCurrentBlock('finding_row');
		$tpl->setVar(array(
				   'FINDING_TEXT' => $findings[$current_finding_id]['finding'],
				   )
			     );
		$tpl->parseCurrentBlock();
	      }
	  }


	// Individual Logs

	$table_counter = 2;
	foreach($project_users as $user_id => $user_name)
	  {
	    $user_findings = $report_helper->getFindingListForUser($user_id);

	    if(!empty($user_findings))
	      {
		$tpl->setVar('LABEL_INDIVIDUAL_FINDING_TEXT', $report_label_handler->getMessage('LABEL_INDIVIDUAL_FINDING_TEXT'));

		foreach($user_findings as $current_finding)
		  {
		    $tpl->setCurrentBlock('individual_finding_block');
		    $tpl->setVar('INDIVIDUAL_FINDING_TEXT', $current_finding);
		    $tpl->parseCurrentBlock();
		  }
	      }
	    else
	      {
		$tpl->setCurrentBlock('individual_finding_block');
		$tpl->setVar('INDIVIDUAL_FINDING_TEXT', $report_label_handler->getMessage('NO_FINDINGS_FOR_EVALUATOR'));
		$tpl->parseCurrentBlock();
	      }

    
	    $evaluator_name = $user_name['first_name'] . "&nbsp;" . $user_name['last_name'];
	    $table_counter++;
	    $tpl->setCurrentBlock('individual_log');
	    $tpl->setVar(array(
			       'LABEL_LOG_FOR_EVALUATOR' => $report_label_handler->getMessage('LABEL_LOG_FOR_EVALUATOR'), 
			       'LABEL_INDIVIDUAL_TABLE' => $report_label_handler->getMessage('LABEL_TABLE') . "&nbsp;" . $table_counter,
			       'LABEL_EVALUATOR_NAME' => $evaluator_name,
			       )
			 );
	    $tpl->parseCurrentBlock();
	  }


	$project_title_hash = $report_helper->getProjectTitle($language_code);

	//	$this->dumpArray($project_title_hash);

	//			   'TITLE_EDIT_URL' => 'proj_mgr/run.ProjectManager.php?cmd=addProj&pid='.$project_title_hash['id'],

	$this->app_breadcrumbs_[] = Array(
					  'label' => $this->getLabelText("LABEL_PREVIEW"),
					  );

	$this->app_breadcrumbs_[] = Array(
					  'label' => $project_title_hash['title'],
					  );

	$tpl->setVar(array(
			   'REPORT_TITLE' => $report_label_handler->getMessage('REPORT_TITLE'),
			   'PROJECT_TITLE' => $project_title_hash['title'],
			   'PROJECT_DESCRIPTION' => $project_title_hash['description'],			   
			   'LABEL_EXECUTIVE_SUMMARY' => $report_label_handler->getMessage('LABEL_EXECUTIVE_SUMMARY'),
			   'LABEL_POSITIVE_IMPRESSIONS' => $report_label_handler->getMessage('LABEL_POSITIVE_IMPRESSIONS'),
			   'LABEL_ANALYSIS_MAIN_PROBLEMS' => $report_label_handler->getMessage('LABEL_ANALYSIS_MAIN_PROBLEMS'),
			   'LABEL_LIST_OF_PROBLEMS' => $report_label_handler->getMessage('LABEL_LIST_OF_PROBLEMS'),
			   'LABEL_INDIVIDUAL_LOGS' => $report_label_handler->getMessage('LABEL_INDIVIDUAL_LOGS'),
			   'LABEL_ENVIRONMENT' => $report_label_handler->getMessage('LABEL_ENVIRONMENT'),
			   'LABEL_ENVIRONMENT_TABLE' => $report_label_handler->getMessage('LABEL_ENVIRONMENT_TABLE'),
			   'LABEL_ENVIRONMENT_TABLE_CAPTION' => $report_label_handler->getMessage('LABEL_ENVIRONMENT_TABLE_CAPTION'),
			   'LABEL_FINDING_TABLE_CAPTION' => $report_label_handler->getMessage('LABEL_FINDING_TABLE_CAPTION'),
			   'LABEL_EVALUATOR' => $report_label_handler->getMessage('LABEL_EVALUATOR'),
			   'LABEL_FINDING_TABLE' => $report_label_handler->getMessage('LABEL_FINDING_TABLE'),
			   'LABEL_LEGEND' => $report_label_handler->getMessage('LABEL_LEGEND'),
			   'LABEL_MEANING_OF_CODES' => $report_label_handler->getMessage('LABEL_MEANING_OF_CODES'),
			   'LABEL_CODE' => $report_label_handler->getMessage('LABEL_CODE'),
			   'LABEL_MEANING' => $report_label_handler->getMessage('LABEL_MEANING'),
			   'LABEL_FOUND_SHORT' => $report_label_handler->getMessage('LABEL_FOUND_SHORT'),
			   'LABEL_FOUND_MEANING' => $report_label_handler->getMessage('LABEL_FOUND_MEANING'),
			   )
		     );
	
	
      }

    return TRUE;
  }


  function writeReport($download = null)
  {
    global $REPORTS_DIR, $APP_ROOT, $APP_DIR, $EXPORT_TEMPLATE , $PREVIEW_REPORT_TEMPLATE;

    $error = FALSE;

    if(!file_exists($REPORTS_DIR))
      {
	//	echo "$REPORTS_DIR does not exist<br>";
	if(!@mkdir($REPORTS_DIR))
	  {
	    //	    echo "Could not create Reports Dir $REPORTS_DIR<br>";
	    $error = TRUE;
	  }
      }
    if(!is_writeable($REPORTS_DIR))
      {
	//	echo "$REPORTS_DIR is not writeable<br>";

	$error = TRUE;
      }
    else
      {
	$project_id = $this->getGetRequestField('pid', null);
	$report_sub_dir = $project_id;

	if(!is_null($report_sub_dir) && !file_exists($REPORTS_DIR."/".$report_sub_dir))
	  if(!@mkdir($REPORTS_DIR."/".$report_sub_dir))
	    $error = TRUE;
      }

    if(!$error)
      {
	// Setup export template

	$report_template = & new TemplateHandler($APP_ROOT.$APP_DIR);
	$this->setupScreen($report_template, $EXPORT_TEMPLATE);

	// Setup report generator template
	$generator_template = & new TemplateHandler($this->template_dir_);

	$this->setupScreen($generator_template, $PREVIEW_REPORT_TEMPLATE);

	$status = $this->displayPreviewReport($generator_template, TRUE);

	if($status)
	  {
	    $language_code = $this->getGetRequestField('lang', null);

	    if(!is_null($language_code))
	      $language_code = strtoupper($language_code);
	    else
	      $language_code = $this->language_;
	    
	    switch ($language_code) 
	      {
	      case 'DE':
		$lang = 'de';
		break;
	      case 'US':
		$lang = 'en';
	      default:
		$lang = 'en';
		break;
	      }

				// if(isset($_ENV['LANG']) && stristr($_ENV['LANG'], 'utf-8'))
				$report_template->setVar('CHAR_ENCODING', 'utf-8');
				// else
				//   $report_template->setVar('CHAR_ENCODING', 'iso-8859-1');


	    //	    echo "$lang<br>";
	    $report_template->setVar(array(
					   'CONTENT' => $generator_template->get(),
					   'XML_LANG' => $lang,
					   )
				     );
	
	    $report_handle = fopen($REPORTS_DIR."/".$report_sub_dir."/"."he.html", 'w');

	    if(!@fwrite($report_handle, $report_template->get()))
	      {
		$error = TRUE;
	      }


	    // Write CSS File
	    $css_in_handle = fopen($APP_ROOT.$APP_DIR."/"."report.css", 'r');
	    $css_out_handle = fopen($REPORTS_DIR."/".$report_sub_dir."/"."report.css", 'w');

	    if(!@fwrite($css_out_handle, fread($css_in_handle, filesize($APP_ROOT.$APP_DIR."/"."report.css"))))
	      {
		$error = TRUE;
	      }
	    
	    
	    //	    $this->dumpArray($findings);
	    
	    // Write Screenshots
	    if(!file_exists($REPORTS_DIR."/".$report_sub_dir."/images"))
	      if(!@mkdir($REPORTS_DIR."/".$report_sub_dir."/images"))
		$error = TRUE;
	    
	    if(!$error && file_exists($REPORTS_DIR."/".$report_sub_dir."/images"))
	      {
		$report_helper =  & new ReportHelper($project_id, $this->dbi_);
		$util = & new Util();
		$figure_counter = 0;

		// Positives
		$positives = $report_helper->getAggregatedPositiveFinding();
		$positive_counter = 0;
		if($positives)
		  {
		    foreach($positives as $finding_id => $finding_rating)
		      {
			$positive_counter++;
			if(!empty($positives[$finding_id]['screenshots']['annotated']) && !empty($positives[$finding_id]['screenshots']['fullsize']))
			  {
			    // write the annotated with 600 width and the original in fullsize
			    $fullsize_screenshot = & new Screenshot($positives[$finding_id]['screenshots']['fullsize'], $this->dbi_, $util);
			    $figure_counter++;
			    if(1)
			      //!file_exists($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_f_".$fullsize_screenshot->sName))
			      {
				$fullsize_screenshot->writeOriginalScreenshot($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_f_".$fullsize_screenshot->sName);
				// write it
				//				echo "Writing: ".$REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_f_".$fullsize_screenshot->sName."<br>";
			      }

			    $annotated_screenshot = & new Screenshot($positives[$finding_id]['screenshots']['annotated'], $this->dbi_, $util);
			    if(1)
			      //!file_exists($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_a_".$annotated_screenshot->sName))
			      {
				// write it
				$annotated_screenshot->writeReportScreenshot($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_a_".$annotated_screenshot->sName);
			      }
			    
			  }
			else if(!empty($positives[$finding_id]['screenshots']['fullsize']))
			  {
			    // write the fullsize with  600 width and in original filesize
			    $fullsize_screenshot = & new Screenshot($positives[$finding_id]['screenshots']['fullsize'], $this->dbi_, $util);
			    $figure_counter++;
			    if(1)
			      //!file_exists($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_f_".$fullsize_screenshot->sName))
			      {
				$fullsize_screenshot->writeOriginalScreenshot($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_f_".$fullsize_screenshot->sName);
				// write it
				//				echo "Writing: ".$REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_f_".$fullsize_screenshot->sName."<br>";
			      }
			    
			    if(1)
			      //!file_exists($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_a_".$fullsize_screenshot->sName))
			      {
				// write it
				$annotated_screenshot->writeReportScreenshot($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_a_".$fullsize_screenshot->sName);
			      }

			  }
			else if(!empty($positives[$finding_id]['screenshots']['annotated']))
			  {
			    // Write annotated with 600 width
			    $annotated_screenshot = & new Screenshot($positives[$finding_id]['screenshots']['annotated'], $this->dbi_, $util);
			    $figure_counter++;

			    if(1)
			      //!file_exists($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_a_".$annotated_screenshot->sName))
			      {
				// write it
				$annotated_screenshot->writeReportScreenshot($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_a_".$annotated_screenshot->sName);
			      }
			  }
			
		      }
		    
		  }
		
		

		// Findings
		$findings = $report_helper->getAggregatedFindingsOrdered($language_code);
		
		$finding_counter = 0;
		


		if($findings)
		  {
		    foreach($findings['sorted_index'] as $finding_id => $finding_rating)
		      {
			$finding_counter++;
			if(!empty($findings[$finding_id]['screenshots']['annotated']) && !empty($findings[$finding_id]['screenshots']['fullsize']))
			  {
			    // write the annotated with 600 width and the original in fullsize
			    $fullsize_screenshot = & new Screenshot($findings[$finding_id]['screenshots']['fullsize'], $this->dbi_, $util);
			    $figure_counter++;
			    if(1)
			      //!file_exists($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_f_".$fullsize_screenshot->sName))
			      {
				$fullsize_screenshot->writeOriginalScreenshot($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_f_".$fullsize_screenshot->sName);
				// write it
				//				echo "Writing: ".$REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_f_".$fullsize_screenshot->sName."<br>";
			      }

			    $annotated_screenshot = & new Screenshot($findings[$finding_id]['screenshots']['annotated'], $this->dbi_, $util);
			    if(1)
			      //!file_exists($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_a_".$annotated_screenshot->sName))
			      {
				// write it
				$annotated_screenshot->writeReportScreenshot($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_a_".$annotated_screenshot->sName);
			      }
			    
			  }
			else if(!empty($findings[$finding_id]['screenshots']['fullsize']))
			  {
			    // write the fullsize with  600 width and in original filesize
			    $fullsize_screenshot = & new Screenshot($findings[$finding_id]['screenshots']['fullsize'], $this->dbi_, $util);
			    $figure_counter++;
			    if(1)
			      //!file_exists($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_f_".$fullsize_screenshot->sName))
			      {
				$fullsize_screenshot->writeOriginalScreenshot($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_f_".$fullsize_screenshot->sName);
				// write it
				//				echo "Writing: ".$REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_f_".$fullsize_screenshot->sName."<br>";
			      }
			    
			    if(1)
			      //!file_exists($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_a_".$fullsize_screenshot->sName))
			      {
				// write it
				$annotated_screenshot->writeReportScreenshot($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_a_".$fullsize_screenshot->sName);
			      }

			  }
			else if(!empty($findings[$finding_id]['screenshots']['annotated']))
			  {
			    // Write annotated with 600 width
			    $annotated_screenshot = & new Screenshot($findings[$finding_id]['screenshots']['annotated'], $this->dbi_, $util);
			    $figure_counter++;

			    if(1)
			      //!file_exists($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_a_".$annotated_screenshot->sName))
			      {
				// write it
				$annotated_screenshot->writeReportScreenshot($REPORTS_DIR."/".$report_sub_dir."/images/".$figure_counter."_a_".$annotated_screenshot->sName);
			      }
			  }

			
		      }
		  }	       
	      }
	      

	    if(!$error && !$download)
	      {
		// echo "<a href=\"".$this->server_.$this->app_root_."/reports/".$report_sub_dir."\">Report</a><br />";
		header("Location: ".$this->server_.$this->app_root_."/reports/".$report_sub_dir);
	      }
	    else if(!$error)
	      echo "Shit %$()'#*<br>";
	  }

      }
  }


  function downloadReport()
  {
    
    global $APP_ROOT;

    $format = $this->getGetRequestField('format', null);

    $project_id = $this->getGetRequestField('pid', null);

    $ending = 0;
    if($format == 'tgz')
      $ending = ".tar.gz";
    if($format == 'zip')
      $ending = ".zip";
    
    if($ending)
      {
	//	$this->writeReport(1);
	require_once "File/Archive.php"; 
	$source = File_Archive::read($APP_ROOT."/reports/".$project_id);


	$filename = $project_id . $ending;

	$source->extract( 
			 File_Archive::toArchive(
						 $filename,
						 File_Archive::toOutput()
						 ) 
			 );
      }

  }



  function reportOverview()
  {
    global $REPORT_OVERVIEW_TEMPLATE;

    $this->showScreen($REPORT_OVERVIEW_TEMPLATE, 'displayReportOverview', $this->getAppName());
  }


  function displayReportOverview(& $tpl)
  {
    global $LANGUAGES;

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

    $translation = & new Translation(0, $this->dbi_);

    $i=0;
    if(is_array($project_ids))
      {
	while($current_proj_id = array_pop($project_ids))
	  {
	    $current_project_object =& new Project($current_proj_id, $this->dbi_);

	    foreach($LANGUAGES as $current_language)
	      {
		$tpl->setCurrentBlock('preview_language_block');
		$tpl->setVar(array(
				   'PREVIEW_URL' => $_SERVER['PHP_SELF']."?cmd=previewReport&pid=".$current_project_object->pId."&lang=".$current_language,
				   'LABEL_PREVIEW_REPORT' => $this->getlabelText('LABEL_'.$current_language),
				   )
			     );
		$tpl->parseCurrentBlock();
	      }

	    foreach($LANGUAGES as $current_language)
	      {
		$tpl->setCurrentBlock('export_language_block');
		$tpl->setVar(array(
				   'EXPORT_URL' => $_SERVER['PHP_SELF']."?cmd=writeReport&pid=".$current_project_object->pId."&lang=".$current_language,
				   'LABEL_EXPORT_REPORT' => $this->getlabelText('LABEL_'.$current_language),
				   )
			     );
		$tpl->parseCurrentBlock();
	      }

	    
	    
	    
	    
	    $tpl->setCurrentBlock('project_block');
	    $tpl->setVar(array(
			       'BG_CLASS' => ($i%2?'odd':'even'),
			       'REPORT_TITLE' => $translation->getTranslation($current_project_object->pNameId, $this->language_),
			       'REPORT_DESCRIPTION' => $translation->getTranslation($current_project_object->pDescriptionId, $this->language_),
			       'DEFAULT_PREVIEW_URL' => $_SERVER['PHP_SELF']."?cmd=previewReport&pid=".$current_project_object->pId."&lang=".$this->language_,
			       'LABEL_DOWNLOAD_ZIP' => $this->getLabelText('LABEL_DOWNLOAD_ZIP'),
			       'LABEL_DOWNLOAD_TGZ' => $this->getLabelText('LABEL_DOWNLOAD_TGZ'),
			       'DOWNLOAD_ZIP_URL' => $_SERVER['PHP_SELF'].'?cmd=downloadReport&format=zip&pid='.$current_project_object->pId,
			       'DOWNLOAD_TGZ_URL' => $_SERVER['PHP_SELF'].'?cmd=downloadReport&format=tgz&pid='.$current_project_object->pId,
			       )
			 );
	    
	    $tpl->parseCurrentBlock();
	    $i++;
	  }
      }
    
    $tpl->setCurrentBlock('main_block');

    $tpl->setVar(array(
		       'LABEL_ADD_RATINGSCHEME' => $this->getLabelText('LABEL_ADD_RATINGSCHEME'), 
		       'ADD_URL' => $_SERVER['PHP_SELF']."?cmd=addRatingScheme"
		       )
		 );
    
    $tpl->setVar(array(
		       'REPORTS_OVERVIEW_TITLE' => $this->getLabelText('REPORTS_OVERVIEW_TITLE'),
		       'LABEL_PROJECT_TITLE' => $this->getLabelText('LABEL_PROJECT_TITLE'),
		       'LABEL_PREVIEW_REPORT' => $this->getLabelText('LABEL_PREVIEW_REPORT'),
		       'LABEL_EXPORT_REPORT' => $this->getLabelText('LABEL_EXPORT_REPORT'),
		       'LABEL_DOWNLOAD_REPORT' => $this->getLabelText('LABEL_DOWNLOAD_REPORT'),
		       )
		 );


    $tpl->setVar(array(
		       'MESSAGES' => $message_text
		       )
		 );
    
    $tpl->parseCurrentBlock();


    $this->debug($this->getLabelText('RATINGSCHEME_OVERVIEW_TITLE'));


    return TRUE;
  }

}
?>