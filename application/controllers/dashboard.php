<?php
class Dashboard extends Controller
{
    function Dashboard()
    {
        parent::Controller();
        $this->load->model('projects_model');
        $this->load->model('tasks_model');
        $this->load->model('invoices_model');
    }
    function index()
    {
        // Set Page Title
        $data['page_heading'] = $this->lang->line('dashboard_title');
        // Get Project List
        $data['projects']=$this->projects_model->get_all_projects($this->session->userdata('company'));
        // Get All Open Tasks
        $data['tasks']=$this->tasks_model->get_all_tasks($this->session->userdata('company'), 'ALL', array('viewactive'));
        
        // Get All Open Invoices using filter
        $data['invoices'] = $this->invoices_model->get_all_invoices($this->session->userdata('company'), 'ALL',$this->config->item('invoice_due_status_types'));
        //   Calculate Invoice Total Amount
        if (count($data['invoices']) > 0)
        {
            foreach ($data['invoices'] as $invoice):
                $data['time'][$invoice['unq']] = $this->invoices_model->get_total_invoice_amount($this->session->userdata('company'), 'ALL', $invoice['unq']);
            endforeach;
        }

        $this->load->view('dashboard/view', $data);        
    }
}
/* End of File */