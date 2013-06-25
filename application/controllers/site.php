<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {
	public function index()
	{
		if($this->session->userdata('vpscp')) {
			redirect('site/main');
		} else {
			redirect('site/login');
		}
	}
	public function login() {
		if($this->session->userdata('vpscp')) {
			redirect('site/main');
		}
		$this->load->library('form_validation');
		$this->load->view('login');
	}
	public function login_validate() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean|callback_validate_credentials');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean|md5');
		if($this->form_validation->run() == FALSE) {
			echo 'error';
		} else {
			$data = array('vpscp' => '1', 'user' => $this->input->post('username'));
			$this->session->set_userdata($data);
			echo 'valid';
		}
	}
	public function validate_credentials()
	{
		$this->load->model('model_users');
		if($this->model_users->can_login()) {
			return true;
		} else { $this->form_validation->set_message('validate_credentials', 'Username or password was invalid.'); return false; }
	}
	public function logout()
	{
		$this->session->sess_destroy();
		echo 'logged out';
	}
	public function main() {
		if($this->session->userdata('vpscp')) {
			$this->load->model('Vpscp_mod_vlist');
			$this->load->view('main');
		} else {
			redirect('site/login');
		}
	}
	public function vserver_validate() {
		$this->load->model('Vpscp_mod_vlist');
		$this->Vpscp_mod_vlist->vserver();
	}
	public function vserver_update() {
		$this->load->model('Vpscp_mod_vlist');
		$this->Vpscp_mod_vlist->vupdate();
	}
	public function pages() {
		$this->load->model('Vpscp_mod_vlist');
		$this->Vpscp_mod_vlist->pages();
	}
	public function security_error() {
		$this->load->model('Vpscp_mod_vlist');
		$this->Vpscp_mod_vlist->security_trigger();
	}
	public function loadadminoptions() {
		$this->load->model('Vpscp_mod_vlist');
		$this->Vpscp_mod_vlist->adminz();
	}
	public function switchadminoptions() {
		$this->load->model('Vpscp_mod_vlist');
		$this->Vpscp_mod_vlist->adminz_options();
	}
}