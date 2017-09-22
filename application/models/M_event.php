<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class m_event extends CI_Model{
        
        public function tampil()
        {
             $this->db->select("*");
             $this->db->FROM("event");
             $this->db->join("jadwal","event.idJadwal=jadwal.idJadwal");
             $this->db->join("carfree","jadwal.idCarFree=carfree.idCarFree");
             return $this->db->get()->result();
        }

        public function tambah_admin(){
             $idAdmin  = $this->input->post('idAdmin');
             $namaAdmin = $this->input->post('nama');
             $email = $this->input->post('email');
             $password = $this->input->post('katasandi');
                     
              $object = array(
                        'idAdmin' => $idAdmin,
                        'namaAdmin' => $namaAdmin,
                        'email' => $email,
                        'password' => $password,
                       );

              return $this->db->insert('admin', $object);
        }

        public function getActivasi($idEvent){
            $this->db->select('*');
            $this->db->FROM('event');
            $this->db->join('jadwal','event.idJadwal=jadwal.idJadwal');
            $this->db->join('carfree','jadwal.idCarFree=carfree.idCarFree');
            $this->db->where('event.idEvent',$idEvent);
            return $this->db->get();
        }

        public function konfirmasi($idEvent, $data){
            $this->db->where('idEvent', $idEvent);
            $this->db->update('event', $data);
        }        

        public function hapus($idEvent){
            $this->db->where('idEvent', $idEvent);
            $this->db->delete('event');
        }

        function inputEvent() {
            //kode unik
            $q = $this->db->query("SELECT MAX(RIGHT(idEvent,2)) AS idmax FROM event");
            $kd = ""; //kode awal
            if($q->num_rows()>0){ //jika data ada
                foreach($q->result() as $k){
                    $tmp = ((int)$k->idmax)+1; //string kode diset ke integer dan ditambahkan 1 dari kode terakhir
                    $kd = sprintf("%02s",$tmp); //kode ambil 4 karakter terakhir
                }
            }else{ //jika data kosong diset ke kode awal
                $kd = "01";
            }
            $kar = "E"; //karakter depan kodenya
            //gabungkan string dengan kode yang telah dibuat tadi

            $nama       = $this->input->post('nama');
            $deskripsi  = $this->input->post('desk');

            //untuk id jadwal
            $tgl   = $this->input->post('tgl');
            $this->load->model('m_jadwal');
            $jadwal  = $this->m_jadwal->getJadwal($tgl);
            
            $this->load->library('upload');
            $config['upload_path'] = './assets/img/pamflet'; //path folder
            $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
            $config['file_name'] = $nama; //nama yang terupload nantinya
            $this->upload->initialize($config);
            if($_FILES['foto']['name'])
            {
                if ($this->upload->do_upload('foto'))
                {
                    $gbr = $this->upload->data();
                    $input = array(
                        'idEvent'     =>$kar.$kd,
                        'namaEvent'   =>$nama,
                        'deskripEvent'=>$deskripsi,
                        'pamflet'     =>$gbr['file_name'],
                        'status'      =>"Belum Aktif",
                        'idJadwal'    =>$jadwal->idJadwal,
                        'idAdmin'     =>''
                    );
                }
            }
                    $this->db->insert('event',$input);
        }

        function allEvent($id){
            $this->db->select('event.*,jadwal.*,carfree.*,panitia.idCarFree');
            $this->db->from('event');
            $this->db->join('jadwal','event.idJadwal=jadwal.idJadwal');
            $this->db->join("carfree","jadwal.idCarFree=carfree.idCarFree");
            $this->db->join("panitia","panitia.idCarFree=carfree.idCarFree");
            $this->db->where("panitia.idCarFree",$id);
            $this->db->order_by("jadwal.tgl","desc");
            $query = $this->db->get();
            return $query;
        }

        function selectEvent($tgl){
            error_reporting(E_ALL^(E_NOTICE | E_WARNING));
            $this->load->model('m_jadwal');
            $data  = $this->m_jadwal->getJadwal($tgl);
            $this->db->select('*');
            $this->db->from('event');
            $this->db->where('idJadwal', $data->idJadwal);
            $query = $this->db->get();
            return $query->num_rows();
        } 
        
        function eventKonf(){
            $this->db->select('*');
            $this->db->from('event');
            $this->db->join('jadwal','event.idJadwal=jadwal.idJadwal');
            $this->db->join("carfree","jadwal.idCarFree=carfree.idCarFree");
            $this->db->where('event.status', 'Aktif');
             $this->db->order_by("jadwal.tgl","desc");
            $query = $this->db->get();
            return $query;
        }

        function eventGagal(){
            $this->db->select('*');
            $this->db->from('event');
            $this->db->where('status', 'Belum Aktif');
            $query = $this->db->get();
            return $query->num_rows();
        }

        function editEvent($idEvent) {
            
            $nama       = $this->input->post('nama');
            $deskripsi  = $this->input->post('desk');

            $tgl   = $this->input->post('tgl');
            $this->load->model('m_jadwal');
            $jadwal  = $this->m_jadwal->getJadwal($tgl);
            
            $this->load->model('m_event');
            $foto  = $this->m_event->getActivasi($idEvent)->row();
            
            $this->load->library('upload');
            $config['upload_path'] = './assets/img/pamflet'; //path folder
            $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp'; //type yang dapat diakses bisa anda sesuaikan
            $config['file_name'] = $nama; //nama yang terupload nantinya
            $this->upload->initialize($config);
            
            if($_FILES['foto']['name'])
            {
                if ($this->upload->do_upload('foto'))
                {
                    unlink("./assets/img/pamflet/".$foto->pamflet);
                    $gbr = $this->upload->data();
                    $input = array(
                        'namaEvent'   =>$nama,
                        'deskripEvent'=>$deskripsi,
                        'pamflet'     =>$gbr['file_name'],
                        'idJadwal'    =>$jadwal->idJadwal
                    );
                }
            }else{
                    rename("./assets/img/pamflet/".$foto->pamflet,"./assets/img/pamflet/".$config['file_name'].'.jpg');
                    $input = array(
                        'namaEvent'   =>$nama,
                        'deskripEvent'=>$deskripsi,
                        'pamflet'     =>$config['file_name'].'.jpg',
                        'idJadwal'    =>$jadwal->idJadwal
                    );
                }
                    
          $this->db->set($input);
          $this->db->where('idEvent', $idEvent);
          $this->db->update('event');
        }

}