
	    <div class="main-panel">
			<nav class="navbar navbar-transparent navbar-absolute">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="#">Kelola Admin </a>
						<!-- <div class="navbar-brand" >Material Dashboard / <a href=""> Hello</a> </div> -->
					</div>
					<div class="collapse navbar-collapse">
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="material-icons">person</i>
									<?=$nama_admin?> <i class="material-icons right">arrow_drop_down</i>
									<p class="hidden-lg hidden-md">Notifications</p>
								</a>
								<ul class="dropdown-menu">
									<li><a href="<?=base_url('admin/profil');?>">Profile</a></li>
									<li><a href="<?=base_url('panitia/logout');?>">Log Out</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</nav>

			<div class="content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
	                        <div class="card">
	                            <div class="card-header" style="background-color:darkblue">
	                                <h4 class="title">Data Admin</h4>
	                                <p class="category">Tabel Data Admin Kota Bandung</p>
	                            </div>
	                        	<div class="col-md-12">

					                <?php if($this->session->flashdata('info')):?>
				                        <br><div class="alert alert-success"> <a class="close" data-dismiss="alert" aria-label="close">×</a> <?php echo $this->session->flashdata('info'); ?>  
				                        </div>
				                    <?php endif;?>
	                        		<a class="btn btn-danger" href="<?= base_url(); ?>admin/kelolaadmin/tambah">Tambah </a>
	                        		<table id="example" class="mdl-data-table" cellspacing="0" width="100%">
								        <thead>
								            <tr>
								                <th>ID</th>
								                <th>Nama</th>
								                <th>Email</th>
								                <th>Opsi</th>
								                
								            </tr>
								        </thead>

								        <tbody>
								        <?php 
								        	foreach ($data as $admin) {
								        	
								        	
								        ?>
								            <tr>
								                <td ><?php echo $admin->idAdmin?></td>
								                <td ><?php echo $admin->namaAdmin?></td>
								                <td ><?php echo $admin->emailAdmin?></td>
								                <td >
								                	 <a href="<?php echo base_url('admin/kelolaadmin/ubah/'.$admin->idAdmin); ?>" rel="tooltip" title="Ubah" style="color:purple"><i class="material-icons">mode_edit</i></a> &nbsp;
								                	 <!-- <a href="" style="color:#4286f4"><i class="material-icons">pageview</i></a> &nbsp; -->
								                	 <a href="<?php echo base_url('admin/kelolaadmin/hapus/'.$admin->idAdmin); ?>" rel="tooltip" style="color:red" title="Hapus !"><i class="material-icons">delete</i></a> &nbsp;
								                </td>

								            </tr>
								         <?php
								         	}
								         ?> 
								        </tbody>
								    </table>
	                        	</div>
	                        </div>
	                    </div>
					</div>
				</div>
			</div>

			<footer class="footer">
				<div class="container-fluid">
					<nav class="pull-left">
						<ul>
							<li>
								<a href="#">
									&copy; Team Developer CFD 2017
								</a>
							</li>
						</ul>
					</nav>
					<p class="copyright pull-right">
						Creative Team Web
					</p>
				</div>
			</footer>
		</div>
	</div>


