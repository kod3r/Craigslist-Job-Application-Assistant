<?php
//error_reporting(E_ALL);
//#736F6E gray background	
//#806D7E button lavander
//#FDD017 button text orange
//#EBDDE2 table column lavander
// text #7E354D
/*
CREATE TABLE logs (
`id` INT( 7 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`url` VARCHAR( 255 ) NOT NULL
) ENGINE = MYISAM ;
 */
require_once(dirname(__FILE__) . '/include/craigslist_feed.class.php');
require_once(dirname(__FILE__) . '/include/mailer.class.php');
require_once(dirname(__FILE__) . '/include/mysql.class.php');
require_once(dirname(__FILE__) . '/config/config.inc.php');
require_once(dirname(__FILE__) . '/include/html2doc.php');

function SendJSONResponse($data)  {
	/*echo json_encode(
		array(
		       	"Person" => array(
			 	"firstName" => "John",
			    	"lastName" => "Smith",
				"age" => 25,
			    	"Address" => array(
				  	"streetAddress" => "21 2nd Street",
				  	"city" => "New York",
				  	"state" => "NY",
				  	"postalCode" => "10021"
			    	),
				"PhoneNumbers" => array(
				  	"home" => "212 555-1234",
				   	"fax" => "646 555-4567"
				)
			)
		)
	);
	
	exit();
	 */
	echo json_encode($data);

}
if(isset($_GET["ajax"]) && $_GET["ajax"] == 1) {
	// contacting a job posting
	$attachmentmessages = array();
	//$_POST["selected"] = array($_GET["selected"]); don't need this anymore
	$_POST["selected"] = array($_POST["selected"]);
	$mysql = new Mysql($config['mysql']['username'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['host']);
	$attachment_files = setupAttachments(array(), $mysql, TRUE);
	if(is_array($attachment_files[count($attachment_files)-1])) 	
		$attachmentmessages = array_pop($attachment_files);
	
	$feed = new CraigslistFeed();
	$ret = ProcessPost($mysql, $config, $attachment_files, TRUE);
	SendJSONResponse(array($ret, $attachmentmessages));
	exit();
} else if(isset($_GET["ajax"]) && $_GET["ajax"] == 2) {
	//fetching coverletter by job id
	$mysql = new Mysql($config['mysql']['username'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['host']);
	SendJSONResponse(GetCoverLetterOrDefault($mysql, $_POST["selected"]), TRUE);	
	//
	exit();
	
} else if(isset($_GET["ajax"]) && $_GET["ajax"] == 3) {
	//fetching custom subject by job id
	exit();
	
} else if(isset($_GET["ajax"]) && $_GET["ajax"] == 4) {
	//saving coverletter by job id
	$mysql = new Mysql($config['mysql']['username'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['host']);
	SendJSONResponse(SaveCoverLetter($mysql, $_POST["selected"], $_POST["content"]));
	exit();
	
} else if(isset($_GET["ajax"]) && $_GET["ajax"] == 5) {
	//saving custom subject by job id
	exit();
	
} else if(isset($_GET["ajax"]) && $_GET["ajax"] == 6) {
	//fetching custom recipient by job id
	exit();
	
} else if(isset($_GET["ajax"]) && $_GET["ajax"] == 7) {
	//saving custom recipient by job id
	exit();
	
} else if(isset($_GET["ajax"]) && $_GET["ajax"] == 8) {
	//fetching email address from posting by job id
	exit();
	
} else { 
	$mysql = new Mysql($config['mysql']['username'], $config['mysql']['password'], $config['mysql']['database'], $config['mysql']['host']);
	$attachment_files = setupAttachments(array(), $mysql, TRUE);
	$feed = new CraigslistFeed();
}
function SaveCoverLetter($mysql, $jobid, $content, $ajax=TRUE) { 
	$ret = array();
// Will keep this out for now because its nice to have revisions around
//	$sql = "DELETE from coverletters where jobid = '".$jobid."'";
//	$mysql->query($sql);
	$sql = "insert into coverletters (url, content) VALUES('" .$jobid."', '".$content."')";
	$success = $mysql->query($sql);
	if(!$success)
		/* TODO: refactor */
		if($ajax) {
			array_push($ret, array("save_success" => "<strong>ERROR:</strong> " . $mysql->getError() . "<br/>\n"));	
			return($ret);
		}
		else 
			print "<strong>ERROR:</strong> " . $mysql->getError() . "<br/>\n";
	else {
		array_push($ret, array("save_success" => "successfully saved.")); 
		return($ret);
	}

}
/*
function FetchCoverLetterByJobIDOrGetDefault($mysql, $jobid, $ajax=TRUE) {
	$ret = array();
	$sql = "select * from coverletters where url = '" .$jobid."' LIMIT 1";
	$success = $mysql->query($sql);
	if(!$success)
		if($ajax)
			array_push($ret, "<strong>ERROR:</strong> " . $mysql->getError() . "<br/>\n");
		else 
			print "<strong>ERROR:</strong> " . $mysql->getError() . "<br/>\n";
	if($mysql->GetNumRows() == 0) {
		$sql = "select * from coverletters where url = 'default' LIMIT 1";
	}
	else {
		$decoded = $mysql->fetchAssoc();
		$decoded["content"] = $decoded["content"];
		$decoded["jobid"] = $jobid;
		array_push($ret, $decoded); 
		return($ret);
	}
	$success = $mysql->query($sql);
	if(!$success)
		if($ajax)
			array_push($ret, "<strong>ERROR:</strong> " . $mysql->getError() . "<br/>\n");
		else 
			print "<strong>ERROR:</strong> " . $mysql->getError() . "<br/>\n";
	else {
		$decoded = $mysql->fetchAssoc();
		$decoded["content"] = $decoded["content"];
		$decoded["jobid"] = $jobid;
		array_push($ret, $decoded); 
		return($ret);
	}
	return($ret);
}
*/
function GetCoverLetterOrDefault($mysql, $selected, $ajax=TRUE) { 
	$jobid = $selected;
	$sql = "select * from coverletters where url = '" .$jobid."' LIMIT 1";
	$success = $mysql->query($sql);
	if(!$success)
		$ret = SQLMessage($ret, "ERROR: ".  $mysql->getError(), $ajax);
	if($mysql->GetNumRows() == 0) {
		$sql = "select * from coverletters where url = 'default' LIMIT 1";
		$success = $mysql->query($sql);
		if(!$success) 
			$ret = SQLMessage($ret, "ERROR: ".  $mysql->getError(), $ajax);
	}
	$coverLetter = $mysql->fetchAssoc();
	$coverLetter["jobid"] = $selected;
	return($coverLetter);
}
function setupAttachments($attachment_files, $mysql, $ajax = FALSE) {
	$attachment_dir = dirname(__FILE__) . '/config/attachments/';
	$success = file_exists(dirname(__FILE__) . '/config/email.txt');
	if($ajax)
		$setupAttachmentsret = array();
	if(!$success)
		$setupAttachmentsret = AttatchmentMessage($setupAttachmentsret, "ERROR: email file: config/email.txt does not exist");

	if ($handle=opendir($attachment_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file == '.' | $file == '..') {
				continue;
			}
			array_push($attachment_files, $attachment_dir . $file);
		}
		if (count($attachment_files) < 1) {
			$setupAttachmentsret = AttatchmentMessage($setupAttachmentsret,"WARNING: too few attachments in: $attachment_dir");
		}
	} else {
		$setupAttachmentsret = AttatchmentMessage($setupAttachmentsret, "Cannot open attachments directory");	
	}
	array_push($attachment_files, $setupAttachmentsret);
	return($attachment_files);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	ProcessPost($mysql, $config, $attachment_files);
}
function AttachmentMessage($msgstack, $message) { 
	if(!is_array($msgstack)) { 
		$msgstack = array();
	}
	if($ajax)
		array_push($msgstack, $message);
	else
		print $message; 
}
function SQLMessage($msgstack, $message, $ajax) {
	if(!is_array($msgstack)) { 
		$msgstack = array();
	}
	if($ajax)
		array_push($msgstack, $message);
	else 
		print $message;
}
function MailerMessage($msgstack, $message, $ajax, $selected, $type, $mysql=NULL) {
	if(!is_array($msgstack)) { 
		$msgstack = array();
	}
	if($ajax)
		array_push($msgstack, $message);
	else
		//print "DEBUG MODE:  will not email out: disabled.  address to send to is: " . $mailer->getEmailAddress() . "<br/>\n";
		print $message;
	if($mysql != NULL) { 
		$sql = "INSERT INTO logs (url, status) VALUES ('$selected', '$type')";
		$success = $mysql->query($sql);
		if(!$success)
			$ret = SQLMessage($ret, "ERROR: ".  $mysql->getError(), $ajax);
	}
	return($msgstack);
}
function ProcessPost($mysql, $config, $attachment_files, $ajax = FALSE) {
	if($ajax) 
		$ret = array();
	foreach ($_POST['selected'] as $selected) {
		try {
			$mailer = new Mailer($selected, $config, $attachment_files, $ajax);
			$coverLetter = GetCoverLetterOrDefault($mysql, $selected);		
			/* convert this HTML Doc to a word doc :D
			 * http://www.phpclasses.org/browse/file/14707.html 
			 */
			$doc = "";
			$myhtmldocfactory = new HTML_TO_DOC();
			$myhtmldocfactory->_parseHtml($coverLetter["content"]);
			$doc = $myhtmldocfactory->getHeader();
			$doc .= $myhtmldocfactory->htmlBody;
			$doc .= $myhtmldocfactory->getFotter();
			$mailer->xpm_obj->Attach($doc, "application/msword", "coverletter.doc", null, null, 'inline', MIME::unique());
			/**/
			if ($config['debug'] == true) {			

				$ret = MailerMessage(
					$ret, 
					"DEBUG MODE:  will not email out: disabled.  address to send to is: " . $mailer->getEmailAddress() . "<br/>\n", 
					$selected,
					"notice",
					$ajax);
				continue;
				 
			}
			if ( $mailer->send() !== true ) {
				$ret = MailerMessage(
					$ret, 
					"Error Sending Email: " . $mailer->getEmailAddress() . "<br/>\n", 
					$ajax, 
					$selected,
					"error",
					$mysql
				);
			} 
			else {
				$ret = MailerMessage(
					$ret, 
					"Successfully Mailed: " . $mailer->getEmailAddress() . "<br/>\n", 
					$ajax, 
					$selected,
					"sent",
					$mysql
				);
			}
		
		} 
		catch (Exception $e) {
			$ret = MailerMessage(
				$ret,
				"Email exception caught in post",
				$ajax,
				$selected,
				"error",
				$mysql
			);
		}
	}
	if($ajax) { 
		return($ret);
	}
	else if ($config['display_posts_after_email'] == false) {
		die();
	}
}

//ie quirks 
print '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
print "<html>\n";
print "<head>\n";

?>
<script src="/js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="/js/jquery.tablesorter.js"></script> 
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript" src="../js/tiny_mce/jquery.tinymce.js"></script>

<script type="text/javascript">
$(function() {

$('textarea.tinymce').tinymce({
// Location of TinyMCE script
script_url : '../js/tiny_mce/tiny_mce.js',
theme : "advanced",
plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
 
theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
theme_advanced_toolbar_location : "top",
theme_advanced_toolbar_align : "left",
theme_advanced_statusbar_location : "bottom",
theme_advanced_resizing : true,
 
// Example content CSS (should be your site CSS)
content_css : "css/content.css",
 
// Drop lists for link/image/media/template dialogs
template_external_list_url : "lists/template_list.js",
external_link_list_url : "lists/link_list.js",
external_image_list_url : "lists/image_list.js",
media_external_list_url : "lists/media_list.js",
 
// Replace values for the template plugin
template_replace_values : {
username : "Some User",
staffid : "991234"
}
});
});
</script>



<?
print "<title>$config[page_title]</title>\n";
print "<link rel='stylesheet' type='text/css' href='$config[css_url]'/>\n";
print "</head>\n";
print "<body>\n";
?>
 <!-- This <div> holds alert messages to be display in the sample page. -->
         <div id="alerts">
                 <noscript>
                         <p>
                                 <strong>CKEditor requires JavaScript to run</strong>. In a browser with no JavaScript
                                 support, like yours, you should still see the contents (HTML data) and you should
                                 be able to edit it normally, without a rich editor interface.
                         </p>
                 </noscript>
         </div>

  <div id="boxes">
       <!-- #customize your modal window here -->
	<div id="dialog2" class="logwindow window">
<?
 print "<table class='tablesorter' id='myTable2'>\n";
 print "<thead>\n";
 print "<tr>\n";
 print "<th>id</th>\n";
 print "<th>url</th>\n";
 print "<th>status</th>\n";
 print "<th>modified</th>\n";
 print "</tr>\n";
 print "</thead><tbody>\n";
 $sql = "SELECT * FROM logs";
 $res = $mysql->query($sql);
 while($row = mysql_fetch_row($res)) { 
  print "<tr>\n";
  print "<td>".$row[0]."</td><td><a href=\"".$row[1]."\">".$row[1]."</a></td><td>".$row[2]."</td><td>".$row[3]."</td>";
  print "</tr>\n";
 }
 print "</tbody></table>";

?>
<!--a href="#" class="close">Close it</a-->
<center><div id="closebutton" class="button popupwindowbuttons close"><center>Close</center></div></center>

	</div>
	
	<div id="dialog" class="window">
	
<form action="sample_posteddata.php" method="post">
			<div id="coverletterselector" class="coverletterselectorclass"><span>Default Cover Letter</span><div id="coverletterselectorbutton" class="sidebarcombobutton"><img style="padding-top: 6px;" src="include/asc.gif"></div></div>
				<!--textarea id="editor1" name="editor1" cols="80" rows="10"></textarea-->
			  <!--textarea class="ckeditor" cols="80" id="editor1" name="editor1" rows="10"></textarea-->
			<form method="post" action="somepage">
<textarea id="content" name="content" class="tinymce" style="width:100%; height:350px;">
</textarea>
 
<!-- Some integration calls -->
<!--div id="closebutton3" class="button popupwindowbuttons close" onmousedown="$('#content').tinymce().show();"><center>Show</center></div-->
<!--div id="closebutton4" class="button popupwindowbuttons close" onmousedown="$('#content').tinymce().hide();"><center>Hide</center></div--> 
<div id="closebutton5" class="button popupwindowbuttons close" onmousedown="$('#content').tinymce().execCommand('Bold');"><center>Bold</center></div> 
<div id="closebutton6" class="button popupwindowbuttons close" onmousedown="alert($('#content').html());"><center>Get contents</center></div> 
<div id="closebutton7" class="button popupwindowbuttons close" onmousedown="alert($('#content').tinymce().selection.getContent());"><center>Get selected HTML</center></div> 
<div id="closebutton8" class="button popupwindowbuttons close" onmousedown="alert($('#content').tinymce().selection.getContent({format : 'text'}));"><center>Get selected text</center></div> 
<div id="closebutton9" class="button popupwindowbuttons close" onmousedown="alert($('#content').tinymce().selection.getNode().nodeName);"><center>Get selected element</center></div> 
<div id="closebutton10" class="button popupwindowbuttons close" onmousedown="$('#content').tinymce().execCommand('mceInsertContent',false,'<b>Hello world!!</b>');"><center>Insert HTML</center></div> 
<div id="closebutton11" class="button popupwindowbuttons close" onmousedown="$('#content').tinymce().execCommand('mceReplaceContent',false,'<b>{$selection}</b>');"><center>Replace selection</center></div> 
               
 <!--a href="#" class="close">Close it</a-->
<div id="closebutton2" class="closebutton2 button popupwindowbuttons close"><center>Save</center></div> 
<div id="closebutton12" class="button popupwindowbuttons close"><center>Close</center></div> 
<div style="margin-top:4px; float:left; margin-left:2px;"><input style="float: left; margin-top:4px;" type="checkbox" id="savedefault" value=""><span style="color: #FDD017;">&nbsp;&nbsp;Save Default</span></input></div>
</form>	
</div>
        <!-- Do not remove div#mask, because you'll need it to fill the whole screen -->
        <!--div id="mask"></div-->
    </div>
<div class="bodywrapper">

<div id="sidebar">
<center>
<div id="other" class="button sidebarbuttons"><center>Contact Checked Jobs</center></div>				  
<div id="viewlogbutton" class="button sidebarbuttons"><center>View Log</center></div> 
<div id="recontact" class="button sidebarbuttons"><center>Recontact All</center></div>
<div id="uncheckall" class="button sidebarbuttons"><center>Uncheck All</center></div>
<div id="selectionsummary" class="button sidebarbuttons"><center>Selection Summary</center></div>
<div id="favorites" class="button sidebarbuttons"><center>Favorites</center></div>
		<!--textarea class="sidebarstatuswindow" id="statusm">Ready.</textarea-->
		<div id="statusm" class="sidebarstatuswindow">
		<ul id="statusmlist">
		<li><i>Ready to run, new messages start here.</i></li>
		<li>--------------------------------------</li>
		</ul>
		<a rel="license" href="http://creativecommons.org/licenses/by/3.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by/3.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type">Job Application Assistant</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="http://www.github.com/paigeadele" property="cc:attributionName" rel="cc:attributionURL">Paige Adele Thompson</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by/3.0/">Creative Commons Attribution 3.0 Unported License</a>.<br />Based on a work at <a xmlns:dct="http://purl.org/dc/terms/" href="http://www.github.com/paigeadele" rel="dct:source">www.github.com</a>.<br />Permissions beyond the scope of this license may be available at <a xmlns:cc="http://creativecommons.org/ns#" href="http://www.github.com/paigeadele" rel="cc:morePermissions">http://www.github.com/paigeadele</a>.
		</div>		
<!--select name="sortby" size="1" onchange="alert('todo');" class="sidebarnavcombo">
<option value="" name="sortby2" selected><b>Sort Posts By</b></option>
<option value="selected">Selected</option>
<option value="postdate">Date Posted</option>
<option value="sourcefeed">Source Feed</option>
<option value="body">Body</option>
<option value="modified">Modified Date</option<>
<option value="favorites">Favorites</option>
<option value="erroredposts">Posts with Errors</option>
<option value="sent">Contacted Posts</option>

</select-->
<div id="sortby" class="sidebarsortby"><span>Sort By</span><div id="sidebarsortbycombobutton" class="sidebarcombobutton"><img style="padding-top: 6px;" src="include/asc.gif"></div></div>
	</center>
	<div id="dialog3" class="combowindow">
	<ul>
	<li id="sortselected">Selected</li>
	<li id="sortdateposted">Date Posted</li>
	<li id="sortsourcefeed">Source Feed</li>
	<li>Body</li>
	<li>Modified Date</li>
	<li>Favorites</li>
	<li>Posts with Errors</li>
	<li>Contacted Posts</li>
	</ul>
	</div>
	</div>

<SCRIPT language="JavaScript">
$(document).ready(function() {
	 //sanitize
	 $('input:checked').attr('checked', false); 
});
 
$(function() {
	scrollModalDivs();
});
function scrollModalDivs() {
	scrollDiv("#sidebar", 7, 15);
	scrollDiv("#dialog", 20, 15);
	scrollDiv("#dialog1", 20, 15);
	scrollDiv("#dialog2", 20, 15);
//	scrollDiv("#dialog3", 20, 15);
}
function scrollDiv(what, margintop, topPadding) {
	var offset = $(what).offset();
	if(offset == undefined) return;
	$(window).scroll(function() {
		if ($(window).scrollTop() > offset.top) {
    			$(what).stop().animate({
	    			marginTop: $(window).scrollTop() - offset.top + topPadding
			});
		} else {
    			$(what).stop().animate({
	    			marginTop: margintop 
      			});
		};
	});
}
</script>
<div class="poststable">

<?

foreach ($config['feed'] as $url) {
	$feed->addFeed($url);
}

$feed->sortFeedsByDate();
print "<form action='$_SERVER[PHP_SELF]' id='senddata'>\n";
print "<table class='tablesorter' id='myTable'>\n";
print "<thead>\n";
print "<tr>\n";
print "<th width='25'>X</th>\n";
print "<th>Date</th>\n";
print "<th>Source Feed</th>\n";
print "<th>Body</th>\n";
print "<th width='70'>modified</th>\n";
print "</tr>\n";
print "</thead><tbody>\n";
foreach ($feed->getPosts() as $post) {
	$please = FALSE;
	$sql = "SELECT * FROM logs WHERE url='$post[link]' LIMIT 1";
	$res = $mysql->query($sql);
	if ($mysql->getNumRows() == 1) {
		$row = $mysql->fetchAssoc();
		switch($row['status']) { 
			case 'sent':
				print "<tr class='mailed'>\n";
				break;
			case 'error':
				print "<tr class='error1'>\n";
				break;
			default:
				break;
		}
		$please = TRUE;
	} else {
		print "<tr>\n";
	}
	if(!$please) 
		print "<td valign='top'>";
	else
		 print "<td valign='top'>";
	if(!$please)
		print "<input type='checkbox' name='selected[]' value='$post[link]'/></td>\n";
	else 
	        print "<input type='checkbox' name='selected[]' value='$post[link]' disabled/></td>\n";
	if(!$please)
		print "<td valign='top'>";
	else 
		print "<td valign='top'>";
	print date('m/d/Y h:i:s A', $post['date']) . "</td>\n";
	print "<td valign='top'>$post[feed_url]</td>\n";
	print "<td valign='top'>\n";
	print "<strong><a href='$post[link]'>$post[title]</strong></a><br/><br/>\n";
	print "$post[body]\n";
	print "<br /><br />";
?>
<br style="clear: both;"/>
<div id="<? echo $post['link'];?>" class="addfavoritebutton button tabledescbuttons"><center>Add Favorite</center></div>
<div id="<? echo $post['link'];?>" class="coverletterbutton button tabledescbuttons"><center>Edit Cover Letter</center></div>
	<div id="<? echo $post['title'];?>" class="customsubjectbutton button tabledescbuttons"><center>Edit Subject Line</center></div>
	<div id="<? echo $post['link'];?>" class="customrecipientbutton button tabledescbuttons"><center>Edit RCPT Address</center></div>
	<div id="<? echo $post['link'];?>" class="sendemailbutton button tabledescbuttons"><center>Send E-mail</center></div>

<br /><br />
<?
	$uhm = mysql_fetch_row($res);
	print "</td>\n";
	print "<td>". $uhm[3]."</td>";
	print "</tr>\n";
}
print "<tr>\n";
print "<td colspan='4'><input type='submit' style='visibility: hidden;' value='Spam'</td>\n";
print "</tr>\n";
print "</tbody>\n";
print "</table>\n";
print "</form>\n";
?>
<SCRIPT language="JavaScript">
$(document).ready(function() { 
	$("#myTable").tablesorter();
	$("#myTable2").tablesorter();
/*	
	$(".mailed").children().css('background-color', 'CC66FF');
	$(".mailed").children().css('color', 'FFF');
	$(".error1").children().css('background-color', 'red');
	$(".error1").children().css('color', 'FDD017');
*/
	$("tr.mailed").children().addClass('mailed');
	$("tr.error1").children().addClass('error1');
	

});
function updateProgress(data, textStatus, xmlHTTPRequest) { 
	for(var i = 0; i< data.length; i++) { 
		$("#statusmlist").prepend("<li><span style='color: green;'>"+data[i][0]+"</span></li>");
		//$("#statusm").scrollBottom = $("#statusm").scrollHeight;
	}
}
function iteratePostsError(XMLHttpRequest, textStatus, errorThrown) { 
	//alert(textStatus);
	//alert(errorThrown);
	$("#statusmlist").prepend("<li><span style='color: red;'>"+textStatus+"</span></li>");
	$("#statusmlist").prepend("<li><span style='color: red;'>"+errorThrown+"</span></li>");
}
function iteratePosts(whatkind) {
	var sel = $(whatkind);
	for(var i = 0; i < sel.length; i++) { 
		$.ajax({ 
			//url: "index.php?ajax=1&selected=" + sel[i].value,  
			url: "index.php?ajax=1",
			type: "POST", 
			//contentType: "application/x-www-form-urlencoded",
			data: { selected: sel[i].value },
			//context: document.body, <-- what is this even for :< *angry* *angry* *angry*
			dataType: "json",
			success: updateProgress,
			error: iteratePostsError,
		});
/*
		$.post({
			url: "index.php?ajax=1",
			
			context: document.body,
			type: "json",
			callback: updateProgress,
			error: iteratePostsError,
	});
*/
	}
	if(whatkind == "input:checked") {
		//* TODO: there should be code to determine whether or not it was successful or an error. */
		sel.parent().parent().children().css('background-color', 'DBC5CD');
		sel.parent().parent().children().css('color', 'FFF');
		//sel.parent.parent.children.addClass('
		sel.attr("checked", false);
		sel.toggle();
	}
	return false;
}


//$(document).ready(function() {	

	//select all the a tag with name equal to modal
	//$('a[name=modal]').click(function(e) {
function showModalWindow(content, which) {
	// 	ck editor broken.
	//	$('#editor1').ckeditor( function() { }, { skin : 'office2003' } );
	//	var editor = $('#editor1').ckeditorGet(); 
	//	alert( editor.checkDirty() ); 
	//	editor.insertText(content);
	//
	//	tinyMCE.execCommand('mceInsertContent',false,'<br><img alt=$img_title src=$link/img/sadrzaj/$file\>');">Insert Image</a>
		$('textarea.tinymce').tinymce().execCommand('mceInsertContent',true, content);
		
		//Cancel the link behavior
	//	e.preventDefault();
		//Get the A tag
		var id = which;

		//Get the screen height and width
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();
	
		//Set height and width to mask to fill up the whole screen
		$('#mask').css({'width':maskWidth,'height':maskHeight});
		
		//transition effect		
		$('#mask').fadeIn(1000);	
		$('#mask').fadeTo("slow",0.8);	
	
		//Get the window height and width
		var winH = $(window).height();
		var winW = $(window).width();
              
		//Set the popup window to center
		$(id).css('top',  winH/2-$(id).height()/2);
		$(id).css('left', winW/2-$(id).width()/2);
	
		//transition effect
		$(id).fadeIn(2000); 
	
	};
	
	//if close button is clicked
	$('.window .close').click(function (e) {
		//Cancel the link behavior
		e.preventDefault();
		$('#mask, .window').hide();
	});		
	
	//if mask is clicked
	$('#mask').click(function () {
		$(this).hide();
		$('.window').hide();
	});			
	
$('#senddata').submit(function() {
	// I think this can also be accomplished with e.preventDefault()
	return(iteratePosts("input:checked"));
});
$('#other').click(function() {
	$('#senddata').submit();
});
function fetchCoverLetter(data, textStatus, xmlHTTPRequest)
{
	$('.closebutton2').attr("id", data.jobid);
	//$('textarea.tinymce').tinymce().execCommand('mceInsertContent', false, data[0].content.toString());
	showModalWindow(data.content, '#dialog');
}
function saveCoverLetterStatus(data, textStatus, xmlHTTPRequest) { 
	$("#statusmlist").prepend("<li><span style='color: green; background-color: black;'>" + data[0].save_success + "</span></li>");
}
$('.closebutton2').click(function(e) {
	var selectid = this.id;
	//	var savedefaultselectbox = $('#savedefault');
	/*if($("#savedefault:checked").val() !== null) { 
		selectid = "default";
}*/
	$.ajax({
		url: "index.php?ajax=4",
		data: { 
			selected: selectid, 
			content: $('textarea.tinymce').tinymce().getContent() 
		},	
		type: "POST", 
		dataType: "json",
		success: saveCoverLetterStatus,
		error: iteratePostsError,
	});
	return(false);
});

$('.coverletterbutton').click(function(e) {
	$.ajax({ 
		url: "index.php?ajax=2",  
		data: { selected:  this.id },	
		type: "POST", 
		dataType: "json",
		success: fetchCoverLetter,
		error: iteratePostsError,
	});
	return(false);
});
$('.customsubjectbutton').click(function(e) {
	showModalWindow(this.id, '#dialog');

});
 $('.customrecipientbutton').click(function(e) {
	 showModalWindow(this.id, '#dialog');
});
$('#viewlogbutton').click(function(e) {
	showModalWindow(this.id, '#dialog2');
});
$('.sendemailbutton').click(function(e) {
	alert("todo");//showModalWindow(e, this.id, '#dialog2');
});

$('#recontact').click(function(e) {
	iteratePosts("input:disabled");
});
$('#uncheckall').click(function(e) {
	alert("todo");
});
$('#selectionsummary').click(function(e) {
	alert("todo");
});
$('#sidebarsortbycombobutton').click(function(e) {
	$('#dialog3').css("visibility", "visible");
});
$('#dialog3').click(function(e) {
		$('#dialog3').css("visibility", "hidden");
});
</script>


</div>
</div>
</body>
</html>

