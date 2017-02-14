<?php
/* Email Detials */
  $mail_to = "shenli@microsolution.co.nz";
  $from_mail = "shenli@microsolution.co.nz>";
  $from_name = "Shen Li";
  $reply_to = "shenli@microsolution.co.nz";
  $subject = "Attachment";
  $message = "Hello";
 
/* Attachment File */
  // Attachment location
  $file_name = "Signed Doc.pdf";
  $path = "lib/323764867/";
   
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
  if (mail($mail_to, $subject, "", $header)) {
    echo "Sent";
  } else {
    echo "Error";
  }
?>