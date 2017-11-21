<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'application_functions.php';
require 'api_calls.php';
require 'data_processing.php';
require 'sms_functions.php';

$transactionID = $_REQUEST['trans_id'];
$status = $_REQUEST['status'];
$responseMessage = $_REQUEST['responseMessage'];
file_put_contents('ussd_access.log', $responseMessage, FILE_APPEND);

$send_sms  = new SMS_Functions();
$ussd = new ApplicationFunctions();
$data_processor = new ProcessUserInput();
$api_accesor = new APICalls();

if($status == 'success'){
    
    //Set debit_status to succes in the transaction table
    $ussd->updateColumn($transactionID, "credit_status", "success"); 
    $transaction = $ussd->getTransactionDetails($transactionID);
    
    //Message recipient that money has been tranfeered into her mobile money
    //account
    $send_sms->sendCreditSuccessMessage($transaction,"sender",$responseMessage);
    
    //Message sender that money has been tranferred to the recipient succesfully
    $send_sms->sendCreditSuccessMessage($transaction,"reciever",$responseMessage);
}else{
    //Send  a text message that transaction could not be processed
}

?>
