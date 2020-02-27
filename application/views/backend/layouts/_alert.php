<?php 
	$success = $this->session->flashdata('success');
	$error = $this->session->flashdata('error');
	$warning = $this->session->flashdata('warning');
	if ($error) {
		$alert_status = 'alert-danger';
		$status = 'Error !';
		$message = $error;
	}

	if ($success) {
		$alert_status = 'alert-success';
		$status = 'Success !';
		$message = $success;
	}

	if ($warning) {
		$alert_status = 'alert-warning';
		$status = 'Warning !';
		$message = $warning;
	}
 ?>

 <?php if ($success || $error || $warning): ?>
<div class="row">
	<div class=" container-fluid">
		<div class="col-md-12">
		<div 
			class="alert <?= $alert_status; ?> alert-dismissible " 
			role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="close">
						<span aria-hidden="true">&times;</span>
				</button>
				<h4><i class="icon fa fa-warning"></i> <?= $status; ?></h4>
				<?= $message; ?>
			</div>
	</div>
	</div>	
</div>
<?php endif; ?>
