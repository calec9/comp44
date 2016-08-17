<?php
session_save_path("/hermes/bosoraweb093/b1118/ipg.argentetpierrescom/");
session_start();
?>
<script src="../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />

<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>

<link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />

<center>
<?php
include "functions.php";
include "database.php";
include "websiteEntries.php";
include "websiteStatistics.php";
include "websiteMetadata.php";

$mysqli = new mysqli("argentetpierrescom.ipagemysql.com", "datamaster", "test0r!", "eyespyhq");
if($mysqli->connect_errno) 
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;

$pw = $_POST['pw'];
if($_SESSION['isActive'] == false)
 {
	if(isset($pw)) 
	{
		if(isClean($pw)) 
		{
			$password = $_POST['pw'];
			$result = $mysqli->query("SELECT * FROM  logon WHERE PASSWORD =  '$password'");
			if($result->num_rows >= 1) {
				$_SESSION['isActive'] = true;
                                 // update last login time.
			} else {
				$_SESSION['error'] = "Invalid username/password.";
				redirect("index.php");
			}
		} else {
			$_SESSION['error'] = "Incorrect password/password format.";
			redirect("index.php");
		}
	} else {
		$_SESSION['error'] = "Password not set.";
		redirect("index.php");
	} 
} 

if($_SESSION['isActive'] == true) 
{
	echo "Welcome to HQ Corp. <a href='?logout'>Click here to logout.</a>";
	$request = getRequest($_SERVER['REQUEST_URI']);
	
	if($request == "?logout") 
	{
		session_destroy();
		redirect("index.php");
		die("You shouldn't be here.");
	} 
	if($request == "?delentries")
	{
		$del_entries_query = $mysqli->query("TRUNCATE TABLE entries");
		if($del_entries_query)
			echo("<br><br><b>Website entries removed.</b>");
	} 
	if($request == "?delcategories")
	{
		$del_categories_query = $mysqli->query("TRUNCATE TABLE category");
		$del_link_query = $mysqli->query("TRUNCATE TABLE websiteCategoryLink");
		if($del_categories_query && $del_link_query)
			echo("<br><br><b>Website categories removed.</b>");
	}
	if($request == "?resethitcounters")
	{
		$reset_query = $mysqli->query("UPDATE entries SET hitcounter = 0 WHERE 1");
		if($reset_query)
			echo("<br><br><b>Website hitcounters reset.</b>");
	}
	///<summary>
	///Website record fetching.
	///</summary>
	$records 	 = new websiteEntries($mysqli);
	$site 	 	 = $records->get_top_10_url();
	$sitep 		 = $records->get_10_lastvisit_url();
	$hit 		 = $records->get_top_10_hitcounter();
	$hitp 		 = $records->get_10_lastvisit_hitcounter();
	$date 		 = $records->get_top_10_lastvisit();
	$datep 		 = $records->get_10_lastvisit_lastvisit();
	$firstVisit  = $records->get_top_10_firstvisit();
	$firstVisitp = $records->get_10_lastvisit_firstvisit();
	$category	 = $records->get_top_10_category();
	$categoryp   = $records->get_top_10_lastvisit_category();
	
	///<summary>
	///Website statistics fetching.
	///</summary>	
	$statistics 	= new websiteStatistics($mysqli);
	$visit_mean 	= $statistics->get_top_10_most_visited_mean($records);
	$visits_today   = $statistics->get_top_10_most_visited_today($records);
	$ordered_visits = $statistics->get_10_most_visited_today($statistics, $records);
	$total_visits   = $statistics->get_total_hits_today();
}

