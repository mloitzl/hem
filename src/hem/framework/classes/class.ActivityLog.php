<?php
  /**
   * Class for logging Activities
   *
   *
   * @author Martin Loitzl, martin@loitzl.com
   *
   */

class ActivityLog extends DBObject
{

  /**
   * The name of the table with the activity data
   *
   * @access private
   * @var    string
   */
  var $table_ = 'activity';

  /**
   * The fields of the activity table
   *
   * @access private
   * @var    array
   */
  var $table_fields_ = array(
			     'actId' => 'text',
			     'actType' => 'text',
			     'actUser' => 'text',
			     'actDescription' => 'text',
			     'actTime' => 'text',
			     );
  
  /**
   * The Primary Key of the project table
   *
   * @access private
   * @var    string
   */
  var $table_primary_key_ = 'actId';

  
 /**#@+
  * Properties of the object defined @see $table_fields_
  *
  * @var string
  */
  var $actId = null;
  var $actType = null;
  var $actUser = null;
  var $actDescription = null;
  var $actTime = null;
 /**#@-*/


  /**
   * Constructor
   *
   *
   *
   * @param int Project to instantiate
   * @param object Database handle
   */

  function ActivityLog($act_id = null, & $dbh)
  {
    global $ACTIVITY_TABLE;

    if(isset($ACTIVITY_TABLE)) $this->table_ = $ACTIVITY_TABLE;

    DBObject::DBObject($act_id, $dbh);
  }


  function logActivity($act_type, $act_user = null, $act_description = null)
  {
    if(!is_null($act_user))
      $data['actUser'] = $act_user;
    if(!is_null($act_description))
      $data['actDescription'] = $act_description;
    if(!is_null($act_type))
      $data['actType'] = $act_type;
    

    $data['actId'] = $this->dbh_->getUniqueId();
    $data['actTime'] = date('YmdHms',time());

    $this->addData($data);
  }

}
?>
