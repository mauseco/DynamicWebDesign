<?php
	namespace Ps2;
	$max = 51200;
	// set the maximum upload size in bytes
	// maximum upload size is 50kB  or 51,200 bytes
	if (isset($_POST['upload'])) {
	// define the path to the upload folder
	//checks whether the Upload button has been clicked by checking to see if its key is in the $_POST array
		$destination = '/Applications/MAMP/htdocs/phpsols/uploads/';
		// move the file to the upload folder and rename it
		require_once('../classes/Ps2/upload.php');
		try {
			$upload = new Upload($destination);
			//creates an instance of the class called $upload by passing it the path
			$upload->setMaxSize($max);
			$upload->move();
			//calls the $upload object move()
			$result = $upload->getMessages();
			//calls getMessages() methods & stores the result of getMessages() in $result
		} catch (Exception $e) {
			echo $e->getMessage();
		} //Because there might be an exception, put in try/catch block
	}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
		<title>Upload File</title>
        <link href="css/journey.css" rel="stylesheet" type="text/css" media="screen">
    </head>
    <body>
		<?php
        if (isset($result)) {
            echo '<ul>';
            foreach ($result as $message) {
                echo "<li>$message</li>";
            }
            echo '</ul>';
        }
        ?>
        <form action="" method="post" enctype="multipart/form-data" id="uploadImage">
          <p>
            <label for="image">Upload image:</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max; ?>">
            <input type="file" name="image[]" id="image" multiple>
            <!-- for multiple uploads//empty brackets submits multiple values as an array -->
          </p>
          <p>
            <input type="submit" name="upload" id="upload" value="Upload">
          </p>
        </form>
        <!--<pre>
            //if (isset($_POST['upload'])) {
            	//print_r($_FILES); //checks whether the $_POST array contains upload
            //}
            ?>
        </pre>-->
    </body>
</html>