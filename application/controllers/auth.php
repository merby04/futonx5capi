<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function login()
	{				

			$postdata = file_get_contents("php://input");	
			$request = json_decode($postdata);	
			if(!$request->username || !$request->password)
			{
				echo json_encode(array("code" => 2, "response" => "Data tidak boleh kosong"));
			}
			$username = $request->username;
			$password = $request->password;
			$this->load->model("auth_model");
			$user = $this->auth_model->login($username, $password);
			if($user !== false)
			{
				//ha hecho login
				$user->iat = time();
				$user->exp = time() + 20000;
				$jwt = JWT::encode($user, '');
				echo json_encode(
					array(
						"code" => 0, 
						"response" => array(
							"token" => $jwt
						)
					)
				);
			}
	
			
	}
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */