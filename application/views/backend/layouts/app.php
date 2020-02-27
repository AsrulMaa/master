<!DOCTYPE html>
<html>
<head>
	<title><?= $title; ?></title>
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/libraries/bootstrap/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/libraries/fontawesome/css/all.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/libraries/fontawesome/css/fontawesome.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/libraries/fine-upload/fine-uploader.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/libraries/toastr/build/toastr.min.css') ?>">

<!-- 	<script type="text/javascript" src="
	<?= base_url('assets/libraries/jquery/jquery-3.4.1.min.js'); ?>
	"></script> -->
	<script type="text/javascript" src="
	<?= base_url('assets/libraries/js/jquery2.js'); ?>
	"></script>
	<script type="text/javascript" src="
	<?= base_url('assets/libraries/fine-upload/fine-uploader.js'); ?>
	"></script>

	  <!-- Fine Uploader Gallery CSS file
====================================================================== -->
<link href="<?= base_url('assets/libraries/fine-upload/fine-uploader-gallery.min.css') ?>" rel="stylesheet">
<!-- Fine Uploader jQuery JS file
====================================================================== -->
<script src="<?= base_url('assets/libraries/fine-upload/jquery.fine-uploader.js')?>"></script>

	<script type="text/javascript">
		var BASE_URL = '<?= base_url() ?>';
		var BASE_ASSET = '<?= base_url('assets/libraries/') ?>';
	</script>
</head>
<body>
	<div class="container">
	<!-- Content -->
		<?= $contents ?>
	</div>
	
	<script type="text/javascript" src="
	<?= base_url('assets/libraries/bootstrap/js/bootstrap.bundle.min.js'); ?>
	"></script>
	<script type="text/javascript" src="
	<?= base_url('assets/libraries/js/app.js'); ?>
	"></script>
	<script type="text/javascript" src="
	<?= base_url('assets/libraries/fontawesome/js/all.min.js'); ?>
	"></script>
	<script type="text/javascript" src="
	<?= base_url('assets/libraries/fontawesome/js/fontawesome.min.js'); ?>
	"></script>
	
	
	
</body>
</html>