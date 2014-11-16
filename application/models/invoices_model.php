<?php
class invoices_model extends Model
{
    function invoices_model()
    {
        // call the Model constructor
        parent::Model();
    }
    function get_all_invoices($company_id, $project_id, $status_list = NULL)
    {
        if ($status_list != NULL)
        {
            $this->db->where_in('status', $status_list);
        }
        if($project_id!='ALL')
        {
        $this->db->where('project', $project_id);
        }
        $this->db->where('company', $company_id);
        $this->db->order_by('due_date','deSC');
        $query = $this->db->get('invoices');
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
    }
    function get_invoice($company_id, $project_id, $invoice_id)
    {
        $this->db->where('unq', $invoice_id);
        $this->db->where('project', $project_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $this->db->order_by('unq', 'DESC'); 
        $query = $this->db->get('invoices');
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
    }
    function get_work_item($company_id, $project_id, $work_item_id)
    {
        $this->db->where('unq', $work_item_id);
        $this->db->where('project', $project_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $this->db->order_by('unq', 'DESC');
        $query = $this->db->get('billables');
        if ($query->num_rows() > 0) return $query->result_array();
    }
    function get_total_invoice_amount($company_id, $project_id, $invoice_id)
    {
        $invoice_total_due = 0;
        // Get Billables
        $bills = $this->get_billables($company_id, $project_id, $invoice_id);
        // Loop Through Billables to Get Tracked Times from Tasks
        if (count($bills) > 0)
        {
            foreach ($bills as $bill):
                $invoice_total_due = $invoice_total_due + $bill['flatfee'];
                $task_time = $this->get_actual_times($company_id, $project_id, $bill['unq']);
                $invoice_total_due = $invoice_total_due + ($task_time / 60) * $bill['hourlyrate'];
            endforeach;
        }
        return $invoice_total_due;
    }
    function get_billables($company_id, $project_id, $invoice_id)
    {
        $this->db->where('invoice_id', $invoice_id);
        if($project_id!='ALL')
        {
        $this->db->where('project', $project_id);
        }
        $this->db->where('company', $company_id);
        $this->db->order_by('unq', 'ASC');
        $query = $this->db->get('billables');
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
    }
    function get_active_billables($company_id, $project_id, $status_list)
    {
        if($company_id=="")
        {
            return NULL;
        }
        $sql = "SELECT bill.unq,bill.description AS name FROM ".$this->db->dbprefix('billables')." bill,".$this->db->dbprefix('invoices').
            " inv WHERE inv.unq=bill.invoice_id AND inv.status IN ('".implode("','", $status_list)."') AND inv.company=".$company_id.
            " AND inv.project=".$project_id.";";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
    }
    function get_actual_times($company_id, $project_id, $work_item_id)
    {
        $this->db->select('sum(actual) as total_time');
        $this->db->where('invoice', $work_item_id);
        $this->db->where('company', $company_id);
        $this->db->where('project', $project_id);
        $query = $this->db->get('tasks');
        if ($query->num_rows() > 0)
        {
            $row = $query->row_array();
            return $row['total_time'];
        }
    }
    function get_associated_tasks($company_id, $project_id, $work_item_id)
    {
        $this->db->where('invoice', $work_item_id);
        $this->db->where('company', $company_id);
        $this->db->where('project', $project_id);
        $query = $this->db->get('tasks');
        if ($query->num_rows() > 0)
        {
            // return result set as an associative array
            return $query->result_array();
        }
    }
    function update_invoice($company_id, $invoice_id, $invoice_data)
    {
        $this->db->where('unq', $invoice_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $this->db->update('invoices', $invoice_data);
        if ($this->db->affected_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function update_work_item($company_id, $work_item_id, $work_item_data)
    {
        $this->db->where('unq', $work_item_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $this->db->update('billables', $work_item_data);
        if ($this->db->affected_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function insert_invoice($invoice_info)
    {
        $this->db->insert('invoices', $invoice_info);
        
		return $this->db->insert_id();
    }
    function insert_work_item($work_item_info)
    {
        $this->db->insert('billables', $work_item_info);
        
		return $this->db->insert_id();
    }
    function delete_invoice($company_id, $invoice_id)
    {
        $this->db->where('unq', $invoice_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        
		$this->db->delete('invoices');
        if ($this->db->affected_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function delete_work_item($company_id, $work_item_id)
    {
        $this->db->where('unq', $work_item_id);
        $this->db->where('company', $company_id);
        $this->db->limit(1);
        $this->db->delete('billables');

        if ($this->db->affected_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function count_invoices($company_id, $project_id = '*')
    {
        if ($project_id != "*")
        {
            $this->db->where('project', $project_id);
        }
        $this->db->select('count("unq") as invoices');
        $this->db->where('company', $company_id);
        // Due Date Not in Future
        $this->db->where('DATE_SUB(CURDATE(),INTERVAL 0 DAY) >= actiondate');
        $duetypes = $this->config->item('invoice_due_status_types');
        $orwhere = "(";
        $tot = count($duetypes);
        $count = 1;
        foreach ($duetypes as $due)
        {
            $orwhere .= 'status="'.$due.'"';
            if ($tot > $count) $orwhere .= ' OR ';
            $count++;
        }
        $orwhere .= ")";
        $this->db->where($orwhere);
        $query = $this->db->get('invoices');
        foreach ($query->result() as $row)
        {
            $total_invoices = $row->invoices;
        }
        return $total_invoices;
    }
    function delete_all_invoices($company_id,$project_id)
    {
    	        // Delete Invoices
        $this->db->where('company', $company_id);
        $this->db->where('project', $project_id);
        $this->db->delete('invoices');
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