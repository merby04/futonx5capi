<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Futsalarena extends CI_Controller {

	protected $headers;

	public function __construct()
	{
		parent::__construct();
		$this->headers = apache_request_headers();
	}

	public function index()
	{		

		if(!isset($this->headers["Authorization"]) || empty($this->headers["Authorization"]))
		{
			//mejorar la validación, pero si está aquí es que no tenemos el token
			echo "ewe";
		}
		else
		{
			$token = explode(" ", $this->headers["Authorization"]);
			$user = JWT::decode(trim($token[1],'"'));
			$this->load->model("auth_model");

			if($this->auth_model->checkUser($user->member_email, $user->member_password) !== false)
			{
				$this->load->model("futsalarena_model");
				$futsals = $this->futsalarena_model->get();
				$user->iat = time();
				$user->exp = time() + 300;
				$jwt = JWT::encode($user, '');
				echo json_encode(
					array(
						"code" => 0, 
						"response" => array(
							"token" => $jwt,
							"futsalarena"=> $futsals
						)
					)
				);
			}

		}
	}	

	public function details($futsalId)
	{		
		$this->load->model("futsalarena_model");
		$this->futsalarena_model->generateSchedule($futsalId);				
		if(!isset($this->headers["Authorization"]) || empty($this->headers["Authorization"]))
		{
			//mejorar la validación, pero si está aquí es que no tenemos el token
			echo "ewe";
		}
		else
		{
			$token = explode(" ", $this->headers["Authorization"]);
			$user = JWT::decode(trim($token[1],'"'));
			$this->load->model("auth_model");

			if($this->auth_model->checkUser($user->member_email, $user->member_password) !== false)
			{
		
				$futsals = $this->futsalarena_model->get_details($futsalId);				
				$lapangan = array("details"=>$futsals,"lapangan"=>$this->futsalarena_model->get_fields($futsalId));												
				$user->iat = time();
				$user->exp = time() + 300;
				$jwt = JWT::encode($user, '');
				echo json_encode(
					array(
						"code" => 0, 
						"response" => array(
							"token" => $jwt,
							"futsaldetails"=> $lapangan
						)
					)
				);
			}

		}
	}
	public function getSchedule()
	{				
		
		$postdata = file_get_contents("php://input");	
		$request = json_decode($postdata);				

		if(!isset($this->headers["Authorization"]) || empty($this->headers["Authorization"]))
		{
			//mejorar la validación, pero si está aquí es que no tenemos el token
			echo "ewe";
		}
		else
		{
			$token = explode(" ", $this->headers["Authorization"]);
			$user = JWT::decode(trim($token[1],'"'));
			$this->load->model("auth_model");

			if($this->auth_model->checkUser($user->member_email, $user->member_password) !== false)
			{		
				$this->load->model("futsalarena_model");
				$d = date('d');		
				$y = date('y');
				$m = date('m');
				$day = array("data"=>"0");
				if(strlen($m)<2)
				{
					$m = "0".$m;
				}			
				if(strlen($d)<2)
				{
					$d= "0".$d;
				}							
				$futid = strtolower($request->futid);
				$table = $futid.$y.$m;		
				$lapid = $request->lapid;								
				$jadwals = $this->futsalarena_model->get_jadwal($table,$lapid);			
				//$lapangan = array("details"=>$futsals,"lapangan"=>$this->futsalarena_model->get_fields($futsalId));												
				$query = array("jadwal"=>$jadwals);				
				$user->iat = time();
				$user->exp = time() + 300;
				$jwt = JWT::encode($user, '');
				echo json_encode(
					array(
						"code" => 0, 
						"response" => array(
							"token" => $jwt,
							"jadwals"=> $query
						)
					)
				);
			}

		}
	}
	public function getServertime()
	{
		$date = date("Y-m-d");
		$time = date("H:i:s");
		$config = array("timex"=>$time,"datex"=>$date);
		echo json_encode($config);
	}

}

/* End of file movies.php */
/* Location: ./application/controllers/movies.php */