<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Futsalarena_model extends CI_Model 
{
	public function get()
	{
		return $this->db->select("*")->from("futsal_arena")->get()->result();
	}
	public function get_details($futsalId)
	{		
		$query = $this->db->select("*")
		->from("futsal_arena")
		->where("futsal_id", $futsalId)		
		->get();
		if($query->num_rows() === 1)
		{
			return $query->row();
		}
		return false;
	}
	public function get_fields($futsalId)
	{		
		return $this->db->select("*")
		->from("futsal_arena_field")
		->where("futsal_id", $futsalId)		
		->get()->result();		
	}
	//generate jadwal

	public function generateSchedule($futsalId)
	{
		$y = date('y');
		$m = date('m');
		if(strlen($m)<2)
		{
			$m = "0".$m;
		}
		$futid = strtolower($futsalId);
		$futoncode = $futid.$y.$m;			
		$totalhari = cal_days_in_month(CAL_GREGORIAN, $m,$y);
		if(!$this->db->table_exists($futoncode))
			{
				$this->db->query("create table $futoncode like master_schedule"); 	
				$query = $this->db->query("select * from futsal_arena_field where futsal_id='$futsalId'");				
				if($query->num_rows()>0)
							{
								foreach ($query->result() as $row)
									{
										$field_id = $row->futsal_field_id;
										for($i=1;$i<=$totalhari;$i++)
										{
											if($i<10)
												{
													$i = "0".$i;
												}
											for($j=1;$j<24;$j++)
											{
												if($j<10)
												{
													$j = "0".$j;
												}
												$hour = $j.":00";
												$this->db->query("insert into $futoncode(futsal_field_id, day, time, team_id) values('$field_id','$i','$hour',null)"); 	
											}
										}
									}
							}

				
			}

	}
	public function get_jadwal($table,$lapid)
	{
		$y = date('y');
		$m = date('m');
		$d = date('d');		
		if(strlen($d)<2)
		{
			$d= "0".$d;
		}				
		$d7 = $d+6;

		$totalhari = cal_days_in_month(CAL_GREGORIAN, $m,$y);
		$query = $this->db->query("select * from $table where `day`>='$d' and `day`<='$d7' and futsal_field_id='$lapid'");				
		if($query->num_rows()>0)
			{
				return $query->result();
			}

				
		
	}

}

/* End of file movies_model.php */
/* Location: ./application/models/movies_model.php */