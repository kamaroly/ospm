<?php
class clients_model extends Model
{
    function clients_model()
    {
        // call the Model constructor
        parent::Model();
    }
    function get_clients($company_id)
    {
        $this->db->where('company', $company_id);
        $query = $this->db->get('clients');
        if ($query->num_rows() > 0) return $query->result_array();
    }
    function get_one_client($company_id, $client_id)
    {
        $this->db->where('company', $company_id);
        $this->db->where('unq', $client_id);
        $this->db->limit(1);
        $query = $this->db->get('clients');
        if ($query->num_rows() > 0) return $query->result_array();
    }
    function get_client_options($company_id)
    {
        // Get Unq => Organisation name array
        $this->db->select('unq,organisationname');
        $this->db->where('company',$company_id);
        $this->db->order_by('organisationname'); 
        $query=$this->db->get('clients');
        $clients['']='';
        if($query->num_rows()>0)
        {
            foreach ($query->result() as $row)
            {
                $clients[$row->unq] = $row->organisationname;

            }

        }
        // return result set as an associative array
        return $clients;
    }
    function get_client_from_project($company_id, $project_id)
    {
        $sql = "SELECT a.* FROM ".$this->db->dbprefix('clients')." a,
		".$this->db->dbprefix('projects')." b 
		WHERE b.unq='".$project_id."' 
		AND a.company='".$company_id."' 
		AND b.client=a.unq LIMIT 1;";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
        {
            // return result set as an associative array
            return $query->result_array();
        }
    }
    function update_client($company_id, $client_id, $client_data)
    {
        $this->db->where('unq', $client_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $this->db->update('clients', $client_data);
        if ($this->db->affected_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function insert_client($client_data)
    {
        $this->db->insert('clients', $client_data);
        return $this->db->insert_id();
    }
    function delete_client($company_id, $client_id)
    {
        $this->db->where('unq', $client_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $this->db->delete('clients');
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
