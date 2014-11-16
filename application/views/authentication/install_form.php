<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title><?php echo $this->lang->line('installer_title'); ?></title>
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
echo "\n".heading($this->lang->line('installer_title') , 2);
echo '<p class="notice">'.$this->lang->line('installer_help').'</p>';
$this->load->view('messages');
echo form_open('install');
echo '<fieldset>';
echo form_open('install', array('id'=>'loginform'));

echo '<p>'.form_label('<span>'.$this->lang->line('user_fullname').'</span>', 'user_fullname'); 
echo form_input(array('name' => 'user_fullname', 'id' => 'user_fullname','maxlength' => '100', 'size' => '30', 'value' => set_value('user_fullname')));
echo '<p>'.form_label('<span>'.$this->lang->line('user_email').'</span>', 'user_email'); 
echo form_input(array('name'=> 'user_email','id'=> 'user_email','maxlength'=> '50','size'=> '35','value'=>set_value('user_email')));
echo '<p>'.form_label('<span>'.$this->lang->line('user_password').'</span>', 'user_password');
echo form_password(array('name'=> 'user_password','id'=> 'user_password','maxlength'=> '15','size'=> '15'));
echo '<p>'.form_label('<span>'.$this->lang->line('user_repeat_password').'</span>', 'user_repeat_password'); 
echo form_password(array('name'=> 'user_repeat_password','id'=> 'user_repeat_password','maxlength'=> '15','size'=> '15'));
echo '<p>'.form_submit('install_info', $this->lang->line('installer_install')).'</p>';
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