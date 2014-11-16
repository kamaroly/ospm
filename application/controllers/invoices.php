<?php
class invoices extends Controller
{
    function invoices()
    {
        parent::Controller();
        $this->load->model('invoices_model');
    }
    function index()
    {
        // Get All Open Invoices using filter
        $data['invoices'] = $this->invoices_model->get_all_invoices($this->session->userdata('company'), $this->session->userdata('project_id'));
        /*
        |   Calculate Invoice Total Amount
        */
        if (count($data['invoices']) > 0)
        {
            foreach ($data['invoices'] as $invoice):
                $data['time'][$invoice['unq']] = $this->invoices_model->get_total_invoice_amount($this->session->userdata('company'), $this->session->userdata('project_id'), $invoice['unq']);
            endforeach;
        }
        /*
        |   Display Invoice Details
        */
        $data['page_heading'] = $this->lang->line('invoice_main');
        $this->load->view('invoices/view.php', $data);
    }
    function add()
    {
        /*
        |   Set Additional CSS and Javascript and Display New Invoice Form
        */
        $data['additional_css'] = array(base_url().'css/date_input.css');
        $data['additional_js'] = array(base_url().'js/jquery-1.3.2.min.js', base_url().'js/jquery.date_input.min.js', base_url().'js/datepicker.js');
        $data['page_heading'] = $this->lang->line('invoice_add');
        $this->load->view('invoices/form.php', $data);
    }
    function edit($id)
    {
        /*
        |   Get Invoice Details
        */
        $invoice_id = (int)$id;
        $data['invoice_details'] = $this->invoices_model->get_invoice($this->session->userdata('company'), $this->session->userdata('project_id'), $invoice_id);
        /*
        |   Get Work Items
        */
        $data['billable_details'] = $this->invoices_model->get_billables($this->session->userdata('company'), $this->session->userdata('project_id'), $invoice_id);
        /*
        |   Count Total Actual Times for Work Items
        */
        if (count($data['billable_details']) > 0)
        {
            foreach ($data['billable_details'] as $work_items):
                $data['work_item_time'][$work_items['unq']] = $this->invoices_model->get_actual_times($this->session->userdata('company'), $this->session->userdata('project_id'), $work_items['unq']);
            endforeach;
        }
        /*
        |   Set Additional CSS and Javascript and Display New Invoice Form
        */
        $data['additional_css'] = array(base_url().'css/date_input.css');
        $data['additional_js'] = array(base_url().'js/jquery-1.3.2.min.js', base_url().'js/jquery.date_input.min.js', base_url().'js/datepicker.js');
        $data['page_heading'] = $this->lang->line('invoice_edit');
        $this->load->view('invoices/form.php', $data);
    }
    function create()
    {
        /*
        |   Run Validation on New Invoice Inputs
        */
        if ($this->form_validation->run('invoices') == TRUE)
        {
            /*
            |   Set Category
            */
            if ($this->input->post('invoice_category') <> "")
            {
                $category = $this->input->post('invoice_category');
            }
            else
            {
                $category = $this->input->post('invoice_categorylist');
            }
            /*
            |   Set Variables for Insert into Database
            */
            $invoice_info['unq'] = NULL;
            $invoice_info['project'] = $this->session->userdata('project_id');
            $invoice_info['name'] = $this->input->post('invoice_name');
            $invoice_info['status'] = str_replace('_', ' ', $this->input->post('invoice_status'));
            $invoice_info['company'] = $this->session->userdata('company');
            $invoice_info['actiondate'] = date_conversion($this->input->post('invoice_action_date'), 'import');
            $invoice_info['due_date'] = date_conversion($this->input->post('invoice_due_date'), 'import');
            $invoice_info['paid_date'] = date_conversion($this->input->post('invoice_paid_date'), 'import');
            $invoice_info['created'] = mdate("%Y-%m-%d %H:%i:%s", time());
            /*
            |    Insert Invoice into Database
            */
            $new_invoice_id = $this->invoices_model->insert_invoice($invoice_info);
            if ($new_invoice_id > 0)
            {
                /*
                |   Invoice Created Successfully
                */
                $this->session->set_flashdata('success', $this->lang->line('invoice_msg_created'));
                redirect('invoices/edit/'.$new_invoice_id);
            }
            else
            {
                /*
                |   Invoice Creation Failed
                */
                redirect('invoices');
            }
        }
        else
        {
            /*
            |   Failed Initial Validation
            */
            $this->add();
        }
    }
    function update()
    {
        /*
        |   Run Validation on Existing Invoice Inputs
        */
        if ($this->form_validation->run('invoices') == TRUE)
        {
            /*
            |   Set Category
            */
            if ($this->input->post('invoice_category') <> "")
            {
                $category = $this->input->post('invoice_category');
            }
            else
            {
                $category = $this->input->post('invoice_categorylist');
            }
            /*
            |   Set Variables for Insert into Database
            */
            $invoice_info['name'] = $this->input->post('invoice_name');
            $invoice_info['status'] = str_replace('_', ' ', $this->input->post('invoice_status'));
            $invoice_info['actiondate'] = date_conversion($this->input->post('invoice_action_date'), 'import');
            $invoice_info['due_date'] = date_conversion($this->input->post('invoice_due_date'), 'import');
            $invoice_info['paid_date'] = date_conversion($this->input->post('invoice_paid_date'), 'import');
            $invoice_id = (int)$this->input->post('invoiceUnq');
            /*
            |   Update Invoice in Database
            */
            $this->invoices_model->update_invoice($this->session->userdata('company'), $invoice_id, $invoice_info);
            $this->session->set_flashdata('success', $this->lang->line('invoice_msg_updated'));
            redirect('invoices');
        }
        else
        {
            /*
            |   Initial Validation Failed
            */
            $this->edit($this->input->post('invoiceUnq'));
        }
    }
    function destroy($id)
    {
        /*
        |   Delete Invoice from Database
        */
        $invoice_id = (int)$id;
        if ($this->invoices_model->delete_invoice($this->session->userdata('company'), $invoice_id))
        {
            $this->session->set_flashdata('success', $this->lang->line('invoice_msg_deleted'));
        }
        redirect('invoices');
    }
    function decimal_check($str)
    {
        /*
        |   Check number is in valid currency format nnn.nn and is not blank
        */
        $regexp = "#^[0-9]{1,3}[.?]{0,1}[0-9]{0,2}$#";
        if ((!preg_match($regexp, $str)) & ($str <> ""))
        {
            $this->form_validation->set_message('decimal_check', 'The %s field must be in the format nnn.nn');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    function add_work($id)
    {
        /*
        |   Get Invoice Details / Invoice Name is Needed for Work Item
        */
        $data['invoice_id'] = (int)$id;
        $data['invoice_details'] = $this->invoices_model->get_invoice($this->session->userdata('company'), $this->session->userdata('project_id'), $data['invoice_id']);
        /*
        |   Display Create New Work Item Form
        */
        $data['page_heading'] = $this->lang->line('invoice_work_item_add');
        $this->load->view('invoices/form-work-item.php', $data);
    }
    function create_work()
    {
        $invoice_id = (int)$this->input->post('invoiceUnq');
        /*
        |   Run Validation on Work Item Inputs
        */
        if ($this->form_validation->run('work_item') == TRUE)
        {
            /*
            |   Set Variables for Insert into Database
            */
            $work_item_info['unq'] = NULL;
            $work_item_info['project'] = $this->session->userdata('project_id');
            $work_item_info['invoice_id'] = $invoice_id;
            $work_item_info['description'] = $this->input->post('invoice_work_item_description');
            $work_item_info['quantity'] = $this->input->post('invoice_work_item_quantity');
            $work_item_info['flatfee'] = $this->input->post('invoice_work_item_flat_fee');
            $work_item_info['hourlyrate'] = $this->input->post('invoice_work_item_rate');
            $work_item_info['company'] = $this->session->userdata('company');
            $work_item_info['created'] = mdate("%Y-%m-%d %H:%i:%s", time());
            /*
            |   Insert Work Item Into Database
            */
            if ($this->invoices_model->insert_work_item($work_item_info))
            {
                $this->session->set_flashdata('success', $this->lang->line('invoice_work_item_created'));
            }
            redirect('invoices/edit/'.$invoice_id);
        }
        else
        {
            /*
            |   Initial Validation Failed
            */
            $this->add_work($invoice_id);
        }
    }
    function edit_work($id)
    {
        /*
        |   Get Work Item Details From Database
        */
        $work_item_id = (int)$id;
        $data['work_item_details'] = $this->invoices_model->get_work_item($this->session->userdata('company'), $this->session->userdata('project_id'), $work_item_id);
        /*
        |   Get Invoice Details / Invoice Name is Needed for Work Item
        */
        $data['invoice_details'] = $this->invoices_model->get_invoice($this->session->userdata('company'), $this->session->userdata('project_id'), $data['work_item_details'][0]['invoice_id']);
        /*
        |   Get Associated Tasks for Work Item
        */
        foreach ($data['work_item_details'] as $work_items)
        {
            $data['tracked_times'] = $this->invoices_model->get_associated_tasks($this->session->userdata('company'), $this->session->userdata('project_id'), $work_items['unq']);
        }
        /*
        |   Display Work Item Form
        */
        $data['page_heading'] = $this->lang->line('invoice_work_item_edit');
        $this->load->view('invoices/form-work-item.php', $data);
    }
    function update_work()
    {
        $work_item_id = (int)$this->input->post('work_item_id');
        /*
        |   Run Validation on Work Item Inputs
        */
        if ($this->form_validation->run('work_item') == TRUE)
        {
            /*
            |   Set Variables for Insert into Database
            */
            $invoice_id = (int)$this->input->post('invoice_id');
            $work_item_info['description'] = $this->input->post('invoice_work_item_description');
            $work_item_info['quantity'] = $this->input->post('invoice_work_item_quantity');
            $work_item_info['flatfee'] = $this->input->post('invoice_work_item_flat_fee');
            $work_item_info['hourlyrate'] = $this->input->post('invoice_work_item_rate');
            /*
            |   Update Work Item In Database
            */
            $this->invoices_model->update_work_item($this->session->userdata('company'), $work_item_id, $work_item_info);
            $this->session->set_flashdata('success', $this->lang->line('work_item_msg_updated'));
            redirect('invoices/edit/'.$invoice_id);
        }
        else
        {
            /*
            |   Initial Validation Failed
            */
            $this->edit_work($work_item_id);
        }
    }
    function delete_work($id)
    {
        /*
        |   Delete Work Item from Database
        */
        $work_item_id = (int)$id;
        $data['work_item_details'] = $this->invoices_model->get_work_item($this->session->userdata('company'), $this->session->userdata('project_id'), $work_item_id);
        $invoice_id = $data['work_item_details'][0]['invoice_id'];
        if ($this->invoices_model->delete_work_item($this->session->userdata('company'), $work_item_id))
        {
            $this->session->set_flashdata('success', $this->lang->line('invoice_work_item_deleted'));
        }
        redirect('invoices/edit/'.$invoice_id);
    }
}
/* End of file */