?>
<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">Data analysis</li>
    <li class="TabbedPanelsTab" tabindex="0">Statistical analysis</li>
    <li class="TabbedPanelsTab" tabindex="0">Configuration</li>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
    <?php
	$day=date("l");
	echo "Total hits for today: $total_visits ($day)";
	$statistics->has_usage_increased_today();
	echo "<br> <br>";
	?>
      <div id="CollapsiblePanel1" class="CollapsiblePanel">
        <div class="CollapsiblePanelTab" tabindex="0">10 most websites visited:</div>
        
        <div class="CollapsiblePanelContent">
        <table width="600" height="400" border="0">
            <tr>
              <td><b>URL</b></td>
              <td><b>Last visit</b></td>
              <td><b>Hit counter<b></td>
              <td><b>First visit</b></td>
              <td><b>Category</b></td>
            </tr>
                         <tr>
              <td><?=$site[1]?></td>
              <td><?=$date[1]?></td>
              <td><?=$hit[1]?></td>
              <td><?=$firstVisit[1]?></td>
              <td><?=$category[1]?></td>
            </tr>            <tr>
              <td><?=$site[2]?></td>
              <td><?=$date[2]?></td>
              <td><?=$hit[2]?></td>
               <td><?=$firstVisit[2]?></td>
               <td><?=$category[2]?></td>
            </tr>            <tr>
              <td><?=$site[3]?></td>
              <td><?=$date[3]?></td>
              <td><?=$hit[3]?></td>
               <td><?=$firstVisit[3]?></td>
               <td><?=$category[3]?></td>
            </tr>            <tr>
              <td><?=$site[4]?></td>
              <td><?=$date[4]?></td>
              <td><?=$hit[4]?></td>
               <td><?=$firstVisit[4]?></td>
               <td><?=$category[4]?></td>
            </tr>            <tr>
              <td><?=$site[5]?></td>
              <td><?=$date[5]?></td>
              <td><?=$hit[5]?></td>
               <td><?=$firstVisit[5]?></td>
               <td><?=$category[5]?></td>
            </tr>            <tr>
              <td><?=$site[6]?></td>
              <td><?=$date[6]?></td>
              <td><?=$hit[6]?></td>
               <td><?=$firstVisit[6]?></td>
               <td><?=$category[6]?></td>
            </tr>            <tr>
              <td><?=$site[7]?></td>
              <td><?=$date[7]?></td>
              <td><?=$hit[7]?></td>
               <td><?=$firstVisit[7]?></td>
               <td><?=$category[7]?></td>
            </tr>            <tr>
              <td><?=$site[8]?></td>
              <td><?=$date[8]?></td>
              <td><?=$hit[8]?></td>
               <td><?=$firstVisit[8]?></td>
               <td><?=$category[8]?></td>
            </tr>            <tr>
              <td><?=$site[9]?></td>
              <td><?=$date[9]?></td>
              <td><?=$hit[9]?></td>
               <td><?=$firstVisit[9]?></td>
               <td><?=$category[9]?></td>
            </tr>            <tr>
              <td><?=$site[10]?></td>
              <td><?=$date[10]?></td>
              <td><?=$hit[10]?></td>
               <td><?=$firstVisit[10]?></td>
               <td><?=$category[10]?></td>
            </tr>
          </table>
        </div>
        <div id="CollapsiblePanel2" class="CollapsiblePanel">
          <div class="CollapsiblePanelTab" tabindex="0">Last 10 websites visited:</div>
          <div class="CollapsiblePanelContent">
            <table width="600" height="400" border="0">
              <tr>
              <td><b>URL</b></td>
              <td><b>Last visit</b></td>
              <td><b>Hit counter<b></td>
              <td><b>First visit</b></td>
              <td><b>Category</b></td>
            </tr>
                         <tr>
              <td><?=$sitep[1]?></td>
              <td><?=$datep[1]?></td>
              <td><?=$hitp[1]?></td>
               <td><?=$firstVisitp[1]?></td>
               <td><?=$categoryp[1]?></td>
            </tr>            <tr>
              <td><?=$sitep[2]?></td>
              <td><?=$datep[2]?></td>
              <td><?=$hitp[2]?></td>
              <td><?=$firstVisitp[2]?></td>
			  <td><?=$categoryp[2]?></td>
            </tr>            <tr>
              <td><?=$sitep[3]?></td>
              <td><?=$datep[3]?></td>
              <td><?=$hitp[3]?></td>
              <td><?=$firstVisitp[3]?></td>
			  <td><?=$categoryp[3]?></td>
            </tr>            <tr>
              <td><?=$sitep[4]?></td>
              <td><?=$datep[4]?></td>
              <td><?=$hitp[4]?></td>
              <td><?=$firstVisitp[4]?></td>
			  <td><?=$categoryp[4]?></td>
            </tr>            <tr>
              <td><?=$sitep[5]?></td>
              <td><?=$datep[5]?></td>
              <td><?=$hitp[5]?></td>
              <td><?=$firstVisitp[5]?></td>
			  <td><?=$categoryp[5]?></td>
            </tr>            <tr>
              <td><?=$sitep[6]?></td>
              <td><?=$datep[6]?></td>
              <td><?=$hitp[6]?></td>
              <td><?=$firstVisitp[6]?></td>
			  <td><?=$categoryp[6]?></td>
            </tr>            <tr>
              <td><?=$sitep[7]?></td>
              <td><?=$datep[7]?></td>
              <td><?=$hitp[7]?></td>
              <td><?=$firstVisitp[7]?></td>
			  <td><?=$categoryp[7]?></td>
            </tr>            <tr>
              <td><?=$sitep[8]?></td>
              <td><?=$datep[8]?></td>
              <td><?=$hitp[8]?></td>
              <td><?=$firstVisitp[8]?></td>
			  <td><?=$categoryp[8]?></td>
            </tr>            <tr>
              <td><?=$sitep[9]?></td>
              <td><?=$datep[9]?></td>
              <td><?=$hitp[9]?></td>
              <td><?=$firstVisitp[9]?></td>
			  <td><?=$categoryp[9]?></td>
            </tr>            <tr>
              <td><?=$sitep[10]?></td>
              <td><?=$datep[10]?></td>
              <td><?=$hitp[10]?></td>
              <td><?=$firstVisitp[10]?></td>
			  <td><?=$categoryp[10]?></td>
            </tr>
            </table>
          </div>
        </div>
        <p>&nbsp;</p>
      </div>
    </div>
    <div class="TabbedPanelsContent">
      <?php
	$day=date("l");
	echo "Total hits for today: $total_visits ($day)";
	$statistics->has_usage_increased_today();
	echo "<br> <br>";
	?>
      <div id="CollapsiblePanel3" class="CollapsiblePanel">
        <div class="CollapsiblePanelTab" tabindex="0">10 most visited websites - statistics</div>
        <div class="CollapsiblePanelContent">
          <table width="600" height="400" border="0">
            <tr>
              <td><b>URL</b></td>
              <td><b>Last visit</b></td>
              <td><b>Hit counter<b></b></b></td>
              <td><b>First visit</b></td>
              <td><b>Hits per day</b></td>
              <td><b>Hits today</b></td>
              <td><b></b></td>
            </tr>
            <tr>
              <td><?=$site[1]?></td>
              <td><?=$date[1]?></td>
              <td><?=$hit[1]?></td>
              <td><?=$firstVisit[1]?></td>
              <td><?=$visit_mean[1]?></td>
              <td><?=$visits_today[1]?></td>
            </tr>
            <tr>
              <td><?=$site[2]?></td>
              <td><?=$date[2]?></td>
              <td><?=$hit[2]?></td>
              <td><?=$firstVisit[2]?></td>
              <td><?=$visit_mean[2]?></td>
              <td><?=$visits_today[2]?></td>
            </tr>
            <tr>
              <td><?=$site[3]?></td>
              <td><?=$date[3]?></td>
              <td><?=$hit[3]?></td>
              <td><?=$firstVisit[3]?></td>
              <td><?=$visit_mean[3]?></td>
              <td><?=$visits_today[3]?></td>
            </tr>
            <tr>
              <td><?=$site[4]?></td>
              <td><?=$date[4]?></td>
              <td><?=$hit[4]?></td>
              <td><?=$firstVisit[4]?></td>
              <td><?=$visit_mean[4]?></td>
              <td><?=$visits_today[4]?></td>
            </tr>
            <tr>
              <td><?=$site[5]?></td>
              <td><?=$date[5]?></td>
              <td><?=$hit[5]?></td>
              <td><?=$firstVisit[5]?></td>
              <td><?=$visit_mean[5]?></td>
              <td><?=$visits_today[5]?></td>
            </tr>
            <tr>
              <td><?=$site[6]?></td>
              <td><?=$date[6]?></td>
              <td><?=$hit[6]?></td>
              <td><?=$firstVisit[6]?></td>
              <td><?=$visit_mean[6]?></td>
              <td><?=$visits_today[6]?></td>
            </tr>
            <tr>
              <td><?=$site[7]?></td>
              <td><?=$date[7]?></td>
              <td><?=$hit[7]?></td>
              <td><?=$firstVisit[7]?></td>
              <td><?=$visit_mean[7]?></td>
              <td><?=$visits_today[7]?></td>
            </tr>
            <tr>
              <td><?=$site[8]?></td>
              <td><?=$date[8]?></td>
              <td><?=$hit[8]?></td>
              <td><?=$firstVisit[8]?></td>
              <td><?=$visit_mean[8]?></td>
              <td><?=$visits_today[8]?></td>
            </tr>
            <tr>
              <td><?=$site[9]?></td>
              <td><?=$date[9]?></td>
              <td><?=$hit[9]?></td>
              <td><?=$firstVisit[9]?></td>
              <td><?=$visit_mean[9]?></td>
              <td><?=$visits_today[9]?></td>
            </tr>
            <tr>
              <td><?=$site[10]?></td>
              <td><?=$date[10]?></td>
              <td><?=$hit[10]?></td>
              <td><?=$firstVisit[10]?></td>
              <td><?=$visit_mean[10]?></td>
              <td><?=$visits_today[10]?></td>
            </tr>
          </table>
        </div>
      </div>
      <div id="CollapsiblePanel4" class="CollapsiblePanel">
        <div class="CollapsiblePanelTab" tabindex="0">Last 10 visited websites - statistics</div>
        <div class="CollapsiblePanelContent">
          <table width="600" height="400" border="0">
            <tr>
              <td><b>URL</b></td>
              <td><b>Last visit</b></td>
              <td><b>Hit counter<b></b></b></td>
              <td><b>First visit</b></td>
              <td><b>Hits per day</b></td>
              <td><b>Hits today</b></td>
              <td><b></b></td>
            </tr>
            <tr>
              <td><?=$sitep[1]?></td>
              <td><?=$datep[1]?></td>
              <td><?=$hitp[1]?></td>
              <td><?=$firstVisitp[1]?></td>
              <td><?=$visit_mean[1]?></td>
              <td><?=$visits_today[1]?></td>
            </tr>
            <tr>
              <td><?=$sitep[2]?></td>
              <td><?=$datep[2]?></td>
              <td><?=$hitp[2]?></td>
              <td><?=$firstVisitp[2]?></td>
              <td><?=$visit_mean[2]?></td>
              <td><?=$visits_today[2]?></td>
            </tr>
            <tr>
              <td><?=$sitep[3]?></td>
              <td><?=$datep[3]?></td>
              <td><?=$hitp[3]?></td>
              <td><?=$firstVisitp[3]?></td>
              <td><?=$visit_mean[3]?></td>
              <td><?=$visits_today[3]?></td>
            </tr>
            <tr>
              <td><?=$sitep[4]?></td>
              <td><?=$datep[4]?></td>
              <td><?=$hitp[4]?></td>
              <td><?=$firstVisitp[4]?></td>
              <td><?=$visit_mean[4]?></td>
              <td><?=$visits_today[4]?></td>
            </tr>
            <tr>
              <td><?=$sitep[5]?></td>
              <td><?=$datep[5]?></td>
              <td><?=$hitp[5]?></td>
              <td><?=$firstVisitp[5]?></td>
              <td><?=$visit_mean[5]?></td>
              <td><?=$visits_today[5]?></td>
            </tr>
            <tr>
              <td><?=$sitep[6]?></td>
              <td><?=$datep[6]?></td>
              <td><?=$hitp[6]?></td>
              <td><?=$firstVisitp[6]?></td>
              <td><?=$visit_mean[6]?></td>
              <td><?=$visits_today[6]?></td>
            </tr>
            <tr>
              <td><?=$sitep[7]?></td>
              <td><?=$datep[7]?></td>
              <td><?=$hitp[7]?></td>
              <td><?=$firstVisitp[7]?></td>
              <td><?=$visit_mean[7]?></td>
              <td><?=$visits_today[7]?></td>
            </tr>
            <tr>
              <td><?=$sitep[8]?></td>
              <td><?=$datep[8]?></td>
              <td><?=$hitp[8]?></td>
              <td><?=$firstVisitp[8]?></td>
              <td><?=$visit_mean[8]?></td>
              <td><?=$visits_today[8]?></td>
            </tr>
            <tr>
              <td><?=$sitep[9]?></td>
              <td><?=$datep[9]?></td>
              <td><?=$hitp[9]?></td>
              <td><?=$firstVisitp[9]?></td>
              <td><?=$visit_mean[9]?></td>
              <td><?=$visits_today[9]?></td>
            </tr>
            <tr>
              <td><?=$sitep[10]?></td>
              <td><?=$datep[10]?></td>
              <td><?=$hitp[10]?></td>
              <td><?=$firstVisitp[10]?></td>
              <td><?=$visit_mean[10]?></td>
              <td><?=$visits_today[10]?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="TabbedPanelsContent">
      <table width="296" border="0">
        <tr>
          <td width="140">Delete website entries</td>
          <td width="146"><form id="form1" name="form1" method="post" action="?delentries">
            <input type="submit" name="button" id="button" value="Submit" />
          </form></td>
        </tr>
        <tr>
          <td>Delete categories</td>
          <td><form id="form2" name="form1" method="post" action="?delcategories">
            <input type="submit" name="button2" id="button2" value="Submit" />
          </form></td>
        </tr>
        <tr>
          <td>Reset hitcounters</td>
          <td><form id="form2" name="form1" method="post" action="?resethitcounters">
            <input type="submit" name="button2" id="button2" value="Submit" />
          </form></td>
        </tr>
              </table>
    </div>
  </div>
      <p>&nbsp;</p>
  </div>Click on the titles to expand the menu for a full view.</div>
</div>
</center>
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2");
var CollapsiblePanel3 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel3");
var CollapsiblePanel4 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel4");
</script>
