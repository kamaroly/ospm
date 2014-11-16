<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
   
// Auto Email Sender Details
$config['sender_email']="noreply@your-email-here.com";
$config['sender_name']="Your Administrator Name Here";
// Organisation Name
$organisation_name="Your Organisation Name";
$config['organisation_branding']=$organisation_name;
// Invoice / Receipt Text
$config['invoice_header'] = $organisation_name;
$config['invoice_address'] = "Address Line 1, Address Linen 2, Postcode, Telephone: 00000 000 000";
$config['invoice_currency'] = "£";
$config['invoice_footer_main'] = "Thank you for choosing ".$organisation_name;
$config['invoice_terms'] = "Payment is due upon receipt";
$config['invoice_footer1'] = "Please make cheques payable to ".$organisation_name;
$config['invoice_footer2'] = "or alternatively payments can be made direct to our bank as follows:";
$config['invoice_footer3'] = "Bank of Scotland, Sort Code: 00-00-00, A/C No: 00000000";
$config['receipt_footer1'] = "Received with Thanks";
$config['receipt_footer2'] = "If you have been satisfied with our services,";
$config['receipt_footer3'] = "please recommend us to your friends, family and colleagues.";
// Fonts Path for FPDF
$config['fonts_path']= "fonts/";
// User Types
$config['user_types'] = array("Normal", "Administrator");
// User Statuses Available - Inactive Status Required for Deletions
$config['user_statuses'] = array("Active", "Inactive");
// Project Status Types
$config['project_statuses'] = array("In Progress" => "In Progress", "Closed" => "Closed", "Suspended" => "Suspended");
$config['project_closed_status'] = array("Closed", "Suspended");
// Customised Elements for
$config['task_priority_options'] = array("5" => "Low", "10" => "Medium", "20" => "High", "30" => "Urgent");
$config['task_priority_cols'] = array("5" => "#AAFF7F", "10" => "#FFFF2A", "20" => "#FF7F00", "30" => "#F73E3E");
$config['task_type_options'] = array("0" => "Task", "1" => "Milestone", "2" => "Bug");
$config['task_status_options'] = array("Closed", "Fixed", "New", "Open", "Rejected", "Reopen");
$config['initial_status'] = "New"; // Define Status for Initial New
$config['closed_status'] = array("Closed", "Rejected"); // Define Status for Closed
$config['invoice_status_types'] = array("Future Invoice", "Work In Progress", "Invoice Due", "Invoice Paid"); // Total Status Types
$config['invoice_due_status_types'] = array("Future Invoice", "Work In Progress", "Invoice Due"); // These are the statuses which are classed as still to be paid / due - They must contain values from the list above only
/* End of File */