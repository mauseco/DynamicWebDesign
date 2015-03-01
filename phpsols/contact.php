<?php
	include('./includes/title.inc.php');
	//makes sure the emails arent garbled with nonalphabtic scripts
	// stores error details
	$errors = array();
	// controls display of error and
	$missing = array();
	// check if the form has missing fields
	if (isset($_POST['send'])) {
		// check if the form has been submitted
		// email processing script
		$to = 'mauseco@mail.com'; 
		// sends to sepcified email address
		$subject = 'Feedback'; 
		//subject of email
		$expected = array('name', 'email', 'comments');
		// lists the names of each field used below
		$required = array('name', 'email', 'comments', 'subscribe', 'interests');
		// sets required fields (for testing, remove some from array)
		if (!isset($_POST['subscribe'])) {
			$_POST['subscribe'] = '';
		}
		// set default values for variables that might not exist
		if (!isset($_POST['interests'])) {
			$_POST['interests'] = array();
		}
		if (!isset($_POST['characteristics'])) {
			$_POST['characteristics'] = array();
		}
		$minCheckboxes = 2;
		if (count($_POST['interests']) < $minCheckboxes) {
			$errors['interests'] = true;
		}
		// minimum number of required check boxes
			//$headers .= " From: Japan Journey<feedback@example.com>\r\n";
		// \r\n carriage return (reset a device's position to the beginning of a line of text) and newline character (signifies the end of a line of text and creates a new line)
			//include('./includes/nuke_magic_quotes.php');
		// You don't need to include nuke_magic_quotes.php if your remote server has turned off magic_quotes_gpc
		$headers .= "Cc: baezsam@yahoo.com, mausuo@aol.com\r\n";
			//$headers .= 'Bcc: secretplanning@example.com';
		$headers = "Content-Type: text/plain; charset=utf-8\r\n";
		$mailSent = mail($to, $subject, $message, $headers);
		// create additional headers
			//$headers .= "Cc: sales@example.com, finance@example.com\r\n";
			//$headers .= 'Bcc: secretplanning@example.com';
			require('./includes/processmail.inc.php');
			if ($mailSent) {
				header('Location: http://localhost:8888/phpsols/thankyou.php');
				exit;
			}
		}
		?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Japan Journey<?php echo "&#8212;{$title}"; ?></title>
        <link href="css/journey.css" rel="stylesheet" type="text/css" media="screen">
    </head>
    <body>
        <div id="header">
            <h1>Japan Journey</h1>
        </div>
        <div id="wrapper">
            <?php include('./includes/menu.inc.php'); ?>
            <div id="maincontent">
                <h2>Contact Us </h2>
				<?php if (($_POST && $suspect) || ($_POST && isset($errors['mailfail']))) { ?>
                <p class="warning">Sorry, your mail could not be sent. Please try later.</p>
                <?php } elseif ($missing || $errors) { ?>
                <p class="warning">Please fill out the fields below.</p>
                <?php } ?>
            	<p>Ut enim ad minim veniam, quis nostrud exercitation consectetur adipisicing elit. Velit esse cillum dolore ullamco laboris nisi in reprehenderit in voluptate. Mollit anim id est laborum. Sunt in culpa duis aute irure dolor excepteur sint occaecat.</p>
                <form id="feedback" method="post" action="">
                    <p>
                        <label for="name">Name:
                        <?php if ($missing && in_array('name', $missing)) { ?>
						<span class="warning">Please enter your name</span>
						<?php } ?>
                        </label>
                        <input name="name" id="name" type="text" class="formbox" placeholder="Name"
                        <?php if ($missing || $errors) {
							echo 'value="' . htmlentities($name, ENT_COMPAT, 'UTF-8') . '"'; //Maintains quotes in names
						}//If other values are missing, this one stays in as a value injected in the HTML 
						 ?>>
                    </p>
                    <p>
                        <label for="email">Email:
                        <?php if ($missing && in_array('email', $missing)) { ?>
							<span class="warning">Please enter your email address</span>
						<?php } elseif (isset($errors['email'])) { ?>
                        	<span class="warning">Invalid email address</span>
						<?php } ?>
                        </label>
                        <input name="email" id="email" type="text" class="formbox" placeholder="you@example.com"
                        <?php if ($missing || $errors) {
							echo 'value="' . htmlentities($email, ENT_COMPAT, 'UTF-8') . '"';
						}//If other values are missing, this one stays in as a value injected in the HTML 
						 ?>>
                    </p>
                    <p>
                        <label for="comments">Comments:
                        <?php if ($missing && in_array('comments', $missing)) { ?>
						<span class="warning">Please enter your comments</span>
						<?php } ?>
                        </label>
                        <textarea name="comments" id="comments" cols="60" rows="8" placeholder="Start typing here!"><?php
						if ($missing || $errors) {
							echo htmlentities($comments, ENT_COMPAT, 'UTF-8');
						} ?></textarea>
                    </p>
                    <fieldset id="subscribe">
                        <h2>Subscribe to newsletter?</h2>
                        <?php if ($missing && in_array('subscribe', $missing)) { ?>
                        <span class="warning">Please make a selection</span>
                        <?php } ?>
                        <p>
                            <input name="subscribe" type="radio" value="Yes" id="subscribe-yes"
                            <?php
                            if ($_POST && $_POST['subscribe'] == 'Yes') { //The conditional statement in the Yes button checks $_POST to see if the form has been submitted. If it has and the value of $_POST['subscribe'] is “Yes,” the checked attribute is added to the <input> tag.
                            echo 'checked';
                            } ?>>
                            <label for="subscribe-yes">Yes</label>
                            <input name="subscribe" type="radio" value="No" id="subscribe-no"
                            <?php
                            if ($_POST || $_POST['subscribe'] == 'No') { //In the No button, the conditional statement uses || (or). The first condition is !$_POST, which is true when the form hasn't been submitted. If true, the checked attribute is added as the default value when the page first loads. If false, it means the form has been submitted, so the value of $_POST['subscribe'] is checked.
                            echo 'checked';
                            } ?>>
                            <label for="subscribe-no">No</label>
                        </p>
                    </fieldset>
                    <fieldset id="interests">
                        <h2>Interests in Japan</h2>
                        <?php if (isset($errors['interests'])) { ?>
                            <span class="warning">Please select at least <?php echo $minCheckboxes;?></span>
                        <?php } ?>
                        <div>
                            <p>
                                <input type="checkbox" name="interests[]" value="Anime/manga" id="anime" 
                                <?php
                                if ($_POST && in_array('Anime/manga', $_POST['interests'])) {
                                  echo 'checked';
                                } ?>>
                                <label for="anime">Anime/manga</label>
                            </p>
                            <p>
                                <input type="checkbox" name="interests[]" value="Arts & crafts" id="art" 
                                <?php
                                if ($_POST && in_array('Arts & crafts', $_POST['interests'])) {
                                  echo 'checked';
                                } ?>>
                                <label for="art">Arts &amp; crafts</label>
                            </p>
                            <p>
                                <input type="checkbox" name="interests[]" value="Judo, karate, etc" id="judo" 
                                <?php
                                if ($_POST && in_array('Judo, karate, etc', $_POST['interests'])) {
                                  echo 'checked';
                                } ?>>
                                <label for="judo">Judo, karate, etc</label>
                            </p>
                        </div>
                        <div>
                            <p>
                                <input type="checkbox" name="interests[]" value="Language/literature" id="lang_lit" 
                                <?php
                                if ($_POST && in_array('Language/literature', $_POST['interests'])) {
                                  echo 'checked';
                                } ?>>
                                <label for="lang_lit">Language/literature</label>
                            </p>
                            <p>
                                <input type="checkbox" name="interests[]" value="Science & technology" id="scitech" 
                                <?php
                                if ($_POST && in_array('Science & technology', $_POST['interests'])) {
                                  echo 'checked';
                                } ?>>
                                <label for="scitech">Science &amp; technology</label>
                            </p>
                            <p>
                                <input type="checkbox" name="interests[]" value="Travel" id="travel" 
                                <?php
                                if ($_POST && in_array('Travel', $_POST['interests'])) {
                                  echo 'checked';
                                } ?>>
                                <label for="travel">Travel</label>
                            </p>
                        </div>
                    </fieldset>
                    <p>
                        <label for="select">How did you hear of Japan Journey?
                        <?php if ($missing && in_array('howhear', $missing)) { ?>
                         <span class="warning">Please make a selection</span>
                        <?php } ?>
                        </label>
                        <select name="howhear" id="howhear">
                            <option value=""
                            <?php
                            if (!$_POST || $_POST['howhear'] == '') {
                              echo 'selected';
                            } ?>>Select one</option>
                            <option value="foED"
                            <?php
                            if ($_POST && $_POST['howhear'] == 'foED') {
                              echo 'selected';
                            } ?>>friends of ED</option>
                            <option value="recommended by friend"
                            <?php
                            if ($_POST && $_POST['howhear'] == 'recommended by friend') {
                              echo 'selected';
                            } ?>>Recommended by a friend</option>
                            <option value="search engine"
                            <?php
                            if ($_POST && $_POST['howhear'] == 'search engine') {
                              echo 'selected';
                            } ?>>Search engine</option>
                        </select>
                    </p>
                    <p>
                        <label for="select">What characteristics do you associate with Japan?
                        <?php if (isset($errors['characteristics'])) { ?>
                          <span class="warning">Please select at least <?php echo $minList; ?></span>
                        <?php } ?>
                        </label>
                        <select name="characteristics[]" size="6" multiple="multiple" id="characteristics">
                            <option value="Dynamic"
                            <?php
                            if ($_POST && in_array('Dynamic', $_POST['characteristics'])) {
                              echo 'selected';
                            } ?>>Dynamic</option>
                            <option value="Honest"
                            <?php
                            if ($_POST && in_array('Honest', $_POST['characteristics'])) {
                              echo 'selected';
                            } ?>>Honest</option>
                            <option value="Pacifist"
                            <?php
                            if ($_POST && in_array('Pacifist', $_POST['characteristics'])) {
                              echo 'selected';
                            } ?>>Pacifist</option>
                            <option value="Devious"
                            <?php
                            if ($_POST && in_array('Devious', $_POST['characteristics'])) {
                              echo 'selected';
                            } ?>>Devious</option>
                            <option value="Inscrutable"
                            <?php
                            if ($_POST && in_array('Inscrutable', $_POST['characteristics'])) {
                              echo 'selected';
                            } ?>>Inscrutable</option>
                            <option value="Warlike"
                            <?php
                            if ($_POST && in_array('Warlike', $_POST['characteristics'])) {
                              echo 'selected';
                            } ?>>Warlike</option>
                        </select>
                    </p>
                    <p>
                        <input name="send" id="send" type="submit" value="Send message">
                    </p>
                </form>
                <pre>
                    <?php if ($_POST && isset($mailSent) && $mailSent) {
                      echo htmlentities($message);
                    } 
                    if ($_POST) {
                       echo 'Missing:';
                       print_r($missing);
                       echo 'howhear: ' . $_POST['howhear'];
                    }
                    ?>
                </pre>
            </div>
            <?php include('./includes/footer.inc.php'); ?>
        </div>
    </body>
</html>