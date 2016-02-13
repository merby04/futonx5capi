<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model 
{
	public function __construct()
	{
		parent::__construct();
	}

	public function login($user, $password)
	{
		$query = $this->db->select("member_name,member_email,member_id,member_phone,member_password")
		->from("member")
		->where("member_email", $user)
		->where("member_password", sha1($password))
		->get();
		if($query->num_rows() === 1)
		{
			return $query->row();
		}
		return false;
	}

	public function checkUser($email, $password)
	{
		$query = $this->db->limit(1)->get_where("member", array("member_email" => $email, "member_password" => $password));
		return $query->num_rows() === 1;
	}
}

/* End of file auth_model.php */
/* Location: ./application/models/auth_model.php */