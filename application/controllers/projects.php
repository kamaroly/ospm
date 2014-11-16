<?php
class Projects extends Controller
{
    function projects()
    {
        parent::Controller();
        $this->load->model('projects_model');
        $this->load->model('invoices_model');
        $this->load->model('timetrack_model');
        $this->load->model('tasks_model');
        $this->load->model('clients_model');
        $this->load->model('users_model');
    }
    function index()
    {
        /*
        |   Display Header Dependent upon Inactive Filter
        */
        if ($this->session->userdata('view_inactive_projects') == 0)
        {
            $data['page_heading'] = $this->lang->line('project_active');
        }
        else
        {
            $data['page_heading'] = $this->lang->line('project_inactive');
        }
        $this->load->view('header', $data);
        $this->load->view('projects/view.php');
        $this->project_summaries();
        $this->load->view('projects/view_2.php');
        $this->load->view('footer', $data);
    }
    function new_project()
    {
        /*
        |   Get List of Clients
        */
        $data['clients'] = $this->clients_model->get_client_options($this->session->userdata('company'));
        /*
        |   Display New Project Form
        */
        $data['page_heading'] = $this->lang->line('project_new');
        $this->load->view('projects/form', $data);
    }
    function create_project()
    {
        /*
        |   Validate New Project Inputs
        */
        if ($this->form_validation->run('projects') == true)
        {
            /*
            |   Prepare Variables for Insert Into Database
            */
            $project_info['unq'] = null;
            $project_info['name'] = $this->input->post('project_name');
            $project_info['description'] = $this->input->post('project_description');
            $project_info['status'] = $this->input->post('project_status');
            $project_info['client'] = $this->input->post('project_clientlist');
            $project_info['company'] = $this->session->userdata('company');
            $project_info['created'] = mdate("%Y-%m-%d %H:%i:%s", time());
            /*
            |   Insert Project Into Database
            */
            $newproject = $this->projects_model->insert_project($project_info);
            $this->session->set_flashdata('success', $this->lang->line('project_msg_created'));
            redirect('projects/activate/'.$newproject);
        }
        else
        {
            /*
            |   Failed Initial Validation
            */
            $this->new_project();
        }
    }
    function project_delete()
    {
        /*
        |   Only Administrators can delete projects
        */
        if (!$this->session->userdata('usertype') == 'Administrator')
        {
            echo "<h1>Unauthorised Access</h1>";
            exit;
        }
        /*
        |   Display Form to Delete Project
        */
        $data['page_heading'] = $this->lang->line('project_delete').': '.$this->session->userdata('project_name');
        $this->load->view('projects/form_deleteproject', $data);
    }
    function project_destroy()
    {
        /*
        |   Only Administrators can delete projects
        */
        if (!$this->session->userdata('usertype') == 'Administrator')
        {
            echo "<h1>Unauthorised Access</h1>";
            exit;
        }
        /*
        |   Validate Delete Project Inputs
        */
        $this->load->helper('encryption');
        $enc_password = encryptPass($this->input->post('Password'), $this->input->post('Password'));
        $user_info = $this->users_model->check_login($this->session->userdata('user_email'), $enc_password);
        if (count($user_info) == 1)
        {
            /*
            |   Delete All Invoices
            */
            $this->invoices_model->delete_all_invoices($this->session->userdata('company'), $this->session->userdata('project_id'));
            /*
            |   Delete All Timetracks
            */
            $this->timetrack_model->delete_all_timetracks($this->session->userdata('company'), $this->session->userdata('project_id'));
            /*
            |   Delete All Tasks
            */
            $this->load->model('tasks_model');
            $this->tasks_model->delete_all_tasks($this->session->userdata('company'), $this->session->userdata('project_id'));
            /*
            |   Delete Project
            */
            $this->projects_model->delete_project($this->session->userdata('company'), $this->session->userdata('project_id'));
            $this->session->set_flashdata('error', $this->lang->line('project_delete_confirmed').$this->session->userdata('project_name'));
            /*
            |   Remove Session Variables relating to Project
            */
            $this->session->unset_userdata('url_list'); // Remove URL List
            $this->session->unset_userdata('project_name'); // Remove Project Name
            $this->session->unset_userdata('project_id'); // Remove Project ID
            $this->session->unset_userdata('side_active_project_list');
            /*
            |   Redirect to Main Page
            */

            redirect('/projects/', 'refresh');
        }
        else
        {
            /*
            |   Invalid Password Entered for Deletion
            */
            $this->session->set_flashdata('error', $this->lang->line('user_invalid_password'));
            redirect('projects/project_delete');
        }
    }
    function inactive()
    {
        /*
        |   Update Session Variables to Toggle Active / Inactive Project Listing
        */
        if ($this->session->userdata('view_inactive_projects') == 0)
        {
            $this->session->set_userdata('view_inactive_projects', 1);
        }
        else
        {
            $this->session->set_userdata('view_inactive_projects', 0);
        }
        $prevurl = str_replace(site_url(), '', $_SERVER['HTTP_REFERER']);
        redirect($prevurl);
    }
    function update()
    {
        /*
        |   Validation Project Update Inputs
        */
        if ($this->form_validation->run('projects') == true)
        {
            /*
            |   Prepare Variables for Database Update
            */
            $project_info['name'] = $this->input->post('project_name');
            $project_info['description'] = $this->input->post('project_description');
            $project_info['status'] = $this->input->post('project_status');
            $project_info['client'] = $this->input->post('project_clientlist');
            $project_id = (int)$this->input->post('projectUnq');
            /*
            |   Update Project Details in Database
            */
            $this->projects_model->update_project($this->session->userdata('company'), $project_id, $project_info);
            $this->session->set_flashdata('success', $this->lang->line('project_msg_updated'));
            redirect('projects');
        }
        else
        {
            /*
            |   Failed Initial Validation
            */
            $this->edit($this->input->post('projectUnq'));
        }
    }
    function activate($id)
    {
        /*
        |   Validate User is Authorised to Access Project
        */
        $project_id = (int)$id;
        $authorised = $this->projects_model->check_authorisation($this->session->userdata('company'), $project_id);
        if ($authorised)
        {
            /*
            |   Get Project Details
            */
            $project_info = $this->projects_model->get_project($this->session->userdata('company'), $project_id);
            /*
            |   Setup Project Session Variables
            */
            $newdata = array('project_name' => $project_info->name, 'project_id' => $project_id);
            $this->session->set_userdata($newdata);
            /*
            |   Update User Details to Last Project Accessed
            */
            $this->load->model('users_model');
            $user_data['lastproject'] = $project_id;
            $project_info = $this->users_model->update_user($this->session->userdata('company'), $this->session->userdata('user_id'), $user_data);
            /*
            |   Redirect to Tasks Page
            */
            $this->session->set_flashdata('success', $this->lang->line('project_switch_confirmed').$this->session->userdata('project_name'));
            redirect('tasks');
        }
        else
        {
            /*
            |   Invalid Access to Project
            */
            $this->session->set_flashdata('error', $this->lang->line('project_switch_denied'));
            redirect('projects');
        }
    }
    function edit($id)
    {
        /*
        |   Get Project Details
        */
        $project_id = (int)$id;
        $data['project_details'] = $this->projects_model->get_project($this->session->userdata('company'), $project_id);
        /*
        |   Get Client Details
        */
        $this->load->model('clients_model');
        $data['clients'] = $this->clients_model->get_client_options($this->session->userdata('company'));
        /*
        |   Display Edit Project Form
        */
        $data['page_heading'] = $this->lang->line('project_edit');
        $this->load->view('projects/form.php', $data);
    }
    function project_summaries()
    {
        /*
        |   Get Active Project Details
        */
        $data['active_project_details'] = $this->projects_model->get_selected_projects($this->session->userdata('company'), 'NOT IN', $this->config->item('project_closed_status'));
        /*
        |   Get Inactive Project Details
        */
        $data['inactive_project_details'] = array();
        if ($this->session->userdata('view_inactive_projects') == 1)
        {
            $data['inactive_project_details'] = $this->projects_model->get_selected_projects($this->session->userdata('company'), 'IN', $this->config->item('project_closed_status'));
        }
        /*
        |   Merge Active and Inactive Project Lists
        */
        if ((count($data['active_project_details']) > 0) & (count($data['inactive_project_details']) > 0))
        {
            $data['project_list'] = array_merge($data['active_project_details'], $data['inactive_project_details']);
        } elseif (count($data['active_project_details']) > 0)
        {
            $data['project_list'] = $data['active_project_details'];
        } elseif (count($data['inactive_project_details']) > 0)
        {
            $data['project_list'] = $data['inactive_project_details'];
        }
        else
        {
            $data['project_list'] = array();
        }
        $countid = 0;
        if (count($data['project_list']) > 0)
        {
            foreach ($data['project_list'] as $project)
            {
                if ($countid == 2) $countid = 0;
                /*
                |   Count Tasks Outstanding
                */
                $data['total_tasks'] = $this->tasks_model->count_tasks($this->session->userdata('company'), $project["unq"]);
                /*
                |   Count Invoices Outstanding
                */
                $data['totalinvoices'] = $this->invoices_model->count_invoices($this->session->userdata('company'), $project["unq"]);
                /*
                |   Count Total Time Tracked
                */
                $data['totaltime'] = $this->timetrack_model->count_times($this->session->userdata('company'), $project["unq"]);
                /*
                |   Set Data Variabes for Output
                */
                $data['projectid'] = $project['unq'];
                $data['projectname'] = $project['name'];
                $data['projectstatus'] = $project['status'];
                $data['countid'] = $countid;
                /*
                |   Display Summary
                */
                $this->load->view('projects/summary', $data);
                $countid++;
            }
        }
        else
        {
            /*
            |   No Project Exist
            */
            $this->load->view('projects/no_projects');
        }
    }
}
/* End of file */
