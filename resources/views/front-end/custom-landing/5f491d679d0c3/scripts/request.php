<?php

								if($_POST['id'] === "") {
									$to = ""; // Your e-mail address here.
									$body = "\nName: {$_POST['contactname']}\nEmail: {$_POST['contactemail']}\n\n\n{$_POST['contactmessage']}\n\n";
									mail($to, "", $body, "From: {$_POST['contactemail']}"); // E-Mail subject here.
								}
								