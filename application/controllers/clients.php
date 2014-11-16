<?php
class Clients extends Controller
{
    function Clients()
    {
        parent::Controller();
        $this->load->model('clients_model');
        //Only Administrator Users Can View Client Area
        if ($this->session->userdata('usertype') != "Administrator")
        {
            redirect('projects');
        }
    }
    function index()
    {
        //   Get All Clients
        $data['clients'] = $this->clients_model->get_clients($this->session->userdata('company'));
        // Display Client List
        $data['page_heading'] = $this->lang->line('client_admin');
        $this->load->view('clients/view', $data);
    }
    function add()
    {
        // Display Form for Adding a New Client
        $data['page_heading'] = $this->lang->line('client_add');
        $this->load->view('clients/form', $data);
    }
    function delete($id)
    {
        /*
        |   Delete A Client
        */
        $client_id = (int)$id;
        if ($this->clients_model->delete_client($this->session->userdata('company'), $client_id))
        {
            $this->session->set_flashdata('success', $this->lang->line('client_msg_deleted'));
        }
        redirect('clients');
    }
    function edit($id)
    {
        /*
        |   Get Client Details
        */
        $client_id = (int)$id;
        $data['client_details'] = $this->clients_model->get_one_client($this->session->userdata('company'), $client_id);
        /*
        |   Display Edit Client Form
        */
        $data['page_heading'] = $this->lang->line('client_change');
        $this->load->view('clients/form', $data);
    }
    function create()
    {
        /*
        |   Validate New Client Inputs
        */
        if ($this->form_validation->run('clients') == true)
        {
            /*
            |   Set Variables for Insert Into Database
            */
            $client_info['unq'] = NULL;
            $client_info['organisationname'] = $this->input->post('client_organisation');
            $client_info['address'] = $this->input->post('client_address');
            $client_info['orgtelephone'] = $this->input->post('client_telephone');
            $client_info['contactname'] = $this->input->post('client_name');
            $client_info['email'] = $this->input->post('client_email');
            $client_info['website'] = $this->input->post('client_website');
            $client_info['other'] = $this->input->post('client_other');
            $client_info['company'] = $this->session->userdata('company');
            $client_info['created'] = mdate("%Y-%m-%d %H:%i:%s", time());
            /*
            |   Insert Client Into Database
            */
            if ($this->clients_model->insert_client($client_info))
            {
                $this->session->set_flashdata('success', $this->lang->line('client_msg_created'));
            }
            redirect('clients');
        }
        else
        {
            /*
            |   Failed Validation
            */
            $this->add();
        }
    }
    function update()
    {
        /*
        |   Validate Client Update Inputs
        */
        if ($this->form_validation->run('clients') == TRUE)
        {
            /*
            |   Set Variables for Insert Into Database
            */
            $client_id = (int)$this->input->post('clientUnq');
            $client_info['organisationname'] = $this->input->post('client_organisation');
            $client_info['address'] = $this->input->post('client_address');
            $client_info['orgtelephone'] = $this->input->post('client_telephone');
            $client_info['contactname'] = $this->input->post('client_name');
            $client_info['email'] = $this->input->post('client_email');
            $client_info['website'] = $this->input->post('client_website');
            $client_info['other'] = $this->input->post('client_other');
            /*
            |   Update Client Details in Database
            */
            $this->clients_model->update_client($this->session->userdata('company'), $client_id, $client_info);
            $this->session->set_flashdata('success', $this->lang->line('client_msg_updated'));
            redirect('clients');
        }
        else
        {
            /*
            |   Failed Validation
            */
            $this->edit($this->input->post('clientUnq'));
        }
    }
}
/* End of file */
