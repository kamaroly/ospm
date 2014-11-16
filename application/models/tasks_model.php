<?php
class Tasks_model extends Model
{
    function Tasks_model()
    {
        // call the Model constructor
        parent::Model();
    }
    function get_task($company_id, $project_id, $task_id)
    {
        $this->db->where('unq', $task_id);
        $this->db->where('project', $project_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $query = $this->db->get('tasks');
        if ($query->num_rows() > 0) return $query->result_array();
    }
    function get_all_tasks($company_id, $project_id, $filters)
    {
        foreach ($filters as $rowname => $filter)
        {
            if ($filter != 'viewall')
            {
                if ($filter == 'viewactive')
                {
                    $closed = "(";
                    $total_st = count($this->config->item('closed_status'));
                    $count = 1;
                    foreach ($this->config->item('closed_status') as $closed_status)
                    {
                        $closed .= "'$closed_status'";
                        if ($count < $total_st)
                        {
                            $closed .= ",";
                            $count++;
                        }
                    }
                    $closed .= ")";
                    $this->db->where("status NOT IN $closed");
                    $this->db->where('closed', 0);
                }
                else
                {
                    $this->db->where($rowname, $filter);
                }
            }
        }
        if($project_id!='ALL')
        {
        $this->db->where('project', $project_id);
        }
        $this->db->where('company', $company_id);
        $this->db->orderby('priority', 'DESC');
        $query = $this->db->get('tasks');
        if ($query->num_rows() > 0)
        {
            // return result set as an associative array
            return $query->result_array();
        }
    }
    function update_task($company_id, $task_id, $task_data)
    {
        $this->db->where('unq', $task_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $this->db->update('tasks', $task_data);
        if ($this->db->affected_rows() == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function insert_task($task_data)
    {
        $this->db->insert('tasks', $task_data);
		return $this->db->insert_id();
    }
    function delete_task($company_id, $task_id)
    {
        $this->db->where('unq', $task_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $this->db->delete('tasks');
        if ($this->db->affected_rows() == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function delete_all_tasks($company_id, $project_id)
    {
        // Delete Tasks
        $this->db->where('company', $company_id);
        $this->db->where('project', $project_id);
        $this->db->delete('tasks');
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    function get_categories($company_id, $project_id)
    {
        $this->db->select('category');
        $this->db->distinct();
        $this->db->where_not_in('category', '');
        $this->db->where('project', $project_id);
        $this->db->where('company', $company_id);
        $this->db->order_by('category');
        $query = $this->db->get('tasks');
        $category[''] = '';
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                $category[str_replace(' ', '_', $row->category)] = $row->category;
            }
        }
        // return result set as an associative array
        return $category;
    }
    function count_tasks($company_id, $project_id = "*")
    {
        if ($project_id != "*")
        {
            $this->db->where('project', $project_id);
        }
        $this->db->select('count("unq") as tasks');
        $this->db->where('company', $company_id);
        $this->db->where('closed', '0000-00-00 00:00:00');
        $query = $this->db->get('tasks');
        foreach ($query->result() as $row)
        {
            $total_tasks = $row->tasks;
        }
        return $total_tasks;
    }
    function group_tasks($company_id, $project_id)
    {
        if ($project_id != "*")
        {
            $this->db->where('project', $project_id);
        }
        $this->db->select('status,type,priority,count(unq) as total');
        $this->db->where('company', $company_id);
        //$this->db->where('closed', '0000-00-00 00:00:00');
        $this->db->group_by("status,type,priority");
        $query = $this->db->get('tasks');
        return $query->result_array();
    }
}
/* End of file */
