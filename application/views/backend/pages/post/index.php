<?php $this->load->view('backend/layouts/_alert'); ?>
<div class="mb-4"></div>	
<div class="float-right mb-4">
	<?= form_open(base_url('post/search'), ['method' => 'POST']); ?>
	<div class="input-group ">
		<input class="form-control text-ceter" type="text" name="keyword" placeholder="Search your key ..." value="<?=$this->session->userdata('keyword'); ?>"></input>
		<div class="input-group-append">
			<button class="btn btn-info btn-sm" type="submit">
				<i class="fas fa-search"></i>
			</button>
			<a class="btn btn-info btn-sm" href="<?= base_url('post/reset') ?>">
				<i class="fas fa-eraser"></i>
			</a>
		</div>
	</div>
</div>
<?= form_close(); ?>
<table  class="table  table-hover table-striped mt-4">
	<thead>
		<th>No</th>
		<th>Title</th>
		<th>Category</th>
		<th>Status</th>
		<th>Action</th>
	</thead>
	<tbody>
		<?php 
		if ($this->uri->segment(2) == 'search') {
			$no = (!$this->uri->segment(3) ? 0 : $this->uri->segment(3) + 1);
		} else {
			$no = (!$this->uri->segment(2) ? 0 : $this->uri->segment(2) + 1); 
		}
		foreach ($content as $row): $no++;
		?>
			<tr>
				<td><?= $no ?></td>
				<td><?= anchor(base_url('blog/p/').$row->slug, $row->title); ?></td>
				<td><?= anchor(base_url('blog/category/').$row->category_slug, $row->category_title); ?></td>
				<td><?= $row->status ? '<span class="badge badge-primary">'.anchor('#', 'Publish', ['style' => 'color:#fff; text-decoration:none']).'</span>' : 'Draf' ?></span></td>
				<td>
					<?= form_open('post/delete/'.$row->id, ['method' => 'POST', 'id' => 'form_'.$row->id]); ?>
					<?= form_hidden('id', $row->id); ?>

					<a href="<?= base_url('post/edit/').$row->id ?>">
						<button class="btn btn-sm" type="button">
							<i class="fas fa-edit text-info"></i>
						</button>
					</a>  

					<button class="btn btn-sm" type="submit" onclick="return confirm('Anda yakin ingin menghapus data ?')">
						<i class="fas fa-trash text-danger"></i>
					</button>
					<?= form_close(); ?>
				</td>
			</tr>
		<?php  endforeach ?>
	</tbody>
</table>

<div class="pagiation">
	<?= $pagination ?>
</div>