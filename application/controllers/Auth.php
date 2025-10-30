<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function login() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if($this->form_validation->run() == TRUE) {
            $username = $this->input->post('username');
            $password = md5($this->input->post('password')); // atau sesuai enkripsi Anda
            
            $this->db->where('username', $username);
            $this->db->where('password', $password);
            $user = $this->db->get('users')->row();
            
            if($user) {
                // Set session
                $this->session->set_userdata([
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'level' => $user->level,
                    'logged_in' => TRUE
                ]);
                
                // REDIRECT KE ADMIN - INI YANG DIPERBAIKI
                redirect('admin/index');
            } else {
                $this->session->set_flashdata('error', 'Username atau password salah!');
                redirect('auth/login');
            }
        } else {
            $this->load->view('auth/login');
        }
    }
}
?>