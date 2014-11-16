<?php
class Authenticate extends Controller
{
    function Authenticate()
    {
        parent::Controller();
        $this->load->model('users_model');
        $this->load->helper('encryption');
    }
    /*
    |   index() :: Display main login form
    */
    function index()
    {
        /*
        |   Run Installer if Database Table 'people' Does Not Exist Yet
        */
        if (!$this->db->table_exists('people'))
        {
            redirect('/install', 'refresh');
            exit;
        }
        /*
        |   Display Login Form
        */
        $data['page_heading'] = $this->lang->line('user_login');
        $this->load->view('authentication/login_form', $data);
    }
    /*
    |   check_login_details() :: Validate User Login Details
    */
    function check_login_details()
    {
        /*
        |   Validate Login Details
        */
        if ($this->form_validation->run('login') == true)
        {
            /* --- Validate User Details --- */
            $encrypted_string = encryptPass($this->input->post('Password'), $this->input->post('Password'));
            $user_info = $this->users_model->check_login($this->input->post('Email'), $encrypted_string);
            /* ---  Login Successful --- */
            if (count($user_info) == 1)
            {
                /* --- Set Session Variables --- */
                $username = $user_info->fullname;
                $newdata['user'] = $username;
                $newdata['user_id'] = $user_info->unq;
                $newdata['user_email'] = $user_info->email;
                $newdata['project_id'] = $user_info->lastproject;
                $newdata['usertype'] = $user_info->type;
                $newdata['company'] = $user_info->company;
                $this->session->set_userdata($newdata);
                /* --- Set Initial Filter Variables --- */
                $newdata['status_filter'] = 'viewactive';
                $newdata['assignedto_filter'] = 'viewall';
                $newdata['priority_filter'] = 'viewall';
                $newdata['category_filter'] = 'viewall';
                $newdata['ttcategory_filter'] = 'viewall';
                $newdata['ttpeople_filter'] = 'viewall';
                $newdata['view_archived_times'] = '';
                $this->session->set_userdata($newdata);
                /*
                |   If project_id (last project accessed) is not zero then redirect to Tasks else to Project Settings
                */
                if (($this->session->userdata('project_id')) > 0)
                {
                    $this->get_project_info();
                    /*
                    |   If user checked the Remember Me checkbox then set a Cookie
                    */
                    if ($this->input->post('Remember-Me'))
                    {
                        $this->load->helper('cookie');
                        set_cookie('username', $this->input->post('Email'), time() + '604800', '', '', '');
                    }
                    redirect('/tasks/', 'refresh');
                }
                else
                {
                    redirect('/projects/', 'refresh');
                }
            }
            else
            {
                /*
                |   Username / Password Not Recognised
                */
                $this->session->set_flashdata('error', $this->lang->line('user_invalid_login'));
                redirect('authenticate');
            }
        }
        else
        {
            /*
            |   Username or Password Missing / Failed Initial Validation
            */
            $this->index();
        }
    }
    function get_project_info()
    {
        /*
        |   Get Project Details from Database
        */
        $this->load->model('Projects_Model');
        $project_info = $this->Projects_Model->get_project($this->session->userdata('company'), $this->session->userdata('project_id'));
        /*
        |   Create Session Variable with Project Name
        */
        if (isset($project_info->name))
        {
            // Setup Session Variables
            $newdata = array('project_name' => $project_info->name);
            $this->session->set_userdata($newdata);
        }
    }
    function logout()
    {
        /*
        |   Destroy Session to Logout User and Redirect to Homepage
        */
        $this->session->sess_destroy();
        redirect('');
    }
    function lost_password()
    {
        /*
        |   Display Page for Retrieving Lost Passwords
        */
        $data['page_heading'] = $this->lang->line('user_password_reset');
        $this->load->view('authentication/reset_password_form', $data);
    }
    function reset_password()
    {
        /*
        |   Validate Email Details
        */
        $this->form_validation->set_rules('Email', 'Email', 'required|valid_email|max_length[50]');
        if ($this->form_validation->run() == true)
        {
            /*
            |   Generate new Password
            */
            $this->load->helper('string');
            $newpass = random_string('alnum', 10);
            $encrypted_password = encryptPass($newpass, $newpass);
            $resetpass = $this->users_model->reset_password($this->input->post('Email'), $encrypted_password);
            if ($resetpass == 1)
            {
                /*
                |   Password Reset Success
                */
                $this->_automail_newpassword($this->input->post('Email'), $newpass);
                $this->session->set_flashdata('success', $this->lang->line('user_password_reset_success'));
                redirect('authenticate');
            }
            else
            {
                /*
                |   Password Reset Failed
                */
                $this->session->set_flashdata('error', $this->lang->line('user_password_reset_failed'));
                $this->lost_password();
            }
        }
        else
        {
            /*
            |   Invalid Email Address Entered
            */
            $this->lost_password();
        }
    }
    function _automail_newpassword($email, $password)
    {
        /*
        |   Email New Password to User
        */
        $this->lang->load('email');
        $this->load->library('email');
        $this->email->from($this->config->item('sender_email'), $this->config->item('sender_name'));
        $this->email->to($email);
        $this->email->subject($this->lang->line('user_new_password_email_subject'));
        $mailcontent = vsprintf($this->lang->line('user_new_password_email_maintext'), array($email, $password));
        $this->email->message($mailcontent);
        $this->email->send();
        //$this->email->print_debugger();
    }
}
?>