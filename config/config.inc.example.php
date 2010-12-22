<?php

error_reporting(~E_ALL);
error_reporting(E_NONE);


//prevent data from being saved or sent out.
define("testmode", TRUE);

// TODO do this for javascript side or track in $_SESSION
define("secsBetweenPosts", 0.0); // sleep between send

//database configuration -- no table creation or installation necesarry on the user's part
$mysql_host = "localhost";
$mysql_database = "cl_devel_ws";
$mysql_user = "erratic";
$mysql_password = "";

// append a read receipt line to the SMTP headers
$config['getreadreceipt'] = TRUE;

// confirm image (when the email client loads images, it will load this image which is actually a php script for logging .htaccess file makes this possible.
$config['readconfirmimg'] = "http://cl.devel.ws/confirm/image.jpg";

//TODO 
//append a delivery confirmation line to the smtp headers

//TODO setup a gmail filter to forward emails containing "jobfinder-receipt" in the TO in my catchall account :) to forward to erratic@devel.ws
$config['catchalladdr'] = '-jobfinder-receipt-catchall@devel.ws';

// send a blind carbon copy to for checking, comment out to unset 
$config['bccaddr'] = "erratic@devel.ws";

$config['debug'] = false;
$config['display_posts_after_email'] = false;

$config['mysql']['host'] = $mysql_host;
$config['mysql']['username'] = $mysql_user;
$config['mysql']['password'] = $mysql_password;
$config['mysql']['database'] = $mysql_database;

$config['email'][0]['from_addr'] = "jobfinder8@devel.ws";
$config['email'][0]['from_name'] = "P. Adele Thompson";
$config['email'][0]['host'] = "smtp.gmail.com";
$config['email'][0]['port'] = 465;
$config['email'][0]['ssl'] = true;
$config['email'][0]['username'] = "jobfinder8@devel.ws";
$config['email'][0]['password'] = "";

$config['email'][1]['from_addr'] = "jobfinder9@devel.ws";
$config['email'][1]['from_name'] = "P. Adele Thompson";
$config['email'][1]['host'] = "smtp.gmail.com";
$config['email'][1]['port'] = 465;
$config['email'][1]['ssl'] = true;
$config['email'][1]['username'] = "jobfinder9@devel.ws";
$config['email'][1]['password'] = "";

$config['email'][2]['from_addr'] = "jobfinder1@devel.ws";
$config['email'][2]['from_name'] = "P. Adele Thompson";
$config['email'][2]['host'] = "smtp.gmail.com";
$config['email'][2]['port'] = 465;
$config['email'][2]['ssl'] = true;
$config['email'][2]['username'] = "jobfinder1@devel.ws";
$config['email'][2]['password'] = "";

$config['email'][3]['from_addr'] = "jobfinder2@devel.ws";
$config['email'][3]['from_name'] = "P. Adele Thompson";
$config['email'][3]['host'] = "smtp.gmail.com";
$config['email'][3]['port'] = 465;
$config['email'][3]['ssl'] = true;
$config['email'][3]['username'] = "jobfinder2@devel.ws";
$config['email'][3]['password'] = "";

$config['email'][4]['from_addr'] = "jobfinder3@devel.ws";
$config['email'][4]['from_name'] = "P. Adele Thompson";
$config['email'][4]['host'] = "smtp.gmail.com";
$config['email'][4]['port'] = 465;
$config['email'][4]['ssl'] = true;
$config['email'][4]['username'] = "jobfinder3@devel.ws";
$config['email'][4]['password'] = "";

$config['email'][5]['from_addr'] = "jobfinder4@devel.ws";
$config['email'][5]['from_name'] = "P. Adele Thompson";
$config['email'][5]['host'] = "smtp.gmail.com";
$config['email'][5]['port'] = 465;
$config['email'][5]['ssl'] = true;
$config['email'][5]['username'] = "jobfinder4@devel.ws";
$config['email'][5]['password'] = "";

$config['email'][6]['from_addr'] = "jobfinder5@devel.ws";
$config['email'][6]['from_name'] = "P. Adele Thompson";
$config['email'][6]['host'] = "smtp.gmail.com";
$config['email'][6]['port'] = 465;
$config['email'][6]['ssl'] = true;
$config['email'][6]['username'] = "jobfinder5@devel.ws";
$config['email'][6]['password'] = "";

$config['email'][7]['from_addr'] = "jobfinder6@devel.ws";
$config['email'][7]['from_name'] = "P. Adele Thompson";
$config['email'][7]['host'] = "smtp.gmail.com";
$config['email'][7]['port'] = 465;
$config['email'][7]['ssl'] = true;
$config['email'][7]['username'] = "jobfinder6@devel.ws";
$config['email'][7]['password'] = "";

$config['email'][8]['from_addr'] = "jobfinder7@devel.ws";
$config['email'][8]['from_name'] = "P. Adele Thompson";
$config['email'][8]['host'] = "smtp.gmail.com";
$config['email'][8]['port'] = 465;
$config['email'][8]['ssl'] = true;
$config['email'][8]['username'] = "jobfinder7@devel.ws";
$config['email'][8]['password'] = "";

//http://seattle.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss

//list craigslist feeds here

$config['feed'][] = "http://glensfalls.craigslist.org/search/cpg?query=+&format=rss";
$config['feed'][] = "http://atlanta.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://austin.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://boston.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://chicago.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://dallas.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://denver.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://detroit.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://houston.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://lasvegas.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";

$config['feed'][] = "http://losangeles.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";

//$config['feed'][] = "http://losangeles.craigslist.org/sad/index.rss";


$config['feed'][] = "http://miami.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://minneapolis.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://newyork.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://orangecounty.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://philadelphia.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://phoenix.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://raleigh.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://sacramento.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://sandiego.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://sfbay.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://washingtondc.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";

$config['feed'][] = "http://bellingham.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://kpr.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://lewiston.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://moseslake.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://olympic.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://pullman.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://seattle.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";

//$config['feed'][] = "http://seattle.craigslist.org/sad/index.rss";

$config['feed'][] = "http://skagit.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://spokane.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://wenatchee.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://yakima.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://bend.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://corvallis.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";

$config['feed'][] = "http://eastoregon.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://eugene.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://klamath.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://medford.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://oregoncoast.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://portland.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://roseburg.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";
$config['feed'][] = "http://salem.craigslist.org/search/sof?query=&srchType=A&addOne=telecommuting&format=rss";

$config['css_url'] = 'include/style.css';
$config['page_title'] = 'Craigslist Job Application Assistant - Paige Thompson';


?>
