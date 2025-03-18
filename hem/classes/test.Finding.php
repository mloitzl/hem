<?php
// Test Script for class.Finding.php
require_once 'conf.Tests.php';
require_once 'class.Finding.php';

$FINDING_TABLE = $DB_PREFIX . "finding";
$RATING_TABLE = $DB_PREFIX . "finding_rate";

$fin =& new Finding(0, $dbi);
$errors = null;

$id = $util->getUniqueId();
$pid = $util->getUniqueId();
$uid = $util->getUniqueId();
$hid = $util->getUniqueId();

// Test Add Data
$test_data = array(
		   'fId' => $id,
		   'fText' => 'SomeFinding Text',
		   'pId' => $pid,
		   'uId' => $uid,
		   'heurId' => $hid,
		   'fPositive' => 'Y',
		   'fManagerFinding' => 'Y',
		   'fTimestamp' => '20000101111111',
		   'fLastEditedTimestamp' => '20000101111111',
		   'fOrder' => '2',
                   );
$fin->addData($test_data);

$new_fin =& new Finding($test_data['fId'],$dbi);
$result = $new_fin->data_array_;
if($result !== $test_data)
  $errors.= "AddData or init Error<br/>";

$test_data_1 = array(
		     'fId' => $id,
		     'fText' => 'SomeFinding changed Text',
		     'pId' => $pid,
		     'uId' => $uid,
		     'heurId' => $hid,
		     'fPositive' => 'N',
		     'fManagerFinding' => 'N',
		     'fTimestamp' => '20040101121212',
		     'fLastEditedTimestamp' => '20040101121212',
		     'fOrder' => '2',
		     );

$new_fin->updateData($test_data_1);
$new_fin->init();

$result = $new_fin->data_array_;

if($result !== $test_data_1)
  $errors.= "UpdateData or init Error<br/>";




$test_data_2 = array(
		     'fId' => '0000',
		     'fText' => 'SomeFinding changed Text',
		     'pId' => $pid,
		     'uId' => $uid,
		     'heurId' => $hid,
		     'fPositive' => 'N',
		     'fManagerFinding' => 'N',
		     'fTimestamp' => '20040101121212',
		     'fLastEditedTimestamp' => '20040101121212',
		     'fOrder' => '1',
		     );
$new_fin->addData($test_data_2);

$new_fin->swapFindings($id, '0000');

echo "<br>";
if($new_fin->getSuccessorFindingId($id))
  echo "Successor of $id is " . $new_fin->getSuccessorFindingId($id) ."<br>";
else
    echo "$id has no Successor <br>";
if($new_fin->getPredecessorFindingId('0000'))
  echo "Predecessor of 000 is " . $new_fin->getPredecessorFindingId('0000') ."<br>";
else
    echo "$id has no Predecessor <br>";

$result = $new_fin->getAllFindingIds('1r4lsr4nirv758ptag84l4g3lkrzw0tx', 'pkp1phiapbpawfu5k1hge8phlentmff7', 'fId', 'DESC');


$rating_data = array(
		     'fid_1' => array(
				      'some_scale_id' => $id,
				      'some_other_scale_id' => $pid,
				      ),
		     'fid_2' => array(
				      'some_scale_id' => $uid,
				      'some_other_scale_id' => $hid,
				      ),
		     );

$util->dumpArray($rating_data);

$uid ="my_user_id_1";
$new_fin->storeRatings($uid, $rating_data);

// $util->dumpArray($result);

$new_fin->deleteData();
$new_fin->init();
if($new_fin->init_ok_)
    $errors.= "Deletion Error<br/>";


$test_fin = new Finding('0000', $dbi);
$test_fin->deleteData();

if(!is_null($errors)) die("Tests failed: <hr/>$errors<hr/>");
 else echo "Everythings fine";

?>