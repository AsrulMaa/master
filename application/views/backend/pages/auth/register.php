<?php $this->load->view('backend/layouts/_alert'); ?>
<?= form_open('register', ['method' => 'POST']); ?>
	<label>Name</label>
	<?= form_input('name', $input->name, ['class'=> 'form-control', 'required' => true, 'autofocus' => true]); ?>
	&nbsp; <?= form_error('name') ?>
	<br>

	<label>E-Mail</label>
	<?= form_input(['type' => 'email', 'name' => 'email', $input->email, 'class'=> 'form-control', 'required' => true, 'autofocus' => true, 'placeholder' => 'Masukkan email']); ?>
	&nbsp; <?= form_error('email') ?>
	<br>

	<label>Password</label>
	<?= form_password('password', '', ['class' => 'form-control', 'placeholder' => 'masukkan password 8 karakter', 'required' => true]); ?>
	&nbsp; <?= form_error('password') ?>
	<br>

	<label>Konfirmasi Passowrd</label>
	<?= form_password('password_confirmation', '', ['class' => 'form-control', 'placeholder' => 'Masukkan password yang sama', 'required' => true]); ?>
	&nbsp; <?= form_error('password_confirmation') ?>
	<br>

	<input type="submit" name="simpan" value="Save">
<?= form_close() ?>