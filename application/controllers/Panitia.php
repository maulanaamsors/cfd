<?php 
    class panitia extends CI_Controller{
        
        public function __construct(){
            parent::__construct();
            $this->load->model(array('m_admin','m_panitia','m_cfd','m_jadwal','m_event'));
            $this->load->library(array('form_validation','session'));
            $this->load->database();
            $this->load->helper('url');
        }

        public function index()
        {
            $this->data['panitia'] = $this->m_panitia->data_panitia();
            $this->load->view('panitia/header');
            $this->load->view('panitia/index',$this->data);
            $this->load->view('panitia/footer');
        }

        public function home()
        {
            $email    = $this->session->userdata('email_panitia');
            
            if ($email==NULL) {
                redirect('login');             
            }else{
                $this->data['allpanitia'] = $this->m_panitia->data_panitia();
                $tgl = date('Y-m-d', strtotime('sunday'));

                $email = $this->session->userdata('email_panitia');     
                $id = $this->session->userdata('id');     
                $this->data['panitia'] = $this->m_panitia->getEmailPanitia($email)->row();
                $this->data['selectEvent'] = $this->m_event->selectEvent($tgl);
                $this->data['allEvent'] = $this->m_event->allEvent($id);
                $this->data['eventKonf'] = $this->m_event->eventKonf();
                $this->data['eventGagal'] = $this->m_event->eventGagal();
                
                $this->load->view('panitia/header');
                $this->load->view('panitia/home',$this->data);
                $this->load->view('panitia/footer');
            }
        }

        public function pengaturan()
        {
            $email = $this->session->userdata('email_panitia');     
            if ($email==NULL) {
                redirect('login');             
            }else{
                $no_ktp = $this->input->post('noKtp');     
                $sandi_lama = $this->input->post('sandi_lama');     
                $sandi_baru = $this->input->post('sandi_baru');     
                $sandi_konf = $this->input->post('sandi_konf');

                $data = $this->m_panitia->getEmailPanitia($email);
                foreach ($data->result() as $c) {
                    
                    if($_POST==NULL){
                        $this->data['panitia'] = $this->m_panitia->getEmailPanitia($email)->row();
                        $this->load->view('panitia/header');
                        $this->load->view('panitia/pengaturan',$this->data);
                        $this->load->view('panitia/footer');
                
                    }elseif($_FILES['foto']['name']!=NULL){
                        unlink("./assets/img/panitia/".$c->foto);
                        $this->m_panitia->pengaturan($email);
                        redirect('panitia/pengaturan');
                    
                    }elseif($_FILES['foto']['name']==NULL && $sandi_lama==NULL){
                        $this->m_panitia->pengaturan($email);
                        redirect('panitia/pengaturan');
                    
                    }elseif($_FILES['foto']['name']==NULL && $sandi_lama!=NULL){
                        if ($c->password==md5($sandi_lama)) {
                              if ($sandi_baru == $sandi_konf) {
                                 $this->m_panitia->pengaturan($email);
                                 redirect('panitia/logout');
                              }else{
                                 echo "password baru dengan konfirmasi password salah"; 
                              }
                                    
                        }else{
                            echo "password lama salah";
                        }
                    }
                }//end foreach
            }
        }

        public function profil()
        {   
            $email    = $this->session->userdata('email_panitia');
            if ($email==NULL) {
                redirect('login');             
            }elseif($this->session->userdata('ktp')!=NULL){
                redirect('panitia/home');
            }else{            
                if($_POST==NULL){
                $this->data['panitia'] = $this->m_panitia->getEmailPanitia($email)->row();
                $this->load->view('panitia/header');
                $this->load->view('panitia/profil',$this->data);
                $this->load->view('panitia/footer');
                }elseif($_POST['pass'] == $_POST['konf_pass'])
                {
                    $this->m_panitia->update($email);
                    redirect('panitia/home');
                }else{
                    echo "password beda";
                }
            }
        }
        
        public function daftar()
        {
            $email    = $this->session->userdata('email_panitia');
            if ($email==NULL) {
                redirect('login');             
            }else{
                if($_POST==NULL){
                $this->data['panitia'] = $this->m_panitia->getEmailPanitia($email)->row();
                $this->load->view('panitia/header');
                $this->load->view('panitia/daftar',$this->data);
                $this->load->view('panitia/footer');
                }else{
                    $tanggal = $this->input->post('tgl');
                    $data = $this->m_jadwal->getJadwal($tanggal);
                    if ($data->tgl==$tanggal) {
                         $this->m_event->inputEvent();
                    }else{
                         $this->m_jadwal->inputJadwal();
                         $this->m_event->inputEvent();
                    }
                    redirect('panitia/home');
                }
            }
        }

        public function cek()
        {
            $this->load->view('panitia/cek');
        }

        function logout()
        {
            $this->session->sess_destroy();         
            redirect('login','refresh');
        }


        public function kelolapanitia(){
            $data['nama_admin']    = $this->session->userdata('nama_admin');      
            
            if ($data['nama_admin'] != NULL) {
                $data['data'] = $this->m_panitia->tampil()->result_object();

                $data['isi'] = 'admin/kelolapanitia';
                $this->load->view('admin/home',$data);
            }
            else{
                redirect('login');
            }
        }

        public function form_tambahpanitia(){
            $data['nama_admin']    = $this->session->userdata('nama_admin');      
            
            if ($data['nama_admin'] != NULL) {
                $data['data'] = $this->m_cfd->tampil()->result_object(); 
                $data['dataadmin'] = $this->m_admin->tampil()->result_object();
                 
                $data['isi'] = 'admin/formtambahpanitia';
                $this->load->view('admin/home',$data);
            }
            else{
                redirect('login');
            }
        }

        public function sendMail() {
            $receiver_email = $this->input->post('email');
            $cekPanitia   = $this->m_panitia->getData_Panitia($receiver_email);

            if ($cekPanitia->num_rows() == 1 ) {
                    $this->session->set_flashdata('info', 'Email Sudah Ada');
                    redirect('admin/kelolapanitia/tambah');
                    break;
            }

                //password anyar
                $pass="129FAasdsk25kwBjakjDlff"; 
                $panjang='8'; 
                $len=strlen($pass);
                $start=$len-$panjang; 
                $xx=rand('0',$start);
                $yy=str_shuffle($pass);
                $passwordbaru=substr($yy, $xx, $panjang);
                
                // Configure email library
               $config = Array(  
                'protocol'  => 'smtp',  
                'smtp_host' => 'ssl://smtp.googlemail.com',  
                'smtp_port' => 465,  
                'smtp_user' => 'tugaskampusunikom@gmail.com',   
                'smtp_pass' => 'fucktugas',   
                'mailtype'  => 'html',   
                'charset'   => 'iso-8859-1'  
               ); 

                // Load email library and passing configured values to email library 
                $this->load->library('email', $config);
                $this->email->set_newline("\r\n");
                
                // Sender email address
                $this->email->from('tugaskampusunikom@gmail.com', 'CFD Kota Bandung');
                // Receiver email address
                $this->email->to($receiver_email);
                // Subject of email
                $this->email->subject('Password Baru');
                // Message in email
                $this->email->message(
                    "<html>
                        <style>
                        
                        .header{
                            border-radius:3px;
                            padding: 6px;
                            text-align:center;
                            color: #fff;
                        }

                        .doff{
                            color:#555;
                        }
                        </style>
                        <body>
                        
                            <div class=\"header\">
                                <h3>Developer Car Free Day<br><small>Kota Bandung</small></h3>
                            </div><br>
                            Kami telah mengatur ulang password Anda, Berikut Data beserta password baru Anda :<br><br>
                            <table>
                            <tr>
                                <td><b>Password </b><td>: <b>".$passwordbaru."</b></td></td>
                            </tr>
                            </table>
                                <p>Anda dapat login kembali dengan password Anda ke <a href=\"http://localhost/cfd/login/\" target=\"_blank\" style=\"text-decoration:none;font-weight:bold;\">Website Administrator</a>.</p>
                                <p><br><br>
                            Developer CODAMA<br>
                                Universitas Komputer Indonesia
                                </p>
                                <center>
                                <small>
                                <p class=\"doff\">All Rights Reserved &copy; ".date("Y")." CODAMA.<br>
                                <a href=\"http://www.cfdbandung.com/\" target=\"_blank\" style=\"text-decoration:none;\">Website Utama</a></p>
                                </small>
                                </center>
                        
                            </body>
                            </html>"
                    );

                if ($this->email->send()) {
                    $idCarFree = $this->input->post('idCarFree');
                    $idAdmin = $this->input->post('idAdmin');
                    $email = $this->input->post('email');
                    $pass = md5($passwordbaru);

                    $object = array(
                            'emailPanitia' => $email,
                            'password' => $pass,
                            'idAdmin' => $idAdmin,
                            'idCarFree' => $idCarFree,
                     );

                    $query = $this->db->insert('panitia', $object);

                    if ($query) {
                       $this->session->set_flashdata('info', 'Email Terkirim, Data berhasil ditambah');
                       redirect('admin/kelolapanitia');
                    }
                } else {
                    $this->session->set_flashdata('info', 'Email Tidak Terkirim, pastikan koneksi internet aktif');
                    redirect('admin/kelolapanitia/tambah');
                }
        }

        public function tampil($email){
            $data['nama_admin']    = $this->session->userdata('nama_admin');      
            
            if ($data['nama_admin'] != NULL) {
                $data['panitia'] = $this->m_panitia->getDeskrip($email)->row();

                $data['isi'] = 'admin/tampilpanitia';
                $this->load->view('admin/home',$data);
            }
            else{
                redirect('login');
            }            
        }

        public function hapus($email){
            $this->m_panitia->hapus_panitia($email);

            $this->session->set_flashdata('info', 'Data Panitia Dihapus!');
            redirect(base_url('admin/kelolapanitia'));
        }
    }

?>