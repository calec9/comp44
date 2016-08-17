<?php
$mysqli = new mysqli("argentetpierrescom.ipagemysql.com", "datamaster", "test0r!", "eyespyhq");
if($mysqli->connect_errno) 
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;

date_default_timezone_set('UTC'); 
$today = date( "Y-m-d H:i:s");

if(isset($_POST['URL'])) 
{
	$URL = $_POST['URL'];
	if(1) { // preg_match('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/', $URL) NOT WORKING WHEN SUBMITED FROM CORE
		if($result = $mysqli->query("SELECT * FROM entries WHERE URL = '$URL' limit 1")) 
		{
			if($result->num_rows == 1) 
			{ // because we should only ever have one record per URL.
				$data = $result->fetch_array();
				$hit = $data['hitcounter'] + 1;
				$id = $data['ID'];
				$lastvisit = $data['lastvisit'];
				$hits_today = $data['hits_today'];
				$lasthit_value = new datetime($lastvisit);
				$now = new datetime($today);
				$difference = $now->diff($lasthit_value);
				if($difference->d == 0)
					$hits_today ++;
				else $hits_today = 1;
				$entry_update_hitcounter = $mysqli->query("UPDATE entries SET hitcounter = '$hit' WHERE ID = '$id'");
				$entry_update_lastvisit = $mysqli->query("UPDATE entries SET lastvisit = '$today' WHERE ID = '$id'");
				$entry_update_hits_today = $mysqli->query("UPDATE entries SET hits_today = '$hits_today' WHERE ID = '$id'");
				if($entry_update_hitcounter && $entry_update_lastvisit && $entry_update_hits_today)
					die ("entry_updated");
				else 
					die ("entry_update_failed");
			}
		}
		if($mysqli->query("INSERT INTO entries (URL, lastvisit, firstvisit, hitcounter, hits_today) VALUES ('$URL', '$today', '$today', '1', '1')")) 
		{
			$category = $_POST['keywords'];
			if($category != null) 
			{
				$q = $mysqli->query("SELECT * FROM category WHERE category = '$category' LIMIT 1");
				if($q->num_rows == 1) 
				{
					$data = $q->fetch_array();
					$category_id = $data['cID'];
					$qprime = $mysqli->query("SELECT * FROM entries WHERE URL = '$URL' LIMIT 1");
					$result = $qprime->fetch_array();
					$website_id = $result['ID'];
					if(!$mysqli->query("INSERT INTO websiteCategoryLink (wID, cID) VALUES ('$website_id', '$category_id')"))
						die("failed_to_insert_category_link");
					else 
						die("inserted_link_for_existing_category_and_new_website");
				}
				if($mysqli->query("INSERT INTO category (category) VALUES ('$category')")) 
				{
					$q = $mysqli->query("SELECT * FROM category WHERE category = '$category' LIMIT 1");
						$data = $q->fetch_array();
						$category_id = $data['cID'];
						$qprime = $mysqli->query("SELECT * FROM entries WHERE URL = '$URL' LIMIT 1");
						$result = $qprime->fetch_array();
						$website_id = $result['ID'];
						if(!$mysqli->query("INSERT INTO websiteCategoryLink (wID, cID) VALUES ('$website_id', '$category_id')"))
							die("failed_to_insert_category_link");
						else 
							die("inserted_link_for_new_category_and_new_website");

				}
			} else die("category failed.");
		} else die("entry failed."); 
	} else die("regex_no for $URL");
}


?>