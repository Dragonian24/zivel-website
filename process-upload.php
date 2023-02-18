<?php
$to = "tabory.zivel@gmail.com";
$subject = "New file upload";
$message = "A new file has been uploaded.";

// Check if the form was submitted
if (isset($_POST["submit"])) {
  // Check if a file was uploaded
  if (isset($_FILES["fileToUpload"])) {
    $file = $_FILES["fileToUpload"];
    $filename = $file["name"];
    $filetype = $file["type"];
    $filesize = $file["size"];
    $filetmp = $file["tmp_name"];
    
    // Check if the file is an allowed type
    $allowedTypes = array("pdf", "doc", "docx", "txt");
    $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
    if (in_array($fileExtension, $allowedTypes)) {
      // Read the file into a variable
      $fileContent = file_get_contents($filetmp);
      
      // Attach the file to the email
      $boundary = md5(time());
      $headers = "From: yourname@example.com\r\n";
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
      $message .= "\r\n--$boundary\r\n";
      $message .= "Content-Type: application/octet-stream; name=\"$filename\"\r\n";
      $message .= "Content-Disposition: attachment; filename=\"$filename\"\r\n\r\n";
      $message .= $fileContent . "\r\n\r\n--$boundary--";
      
      // Send the email
      $success = mail($to, $subject, $message, $headers);
      
      // Output a success message
      if ($success) {
        echo "File uploaded and sent successfully!";
      } else {
        echo "Failed to send email. Please try again later.";
      }
    } else {
      echo "Invalid file type. Please upload a PDF, Word document, or text file.";
    }
  } else {
    echo "No file uploaded. Please select a file to upload.";
  }
}
?>