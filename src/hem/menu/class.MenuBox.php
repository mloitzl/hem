<?
class MenuBox
{
  
  function MenuBox(& $app_object)
  {
    require_once "conf.MenuBox.php";
    //    global $APP_ROOT, $LOGIN_BOX_TEMPLATE;


    $this->app_object_ = $app_object;
    $this->app_dir_ = $APP_ROOT . $APP_DIR;

    $this->lbl_handler_ = new LabelHandler(
					   array(
						 'name' => '',
						 'language' => $this->app_object_->language_,
						 'file' => $LABEL_FILE,
						 )
					   );
    $this->template_ = $MENU_BOX_TEMPLATE;
  }
  
  function getMenuBox()
  {
    global $REL_APP_ROOT, $REL_REPORTS_DIR;

    $this->app_object_->debug("getMenuBox() called");

    $lTempl = new TemplateHandler($this->app_dir_);
    $lTempl->loadTemplatefile($this->template_, true, true);


    if(!$this->app_object_->isAuthenticated())
      {
	//  Home App
	$lTempl->setCurrentBlock('normal_list_item');
	$lTempl->setVar(array(
			      'LINK_LABEL' => $this->lbl_handler_->write('HOME'),
			      'LINK' => $REL_APP_ROOT.'/'.'home/run.Home.php',
			      )
			);
	$lTempl->parseCurrentBlock();
      }
    else
      {

	//  Home App
	$lTempl->setCurrentBlock('normal_list_item');
	$lTempl->setVar(array(
			      'LINK_LABEL' => $this->lbl_handler_->write('HOME'),
			      'LINK' => $REL_APP_ROOT.'/'.'home/run.Home.php',
			      )
			);
	$lTempl->parseCurrentBlock();

	// Project Manager
	if($this->app_object_->auth_handler_->checkRight(MANAGE_PROJECTS))
	  {
	    $lTempl->setCurrentBlock('sub_menu_item');
	    $lTempl->setVar(array(
				  'SUB_LINK_LABEL' => $this->lbl_handler_->write('PROJECT_OVERVIEW'),
				  'SUB_LINK' => $REL_APP_ROOT.'/'.'proj_mgr/run.ProjectManager.php',
				  )
			    );
	    $lTempl->parseCurrentBlock();


	    $lTempl->setCurrentBlock('sub_menu_item');
	    $lTempl->setVar(array(
				  'SUB_LINK_LABEL' => $this->lbl_handler_->write('ADD_PROJECT'),
				  'SUB_LINK' => $REL_APP_ROOT.'/'.'proj_mgr/run.ProjectManager.php?cmd=addProject',
				  )
			    );
	    $lTempl->parseCurrentBlock();

	    $lTempl->setCurrentBlock('sub_menu_item');
	    $lTempl->setVar(array(
				  'SUB_LINK_LABEL' => $this->lbl_handler_->write('IMPORT_PROJECT'),
				  'SUB_LINK' => $REL_APP_ROOT.'/'.'proj_mgr/run.ProjectManager.php?cmd=importProject',
				  )
			    );
	    $lTempl->parseCurrentBlock();


	    /*
	    $project_object = & new Project(0, $this->app_object_->dbi_);
	    $project_ids = $project_object->getAllProjectIds();
	    $translation = & new Translation(0, $this->app_object_->dbi_);

	    if(!empty($project_ids))
	      {
		foreach($project_ids as $current_id)
		  {
		    $current_project_object =& new Project($current_id, $this->app_object_->dbi_);
		    
		    $lTempl->setCurrentBlock('sub_menu_item');
		    $lTempl->setVar(array(
					  'SUB_LINK_LABEL' => $translation->getTranslation($current_project_object->pNameId, $this->app_object_->language_),
					  'SUB_LINK' => $REL_APP_ROOT.'/'.'proj_mgr/run.ProjectManager.php?cmd=addProject&pid='.$current_id,
					  )
				    );
		    $lTempl->parseCurrentBlock();
		  }
	      }
	    */
	    $lTempl->setCurrentBlock('sub_menu');
	    $lTempl->setVar(array(
				  'SUB_MENU_LINK_LABEL' => $this->lbl_handler_->write('PROJECT_MGR'),
				  'SUB_MENU_LINK' => $this->app_object_->getSelfUrl(),
				  )
			    );

	    $lTempl->parseCurrentBlock();

	    
	  }


	// Enter Environment Data
	if($this->app_object_->auth_handler_->checkRight(ADD_ENVIRONMENT_DATA))
	  {
	    $lTempl->setCurrentBlock('sub_menu_item');
	    $lTempl->setVar(array(
				  'SUB_LINK_LABEL' => $this->lbl_handler_->write('ENVIRONMENT_DATA_COLLECTOR'),
				  'SUB_LINK' => $REL_APP_ROOT.'/'.'environment_collector/run.EnvironmentCollector.php',
				  )
			    );
	    $lTempl->parseCurrentBlock();
	  }

	// Collect Findings
	if($this->app_object_->auth_handler_->checkRight(COLLECT_FINDINGS))
	  {
	    $lTempl->setCurrentBlock('sub_menu_item');
	    $lTempl->setVar(array(
				  'SUB_LINK_LABEL' => $this->lbl_handler_->write('FINDING_COLLECTOR'),
				  'SUB_LINK' => $REL_APP_ROOT.'/'.'finding_collector/run.FindingCollector.php',
				  )
			    );
	    $lTempl->parseCurrentBlock();
	  }

	// Merge Findings
	if($this->app_object_->auth_handler_->checkRight(MERGE_FINDINGS))
	  {
	    $lTempl->setCurrentBlock('menu_item_block');
	    $lTempl->setVar(array(
				  'SUB_LINK_LABEL' => $this->lbl_handler_->write('FINDING_MERGER'),
				  'SUB_LINK' => $REL_APP_ROOT.'/'.'finding_merger/run.FindingMerger.php',
				  )
			    );
	    $lTempl->parseCurrentBlock();
	  }

	// Collect Ratings
	if($this->app_object_->auth_handler_->checkRight(COLLECT_RATINGS))
	  {
	    $lTempl->setCurrentBlock('sub_menu_item');
	    $lTempl->setVar(array(
				  'SUB_LINK_LABEL' => $this->lbl_handler_->write('RATING_COLLECTOR'),
				  'SUB_LINK' => $REL_APP_ROOT.'/'.'rating_collector/run.RatingCollector.php',
				  )
			    );
	    $lTempl->parseCurrentBlock();
	  }

	$lTempl->setCurrentBlock('sub_menu');
	$lTempl->setVar(array(
			      'SUB_MENU_LINK_LABEL' => $this->lbl_handler_->write('EVALUATION'),
			      'SUB_MENU_LINK' => $this->app_object_->getSelfUrl(),
			      )
			);
	
	$lTempl->parseCurrentBlock();



	if($this->app_object_->auth_handler_->checkRight(MANAGE_HEURISTICS) ||
	   $this->app_object_->auth_handler_->checkRight(MANAGE_RATINGSCALES) ||
	   $this->app_object_->auth_handler_->checkRight(MANAGE_RATINGSCHEMES) ||
	   $this->app_object_->auth_handler_->checkRight(MANAGE_ENVIRONMENTS) ||
	   $this->app_object_->auth_handler_->checkRight(CHANGE_OTHER_USERS))
	  {


	    if($this->app_object_->auth_handler_->checkRight(CHANGE_OTHER_USERS))
	      {
		
		$lTempl->setCurrentBlock('sub_menu_item');
		$lTempl->setVar(array(
				      'SUB_LINK_LABEL' => $this->lbl_handler_->write('USER_MGR'),
				      'SUB_LINK' => $REL_APP_ROOT.'/'.'user_mgr/run.UserManager.php',
				      )
				);
		$lTempl->parseCurrentBlock();
	      }
	    
	    // Manage Heuristics
	    if($this->app_object_->auth_handler_->checkRight(MANAGE_HEURISTICS))
	      {
		$lTempl->setCurrentBlock('sub_menu_item');
		$lTempl->setVar(array(
				      'SUB_LINK_LABEL' => $this->lbl_handler_->write('HEURISTICS_MGR'),
				      'SUB_LINK' => $REL_APP_ROOT.'/'.'heuristicset_mgr/run.HeuristicSetManager.php',
				      )
				);
		$lTempl->parseCurrentBlock();
	      }
	    
	    // Rating Scale Manager
	    if($this->app_object_->auth_handler_->checkRight(MANAGE_RATINGSCALES))
	      {
		$lTempl->setCurrentBlock('sub_menu_item');
		$lTempl->setVar(array(
				      'SUB_LINK_LABEL' => $this->lbl_handler_->write('RATINGSCALE_MGR'),
				      'SUB_LINK' => $REL_APP_ROOT.'/'.'ratingscale_mgr/run.RatingScaleManager.php',
				      )
				);
		$lTempl->parseCurrentBlock();
	      }
	    
	    // Rating Scheme Manager
	    if($this->app_object_->auth_handler_->checkRight(MANAGE_RATINGSCHEMES))
	      {
		$lTempl->setCurrentBlock('sub_menu_item');
		$lTempl->setVar(array(
				      'SUB_LINK_LABEL' => $this->lbl_handler_->write('RATINGSCHEME_MGR'),
				      'SUB_LINK' => $REL_APP_ROOT.'/'.'ratingscheme_mgr/run.RatingSchemeManager.php',
				      )
				);
		$lTempl->parseCurrentBlock();
	      }
	    
	    // Environment Manager
	    if($this->app_object_->auth_handler_->checkRight(MANAGE_ENVIRONMENTS))
	      {
		$lTempl->setCurrentBlock('sub_menu_item');
		$lTempl->setVar(array(
				      'SUB_LINK_LABEL' => $this->lbl_handler_->write('ENVIRONMENT_MGR'),
				      'SUB_LINK' => $REL_APP_ROOT.'/'.'environment_mgr/run.EnvironmentManager.php',
				      )
				);
		$lTempl->parseCurrentBlock();
	      }
	    
	    $lTempl->setCurrentBlock('sub_menu');
	    $lTempl->setVar(array(
				  'SUB_MENU_LINK_LABEL' => $this->lbl_handler_->write('HE_ATTRIBUTES'),
				  'SUB_MENU_LINK' => $this->app_object_->getSelfUrl(),
				  )
			    );
	    
	    $lTempl->parseCurrentBlock();
	    
	  }

	// Reports
	if($this->app_object_->auth_handler_->checkRight(MANAGE_REPORTS))
	  {

	    $lTempl->setCurrentBlock('sub_menu_item');
	    $lTempl->setVar(array(
				  'SUB_LINK_LABEL' => $this->lbl_handler_->write('REPORTS_OVERVIEW'),
				  'SUB_LINK' => $REL_APP_ROOT.'/'.'report_generator/run.ReportGenerator.php',
				  )
			    );
	    $lTempl->parseCurrentBlock();

	    $lTempl->setCurrentBlock('sub_menu_item');
	    $lTempl->setVar(array(
				  'SUB_LINK_LABEL' => $this->lbl_handler_->write('REPORTS_EXPORT'),
				  'SUB_LINK' => $REL_REPORTS_DIR,
				  )
			    );
	    $lTempl->parseCurrentBlock();

	    /*
	    $project_object = & new Project(0, $this->app_object_->dbi_);
	    $project_ids = $project_object->getAllProjectIds();
	    $translation = & new Translation(0, $this->app_object_->dbi_);
	    if(!empty($project_ids))
	      {
		foreach($project_ids as $current_id)
		  {
		    $current_project_object =& new Project($current_id, $this->app_object_->dbi_);
		    
		    $lTempl->setCurrentBlock('sub_menu_item');
		    $lTempl->setVar(array(
					  'SUB_LINK_LABEL' => $translation->getTranslation($current_project_object->pNameId, $this->app_object_->language_),
					  'SUB_LINK' => $REL_APP_ROOT.'/'.'report_generator/run.ReportGenerator.php?cmd=previewReport&pid='.$current_id."&lang=".$this->app_object_->language_,
					  )
				    );
		    $lTempl->parseCurrentBlock();
		  }
	      }
	    */
	    $lTempl->setCurrentBlock('sub_menu');
	    $lTempl->setVar(array(
				  'SUB_MENU_LINK_LABEL' => $this->lbl_handler_->write('REPORTS'),
				  'SUB_MENU_LINK' => $this->app_object_->getSelfUrl(),
				  )
			    );
	    
	    $lTempl->parseCurrentBlock();
	  }

      }
    $content = $lTempl->get();
    return $content;
  }
  
}

?>