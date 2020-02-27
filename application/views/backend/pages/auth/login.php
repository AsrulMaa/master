<?php $this->load->view('backend/layouts/_alert'); ?>
<?= form_open('login', ['method' => 'POST']); ?>

	<div class="form-group has-feedback">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $token ?>">
		<?= form_input(['type' => 'email', 'name' => 'email', $input->email, 'class'=> 'form-control', 'required' => true, 'autofocus' => true, 'placeholder' => 'Masukkan email']); ?>
			<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
		<?= form_error('email') ?>
	</div>

    <div class="form-group has-feedback">
		<?= form_password('password', '', ['class' => 'form-control', 'placeholder' => 'masukkan password ', 'required' => true]); ?>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
		<?= form_error('password') ?>
	</div>
	
	<div class="row">
        <div class="col-xs-8">
          <!-- <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div> -->
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
      
<?= form_close() ?>

     