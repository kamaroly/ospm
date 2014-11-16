<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('edit_action'))
{
    function edit_action($url,$alt_text) 
    {
        $actions = anchor($url, img(array('src' =>'img/icons/edit_16x16.png', 'alt' => $alt_text, 'title' => $alt_text)));
        return $actions;
    }
}
if ( ! function_exists('add_action'))
{
    function add_action($url,$alt_text) 
    {
        $actions = anchor($url, img(array('src' =>'img/icons/add_16x16.png', 'alt' => $alt_text, 'title' => $alt_text)));
        return $actions;
    }
}
if ( ! function_exists('delete_action'))
{
    function delete_action($url,$alt_text,$popup_text) 
    {
		// Create Javascript for Delete Confirmation
        $attributes = array('onclick' => 'return confirm(\'' . $popup_text .
            '\')');
        $actions=anchor($url, img(array('src' =>
            'img/icons/delete_16x16.png', 'alt' => $alt_text, 'title' => $alt_text)), $attributes);
        return $actions;
    }
}
if ( ! function_exists('pdf_action'))
{
    function pdf_action($url,$alt_text) 
    {
        $actions = anchor($url, img(array('src' =>'img/icons/document_16x16.png', 'alt' => $alt_text, 'title' => $alt_text)));
        return $actions;
    }
}
if ( ! function_exists('warning_action'))
{
    function warning_action($url,$alt_text) 
    {
        $actions = anchor($url, img(array('src' =>'img/icons/warning_16x16.png', 'alt' => $alt_text, 'title' => $alt_text)));
        return $actions;
    }
}
/* End of file */