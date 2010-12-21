<?php
require_once(dirname(__FILE__) . '/xpm/MAIL.php');

class Mailer
{
	public  $xpm_obj;
	private $config;
	private $email_addr;
	private $subject;
	private $body;
	private $attachment_files;
	private $ajax;
	public $messages;
	public function GenerateReadReceiptHeader($toAddr=NULL)  {
		//$headers="From: support@mydomain.com" & "Return-Path: Mailer-Daemon@pass14.dizinc.com\r\n" & "Return-Receipt-To: support@mydomain.com\r\n";  
		//$headers .= "From: ".$this->email_addr . " & ";
		if($toAddr != NULL) {
			$addressto = explode("@", $toAddr);
			$h = $this->xpm_obj->AddHeader("Disposition-Notification-To", $addressto[0] . $this->config['catchalladdr']);
		}
		else {
			$h = $this->xpm_obj->AddHeader("Disposition-Notification-To", $this->email_addr);
		}
		if($this->ajax) 
			array_push($this->messages, $h ? 'return receipt header added' : 'return receipt header error'); 
		else
			echo $h ? 'return receipt header added' : 'return receipt header error';
		//$headers .= " \r\n ";

	}
	function Mailer($post_url, $config, $attachment_files, $ajax = FALSE)
	{
		$this->ajax = $ajax;
		$this->messages = array();
		$this->xpm_obj = new MAIL;
		$this->config = $config;
		$this->attachment_files = $attachment_files;

		$handle = fopen($post_url, 'r');
		$contents = stream_get_contents($handle);
		fclose($handle); 
		
		$contents = html_entity_decode($contents);
		
		$pattern='/.*?([\\w-+]+(?:\\.[\\w-+]+)*@(?:[\\w-]+\\.)+[a-zA-Z]{2,7})/is';
		
		if (preg_match_all ($pattern, $contents, $matches)) {
			$this->email_addr = $matches[1][0];
			
			$handle = fopen(dirname(__FILE__) . '/../config/email.txt', 'r');
			$this->body = stream_get_contents($handle) . "\n$post_url\n";
			fclose($handle);
		} else {
			throw new Exception ("no email address found");
		}
		
		$pos1 = strpos ($contents, '<h2>');
		$pos2 = strpos ($contents, '</h2>', $pos2);
		
		if ($pos1 === NULL || $pos2 === NULL) {
			throw new Exception ("no subject found");
		}
		
		$this->subject = substr ($contents, $pos1+4, $pos2-$pos1-4);
	}
	
	public function send()
	{
		session_start();
		if(!isset($_SESSION["lastemailaddr"])) { $_SESSION["lastemailaddr"] = 0; }
		if($_SESSION["lastemailaddr"] >= count($this->config['email'])) {
			$_SESSION["lastemailaddr"] = 0;
		}
		$ptr = &$_SESSION["lastemailaddr"];
		$attachment_dir = dirname(__FILE__) . '/../config/attachments/';
		$this->xpm_obj->From($this->config["email"][$ptr]['from_addr'], $this->config["email"][$ptr]['from_name']); // set from address
		$this->xpm_obj->AddTo($this->email_addr); // add to address
		$this->xpm_obj->Subject($this->subject); // set subject
		$this->xpm_obj->Text($this->body); // set text message
		if(isset($this->config['bccaddr'])) { 
			$this->xpm_obj->AddBcc($this->config['bccaddr']);
		}	
		foreach ($this->attachment_files as $attachment) {
			$this->xpm_obj->Attach(file_get_contents($attachment), FUNC::mime_type($attachment), basename($attachment), null, null, 'inline', MIME::unique());
		}
		if ($this->config["email"][$ptr]['ssl'] == true) {
			$connect = $this->xpm_obj->Connect($this->config["email"][$ptr]['host'], $this->config["email"][$ptr]['port'], $this->config["email"][$ptr]['username'], $this->config["email"][$ptr]['password'], 'ssl');
		} else {
			$connect = $this->xpm_obj->Connect($this->config["email"][$ptr]['host'], $this->config["email"][$ptr]['port'], $this->config["email"][$ptr]['username'], $this->config["email"][$ptr]['password']);
		}
		$_SESSION["lastemailaddr"]++;
		if (!connect) {
			throw new Exception("unable to connect to mail server");
		}
		if($this->config['getreadreceipt'] == TRUE) {
			$this->GenerateReadReceiptHeader();
		}
		$sent = $this->xpm_obj->Send($connect);
			
		return ($sent);
	}
	
	public function getEmailAddress()
	{
		$ptr = &$_SESSION["lastemailaddr"];
		return  $this->config["email"][$ptr]['from_addr'].' :: '.$this->email_addr;
	}
	function __destruct() { 
		//print("destructor called on Mailer Class<br />");
		$this->xpm_obj->Disconnect();
	}

}
?>
