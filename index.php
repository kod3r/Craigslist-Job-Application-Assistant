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
	$_POST["selected"] = array($_GET["selected"]);
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
	exit();
	
} else if(isset($_GET["ajax"]) && $_GET["ajax"] == 3) {
	//fetching custom subject by job id
	exit();
	
} else if(isset($_GET["ajax"]) && $_GET["ajax"] == 4) {
	//saving coverletter by job id
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
	<div id="dialog2" class="window">
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
 $sql = "SELECT * FROM logs where status = 'error'";
 $res = $mysql->query($sql);
 while($row = mysql_fetch_row($res)) { 
  print "<tr>\n";
  print "<td>".$row[0]."</td><td><a href=\"".$row[1]."\">".$row[1]."</a></td><td>".$row[2]."</td><td>".$row[3]."</td>";
  print "</tr>\n";
 }
 print "</tbody></table>";

?>
<a href="#" class="close">Close it</a>

	</div>

	<div id="dialog" class="window">
	
<form action="sample_posteddata.php" method="post">
		 <p>
				<!--textarea id="editor1" name="editor1" cols="80" rows="10"></textarea-->
			  <!--textarea class="ckeditor" cols="80" id="editor1" name="editor1" rows="10"></textarea-->
			<form method="post" action="somepage">
<textarea id="content" name="content" class="tinymce" style="width:100%">
</textarea>
 
<!-- Some integration calls -->
<a href="javascript:;" onmousedown="$('#content').tinymce().show();">[Show]</a>
<a href="javascript:;" onmousedown="$('#content').tinymce().hide();">[Hide]</a>
<a href="javascript:;" onmousedown="$('#content').tinymce().execCommand('Bold');">[Bold]</a>
<a href="javascript:;" onmousedown="alert($('#content').html());">[Get contents]</a>
<a href="javascript:;" onmousedown="alert($('#content').tinymce().selection.getContent());">[Get selected HTML]</a>
<a href="javascript:;" onmousedown="alert($('#content').tinymce().selection.getContent({format : 'text'}));">[Get selected text]</a>
<a href="javascript:;" onmousedown="alert($('#content').tinymce().selection.getNode().nodeName);">[Get selected element]</a>
<a href="javascript:;" onmousedown="$('#content').tinymce().execCommand('mceInsertContent',false,'<b>Hello world!!</b>');">[Insert HTML]</a>
<a href="javascript:;" onmousedown="$('#content').tinymce().execCommand('mceReplaceContent',false,'<b>{$selection}</b>');">[Replace selection]</a>
</form>

                  </p>
                  <p>
                          <input type="submit" value="Submit" />
                  </p>
          </form>
 <a href="#" class="close">Close it</a>
        </div>
        <!-- Do not remove div#mask, because you'll need it to fill the whole screen -->
        <!--div id="mask"></div-->
    </div>
<div style="width:1300px; margin-left: 20px;">

<div id="sidebar">
<center>
<div id="other" class="button sidebarbuttons"><center>Contact Checked Jobs</center></div>				  
<div id="viewlogbutton" class="button sidebarbuttons"><center>View Log</center></div> 
<div id="recontact" class="button sidebarbuttons"><center>Recontact All</center></div>
<div id="uncheckall" class="button sidebarbuttons"><center>Uncheck All</center></div>
<div id="selectionsummary" class="button sidebarbuttons"><center>Selection Summary</center></div>
</center>
		<textarea class="default" style="height: 200px; width: 100%;" id="statusm">Ready.</textarea>
<select name="sortby" size="1" onchange="alert('todo');" style="width: 100%;">
<option value="" name="sortby2" selected><b>Sort Posts By</b></option>
<option value="selected">Selected</option>
<option value="postdate">Post Date</option>
<option value="sourcefeed">Source Feed</option>
<option value="body">Body</option>
<option value="modified">Modified Date</option>
</select>
	
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
 <div style="width:1100px;">

<?
function setupAttachments($attachment_files, $mysql, $ajax = FALSE) {
	$attachment_dir = dirname(__FILE__) . '/config/attachments/';
	$success = file_exists(dirname(__FILE__) . '/config/email.txt');
	if($ajax)
		$setupAttachmentsret = array();
	if(!$success)
		if($ajax) 
			array_push($setupAttachmentsret, "<strong>ERROR:</strong> email file: config/email.txt does not exist<br/>\n");
		else
			die("<strong>ERROR:</strong> email file: config/email.txt does not exist<br/>\n");

	if ($handle=opendir($attachment_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file == '.' | $file == '..') {
				continue;
			}
			array_push($attachment_files, $attachment_dir . $file);
		}
		if (count($attachment_files) < 1) {
			if($ajax)
				array_push($setupAttachmentsret, "<strong>WARNING:</strong> attachment directory: $attachment_dir is empty<br/>\n");
			else
				print "<strong>WARNING:</strong> attachment directory: $attachment_dir is empty<br/>\n";
		}
	} else {
		if($ajax)
			array_push($setupAttachmentsret, "<strong>WARNING:</strong> cannot open attachment directory: $attachment_dir<br/>\n");
		else
			print "<strong>WARNING:</strong> cannot open attachment directory: $attachment_dir<br/>\n";
	}
	array_push($attachment_files, $setupAttachmentsret);
	return($attachment_files);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	ProcessPost($mysql, $config, $attachment_files);
}	
function ProcessPost($mysql, $config, $attachment_files, $ajax = FALSE) {
	if($ajax) 
		$ret = array();
	foreach ($_POST['selected'] as $selected) {
		try {
			$mailer = new Mailer($selected, $config, $attachment_files, $ajax);
			if ($config['debug'] == true) {
				if($ajax)
					array_push($ret, "DEBUG MODE:  will not email out: disabled.  address to send to is: " . $mailer->getEmailAddress() . "<br/>\n");
				else
					print "DEBUG MODE:  will not email out: disabled.  address to send to is: " . $mailer->getEmailAddress() . "<br/>\n";
				continue;
			}
			if ( $mailer->send() !== true ) {
				if($ajax)
					array_push($ret,  "<strong>ERROR:</strong> email not sent.<br/>\n");
				else
					print "<strong>ERROR:</strong> email not sent.<br/>\n";
				$sql = "INSERT INTO logs (url, status) VALUES ('$selected', 'error')";
			} else {
					if($ajax)
						array_push($ret,  "Successfully emailed " . $mailer->getEmailAddress() . "<br/>\n");
					else
						print "Successfully emailed " . $mailer->getEmailAddress() . "<br/>\n";
					$sql = "INSERT INTO logs (url, status) VALUES ('$selected', 'sent')";
			}
			$success = $mysql->query($sql);
		        if(!$success)
				if($ajax)
					array_push($ret, "<strong>ERROR:</strong> " . $mysql->getError() . "<br/>\n");
				else 
					print "<strong>ERROR:</strong> " . $mysql->getError() . "<br/>\n";
		} catch (Exception $e) {
			if($ajax)
				array_push($ret,  "Email exception caught in post <a href='$selected'>$selected</a>: " . $e->getMessage() . "<br/>\n");
			else
				print "Email exception caught in post <a href='$selected'>$selected</a>: " . $e->getMessage() . "<br/>\n";
		}
	}
	if($ajax) { 
		return($ret);
	}
	if ($config['display_posts_after_email'] == false) {
		die();
	}
	print "<br/>\n";
}

