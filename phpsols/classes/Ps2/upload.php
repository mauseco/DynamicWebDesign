<?php
	namespace Ps2;
	class Upload{
		protected $_uploaded = array(); //$_FILES array
		protected $_destination; //Path to the upload folder
		protected $_max = 51200; //Maximum file size
		protected $_messages = array(); 
		//Messages to report the status of uploads
		protected $_permitted = array('image/gif', //Permitted file types
									'image/jpeg',
									'image/pjpeg',
									'image/png');
		protected $_renamed = false; 
		//A Boolean variable that records whether a filename has been changed
		public function __construct($path) {
			if (!is_dir($path) || !is_writable($path)) { 
			//which check that the value submitted is a valid directory (folder) that is writable. 
				throw new Exception("$path must be a valid, writable directory.");
				//If either condition fails
				//the constructor throws exception with message
				}
				$this->_destination = $path; //refers to the path
				$this->_uploaded = $_FILES; // refers to the array
		}
		public function move($overwrite = false) {
			$field = current($this->_uploaded);
			if (is_array($field['name'])) {
			//is the $field['name'] and array?
				foreach ($field['name'] as $number => $filename) {
					// process multiple upload. key of each element is assigned to number value and then assigned to $filename
					$this->_renamed = false;
					//renamed property is set to false
					$this->processFile($filename, $field['error'][$number], $field['size'][$number], $field['type'][$number], $field['tmp_name'][$number], $overwrite);
					//values from $_FILES array are passed to processFile() method
				}
			} else {
				$this->processFile($field['name'], $field['error'], $field['size'], $field['type'], $field['tmp_name'], $overwrite);
				//runs for single file uploads
			}
		}
		//public function move($overwrite = false) { //moves to the upload folder
//			$field = current($this->_uploaded);
//			$this->processFile($field['name'], $field['error'], $field['size'], $field['type'], $field['tmp_name'], $overwrite);
			//activate the processFile() method
			//pass the following values as arguments
//			$OK = $this->checkError($field['name'], $field['error']);
			//The arguments passed to the checkError() method 
			//are the filename and the error level reported by the $_FILES array
//			if ($OK) {
//				$sizeOK = $this->checkSize($field['name'], $field['size']);
//				$typeOK = $this->checkType($field['name'], $field['type']);
				//is the size and the type of file okay?
//				if ($sizeOK && $typeOK) {
//					$name = $this->checkName($field['name'], $overwrite);
					//checkName() method is called only if prev checks are true
					//stores in $name
//					$success = move_uploaded_file($field['tmp_name'], $this->_destination . $name);
//					if ($success) {
//						$message = $field['name'] . ' uploaded successfully';
//					if ($this->_renamed) {
//						$message .= " and renamed $name";
//					}
//					$this->_messages[] = $message;
//					} else {
//						$this->_messages[] = 'Could not upload ' . $field['name'];
//					}
//				}
//			}
//		}
		public function getMessages() {
		//returns the contents of the $_messages array
			return $this->_messages;
		}
		protected function checkError($filename, $error) {
			switch ($error) {
				case 0:
					return true;
				case 1:
				case 2:
					$this->_messages[] = "$filename exceeds maximum size: " . $this->getMaxSize();
					return true;
				case 3:
					$this->_messages[] = "Error uploading $filename. Please try again.";
					return false;
				case 4:
					$this->_messages[] = 'No file selected.';
					return false;
				default:
					$this->_messages[] = "System error uploading $filename. Contact webmaster.";
					return false;
			}
		}
		protected function checkSize($filename, $size) {
		//definition for the checkSize() method
			if ($size == 0) {
				return false;
			} elseif ($size > $this->_max) {
				$this->_messages[] = "$filename exceeds maximum size: " . $this->getMaxSize();
				return false;
			} else {
				return true;
			}
		}
		protected function checkType($filename, $type) {
		//checks the MIME type
			if (empty($type)) {
				return false;
			} elseif (!in_array($type, $this->_permitted)) {
				$this->_messages[] = "$filename is not a permitted type of file.";
				return false;
			} else {
				return true;
			}
		}
		public function getMaxSize() {
			return number_format($this->_max/1024, 1) . 'kB';
		}
		public function setMaxSize($num) {
			//this passes the submitted value to the is_numeric() function 
			if (!is_numeric($num)) {
				//checks that its a number
				throw new Exception("Maximum size must be a number.");
			}
			$this->_max = (int) $num;
		}
		 public function addPermittedTypes($types) {
		//permits other types of documents to be uploaded
			$types = (array) $types;
			$this->isValidMime($types);
			$this->_permitted = array_merge($this->_permitted, $types);
		}
		//public function setPermittedTypes($types) {
		//replaces the existing list of permitted MIME types
//			$types = (array) $types;
//			$this->isValidMime($types);
//			$this->_permitted = $types;
//		}
		protected function isValidMime($types) {
			//defining an array of valid MIME types not already listed
			$alsoValid = array('image/tiff',
							'application/pdf',
							'text/plain',
							'text/rtf');
			$valid = array_merge($this->_permitted, $alsoValid);
			//arrays are then merged to produce a full list of valid types
			foreach ($types as $type) {
				if (!in_array($type, $valid)) {
					throw new Exception("$type is not a permitted MIME type");
				}
			}
		}
		protected function checkName($name, $overwrite) {
			$nospaces = str_replace(' ', '_', $name);
			//replaces spaces with underscores for filenames
			if ($nospaces != $name) {
				$this->_renamed = true;
			}
			if (!$overwrite) {
			// rename the file if it already exists
				$existing = scandir($this->_destination);
				//returns array of all files/folders in dir folder
				//and stores it in $existing
					if (in_array($nospaces, $existing)) {
						//checks is duplicate file exists
						$dot = strrpos($nospaces, '.');
						//looks for . after filename and before ext
						if ($dot) {
							$base = substr($nospaces, 0, $dot);
							//extracts filename and stores it
							$extension = substr($nospaces, $dot);
						} else {
							$base = $nospaces;
							$extension = '';
						}
						$i = 1;
						//numeric start for filename rename
						do {
							$nospaces = $base . '_' . $i++ . $extension;
						} while (in_array($nospaces, $existing));
						$this->_renamed = true;
						//loops through existing files and already renamed files 
						//and continues the count after the filename rename (before the .ext)
				}
			}
			return $nospaces;
		}
		protected function processFile($filename, $error, $size, $type, $tmp_name, $overwrite) {
			$OK = $this->checkError($filename, $error);
			if ($OK) {
				$sizeOK = $this->checkSize($filename, $size);
				$typeOK = $this->checkType($filename, $type);
				if ($sizeOK && $typeOK) {
					$name = $this->checkName($filename, $overwrite);
					$success = move_uploaded_file($tmp_name, $this->_destination . $name);
					if ($success) {
						$message = "$filename uploaded successfully";
						if ($this->_renamed) {
							$message .= " and renamed $name";
						}
						$this->_messages[] = $message;
					} else {
						$this->_messages[] = "Could not upload $filename";
					}
				}
			}
		}
	}