<?php
class Mail extends Controller
{
    function Mail()
    {
        parent::Controller();

    }

    function index()
    {
        //Load in the files we'll need
        require_once "Swift.php";
        require_once "Swift/Connection/SMTP.php";

        //Start Swift
        $swift =& new Swift(new Swift_Connection_SMTP("localhost"));

        //Create the message
        $message =& new Swift_Message("My subject", "My body");

        //Now check if Swift actually sends it
        if ($swift->send($message, "zzzabhi@gmail.com", "admin@theuniversalwisdom.org")) echo "Sent";
        else echo "Failed";
    }
}

?>