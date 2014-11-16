<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class users_model extends Model
{
    function users_model()
    {
        // call the Model constructor
        parent::Model();
    }
    function check_login($username, $encrypted_password)
    {
        $this->db->where('email', $username);
        $this->db->where('password', $encrypted_password);
        $this->db->limit(1);
        $query = $this->db->get('people');
        return $query->row();
    }
    function delete_user($company_id, $user_id)
    {
        $this->db->where('unq', $user_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $this->db->delete('people');
        if ($this->db->affected_rows() == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function get_user($company_id, $user_id)
    {
        $this->db->where('unq', $user_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $query = $this->db->get('people');
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
    }
    function get_users($company_id)
    {
        $this->db->where('company', $company_id);
        $this->db->order_by('status');
        $this->db->order_by('fullname', 'ASC');
        $query = $this->db->get('people');
        if ($query->num_rows() > 0) return $query->result_array();
    }
    function get_people($company_id)
    {
        $this->db->where('company', $company_id);
        $this->db->order_by('status');
        $this->db->order_by('fullname', 'ASC');
        $query = $this->db->get('people');
        $people_list[''] = ''; // Create Blank User
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                $people_list[$row->unq] = $row->fullname;
            }
        }
        // return result set as an associative array
        return $people_list;
    }
    function insert_user($user_data)
    {
        $this->db->insert('people', $user_data);
        return $this->db->insert_id();
    }
    function reset_password($email, $encrypted_pass)
    {
        $data = array('password' => $encrypted_pass);
        $this->db->where('email', $email);
        $this->db->limit(1);
        $this->db->update('people', $data);
        return $this->db->affected_rows();
    }
    function update_user($company_id, $user_id, $user_data)
    {
        $this->db->where('unq', $user_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $this->db->update('people', $user_data);
        if ($this->db->affected_rows() == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function verify_password($user_id, $password)
    {
        $this->db->where('unq', $user_id);
        $this->db->where('password', $password);
        $this->db->limit(1);
        $query = $this->db->get('people');
        if ($query->num_rows() == 1)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
}
?>