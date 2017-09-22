<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class m_login extends CI_Model{
        
        public function getPanitia($email, $password){
            $this->db->select('*');
            $this->db->from('panitia');
            $this->db->where('emailPanitia', $email);
            $this->db->where('password', $password);
            $query = $this->db->get();
            return $query;
        }

        public function getAdmin($email, $password){
            $this->db->select('*');
            $this->db->from('admin');
            $this->db->where('emailAdmin', $email);
            $this->db->where('password', $password);
            $query = $this->db->get();
            return $query;
        }

        public function getEmailAdmin($email){
            $this->db->select('*');
            $this->db->from('admin');
            $this->db->where('emailAdmin', $email);
            $query = $this->db->get();
            return $query;
      
        }

        public function getEmailPanitia($email){
            $this->db->select('*');
            $this->db->from('panitia');
            $this->db->where('emailPanitia', $email);
            $query = $this->db->get();
            return $query;
        }

        public function ubahpasswordpanitia($email, $data){
            $this->db->where('emailPanitia', $email);
            $this->db->update('panitia', $data);
        }
        
        public function ubahpasswordadmin($email, $data){
            $this->db->where('emailAdmin', $email);
            $this->db->update('admin', $data);
        }
}