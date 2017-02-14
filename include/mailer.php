<?php

class Mailer
{
   
   /*
    * sendActivation - Sends an activation e-mail to the newly
    * registered user with a link to activate the account.
    */
	
  function sendActivation($user, $email, $pass, $token, $config){
  	$from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
  	$subject = $config['SITE_NAME']." - Welcome!";
  	$body = $user.",\n\n"
      ."Welcome! You've just registered at ".$config['SITE_NAME']." "
      ."with the following username:\n\n"
      ."Username: ".$user."\n\n"
      ."Please visit the following link in order to activate your account: "
      .$config['WEB_ROOT']."registration.php?mode=activate&user=".urlencode($user)."&activatecode=".$token." \n\n"
      .$config['SITE_NAME'];

    return mail($email,$subject,$body,$from);
   }
      
   /**
    * adminActivation - Sends an activation e-mail to the newly
    * registered user explaining that admin will activate the account.
    */
   
  function adminActivation($user, $email, $pass, $config){
  	$from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
  	$subject = $config['SITE_NAME']." - Welcome!";
  	$body = $user.",\n\n"
      ."Welcome! You've just registered at ".$config['SITE_NAME']." "
      ."with the following username:\n\n"
      ."Username: ".$user."\n\n"
      ."Your account is currently inactive and will need to be approved by an administrator. "
      ."Another e-mail will be sent when this has occured.\n\n"
      ."Thank you for registering.\n\n"
      .$config['SITE_NAME'];

    return mail($email,$subject,$body,$from);
   }
      
   /**
    * activateByAdmin - Sends an activation e-mail to the admin
    * to allow him or her to activate the account. E-mail will appear
    * to come FROM the user using the e-mail address he or she registered
    * with.
    */
   
  function activateByAdmin($user, $email, $pass, $token, $config){
  	$from = "From: ".$user." <".$email.">";
  	$subject = $config['SITE_NAME']." - User Account Activation!";
  	$body = "Hello Admin,\n\n"
      .$user." has just registered at ".$config['SITE_NAME']
      ." with the following details:\n\n"
      ."Username: ".$user."\n"
      ."E-mail: ".$email."\n\n"
      ."You should check this account and if neccessary, activate it. \n\n"
      ."Use this link to activate the account.\n\n"
      .$config['WEB_ROOT']."registration.php?mode=activate&user=".urlencode($user)."&activatecode=".$token." \n\n"
      ."Thanks.\n\n"
      .$config['SITE_NAME'];
	
    $adminemail = $config['EMAIL_FROM_ADDR'];
    return mail($adminemail,$subject,$body,$from);
   }
    
	/**
    * adminActivated - Sends an e-mail to the user once
    * admin has activated the account.
    */
   
  function adminActivated($user, $email, $config){
  	$from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
  	$subject = $config['SITE_NAME']." - Welcome!";
  	$body = $user.",\n\n"
      ."Welcome! You've just registered at ".$config['SITE_NAME']." "
      ."with the following username:\n\n"
      ."Username: ".$user."\n\n"
      ."Your account has now been activated by an administrator. "
      ."Please click here to login - "
      .$config['WEB_ROOT']."\n\nThank you for registering.\n\n"
      .$config['SITE_NAME'];
	
    return mail($email,$subject,$body,$from);
   }
    
   /**
    * sendWelcome - Sends an activation e-mail to the newly
    * registered user with a link to activate the account.
    */
   
  function sendWelcome($user, $email, $pass, $config){
  	$from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
  	$subject = $config['SITE_NAME']." - Welcome!";
  	$body = $user.",\n\n"
      ."Welcome! You've just registered at ".$config['SITE_NAME']
      ."with the following information:\n\n"
      ."Username: ".$user."\n\n"
      ."Please keep this e-mail for your records. Your password is stored safely in "
      ."our database. In the event that it is forgotten, please visit the site and click "
      ."the Forgot Password link. "
      ."Thank you for registering.\n\n"
      .$config['SITE_NAME'];

    return mail($email,$subject,$body,$from);
   }
   
   /**
    * sendNewPass - Sends the newly generated password
    * to the user's email address that was specified at
    * sign-up.
    */
   
   function sendNewPass($user, $email, $pass, $config){
      $from = "From: ".$config['EMAIL_FROM_NAME']." <".$config['EMAIL_FROM_ADDR'].">";
      $subject = $config['SITE_NAME']." - Your New Password!";
      $body = $user.",\n\n"
        ."We've generated a new password for you at your "
        ."request, you can use this new password with your "
        ."username to log in to ".$config['SITE_NAME']."\n\n"
        ."Username: ".$user."\n"
        ."New Password: ".$pass."\n\n"
        ."It is recommended that you change your password "
        ."to something that is easier to remember, which "
        ."can be done by going to the My Account page "
        ."after signing in.\n\n"
        .$config['SITE_NAME'];
             
      return mail($email,$subject,$body,$from);
   }
   
  function sendJob($job_id, $truck_id){
	global $database;
	
	  $job_info = $database->getJobInfo($job_id);
  	/* Email Detials */
	  $mail_to = $database->truckIdToEmail($truck_id);
	  $from_mail = "admin@devan.co.nz";
	  $from_name = "Devan Online";
	  $reply_to = "shenli@microsolution.co.nz";
	  $subject = "New delivery Job - ".$job_info['job_number'];
	  $message = $job_info['job_number'].",\n\n"
		  ."A new delivery job has been assigned to you\n\n"
		  ."Please see attachment for job sheet.\n\n"
		  ."Thank You\n\n";
	 
	/* Attachment File */
	  // Attachment location
	  $file_name = $job_info['doc_path'];
	  $path = "lib/".$job_info['job_number']."/";
	   
	  // Read the file content
	  $file = $path.$file_name;
	  $file_size = filesize($file);
	  $handle = fopen($file, "r");
	  $content = fread($handle, $file_size);
	  fclose($handle);
	  $content = chunk_split(base64_encode($content));
	   
	/* Set the email header */
	  // Generate a boundary
	  $boundary = md5(uniqid(time()));
	   
	  // Email header
	  $header = "From: ".$from_name." <".$from_mail.">\r\n";
	  $header .= "Reply-To: ".$reply_to."\r\n";
	  $header .= "MIME-Version: 1.0\r\n";
	   
	  // Multipart wraps the Email Content and Attachment
	  $header .= "Content-Type: multipart/mixed; boundary=\"".$boundary."\"\r\n";
	  $header .= "This is a multi-part message in MIME format.\r\n";
	  $header .= "--".$boundary."\r\n";
	   
	  // Email content
	  // Content-type can be text/plain or text/html
	  $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
	  $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
	  $header .= "$message\r\n";
	  $header .= "--".$boundary."\r\n";
	   
	  // Attachment
	  // Edit content type for different file extensions
	  $header .= "Content-Type: application/xml; name=\"".$file_name."\"\r\n";
	  $header .= "Content-Transfer-Encoding: base64\r\n";
	  $header .= "Content-Disposition: attachment; filename=\"".$file_name."\"\r\n\r\n";
	  $header .= $content."\r\n";
	  $header .= "--".$boundary."--";
	   
	  // Send email
	  return mail($mail_to, $subject, "", $header);
   }
};

/* Initialize mailer object */
$mailer = new Mailer;
 
?>