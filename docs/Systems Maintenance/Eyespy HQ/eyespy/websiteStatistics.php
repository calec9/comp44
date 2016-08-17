<?php

date_default_timezone_set('UTC'); 

class websiteStatistics extends websiteEntries 
{
	private $sql;
	protected $top_10_most_visited_mean;
	protected $top_10_last_visited_mean;
	protected $top_10_most_visited_today;
	protected $total_hits_today;
	
	function __construct($construct_sql) 
	{
		$this->sql = $construct_sql;
	}	
	
	public function get_top_10_most_visited_mean($top10)
	{
		for($i = 1; $i <= 10; $i++) 
		{
			$lastvisit = $top10->get_top_10_lastvisit();
			$lastvisit_current = new datetime($lastvisit[$i]);
			$firstvisit = $top10->get_top_10_firstvisit();
			$firstvisit_current = new datetime($firstvisit[$i]);
			$difference = $lastvisit_current->diff($firstvisit_current);
			$hitcounter = $top10->get_top_10_hitcounter();
			$top_10_most_visited_mean[$i] = round($hitcounter[$i] / $difference->d);
		}
		return $top_10_most_visited_mean;
	}	

	
	public function get_top_10_most_visited_today($top10)
	{
		for($i = 1; $i <= 10; $i++)
		{
			$today = date("Y-m-d H:i:s");
			$lastvisit = $top10->get_top_10_lastvisit();
			$lastvisit_current = new datetime($lastvisit[$i]);
			$today_current = new datetime($today);
			$difference = $today_current->diff($lastvisit_current);
			if($difference->d == 0)
			{
				$usage = $this->sql;
				$ID = $top10->get_top_10_ids();
				$ID_current = $ID[$i];
				$query = $usage->query("SELECT * FROM entries WHERE ID = '$ID_current' LIMIT 1");
				$data = $query->fetch_array();
				$hits_today = $data['hits_today'];
				$top_10_most_visited_today[$i] = $hits_today;
				$this->total_hits_today += $hits_today;
			}			
			else $top_10_most_visited_today[$i] = 0;
		}
		return $top_10_most_visited_today;
	}
	
	public function get_10_most_visited_today($statistics, $records) 
	{
		$sorted_data = array();
		$sorted_data = $statistics->get_top_10_most_visited_today($records);	
		return $sorted_data;
	}
	
	public function get_total_hits_today()
	{
		return $this->total_hits_today;
	}
	
	public function has_usage_increased_today()
	{
		$day=date("l");
		$day_int = 0;
		switch ($day)
		{
			case "Monday": 
				$day_int = 1;
				break;
			case "Tuesday":
				$day_int = 2;
				break;
			case "Wednesday":
				$day_int = 3;
				break;
			case "Thursday":
				$day_int = 4;
				break;
			case "Friday":
				$day_int = 5;
				break;
			case "Saturday": 
				$day_int = 6;
				break;
			case "Sunday": 
				$day_int = 7;
				break;
		}
		$usage = $this->sql;
		$query_mean_today = $usage->query("SELECT * FROM staticStatistics WHERE day = '$day_int'");
		$actuals = $query_mean_today->fetch_array();
		$actuals_value = $actuals['meanVisits'];
		$today = $this->total_hits_today;
		if($actuals_value < $this->total_hits_today)
		{
			echo "<span style='color:red'><b> - INCREASE </b></span>";
			echo "<br>with reference to the $day mean of $actuals_value";
		}			
		else 
		{
			echo "<span style='color:green'><b> - NO INCREASE </b></span>";	
			echo "<br>with reference to the $day mean of $actuals_value";

		}
	}

}

?>