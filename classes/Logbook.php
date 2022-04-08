<?php  
/**
 * Log book
 */
class Logbook
{
	private  $_user,
			 $_db;
	
	function __construct()
	{
		$this->_user = new User();
		$this->_db = Database::getInstance();

	}


	// check placement
	public function checkPlacement($user_uniqueid)
	{
		$check = $this->_db->get('placementInfo', array('stud_unique_id', '=', $user_uniqueid));
		if ($check->count()) {
			return true;
		}else{
			return false;
		}
	}

	// insert to different tables
	public function create($table, $fields=array())
	{
		if (!$this->_db->insert($table, $fields)) {
			throw new Exception("Error Inserting data!", 1);
		}
	}


	// grab year from Database
	public function getYear()
	{
		$year = $this->_db->get('yearTable', array('deleted', '=', 0));
		if ($year->count()) {
				return $year->results();
			}else{
				return false;
			}	
	}

 public function checkEntryDate($date, $uniqueid)
 {
 	// $date = $this->_db->get('logbook', array('log_month', '=', $date));
 	$date = $this->_db->query("SELECT * FROM logbook WHERE log_month = '$date' AND stu_unique_id = '$uniqueid' ");
		if ($date->count()) {
			return true;
		}else{
			return false;
		}	
 }


public function fetchlogs($uniqueid)
{
	$data  = $this->_db->get('logbook', array('stu_unique_id', '=', $uniqueid));
	if ($data->count()) {
		$row = $data->results();
		$output ='';

		$output .= ' <h4 class="text-center  text-bold">Activity</h4>';
			foreach ($row as $log) {
			
             $output .='<div class="row">
               <div class="col-lg-2 text-left"  style="border:2px solid grey;">
                 <strong class="text-center">
                  '.pretty_dayLetterd($log->log_month).'
                 </strong><br>
                 <span class="text-center">
                 '.pretty_dates($log->log_month).'
               </span>
               </div>
              <div class="col-lg-9 text-left"  style="border:2px solid grey;">
                <stong class="text-left">
                '.$log->activity.'</stong>
              </div>
              <div class="col-lg-1  text-right" style="border:2px solid grey;">
              <u>Week: '.$log->week_number.'</u>
              </div>
            </div>';
        }

        return $output;

	}else{
		return 'No log entry found';
	}
}

}//end of class