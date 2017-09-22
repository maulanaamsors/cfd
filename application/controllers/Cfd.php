<?php 
    class cfd extends CI_Controller{
        
        public function __construct(){
            parent::__construct();
            $this->load->model('M_admin');
            $this->load->model('M_panitia');
            $this->load->model('M_cfd');
            $this->load->library(array('form_validation','session'));
            $this->load->database();
            $this->load->helper('url');
        }

        //cek
        public function kelolacfd(){
            $data['nama_admin']    = $this->session->userdata('nama_admin');      
            
            if ($data['nama_admin'] != NULL) {
                $data['datacfd'] = $this->M_cfd->tampil()->result();
                $data['isi'] = 'admin/kelolacfd';

                $this->load->view('admin/home',$data);
            }
            else{
                redirect('login');
            }
        }

        //cek
        public function tambah_cfd(){
            $data['nama_admin']    = $this->session->userdata('nama_admin');      
            
            if ($data['nama_admin'] != NULL) {
                if ($this->input->post('simpan')) {
                         $this->form_validation->set_rules('namaCFD', 'Nama CFD', 'required|trim');
                         $this->form_validation->set_rules('lat', 'Latitude', 'required|trim');
                         $this->form_validation->set_rules('lng', 'Longitude', 'required|trim');
                         $this->form_validation->set_rules('deskrip', 'Deskripsi', 'required|trim');

                         if ($this->form_validation->run() === FALSE) {
                            $kode = 'carfree';  
                            $data['kodeunik'] = $this->M_cfd->getkodeunik($kode);

                            $data['isi'] = 'admin/formtambahcfd';
                            $this->load->view('admin/home',$data);
                         }
                         else {
                             $query = $this->M_cfd->tambah_cfd();

                             if ($query) {
                                $this->session->set_flashdata('info', 'Data berhasil ditambah');
                                redirect('admin/kelolacfd');
                             }
                         } 
                } 
                else {
                    $kode = 'carfree';  
                    $data['kodeunik'] = $this->M_cfd->getkodeunik($kode);

                    $data['isi'] = 'admin/formtambahcfd';
                    $this->load->view('admin/home',$data);
                }
            }
            else{
                redirect('login');
            }
           
        }

        public function ubah($idCarFree){
            $data['nama_admin']    = $this->session->userdata('nama_admin');      
            
            if ($data['nama_admin'] != NULL) {
                if ($this->input->post('simpan')) {
                    $data1['namaCFD'] = $this->input->post('namaCFD');
                    $data1['lat'] = $this->input->post('lat');
                    $data1['lng'] = $this->input->post('lng');
                    $data1['deskrip'] = $this->input->post('deskrip');

                    $idCarFree = $this->input->post('idCarFree');
                    $this->M_cfd->ubah_cfd($idCarFree, $data1);

                    $this->session->set_flashdata('info', 'Data berhasil diubah');
                    redirect('admin/kelolacfd');
                }
                else {
                    $data['carfreeday'] = $this->M_cfd->select_by_id($idCarFree)->row();

                    $data['isi'] = 'admin/formubahcfd';
                    $this->load->view('admin/home',$data);
                } 
            }
            else{
                redirect('login');
            }   
        }

        public function tampil($idCarFree){
            $data['nama_admin']    = $this->session->userdata('nama_admin');      
            
            if ($data['nama_admin'] != NULL) {
                    $data['carfreeday'] = $this->M_cfd->select_by_id($idCarFree)->row();

                    $data['isi'] = 'admin/tampilcfd';
                    $this->load->view('admin/home',$data);
        
            }
            else{
                redirect('login');
            }   
        }

        public function hapus($idCarFree){
            $this->M_cfd->hapus_cfd($idCarFree);

            $this->session->set_flashdata('info', 'Data CFD Dihapus!');
            redirect(base_url('admin/kelolacfd'));
        }


    }

?>