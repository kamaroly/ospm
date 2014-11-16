<?php
echo "\n".'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
echo "\n".'<html xmlns="http://www.w3.org/1999/xhtml" lang="en">';
echo "\n".'<head>';
echo "\n".'<title>';
echo $page_heading;
if ($this->session->userdata('project_name') <> '') echo " - ".$this->session->userdata('project_name');
echo '</title>';
echo "\n".'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
echo "\n".'<link rel="stylesheet" href="'.base_url().'css/blueprint/screen.css" type="text/css" media="screen, projection" />';
echo "\n".'<link rel="stylesheet" href="'.base_url().'css/blueprint/print.css" type="text/css" media="print" />';
echo "\n".'<!--[if lt IE 8]><link rel="stylesheet" href="'.base_url().'css/blueprint/ie.css" type="text/css" media="screen, projection" /><![endif]-->
';
echo "\n".link_tag(array('href' => 'css/default.css', 'media' => 'screen', 'rel' => 'stylesheet', 'type' => 'text/css'));
if (isset($additional_js))
{
    foreach ($additional_js as $js):
        echo "\n".'<script src="'.$js.'" type="text/javascript"></script>';
    endforeach;
}
echo "\n".'<script src="'.base_url().'js/jquery-1.3.2.min.js" type="text/javascript"></script>';
echo "\n".'<script src="'.base_url().'js/toggle_filter.js" type="text/javascript"></script>';
if (isset($additional_css))
{
    foreach ($additional_css as $css):
        echo "\n".'<link href="'.$css.'" media="screen" rel="stylesheet" type="text/css" />';
    endforeach;
}
echo "\n".'</head>';
echo "\n".'<body>';
echo "\n".'<div id="header" class="container">';
echo "\n".'<div class="span-18 top_nav">';
// Preferences
echo '<ul>';

if ($this->session->userdata('usertype') == "Administrator")
{

    echo '<li>'.$this->lang->line('navigation_clients').'</li>';
    echo '<li>|</li>';
    echo '<li>'.$this->lang->line('navigation_users').'</li>';
    echo '<li>|</li>';
}      
echo '<li>'.$this->lang->line('navigation_projects').'</li>';
echo '<li>|</li>';        
echo '<li>'.$this->lang->line('navigation_users_profile').'</li>';
echo '<li>|</li>';
echo '<li>'.$this->lang->line('navigation_users_logout').'</li>';
echo '</ul>';
echo "\n".'</div>';
echo '<div id="top_nav" class="top_nav span-6 last">';
echo '<ul>';
echo '<li>'.$this->lang->line('navigation_report_bug').'</li>';
echo '<li>|</li>';
echo '<li>'.$this->lang->line('navigation_credits').'</li>';
echo '</ul>';


echo '</div>';
echo "\n".'<div class="span-24">';

echo "\n".'<p class="branding">'.$this->config->item('organisation_branding').'</p>';

$this->load->view('timetrack/timer');
echo "\n".'</div>';
echo "\n".'</div>'; // End of Header
$this->load->view('main_navigation');
echo "\n".'<div id="main_container">';
echo "\n".'<div class="container">';
echo "\n".'<div class="span-24" id="wrapper">';

echo "\n".'<div class="span-24" id="main_content">';
echo '<span class="current_date">'.date('l, j F Y').'</span>';
if ($this->session->userdata('user_id') <> '')
{
    if ($this->session->userdata('project_name') <> '')
    {
        echo heading($this->session->userdata('project_name'), 1);
    }
}
if ($this->session->userdata('os_tasks') + $this->session->userdata('os_invoices') > 0)
{
    echo '<span class="os_items">Outstanding Work for Project';
    if ($this->session->userdata('os_tasks') > 0)
    {
        echo ' :: '.$this->session->userdata('os_tasks').' '.$this->lang->line('task_outstanding');
    }
    if ($this->session->userdata('os_invoices') > 0)
    {
        echo ' :: '.$this->session->userdata('os_invoices').' '.$this->lang->line('invoice_outstanding');
    }
}
$this->load->view('messages');
$this->load->view('sub_navigation');
echo "\n".heading($page_heading, 3);


/* End of file */
