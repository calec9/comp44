<?php

include "database.php";

class websiteEntries 
{	
	private $sql;
	protected $top_10_id;
	protected $top_10_url;
	protected $top_10_category;
	protected $top_10_hitcounter;
	protected $top_10_lastvisit;
	protected $top_10_firstvisit;
	protected $top_10_lastvisit_id;
	protected $top_10_lastvisit_url;
	protected $top_10_lastvisit_category;
	protected $top_10_lastvisit_hitcounter;
	protected $top_10_lastvisit_lastvisit;
	protected $top_10_lastvisit_firstvisit;
	
	function __construct($construct_sql) 
	{
		$this->sql = $construct_sql;
		
		$usage = $this->sql;
		$result = $usage->query("SELECT * FROM entries ORDER BY hitcounter DESC LIMIT 10"); // top 10 hitcoutner
		$i = 1;
		while($data = $result->fetch_array()) {
			$this->top_10_id[$i]			 = $data['ID'];
			$this->top_10_url[$i] 			 = $data['URL'];
			$this->top_10_hitcounter[$i]	 = $data['hitcounter'];
			$this->top_10_lastvisit[$i] 	 = $data['lastvisit'];
			$this->top_10_firstvisit[$i] 	 = $data['firstvisit'];
			$i++;
		}
		
		$result = $usage->query("SELECT * FROM entries ORDER BY lastvisit DESC LIMIT 10"); // last 10 visited
		$i = 1;
		while($data = $result->fetch_array()) {
			$this->top_10_lastvisit_id[$i] 			= $data['ID'];
			$this->top_10_lastvisit_url[$i] 		= $data['URL'];
			$this->top_10_lastvisit_hitcounter[$i]  = $data['hitcounter'];
			$this->top_10_lastvisit_lastvisit[$i]   = $data['lastvisit'];
			$this->top_10_lastvisit_firstvisit[$i]  = $data['firstvisit'];
			$i++;
		}
		
		for($i = 1; $i <= 10; $i++) // get categories for top 10
		{
			$entry_current = $this->top_10_id[$i];
			$query_get_top_10_categories = $usage->query("SELECT * FROM websiteCategoryLink WHERE wID = '$entry_current'");
			if($query_get_top_10_categories->num_rows == 0)
				$this->top_10_category[$i] = "Unknown";
			else 
			{
				$category_data = $query_get_top_10_categories->fetch_array();
				$category_id = $category_data['cID'];
				$query_get_category_by_id = $usage->query("SELECT * FROM category WHERE cID = '$category_id'");
				$category_actual_data = $query_get_category_by_id->fetch_array();
				$this->top_10_category[$i] = $category_actual_data['category'];
			}
		}
		
		for($i = 1; $i <= 10; $i++) // get categories for last 10 visited
		{
			$entry_current = $this->top_10_lastvisit_id[$i];
			$query_get_last_10_categories = $usage->query("SELECT * FROM websiteCategoryLink WHERE wID = '$entry_current'");
			if($query_get_last_10_categories->num_rows == 0)
				$this->top_10_lastvisit_category[$i] = "Unknown";
			else 
			{
				$category_data = $query_get_last_10_categories->fetch_array();
				$category_id = $category_data['cID'];
				$query_get_category_by_id = $usage->query("SELECT * FROM category WHERE cID = '$category_id'");
				$category_actual_data = $query_get_category_by_id->fetch_array();
				$this->top_10_lastvisit_category[$i] = $category_actual_data['category'];
			}
		}
	}
	
	public function get_website_URL($id)
	{
		
	}

	// top 10 url //
	
	public function get_top_10_ids()
	{
		return $this->top_10_id;	
	}
	
	public function get_top_10_url() 
	{
		return $this->top_10_url;
	}
	
	public function get_top_10_hitcounter()
	{
		return $this->top_10_hitcounter;
		
	}
	
	public function get_top_10_category()
	{
		return $this->top_10_category;
	}
	
	public function get_top_10_firstvisit()
	{
		return $this->top_10_firstvisit;
	}
	
	public function get_top_10_lastvisit()
	{
		return $this->top_10_lastvisit;
	}
	
	// top 10 lastvisit
	
	public function get_10_lastvisit_url()
	{
		return $this->top_10_lastvisit_url;
	}
	
	public function get_10_lastvisit_hitcounter()
	{
		return $this->top_10_lastvisit_hitcounter;
	}
	
	public function get_10_lastvisit_lastvisit()
	{
		return $this->top_10_lastvisit_lastvisit;

	}

	public function get_10_lastvisit_firstvisit() 
	{
		return $this->top_10_lastvisit_firstvisit;
	}	

	public function get_top_10_lastvisit_category()
	{
		return $this->top_10_lastvisit_category;
	}

}

?>