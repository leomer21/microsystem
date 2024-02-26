<?php
                 
            		// limit 500 per day 48 emails / 30 minutes  for trail users , for busseness 2000
					//SMTP needs accurate times, and the PHP time zone MUST be set
					//This should be done in your php.ini, but this is how to do it if you don't have access to that
					require 'include/mail/PHPMailerAutoload.php';
					//Create a new PHPMailer instance
					$mail = new PHPMailer();
					//Tell PHPMailer to use SMTP
					$mail->isSMTP();
					$mail->CharSet = 'UTF-8';
					//Enable SMTP debugging
					// 0 = off (for production use)
					// 1 = client messages
					// 2 = client and server messages
					$mail->SMTPDebug = 0;
					//Ask for HTML-friendly debug output
					$mail->Debugoutput = 'html';
					//Set the hostname of the mail server
					$mail->Host = 'smtp.gmail.com';
					//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
					$mail->Port = 587;
					//Set the encryption system to use - ssl (deprecated) or tls
					$mail->SMTPSecure = 'tls';
					//Whether to use SMTP authentication
					$mail->SMTPAuth = true;
					//Username to use for SMTP authentication - use full email address for gmail
					$mail->Username = "support@microsystem.com.eg";
					//Password to use for SMTP authentication
					$mail->Password = "1403636mra";
					//Set who the message is to be sent from
					$mail->setFrom('support@microsystem.com.eg', 'Microsystem');
					//Set an alternative reply-to address
					//$mail->addReplyTo($get_mail, $get_name);
					//Set who the message is to be sent to
					//$mail->addAddress('admin@microsystem.com.eg', 'Microsystem');
					$mail->addAddress($user_mail_for_send, $user_uname_for_send);
					//$mail->addAddress('admin@microsystem-eg.com', 'Microsystem');
					//Set the subject line
					
					$mail->Subject = $message_subject;
					$body = file_get_contents("include/mail/contents_ar.html");
					$body = str_replace('[comp_name]', "$message_header", $body);
					$body = str_replace("[msg_header]", "$message_body_title", $body);
					$body = str_replace('[msg_body]', "$message_body_body", $body);
					
					$mail->msgHTML($body, dirname(__FILE__));
					
					//Replace the plain text body with one created manually
					$mail->AltBody = 'This is a plain-text message body';
					//Attach an image file
					//$mail->addAttachment('images/phpmailer_mini.gif');
					
					//send the message, check for errors
					if (!$mail->send()) {
						$status=0;
						if($login_lang=="ar"){ $status_message=$mail_message_dont_send_ar;}
						else { $status_message=$mail_message_dont_send_en;}
						$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
						echo $data = json_encode($data_before);
					} else {
					    $status=1;
						if($login_lang=="ar"){ $status_message=$mail_message_send_successfully_ar;}
						else { $status_message=$mail_message_send_successfully_en;}
						$data_before = array("status" => "$status", "statusMessage" => "$status_message");                                                                    
						echo $data = json_encode($data_before);
					}
					
                
                ?>