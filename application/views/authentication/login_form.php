<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>User Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="<?php echo base_url().'css/blueprint/screen.css'; ?>" type="text/css" media="screen, projection" />
<link rel="stylesheet" href="<?php echo base_url().'css/blueprint/print.css'; ?>" type="text/css" media="print" />	
<!--[if lt IE 8]><link rel="stylesheet" href="<?php echo base_url().'css/blueprint/ie.css'; ?>" type="text/css" media="screen, projection" /><![endif]-->
<link  href="<?php echo base_url().'css/default.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
</head>

<body id="login_page">
<?php
echo "\n".'<div class="container">';
echo "\n".'<div class="last span-24" id="wrapper">';
echo "\n".heading($page_heading, 2);
$this->load->view('messages');	
echo form_open('authenticate/check_login_details');

echo '<fieldset>';

// Check for Cookies
$this->load->helper('cookie');
$user_cookie=get_cookie('username');
if($user_cookie<>"") 
{
	$email=$user_cookie;
}

else
{
	$email=set_value('Email');
}
echo '<p>';
echo form_label($this->lang->line('user_email_address'), 'Email');
echo form_input(array('name'=> 'Email','id'=> 'Email','maxlength'=> '50','size'=> '35','value'=>$email));
echo '</p>';
echo '<p>';
echo form_label($this->lang->line('user_password'), 'Password');
echo form_password(array('name'=> 'Password','id'=> 'Password','maxlength'=> '15','size'=> '15'));
echo '</p>';
echo '<p class="form-spacer">';

$attributes = array(
    'class' => 'inline-label'
    ,'id' => 'Remember-Me'
);
echo form_label($this->lang->line('user_remember_me'), 'Remember-Me',$attributes);
echo form_checkbox('Remember-Me', '1', FALSE);

echo '</p>';
echo '<p>';
echo form_submit('submit', $this->lang->line('user_log_me_in'));
echo '</p>';
echo '<p class="discreet">'.anchor('authenticate/lost_password', $this->lang->line('user_lost_password')).'</p>';
echo '</fieldset>';
echo '</form>';
echo '</div>'; // End of Main Content
echo '<div class="push"></div>';
echo '<div class="span-24 last" id="footer">';
echo '<p><img alt="" src="'.base_url().'img/layout/bottom_logo.png'.'" /></p>';
echo '</div>'; // End of Footer
echo '</div>'; // End of Container
echo '</body>';
echo '</html>';
/* End of file */