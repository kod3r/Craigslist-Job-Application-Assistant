<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                                                                         *
 *  XPertMailer is a PHP Mail Class that can send and read messages in MIME format.        *
 *  This file is part of the XPertMailer package (http://xpertmailer.sourceforge.net/)     *
 *  Copyright (C) 2007 Tanase Laurentiu Iulian                                             *
 *                                                                                         *
 *  This library is free software; you can redistribute it and/or modify it under the      *
 *  terms of the GNU Lesser General Public License as published by the Free Software       *
 *  Foundation; either version 2.1 of the License, or (at your option) any later version.  *
 *                                                                                         *
 *  This library is distributed in the hope that it will be useful, but WITHOUT ANY        *
 *  WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A        *
 *  PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.        *
 *                                                                                         *
 *  You should have received a copy of the GNU Lesser General Public License along with    *
 *  this library; if not, write to the Free Software Foundation, Inc., 51 Franklin Street, *
 *  Fifth Floor, Boston, MA 02110-1301, USA                                                *
 *                                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/* Purpose:
   - compose a mail message in MIME format
   - set 'text/plain' and 'text/html' (with embed image) version of message
   - add an attachment 'file.txt' from source
   - split mail source
   - print result (headers and body parts)
*/

// manage errors
error_reporting(E_ALL); // php errors
define('DISPLAY_XPM4_ERRORS', true); // display XPM4 errors

// path to 'MIME.php' file from XPM4 package
require_once '../MIME.php';

// COMPOSE message ----------------------------------
$id = MIME::unique();
// set text/plain version of message
$text = MIME::message('Text version of message.', 'text/plain');
// set text/html version of message with an embed image
$html = MIME::message('<b>HTML</b> version of <u>message</u>.<br><i>Powered by</i> <img src="cid:'.$id.'">', 'text/html');
// add attachment with name 'file.txt'
$at[] = MIME::message('source file', 'text/plain', 'file.txt', 'ISO-8859-1', 'base64', 'attachment');
$file = 'xpertmailer.gif';
// add inline attachment '$file' with name 'XPM.gif' and ID '$id'
$at[] = MIME::message(file_get_contents($file), FUNC::mime_type($file), 'XPM.gif', null, 'base64', 'inline', $id);
// compose mail message in MIME format
$mess = MIME::compose($text, $html, $at);
// standard mail message RFC2822
$mail = 'From: my@addr.com'."\r\n".
		'To: me@addr.net'."\r\n".
		'Subject: test'."\r\n".
		$mess['header']."\r\n\r\n".
		$mess['content'];
// die($mail); // << uncomment this line to see the mail source

// SPLIT message ------------------------------------
$split = MIME::split_mail($mail, $headers, $body);

// print results
echo '<br /><pre>MAIL HEADERS ####################################'."\n";
print_r($headers);
echo '<br />MAIL BODY PARTS #################################'."\n";
print_r($body);
echo '</pre>';

?>