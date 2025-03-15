<?php
class LoginBox
{
  
  function LoginBox(& $app_object)
  {
    require_once "conf.LoginBox.php";
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
    $this->template_ = $LOGIN_BOX_TEMPLATE;
    $this->login_app_ = $LOGIN_APPLICATION;
    if($this->app_object_->isAuthenticated())
      $this->change_url = $REL_APP_ROOT ."/". 'user_mgr/run.ChangeUser.php?uid='.$this->app_object_->user_id_."&url=".urlencode($_SERVER['REQUEST_URI']);
  }
  
  function getLoginBox()
  {
    $lTempl = new TemplateHandler($this->app_dir_);
    $lTempl->loadTemplatefile($this->template_, true, true);

    $this->app_object_->debug("getLoginBox() called");

    if($this->app_object_->isAuthenticated())
      {
	$lTempl->setCurrentBlock('info_block');
	$lTempl->setVar(array(
			      'LABEL_AUTH_AS' => $this->lbl_handler_->write('AUTH_AS'),
			      'USER_FIRST_NAME' => $this->app_object_->user_->first_name,
			      'USER_LAST_NAME' => $this->app_object_->user_->last_name,
			      'EDIT_URL' => $this->change_url,
			      'LABEL_LOGOUT' => $this->lbl_handler_->write('LOGOUT'),
			      'LOGOUT_URL' =>$_SERVER['PHP_SELF']."?url=".$_SERVER['PHP_SELF']."&logout=1",
			      )
			);
      }
    else
      {
	$lTempl->setCurrentBlock('form_block');
	$lTempl->setVar(array(
			      'METHOD' => 'POST',
			      'ACTION' => $this->login_app_,
			      'USERNAME' => $this->lbl_handler_->write('LABEL_USERNAME'),
			      'PASSWORD' => $this->lbl_handler_->write('LABEL_PASSWORD'),
			      'LOGIN_BUTTON' =>  $this->lbl_handler_->write('LOGIN_BUTTON'),
			      'REDIRECT_URL' => $this->app_object_->getRequestField('url'),
			      )
			);
      }
    $lTempl->parseCurrentBlock();
    $content = $lTempl->get();
    return $content;
  }
  
}


?>