foreach ($config['feed'] as $url) {
	$feed->addFeed($url);
}

$feed->sortFeedsByDate();
print "<form action='$_SERVER[PHP_SELF]' id='senddata'>\n";
print "<table class='tablesorter' id='myTable'>\n";
print "<thead>\n";
print "<tr>\n";
print "<th width='25'>X</th>\n";
print "<th width='70'>Date</th>\n";
print "<th width='100'>Source Feed</th>\n";
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
?> <div id="<? echo $post['link'];?>" class="coverletterbutton button tabledescbuttons"><center>Edit Cover Letter</center></div>
	<div id="<? echo $post['title'];?>" class="customsubjectbutton button tabledescbuttons"><center>Edit Subject Line</center></div>
	<div id="<? echo $post['link'];?>" class="customrecipientbutton button tabledescbuttons"><center>Edit Recipient Address</center></div>
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
		$("#statusm").append(data[i][0]);
	}
}
function iteratePostsError(XMLHttpRequest, textStatus, errorThrown) { 
	alert(textStatus);
	alert(errorThrown);
}
function iteratePosts(whatkind) {
	var sel = $(whatkind);
	for(var i = 0; i < sel.length; i++) { 
		$.ajax({ 
			url: "index.php?ajax=1&selected=" + sel[i].value,  
			type: "POST", 
			context: document.body,
			dataType: "json",
			success: updateProgress,
			error: iteratePostsError,
		});
	}
	if(whatkind == "input:checked") {
		//* TODO: there should be code to determine whether or not it was successful or an error. */
		sel.parent().parent().children().css('background-color', 'CC33CC');
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
function showModalWindow(e, content, which) {
	// 	ck editor broken.
	//	$('#editor1').ckeditor( function() { }, { skin : 'office2003' } );
	//	var editor = $('#editor1').ckeditorGet(); 
	//	alert( editor.checkDirty() ); 
	//	editor.insertText(content);
	//
	//	tinyMCE.execCommand('mceInsertContent',false,'<br><img alt=$img_title src=$link/img/sadrzaj/$file\>');">Insert Image</a>
		$('textarea.tinymce').tinymce().execCommand('mceInsertContent',false, content);
		
		//Cancel the link behavior
		e.preventDefault();
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
	showModalWindow(e, this.id, '#dialog');
}

$('.coverletterbutton').click(function(e) {
	$.ajax({ 
		url: "index.php?ajax=2&selected=" + this.id,  
		type: "POST", 
		context: document.body,
		dataType: "json",
		success: fetchCoverLetter,
		error: iteratePostsError,
	});
	
});
$('.customsubjectbutton').click(function(e) {
	showModalWindow(e, this.id, '#dialog');

});
 $('.customrecipientbutton').click(function(e) {
	 showModalWindow(e, this.id, '#dialog');
});
$('#viewlogbutton').click(function(e) {
	showModalWindow(e, this.id, '#dialog2');
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
</script>


</div>
</div>
</body>
</html>

