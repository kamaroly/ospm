<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class initialisation_hooks
{
    function index()
    {
        $this->CI = &get_instance(); //grab a reference to the controller
        if ($this->CI->db->conn_id == "")
        {
            show_error("Cyberience Projects could not connect to your database with the information provided in cyberience_projects/application/config/database.php");
        }
        if (preg_match('/test.php/', $_SERVER['PHP_SELF']))
        {
            return;
        }
        elseif('127.0.0.1'==$_SERVER['SERVER_NAME'])
		{
			return;	
		}
		elseif(preg_match('/install/',$_SERVER['PHP_SELF']))
		{
			return;
		}
	    if (!$this->CI->db->table_exists('tasks'))
        {
            redirect('install', 'refresh');
        }
        $this->CI->load->library('session');
        if ((!$this->CI->session->userdata('user_id')) & ($this->CI->uri->segment(1) <>
            'authenticate')) redirect('authenticate');
        else
        {
            $this->count_items();
        }
    }
    function count_items()
    {
        $this->CI->load->model('tasks_model');
        $outstandingTasks = $this->CI->tasks_model->count_tasks($this->CI->session->
            userdata('company'), $this->CI->session->userdata('project_id'));
        $this->CI->load->model('invoices_model');
        $outstandingInvoices = $this->CI->invoices_model->count_invoices($this->CI->
            session->userdata('company'), $this->CI->session->userdata('project_id'));
        $this->CI->session->set_userdata('os_tasks', $outstandingTasks);
        $this->CI->session->set_userdata('os_invoices', $outstandingInvoices);
    }
}
/* End of file */