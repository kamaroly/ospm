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
//echo "\n".link_tag(array('href' => 'css/default.css', 'media' => 'screen', 'rel' => 'stylesheet', 'type' => 'text/css'));
echo "\n".link_tag(array('href' => 'css/mobile.css', 'media' => 'screen', 'rel' => 'stylesheet', 'type' => 'text/css'));
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
echo "\n".'<div id="header">';
echo "\n".'<h1>'.$this->config->item('organisation_branding').'</h1>';
if ($this->session->userdata('user_id') <> '')
{
    if ($this->session->userdata('project_name') <> '')
    {
        echo heading($this->session->userdata('project_name'), 2);
    }
}   
echo "\n".'</div>';