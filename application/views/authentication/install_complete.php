<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title><?php echo $this->lang->line('installer_complete'); ?></title>
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
echo "\n".heading($this->lang->line('installer_complete') , 2);
if ($this->session->flashdata('success') <> "") echo '<span class="success"><img src="img/icons/check_mark_16x16.png" />'.$this->   session->flashdata('success').'</span>';
if ($this->session->flashdata('error') <> "") echo '<span class="error">'.$this->session->flashdata('error').'</span>';
echo "\n".validation_errors();
//echo '<fieldset>';
echo '<p class="notice">Cyberience Projects has been successfully installed and is ready to use.</p>';
echo '<p class="success">To <a href="'.base_url().'">login</a> you should use the username and password you setup using the installer.</p>';
echo '<p class="error">For security purposes you are <strong>strongly</strong> encouraged to delete this file now:';
echo '<br /><span style="margin:10px;font-family:monospace;display:block;color:#000;">cyberience_projects/application/controllers/install.php</span></p>';
//echo '</fieldset>';
echo '</div>'; // End of Main Content
echo '<div class="push"></div>';
echo '<div class="span-24 last" id="footer">';
echo '<p><img alt="" src="'.base_url().'img/layout/bottom_logo.png'.'" /></p>';
echo '</div>'; // End of Footer
echo '</div>'; // End of Container
echo '</body>';
echo '</html>';
/* End of file */