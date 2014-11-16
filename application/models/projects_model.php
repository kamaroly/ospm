<?php
class projects_model extends Model
{
    function projects_model()
    {
        // call the Model constructor
        parent::Model();
    }
    function get_all_projects($company_id)
    {
        $this->db->where('company', $company_id);
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('projects');
        if ($query->num_rows() > 0) return $query->result_array();        
    }
    function get_project($company_id, $project_id)
    {
        $this->db->where('unq', $project_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $query = $this->db->get('projects');
        if ($query->num_rows() > 0) return $query->row();
    }
    function get_selected_projects($company_id, $identifier = NULL, $status_list = NULL)
    {
        if ($status_list != NULL)
        {
            if ($identifier == 'NOT IN')
            {
                $this->db->where_not_in('status', $status_list);
            }
            else
            {
                $this->db->where_in('status', $status_list);
            }
        }
        $this->db->where('company', $company_id);
        $this->db->order_by('name');
        $query = $this->db->get('projects');
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
    }
    function check_authorisation($company_id, $project_id)
    {
        $this->db->where('unq', $project_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $query = $this->db->get('projects');
        if ($this->db->affected_rows() == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function update_project($company_id, $project_id, $project_data)
    {
        $this->db->where('unq', $project_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $this->db->update('projects', $project_data);
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    function insert_project($project_data)
    {
        $this->db->insert('projects', $project_data);
        return $this->db->insert_id();
    }
    function delete_project($company_id, $project_id)
    {
        // Delete Project
        $this->db->where('company', $company_id);
        $this->db->where('unq', $project_id);
        $query = $this->db->delete('projects');
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }   
}
/* End of file */