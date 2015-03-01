<?php
	$suspect = false;
	// assume nothing is suspect
	$pattern = '/Content-Type:|Bcc:|Cc:/i';
	// create a pattern to locate suspect phrases
	// function to check for suspect phrases
	function isSuspect($val, $pattern, &$suspect) {
	// if the variable is an array, loop through each element
	// and pass it recursively back to the same function
		if (is_array($val)) {
			foreach ($val as $item) {
				isSuspect($item, $pattern, $suspect);
			}
		} else {
		// if one of the suspect phrases is found, set Boolean to true
			if (preg_match($pattern, $val)) {
				$suspect = true;
			}
		}
	}
	isSuspect($_POST, $pattern, $suspect);
		// check the $_POST array and any subarrays for suspect content
		if (!$suspect) {
		foreach ($_POST as $key => $value) {
		  // assign to temporary variable and strip whitespace if not an array
		  $temp = is_array($value) ? $value : trim($value);
		  // if empty and required, add to $missing array
		  if (empty($temp) && in_array($key, $required)) {
			$missing[] = $key;
		  } elseif (in_array($key, $expected)) {
			// otherwise, assign to a variable of the same name as $key
			${$key} = $temp;
		  }
		}//foreach loop goes through the $_POST array, strips whitespace from text fields and assigns contents to var with the same name. so $_POST['email'] becomes $email. blank req field's name attribute is added to the $missing array.
	}
	// if the input is invalid, you need to display a different message
	//validate the user's email for suspect phrases and empty email field
	if (!$suspect && !empty($email)) { //checks suspect and empty email
		$validemail = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL); //name of element i want to test
		if ($validemail) { //checks if email is valid
			$headers .= "\r\nReply-To: $validemail";
		} else {
			$errors['email'] = true; //if validemail is false, email is added to the errors array
		}
	}
	// When building $headers string, doesnt matter if \r\n is at the end or the beginning of the header, as long as a carriage return and newline character separates them.
	//$mailSent = false;
	//This initializes a variable that will be used to redirect the user to a thank you page after the mail has been sent. It needs to be set to false until you know the mail() function has succeeded.
	// go ahead only if not suspect and all required fields OK
	if (!$suspect && !$missing && !$errors) {
	// initialize the $message variable
	$message = '';
	// loop through the $expected array
	foreach($expected as $item) {
	// assign the value of the current item to $val
	if (isset(${$item}) && !empty(${$item})) {
	$val = ${$item};
	} else {
	// if it has no value, assign 'Not selected'
	$val = 'Not selected';
	}
	// if an array, expand as comma-separated string
	if (is_array($val)) {
	$val = implode(', ', $val);
	}
	// replace underscores and hyphens in the label with spaces
	$item = str_replace(array('_', '-'), ' ', $item);
	// add label and value to the message body
	$message .= ucfirst($item).": $val\r\n\r\n";
	}
	// limit line length to 70 characters
	$message = wordwrap($message, 70);
	$mailSent = mail($to, $subject, $message, $headers);
	if (!$mailSent) {
	$errors['mailfail'] = true;
	}
	}