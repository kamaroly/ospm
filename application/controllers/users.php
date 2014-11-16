<?php
class Users extends Controller
{
    function Users()
    {
        parent::Controller();
        // Only Administration Users Can Use Users Section with exception of profile page and updates
        $current_uri = $this->uri->uri_string();
        //echo $current_uri;
        $valid_uris = array('/users/user_profile', '/users/profile_update', '/users/update_password');
        if (!in_array($current_uri, $valid_uris))
        {
            if ($this->session->userdata('usertype') != "Administrator")
            {
                redirect('projects');
            }
        }
        $this->load->model('users_model');
        $this->load->helper('encryption');
    }
    function index()
    {
        // Only Administration Users Can Use Users Section
        if ($this->session->userdata('usertype') != "Administrator")
        {
            redirect('projects');
        }
        $data['users'] = $this->users_model->get_users($this->session->userdata('company'));
        // Set HTML variables
        $data['page_heading'] = $this->lang->line('user_admin');
        // redisplay web form
        $this->load->view('users/view', $data);
    }
    function edit($id)
    {
        // Get User Data
        $user_id = (int)$id;
        $data['user_details'] = $this->users_model->get_user($this->session->userdata('company'), $user_id);
        // Set HTML variables
        $data['page_heading'] = $this->lang->line('user_edit');
        // redisplay web form
        $this->load->view('users/form', $data);
    }
    function add()
    {
        // Set HTML variables
        $data['page_heading'] = $this->lang->line('user_add');
        // redisplay web form
        $this->load->view('users/form', $data);
    }
    function create()
    {
        // Validate User Information
        if ($this->form_validation->run('users') == true)
        {
            // Insert User Into Database
            $userInfo['unq'] = NULL;
            $userInfo['fullname'] = $this->input->post('user_fullname');
            $userInfo['email'] = $this->input->post('user_email');
            $userInfo['status'] = $this->input->post('user_status');
            $userInfo['type'] = $this->input->post('user_type');
            $userInfo['company'] = $this->session->userdata('company');
            $userInfo['created'] = mdate("%Y-%m-%d %H:%i:%s", time());
            if ($this->users_model->insert_user($userInfo))
            {
                // Create Random Password and Email User
                $this->load->helper('string');
                $ini_pass = random_string('alnum', 10);
                $encrypted_password = encryptPass($ini_pass, $ini_pass);
                $this->users_model->reset_password($this->input->post('user_email'), $encrypted_password);
                $this->_automail_newuser($this->input->post('user_email'), $ini_pass);
                $this->session->set_flashdata('success', $this->lang->line('user_msg_created'));
            }
            redirect('users');
        }
        else
        {
            $this->add();
        }
    }
    function _automail_newuser($email, $password)
    {
        $this->lang->load('email');
        $this->load->library('email');
        $this->email->from($this->config->item('sender_email'), $this->config->item('sender_name'));
        $this->email->to($email);
        $this->email->subject($this->lang->line('user_new_email_subject'));
        $mailcontent = vsprintf($this->lang->line('user_new_email_maintext'), array($email, $password));
        $this->email->message($mailcontent);
        $this->email->send();
        //$this->email->print_debugger();
    }
    function update()
    {
        // Validate User Information
        $user_id = (int)$this->input->post('UserUnq');
        $this->session->set_userdata('active_id', $user_id);
        if ($this->form_validation->run('users') == true)
        {
            $userInfo['fullname'] = $this->input->post('user_fullname');
            $userInfo['email'] = $this->input->post('user_email');
            $userInfo['status'] = $this->input->post('user_status');
            $userInfo['type'] = $this->input->post('user_type');
            $this->users_model->update_user($this->session->userdata('company'), $user_id, $userInfo);
            $this->session->set_flashdata('success', $this->lang->line('user_msg_updated'));
            redirect('users');
        }
        else
        {
            $this->edit($this->input->post('UserUnq'));
        }
    }
    function delete($id)
    {
        // removes an item from the database
        $user_id = (int)$id;
        if ($this->users_model->delete_user($this->session->userdata('company'), $user_id))
        {
            $this->session->set_flashdata('success', $this->lang->line('user_msg_deleted'));
        }
        redirect('users');
    }
    function user_profile()
    {
        $data['page_heading'] = $this->lang->line('user_myprofile');
        $this->load->model('users_model');
        $data['user_details'] = $this->users_model->get_user($this->session->userdata('company'), $this->session->userdata('user_id'));
        // redisplay web form
        $this->load->view('users/form_myprofile', $data);
    }
    function profile_update()
    {
        // Validate User Information
        $this->session->set_userdata('active_id', $this->session->userdata('user_id'));
        if ($this->form_validation->run('user_profile') == true)
        {
            $userInfo['fullname'] = $this->input->post('user_fullname');
            $userInfo['email'] = $this->input->post('user_email');
            $user_id = (int)$this->session->userdata('user_id');
            $this->load->model('users_model');
            $this->users_model->update_user($this->session->userdata('company'), $user_id, $userInfo);
            $this->session->set_flashdata('success', $this->lang->line('user_profile_msg_updated'));
            redirect('users/user_profile');
        }
        else
        {
            $this->user_profile();
        }
    }
    function update_password()
    {
        $this->form_validation->set_rules('user_current_password', $this->lang->line('user_current_password'), 'required');
        $this->form_validation->set_rules('user_new_password', $this->lang->line('user_new_password'), 'required|matches[user_repeat_new_password]');
        $this->form_validation->set_rules('user_repeat_new_password', $this->lang->line('user_repeat_new_password'), 'required');
        if ($this->form_validation->run() == FALSE)
        {
            $this->user_profile();
        }
        else
        {
            $encrypted_password = encryptPass($this->input->post('user_current_password'), $this->input->post('user_current_password'));
            if ($this->users_model->verify_password($this->session->userdata('user_id'), $encrypted_password))
            {
                $encrypted_new_password = encryptPass($this->input->post('user_new_password'), $this->input->post('user_new_password'));
                $this->users_model->reset_password($this->session->userdata('user_email'), $encrypted_new_password);
                $this->session->set_flashdata('success', $this->lang->line('user_password_updated'));
                redirect('users/user_profile');
            }
            else
            {
                $this->session->set_flashdata('error', $this->lang->line('user_invalid_password'));
                redirect('users/user_profile');
            }
        }
    }
}
/* End of file */