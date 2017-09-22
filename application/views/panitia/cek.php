<?php  
echo date('d-m-Y');
echo md5('rizki123');

echo $this->session->userdata('email_panitia');
echo '<br>';
$tgl = date('d-m-Y');
echo date('d m Y',strtotime($tgl));
echo '<br>';
echo $tgl;
?>
