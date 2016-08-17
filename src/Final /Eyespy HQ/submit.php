<?php
$mysqli = new mysqli("", "", "", "eyespyhq");
if($mysqli->connect_errno) 
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;

date_default_timezone_set('UTC'); 
$today = date( "Y-m-d H:i:s");

if(isset($_POST['URL'])) 
{
	$URL = $_POST['URL'];
	if(1) { // preg_match('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/', $URL) NOT WORKING WHEN SUBMITED FROM CORE
		if($result = $mysqli->query("SELECT * FROM entries WHERE URL = '$URL' limit 1")) // query check to see if the website has 																									already been submitted
		{
			if($result->num_rows == 1) 													// result from the query - integer
			{ 																			// execute only if above statement is met
				$data = $result->fetch_array();											// get the result set from the above query
				$hit = $data['hitcounter'] + 1;											// increment hitcounter form the latter
				$id = $data['ID'];														// fetch the ID for updating the result set 
				$lastvisit = $data['lastvisit'];										// last visit from the result set
				$hits_today = $data['hits_today'];										// hits today from the result set
				$lasthit_value = new datetime($lastvisit);								// used to compare the difference between 																								   lastvisit and today, allows us to return the 																						   hits_today variable if the difference (in 																						       days) between today and lastvisit = 0
				$now = new datetime($today);											// today, for comparison	
				$difference = $now->diff($lasthit_value);								// the difference calulated as mentioned
				if($difference->d == 0)													// the difference validation
					$hits_today ++;														// should the diff = 0, increment hist today
				else $hits_today = 1;													// otherwise re enter 1 into the record set
				// update the hitcounter, lastvisit and firstvisit inside the database for the already existing website record entry
				$entry_update_hitcounter = $mysqli->query("UPDATE entries SET hitcounter = '$hit' WHERE ID = '$id'");
				$entry_update_lastvisit = $mysqli->query("UPDATE entries SET lastvisit = '$today' WHERE ID = '$id'");
				$entry_update_hits_today = $mysqli->query("UPDATE entries SET hits_today = '$hits_today' WHERE ID = '$id'");
				if($entry_update_hitcounter && $entry_update_lastvisit && $entry_update_hits_today) // ensure all 3 queries sucseeded and print message accordingly 
					die ("entry_updated");
				else 
					die ("entry_update_failed");
			}
		}
		// the following code will ONLY execute if the above validation returned fales, in which case we need to insert a new record into the database for the new URL -which is what is done with the statmement below
		if($mysqli->query("INSERT INTO entries (URL, lastvisit, firstvisit, hitcounter, hits_today) VALUES ('$URL', '$today', '$today', '1', '1')")) 
		{	// this part of the script extracts the data submitted inside the HTTPWriter class, as defined in the class, the send method was _POST, which means that PHP will need to extract them via the same method, as demonstrated below for the keywords. 
			$category = $_POST['keywords']; 
			if($category != null)  // ensure we do have a defined category from HTTPWriter
			{	// it is mandatory to check to see if the category is allready existing within the database, as should this be the case, we only need to create a new entry inside the website - category link table, and not a new category (avoiding redundant groups) 
				$q = $mysqli->query("SELECT * FROM category WHERE category = '$category' LIMIT 1");
				if($q->num_rows == 1) 
				{ // if we've found a cateogry, insertt a new link record inside the link table as done hereunder
					$data = $q->fetch_array();
					$category_id = $data['cID'];
					$qprime = $mysqli->query("SELECT * FROM entries WHERE URL = '$URL' LIMIT 1");
					$result = $qprime->fetch_array();
					$website_id = $result['ID'];
					// insert the data into the link table or print error message accordingly. 
					if(!$mysqli->query("INSERT INTO websiteCategoryLink (wID, cID) VALUES ('$website_id', '$category_id')"))
						die("failed_to_insert_category_link");
					else 
						die("inserted_link_for_existing_category_and_new_website");
				}
				// if no category is existing, we need to create a new category and website category link entry
				if($mysqli->query("INSERT INTO category (category) VALUES ('$category')")) 
				{
					$q = $mysqli->query("SELECT * FROM category WHERE category = '$category' LIMIT 1"); // get the newly inserted category
						$data = $q->fetch_array();
						$category_id = $data['cID']; // fetch the new category ID from the freshly created category to allow creating the link entry
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