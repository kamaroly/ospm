<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Validation Class
 *
 * Extends Validation library
 *
 * Adds one validation rule, "unique" and accepts a
 * parameter, the name of the table and column that
 * you are checking, specified in the forum table.column
 */
class MY_Form_validation extends CI_Form_validation {

	function My_Form_validation($rules = array())
	{
	parent::CI_Form_validation($rules);
	}

	// --------------------------------------------------------------------

	/**
	 * Unique
	 *
	 * @access    public
	 * @param    string
	 * @param    field
	 * @return    bool
	 */
	function unique($str, $field)
	{
		$CI =& get_instance();
		list($table, $column) = preg_split("#\.#", $field, 2);
		$CI->form_validation->set_message('unique', 'An account already exists with this email address.');
		$db_table=$this->CI->db->dbprefix($table);
		if($this->CI->session->userdata('active_id')<>"")
		{
		$active_id=$this->CI->session->userdata('active_id');
		$query = $CI->db->query("SELECT COUNT(*) dupe FROM $db_table WHERE $column = '$str' AND unq<>'$active_id'");
		$this->CI->session->unset_userdata('active_id');
		}
		else
		{
		$query = $CI->db->query("SELECT COUNT(*) dupe FROM $db_table WHERE $column = '$str'");			
		}
		//echo $CI->db->last_query();
		$row = $query->row();
		return ($row->dupe > 0) ? FALSE : TRUE;
	}
	function format($str,$format)
	{
		$CI =& get_instance();

		if($format=="time") 
		{
			$errorformat="hh:mm";
			$format="^[0-9]{1,2}:[0-9][0-9]$";
		}
		
		if($format=="date") 
		{	
			
			if($this->CI->session->userdata('dateformat')=="uk")
			{
				$errorformat="dd/mm/yy";
				$format="[0-3][0-9]/[0-1][0-9]/[0-9][0-9]";
			}
			else
			{
				$errorformat="mm/dd/yy";
				$format="[0-1][0-9]/[0-3][0-9]/[0-9][0-9]"; 
			}
		}
		$CI->form_validation->set_message('format', '%s must be in the format '.$errorformat);
		if(preg_match("#".$format."#",$str))
		return $str;
		else
		return FALSE;	
	}
}
?>