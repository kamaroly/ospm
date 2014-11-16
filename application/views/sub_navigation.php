<?php
echo '<ul id="sub_navigation">';
// Show Current Date
// Display Timetrack
$sub_navigation = array();
// Sidebar Navigation
if ($this->session->userdata('user_id') <> '')
{
    $active_section = $this->uri->segment(1);
    if ($active_section != "")
    {
        switch ($active_section)
        {
            case 'dashboard':
                if ($this->session->userdata('usertype') == "Administrator")
                {
                    $sub_navigation[] .= $this->lang->line('navigation_projects_new');
                    $sub_navigation[] .= $this->lang->line('navigation_users');
                    $sub_navigation[] .= $this->lang->line('navigation_clients');
                    $sub_navigation[] .= $this->lang->line('navigation_backup_database');
                }
                    $sub_navigation[] .= $this->lang->line('navigation_export_data');
                break;
            case 'tasks':
                $sub_navigation[] .= $this->lang->line('navigation_tasks');
                $sub_navigation[] .= $this->lang->line('navigation_tasks_new');
                break;
            case 'invoices':
                $sub_navigation[] .= $this->lang->line('navigation_invoices');
                $sub_navigation[] .= $this->lang->line('navigation_invoices_new');
                break;
            case 'timetrack':
                $sub_navigation[] .= $this->lang->line('navigation_timetrack');
                $sub_navigation[] .= $this->lang->line('navigation_timetrack_new');
                $sub_navigation[] .= $this->lang->line('navigation_timetrack_start');
                break;
            case 'clients':
                if ($this->session->userdata('usertype') == "Administrator")
                {
                    $sub_navigation[] .= $this->lang->line('navigation_clients');
                    $sub_navigation[] .= $this->lang->line('navigation_clients_new');
                }
                break;
            case 'projects':
                $sub_navigation[] .= $this->lang->line('navigation_projects');
                if ($this->session->userdata('usertype') == "Administrator")
                {
                    $sub_navigation[] .= $this->lang->line('navigation_projects_new');
                    $sub_navigation[] .= $this->lang->line('navigation_backup_database');
                }
                break;
            case 'users':
                if ($this->session->userdata('usertype') == "Administrator")
                {
                    $sub_navigation[] .= $this->lang->line('navigation_users');
                    $sub_navigation[] .= $this->lang->line('navigation_users_new');
                }
                break;
        }
    }
    $current_uri = $this->uri->uri_string();
    if (count($sub_navigation) > 0)
    {
        foreach ($sub_navigation as $navigation):
            if (preg_match("#<a href=\"".base_url().$current_uri."\"#", $navigation))
            {
                $navigation = preg_replace("#<a href=\"".base_url().$current_uri."\"#", "<a class=\"selected\" href=\"".base_url().$current_uri."\"", $navigation);
            }
            echo $navigation;
        endforeach;
        if (isset($next_action))
        {
            echo $next_action;
        }
    }
}
echo '</ul>';
/* End of file */
