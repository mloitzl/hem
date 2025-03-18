<?
class BreadcrumbNavigation
{
  
  function BreadcrumbNavigation(& $app_object)
  {
    require_once "conf.BreadcrumbNavigation.php";
    //    global $APP_ROOT, $LOGIN_BOX_TEMPLATE;


    $this->app_object_ = $app_object;
    $this->app_dir_ = $APP_ROOT . $APP_DIR;

    $this->template_ = $BREADCRUMB_TEMPLATE;
  }
  
  function getBreadcrumbNavigation()
  {
    global $REL_APP_ROOT;

    $this->app_object_->debug("getBreadcrumbNavigation() called");

    $lTempl = new TemplateHandler($this->app_dir_);
    $lTempl->loadTemplatefile($this->template_, true, true);

    if(!empty($this->app_object_->app_breadcrumbs_) && is_array($this->app_object_->app_breadcrumbs_))
      {
	foreach($this->app_object_->app_breadcrumbs_ as $current_crumb)
	  {
	    if(!empty($current_crumb['url']))
	      {
		$lTempl->setCurrentBlock('breadcrumb_url_block');
		$lTempl->setVar(array(
				      'URL' => $current_crumb['url'],
				      'LABEL' => $current_crumb['label'],
				      )
				);
		$lTempl->parseCurrentBlock();
	      }
	    else
	      {
		$lTempl->setCurrentBlock('breadcrumb_no_url_block');
		$lTempl->setVar(array(
				      'LABEL' => $current_crumb['label'],
				      )
				);
		$lTempl->parseCurrentBlock();
	      }
	  }

      }


    $content = $lTempl->get();

    return $content;
  }
  
}

?>