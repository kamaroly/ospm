<?php
class timetrack_model extends Model {
    function timetrack_model()
    {
        // call the Model constructor
        parent::Model();
    }
    function get_all_times($company_id, $project_id,$filters)
    {
		$this->db->where('company', $company_id);
		$this->db->where('project', $project_id);
		$this->db->orderby('startdate','DESC');
		foreach($filters as $rowname=>$filter)
		{
			if($filter!='viewall')
			{
				$this->db->where($rowname,$filter);
			}
		}
		$query = $this->db->get('timetrack');
		if ($query->num_rows() > 0) {
            // return result set as an associative array
            return $query->result_array();
        }
    }
    function insert_timetrack($timetrackData)
    {
        $this->db->insert('timetrack', $timetrackData);
		return $this->db->insert_id();
    }
    function get_timetrack($company_id, $timer_id)
    {
        $this->db->where('unq', $timer_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $query = $this->db->get('timetrack');
        if ($query->num_rows() > 0)
            return $query->result_array();
    }
    function update_timetrack($company_id, $timetrack_id, $timetrack_data)
    {
        $this->db->where('unq', $timetrack_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $this->db->update('timetrack', $timetrack_data);	
        if ($this->db->affected_rows() == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function delete_timetrack($company_id, $timetrack_id)
    {
        $this->db->where('unq', $timetrack_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $this->db->delete('timetrack');
		if ($this->db->affected_rows() == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function delete_all_timetracks($company_id,$project_id)
    {
        // Delete Timetrack
        $this->db->where('company', $company_id);
        $this->db->where('project', $project_id);
        $this->db->delete('timetrack');
        if ($this->db->affected_rows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }    	
    }
    function count_times($company_id,$project_id)
    {
    	if($project_id!="*")
    	{
    	$this->db->where('project', $project_id);	
    	}
    	$this->db->select('sum(time) as totaltime');
    	$this->db->where('company', $company_id);
		$query = $this->db->get('timetrack');
		foreach ($query->result() as $row)
		{
		    $total_time= $row->totaltime;
		}
		return $total_time; 	
    }
}
/* End of file */