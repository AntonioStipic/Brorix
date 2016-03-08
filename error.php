<?php // This page checks all error codes and writes the message for each error.

// Checks for data sent with GET method with name "error", if not found redirects to login page
if ($_GET["code"]){
    $error = $_GET["code"];
    
    // List of all error codes and what to write when an error occurs
    if ($error == 325)
        $errorText = "Wrong combination of email and password. Please check it and try again.";
    if ($error == 941)
        $errorText = "The user doesn't exist!";
    if ($error = 784)
    	$errorText = "The email is already registered!";
    if ($error = 472)
    	//$errorText = "The username is already registered!";
    
    echo $errorText;
        
}else{ // Redirect to login page
    header("Location:index.php");
    die();
}

?>