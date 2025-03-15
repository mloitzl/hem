<?php
require_once 'class.Translation.php';
require_once 'class.Project.php';
require_once 'class.User.php';
require_once 'class.EnvironmentData.php';
require_once 'class.Environment.php';
require_once 'class.RatingScheme.php';
require_once 'class.RatingScaleValue.php';
require_once 'class.Finding.php';
require_once 'class.Heuristic.php';
require_once 'class.Screenshot.php';

   /**
    * Helper Class for the generation of HEM Reports
    *
    *
    * @author Martin Loitzl, martin@loitzl.com
    *
    */

 class ReportHelper
 {


   var $init_ok_ = FALSE;

   /**
    * Constructor
    *
    *
    *
    * @param int Project to instantiate
    * @param object Database handle
    */

   function ReportHelper($project_id = null, & $dbh)
   {
     global $PROJECT_TABLE, $PROJECT_USER_TABLE;

     if(isset($PROJECT_TABLE)) $this->project_table_ = $PROJECT_TABLE;
     if(isset($PROJECT_USER_TABLE)) $this->project_user_table_ = $PROJECT_USER_TABLE;

     $this->translator_ = & new Translation(0, $dbh);
     $this->dbh_ = $dbh;
     if(!is_null($project_id))
       {
	 $this->project_id_ = $project_id;
	 $this->init();
       }
     else
       {
	 $this->init_ok_ = FALSE;
       }
   }


   function init()
   {
     $project = & new Project($this->project_id_, $this->dbh_);

     if($project->init_ok_)
       {
	 $this->init_ok_ = TRUE;
	 $this->project_ = $project;
       }
     else
       $this->init_ok_ = FALSE;
   }




   function getUserFullNames()
   {
     if($this->init_ok_)
       {
	 $user_id_array = $this->project_->getAllUserIdsFromProject();

	 $return_array = Array();
	 foreach($user_id_array as $current_user_id)
	   {
	     $user_obj = & new User($current_user_id, $this->dbh_);

	     $return_array[$current_user_id]['first_name'] = $user_obj->first_name;
	     $return_array[$current_user_id]['last_name'] = $user_obj->last_name;
	   }

	 return $return_array;
       }
     else
       return FALSE;
   }



   function getUserInitials()
   {
     if($this->init_ok_)
       {
	 $user_names = $this->getUserFullNames();

	 $return_array = Array();
	 foreach($user_names as $user_id => $current_user_name_hash)
	   {
	     if($this->isUTF8($current_user_name_hash['first_name']))
	       $user_name_hash['first_name'] = htmlentities(utf8_decode($current_user_name_hash['first_name']));
	     else
	       $user_name_hash['first_name'] = htmlentities($current_user_name_hash['first_name']);

	     //	       $user_name_hash['first_name'] = utf8_decode($current_user_name_hash['first_name']);
	     if($this->isUTF8($current_user_name_hash['last_name']))
	       $user_name_hash['last_name'] = htmlentities($current_user_name_hash['last_name']);
	     else
	       $user_name_hash['last_name'] = htmlentities($current_user_name_hash['last_name']);

	     //	       $user_name_hash['last_name'] = utf8_decode($current_user_name_hash['last_name']);
	       


	     $first_name_array = preg_split('//', $user_name_hash['first_name'], -1, PREG_SPLIT_NO_EMPTY);
	     $last_name_array = preg_split('//', $user_name_hash['last_name'], -1, PREG_SPLIT_NO_EMPTY);
	     
	     /*
	     echo "<pre>";
	     print_r($first_name_array);
	     echo "</pre>";
	     echo "<pre>";
	     print_r($last_name_array);
	     echo "</pre>";
	     */

	     $first_name_i = $this->html_substr($user_name_hash['first_name'], 0, 1);
	     $last_name_i = $this->html_substr($user_name_hash['last_name'], 0, 1);
	     //	     $first_name_i = $first_name_array[0];
	     //	     $last_name_i = $last_name_array[0];

	     $initials = $first_name_i . $last_name_i;

	     $i_length = 1;
	     while(array_search($initials, $return_array))
	       {
		 $i_length++;
		 $first_name_i = $this->html_substr($user_name_hash['first_name'], 0, $i_length);
		 $last_name_i = $this->html_substr($user_name_hash['last_name'], 0, $i_length);
		 //		 $first_name_i = implode('', array_shift(array_chunk($first_name_array, $i_length)));
		 //		 $last_name_i = implode('', array_shift(array_chunk($last_name_array, $i_length)));

		 $initials = $first_name_i . $last_name_i;
	       }

	     if($this->isUTF8($current_user_name_hash['first_name']))
	       $first_name_i = utf8_encode($first_name_i);
	     if($this->isUTF8($current_user_name_hash['last_name']))
	       $last_name_i = utf8_encode($last_name_i);


	     $return_array[$user_id] = $first_name_i . $last_name_i;
	   }

	 return $return_array;
       }
     else
       return FALSE;
   }



   function getUsersEnvironment()
   {
     if($this->init_ok_)
       {
	 $user_id_array = $this->project_->getAllUserIdsFromProject();

	 $env_data_obj = & new EnvironmentData(0, $this->dbh_);

	 $return_array = Array();
	 foreach($user_id_array as $current_user_id)
	   {	    
	     $env_data = $env_data_obj->getAttributeDataForUserAndProject($current_user_id, $this->project_id_);

	     $return_array[$current_user_id] = $env_data; 
	   }

	 return $return_array;
       }
     else
       return FALSE;
   }



   function getEnvironmentAttributes($language = null)
   {
     if($this->init_ok_ && !is_null($language))
       {
	 $env = & new Environment($this->project_->envId, $this->dbh_);

	 $env_data = $env->getEnvironment();

	 $retrun_array = Array();
	 foreach($env_data['attributes'] as $attr_id => $attr_data_hash)
	   {
	     $return_array[$attr_id ] = $attr_data_hash['title_translation'][$language];
	   }

	 return $return_array;
       }
     else
       return FALSE;
   }


   function getProjectTitle($language = null)
   {
     if($this->init_ok_ && !is_null($language))
       {
	 $project_data = $this->project_->getProjectData();

	 $retrun_array = Array();
	 $return_array['title'] = $project_data['title_translation'][$language];
	 $return_array['description'] = $project_data['description_translation'][$language];
	 $return_array['id'] = $this->project_->pId;
	 return $return_array;
       }
     else
       return FALSE;
   }


   function getRatingScheme($language = null)
   {
     if($this->init_ok_ && !is_null($language))
       {
	 $scheme = & new RatingScheme($this->project_->schemeId, $this->dbh_);
	 if($scheme->init_ok_)
	   {
	     $scheme_data = $scheme->getRatingScheme();
	     
	     $return_array = Array();
	     $return_array['schemeId'] = $scheme_data['schemeId'];
	     $return_array['title'] = $scheme_data['title_translation'][$language];
	     $return_array['result_operation'] = $scheme_data['schemeResultOperation'];
	     
	     $scale_ids = $scheme->getAssociatedScaleIds($this->project_->schemeId);
	     
	     $return_array['scale_ids'] = $scale_ids;
	     
	     return $return_array;
	   }
	 else
	   return FALSE;
       }
     else
       return FALSE;
   }


   function getRatingScale($language = null, $scale_id = null)
   {
     if($this->init_ok_ && !is_null($language) && !is_null($scale_id) )
       {
	 $scale = & new RatingScale($scale_id, $this->dbh_);
	 $scale_data = $scale->getRatingScale();

	 if($scale_data)
	   {
	     $return_array['scale_id'] = $scale_data['scaleId'];
	     $return_array['scale_title'] = $scale_data['title_translation'][$language];


	     $value_array = Array();
	     if(isset($scale_data['values']))
	       {
		 foreach($scale_data['values'] as $value_id => $value_data)
		   {
		     $value_array[$value_id]['value_title'] = $value_data['title_translation'][$language];
		     $value_array[$value_id]['value'] = $value_data['scaleValue'];
		   }
	       }

	     $return_array['values'] = $value_array;
	     return $return_array;
	   }
       }
   }

   function getAggregatedPositiveFinding()
   {
     if($this->init_ok_)
       {
	 $finding = & new Finding(0, $this->dbh_);
	 $m_finding_ids = $finding->getAllFindingIds($this->project_->id_, null, null, 'ASC', null, 'Y', 'Y');

	 $return_array = Array();

	 $dummy_screenshot = & new Screenshot(0, $this->dbh_);

	 if(!empty($m_finding_ids))
	   {
	     foreach($m_finding_ids as $current_fid)
	       {
		 $current_finding = & new Finding($current_fid, $this->dbh_);

		 $screenshot_ids = $dummy_screenshot->getScreenshotIds($current_fid);

		 $attached_fids = $current_finding->getAttachedFindingIds($current_fid);
		 $attached_findings_array = Array();
		 // TODO: Check how this reflects on merged list
		 foreach($attached_fids as $current_attached_fid)
		   {
		     $current_attached_finding = & new Finding($current_attached_fid, $this->dbh_);
		     if($current_attached_finding->init_ok_)
		       {
			 $attached_findings_array[] = $current_attached_finding->uId;
		       }
		   }    

		 $return_array[$current_fid]['evaluator_ids'] = $attached_findings_array;
		 $return_array[$current_fid]['finding'] = $current_finding->fText;
		 $return_array[$current_fid]['screenshots'] = $screenshot_ids;

	       }
	     return $return_array;
	   }
       }

   }




   function getAggregatedFindingsOrdered($language = null)
   {
     if($this->init_ok_)
       {
	 $finding = & new Finding(0, $this->dbh_);

	 $m_finding_ids = $finding->getAllFindingIds($this->project_->id_, null, null, 'ASC', null, 'Y', 'N');

	 $return_array = Array();

	 $dummy_screenshot = & new Screenshot(0, $this->dbh_);

	 if(!empty($m_finding_ids))
	   {
	     foreach($m_finding_ids as $current_fid)
	       {
		 $current_finding = & new Finding($current_fid, $this->dbh_);
		 
		 $attached_fids = $current_finding->getAttachedFindingIds($current_fid);

		 $screenshot_ids = $dummy_screenshot->getScreenshotIds($current_fid);
		 
		 // Set user ids for evaluators who found this finding
		 $attached_findings_array = Array();
		 // TODO: Check how this reflects on merged lists
		 if(!empty($attached_fids))
		   {
		     foreach($attached_fids as $current_attached_fid)
		       {
			 $current_attached_finding = & new Finding($current_attached_fid, $this->dbh_);
			 if($current_attached_finding->init_ok_)
			   {
			     $attached_findings_array[] = $current_attached_finding->uId;
			   }
		       }    
		   }
		 
		 // Get all user ids in project to get their ratings
		 $user_ids = $this->project_->getAllUserIdsFromProject();
		 foreach($user_ids as $current_user_id)
		   {
		     // get rating for user
		     $rating = $current_finding->getRatingsForFinding($current_user_id, $current_finding->fId);
		     
		     if(!empty($rating))
		       {
			 foreach($rating as $scale_id => $value_id)
			   {
			     // create ScaleValue Object
			     $scale_value = & new RatingScaleValue($value_id, $this->dbh_);
			     
			     // add scale Value
			     $return_array[$current_fid]['ratings'][$scale_id][$current_user_id] = $scale_value->scaleValue;
			     // add scale value to 'overall' to calculate the average later
			     $return_array[$current_fid]['ratings'][$scale_id]['overall'][] = $scale_value->scaleValue;
			     
			     // init 'sum' if necessary
			     if(!isset($return_array[$current_fid]['ratings'][$scale_id]['sum']))
			       $return_array[$current_fid]['ratings'][$scale_id]['sum'] = 0;
			     
			     // calculate sum
			     $return_array[$current_fid]['ratings'][$scale_id]['sum'] = $return_array[$current_fid]['ratings'][$scale_id]['sum'] + $scale_value->scaleValue;
			   }
		       }
		   }
		 
		 // Get scale ids for postprocessing of each finding
		 $scheme = & new RatingScheme($this->project_->schemeId, $this->dbh_);
		 $scheme_data = $scheme->getRatingScheme();
		 $scale_ids = $scheme->getAssociatedScaleIds($this->project_->schemeId);
		 
		 //	     echo "<pre>".var_dump($scale_ids)."</pre>";
		 
		 // Calculate average for each ratingscale		 
		 foreach($scale_ids as $current_scale_id)
		   {
		     // Calculate finding 'overall' rating for each scale now
		     if(isset($return_array[$current_fid]['ratings'][$current_scale_id]['sum']) 
			&& isset($return_array[$current_fid]['ratings'][$current_scale_id]['overall']))
		       {
			 $return_array[$current_fid]['ratings']['overall'][$current_scale_id] = 
			   (int)$return_array[$current_fid]['ratings'][$current_scale_id]['sum'] 
			   / (int)sizeof($return_array[$current_fid]['ratings'][$current_scale_id]['overall']);
		       }
		   }
		 

		 // If the ratingscheme has more than one ratingscale, calculate the result of the scheme 
		 // according to the resultoperation defined in the scheme
		 if(!empty($rating) && sizeof($scale_ids) > 1 )
		   {
		     //		 echo "<pre>".print_r($scheme_data)."</pre><br>";
		     switch ($scheme_data['schemeResultOperation']) {
		     case 'av':
		       $return_array[$current_fid]['final_rating'] = 
			 array_sum($return_array[$current_fid]['ratings']['overall']) 
			 / (int)sizeof($return_array[$current_fid]['ratings']['overall']);
		       $sort_index[$current_fid] = $return_array[$current_fid]['final_rating'];
		       break;
		     case 'mult':
		       $result = 1;
		       foreach($return_array[$current_fid]['ratings']['overall'] as $current_value)
			 {
			   $result = $result * $current_value;
			 }
		       $return_array[$current_fid]['final_rating'] = $result;
		       $sort_index[$current_fid] = $return_array[$current_fid]['final_rating'];
		       break;
		     default:
		       $return_array[$current_fid]['final_rating'] = array_sum($return_array[$current_fid]['ratings']['overall']);
		       $sort_index[$current_fid] = $return_array[$current_fid]['final_rating'];
		       break;
		     }
		   }
		 // If the ratingscheme has only one ratingscale
		 // the result is just the average of the scale
		 elseif(!empty($rating)) 
		   {
		     $return_array[$current_fid]['final_rating'] = array_sum($return_array[$current_fid]['ratings']['overall']);
		     $sort_index[$current_fid] = $return_array[$current_fid]['final_rating'];
		   }
		 else
		   {
		     $sort_index[$current_fid] = 0;
		   }
		 
		 $return_array[$current_fid]['finding'] = $current_finding->fText;
		 $return_array[$current_fid]['evaluator_ids'] = $attached_findings_array;
		 $return_array[$current_fid]['positive'] = $current_finding->fPositive;
		 $return_array[$current_fid]['screenshots'] = $screenshot_ids;

		 if($this->project_->heurSetId)
		   {
		     $heuristic_obj = & new Heuristic($current_finding->heurId, $this->dbh_);
		     if($heuristic_obj->init_ok_)
		       {
			 $heuristic_data = $heuristic_obj->getHeuristic();
			 $return_array[$current_fid]['heuristic'] = $heuristic_data['title_translation'][$language];
		       }
		     else
		       $return_array[$current_fid]['heuristic'] = NULL;
		   }
		 else
		   $return_array[$current_fid]['heuristic'] = NULL;		 
	       }
	   }

	 // Sort the indices of the return_array by final rating
	 if(isset($sort_index) && sizeof($sort_index))
	   arsort($sort_index);
	 else
	   $sort_index = array_keys($return_array);

	 $return_array['sorted_index'] = $sort_index;

	 return $return_array;
       }
     else
       return FALSE;
   }
   


   function getFindingListForUser($user_id)
   {
     if($this->init_ok_)
       {
	 $dummy_finding = & new Finding(0, $this->dbh_);
	 $users_project_finding_ids = $dummy_finding->getAllFindingIds($this->project_->pId, $user_id, 'fOrder', 'ASC', null, 'N', null);
	 
	 if(!empty($users_project_finding_ids))
	   {
	     $return_array = Array();
	     foreach($users_project_finding_ids as $current_fid)
	       {
		 // Changes id of Object externally
		 // TODO: Check if good or bad style
		 $dummy_finding->id_ = $current_fid;
		 $dummy_finding->init();
		 
		 $return_array[] = $dummy_finding->fText;
	       }
	     return $return_array;
	   }
	 else
	   {
	     return FALSE;
	   }
       }
     else
       {
	 return FALSE;     
       }
   }
   
   

   
   function html_strlen($str) 
   {
     $chars = preg_split('/(&[^;\s]+;)|/', $str, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
     return count($chars);
   }
   
   function html_substr($str, $start, $length = NULL) 
   {
     if ($length === 0) return ""; //stop wasting our time ;)
     
     //check if we can simply use the built-in functions
     if (strpos($str, '&') === false) { //No entities. Use built-in functions
       if ($length === NULL)
	 return substr($str, $start);
       else
	 return substr($str, $start, $length);
     }
     
     // create our array of characters and html entities
     $chars = preg_split('/(&[^;\s]+;)|/', $str, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);
     $html_length = count($chars);
     
     // check if we can predict the return value and save some processing time
     if (
	 ($html_length === 0) /* input string was empty */ or
	 ($start >= $html_length) /* $start is longer than the input string */ or
	 (isset($length) and ($length <= -$html_length)) /* all characters would be omitted */
	 )
       return "";
     
     //calculate start position
     if ($start >= 0) {
       $real_start = $chars[$start][1];
     } else { //start'th character from the end of string
       $start = max($start,-$html_length);
       $real_start = $chars[$html_length+$start][1];
     }
     
     if (!isset($length)) // no $length argument passed, return all remaining characters
       return substr($str, $real_start);
     else if ($length > 0) { // copy $length chars
       if ($start+$length >= $html_length) { // return all remaining characters
	 return substr($str, $real_start);
       } else { //return $length characters
	 return substr($str, $real_start, $chars[max($start,0)+$length][1] - $real_start);
       }
     } else { //negative $length. Omit $length characters from end
       return substr($str, $real_start, $chars[$html_length+$length][1] - $real_start);
     }
     
   }
   
   
   function isUTF8($string)
   {
     if(!empty($string))
       {
	 if (is_array($string))
	   {
	     $enc = implode('', $string);
	     return @!((ord($enc[0]) != 239) && (ord($enc[1]) != 187) && (ord($enc[2]) != 191));
	   }
	 else
	   {
	     return (utf8_encode(utf8_decode($string)) == $string);
	   }   
       }
     return FALSE;
   }

}
?>