<div class="row">
    <!-- Left col -->
    <div class="col-md-12">
      <!-- TABLE: LATEST ORDERS -->
      <div class="box box-info">
        <div class="box-header with-border">
          <a href="<?= site_url('admin/users/create') ?>" class="btn btn-sm btn-info btn-flat pull-left" id="add">Tambah data</a>
          <div class="box-tools pull-right">
            
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
         <form name="form_user" id="form_user" action="<?= base_url('admin/users/index'); ?>">
          <div class="table-responsive">
            <table id="table" class="table table-bordered table-hover">
                <thead>
                    <th width="5"><input type="checkbox" class="flat-red toltip" id="check_all" name="check_all" title="check all"></th>
                    <th width="50" >Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    <?php foreach ($content as $r): ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="flat-red check" name="delete_id[]" value="<?= $r->id ?>">
                            </td>
                            <td width="200">
                                <div class="chip">
                                <?php if (is_file(FCPATH . 'uploads/user/' . $r->avatar)): ?>
                                <?php $img_url = BASE_URL . 'uploads/user/' . $r->avatar; ?>
                                <?php else: ?>
                                <?php $img_url = BASE_URL . 'uploads/user/default.png'; ?>
                                <?php endif; ?>
                                <a class="fancybox" rel="group" href="<?= $img_url; ?>">
                                  <img src="<?= $img_url; ?>" alt="Person" width="50" height="50">
                                </a>
                                <?= ($r->fullname); ?>
                              </div>
                                
                            </td>
                            <td>
                                <?= $r->email ?>
                            </td>
                            <td>
                                <span class="badge bg-green"><?= $r->role ?></span>
                            </td>
                            <td>
                                 <input type="checkbox" name="status" data-user-id="<?= $r->id; ?>" class="switch-button" <?= $r->is_active ? 'checked': ''; ?> >
                            </td>
                            <td>
                               <div class="text-center"><a href="<?= base_url("admin/users/edit/$r->id") ?>" class="btn btn-sm btn-warning edit" title="Edit" id="edit" data-id = "<?= $r->id ?> "><i class="fa fa-pencil"></i>Edit </a>

                                <button type="button" class="btn btn-sm btn-danger delete" title="Delete" id="delete" data-id = "<?= $r->id ?>" ><i class="fa fa-trash"></i>Delete</button></div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    <?php if ($total_rows == 0) :?>
                         <tr>
                           <td colspan="100">
                           Data Users is not Available
                           </td>
                         </tr>
                      <?php endif; ?>
                </tbody>
            </table>
          </div>
         <?php form_close(); ?> 
          <div class="row">
              <div class="col-md-8">
                  <span>
                        <small>
                            <?= $keterangan; ?>
                        </small>
                  </span>
              </div>
          </div>
          <!-- /.table-responsive -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
          <!-- /.widget-user -->
               <div class="row">
                  <div class="col-md-8">
                     <div class="col-sm-2 padd-left-0 " >
                        <select type="text" class="form-control chosen chosen-select" name="bulk" id="bulk" placeholder="Site Email" >
                           <option value="">Bulk</option>
                           <option value="delete">Delete</option>
                        </select>
                     </div>
                     <div class="col-sm-2 padd-left-0 ">
                        <button type="button" class="btn btn-flat"  name="apply" id="apply" value="Apply" title="">Apply</button>
                     </div>
                     <div class="col-sm-3 padd-left-0  " >
                        <input type="text" class="form-control" name="q" id="filter" placeholder="Filter" value="<?= $this->input->get('q'); ?>">
                     </div>
                     <div class="col-sm-3 padd-left-0 " >
                        <select type="text" class="form-control chosen chosen-select" name="f" id="field" >
                           <option value="">All</option>
                           <option <?= $this->input->get('f') == 'id' ? 'selected' :''; ?> value="id">ID</option>
                           <option <?= $this->input->get('f') == 'username' ? 'selected' :''; ?> value="username">Username</option>
                           <option <?= $this->input->get('f') == 'full_name' ? 'selected' :''; ?> value="full_name">Full Name</option>
                           <option <?= $this->input->get('f') == 'email' ? 'selected' :''; ?> value="email">Email</option>
                        </select>
                     </div>
                     <div class="col-sm-1 padd-left-0 ">
                        <button type="submit" class="btn btn-flat" name="sbtn" id="sbtn" value="Apply" title="Filter Search">
                        Filter
                        </button>
                     </div>
                     <div class="col-sm-1 padd-left-0 ">
                        <a class="btn btn-default btn-flat" name="reset" id="reset" value="Apply" href="<?= base_url('admin/users'); ?>" title="Reset">
                        <i class="fa fa-undo"></i>
                        </a>
                     </div>
                  </div>
                  <?= form_close(); ?>
                  <div class="col-md-4">
                     <div class="dataTables_paginate paging_simple_numbers pull-right" id="example2_paginate" >
                        <?= $pagination; ?>
                     </div>
                  </div>
               </div>
        </div>
        <!-- /.box-footer -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
    $('.switch-button').switchButton({
        labels_placement: 'right',
        on_label: 'Active',
        off_label: 'Inactive'
    });

    $('#table').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": true
    });

    $(document).on('change', 'input.switch-button', function() {
        var status = 'inactive';
        var id = $(this).attr('data-user-id');
        var data = [];

        if ($(this).prop('checked')) {
            status = 'active';
        }

        data.push({
            name: 'status',
            value: status
        });
        data.push({
            name: 'id',
            value: id
        });

        $.ajax({
                url: BASE_URL + '/admin/users/set_status',
                type: 'POST',
                dataType: 'JSON',
                data: data,
            })
            .done(function(data) {
                if (data.success) {
                    toastr['success'](data.message);
                } else {
                    toastr['warning'](data.message);
                }

            })
            .fail(function() {
                toastr['error']('Error update status');
            });
    });

   //for delete
    $(document).on('click', '#delete', function(){
       var delete_id = $(this).attr('data-id');
        swal({
                title: "Anda Yakin?",
                text: "data yang di hapus tidak bisa di restore!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, Hapus !",
                cancelButtonText: "Tidak !",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                   $.ajax({
                        url :'<?= base_url()?>admin/users/delete',
                        type :'POST',
                        dataType: 'json',
                        data: {delete_id:delete_id}, 
                    })

                     .done(function(data){
                        if (data.success ==true) {
                                document.location.href = data.redirect;
                                return;
                        } else {
                            toastr['error'](data.message);   
                        }
                    }) 
                } 
            })
        
    });


    $('#apply').click(function() {

        var bulk = $('#bulk');
        var serialize_bulk = $('#form_user').serializeArray();
        if (bulk.val() == 'delete') {
             swal({
             title: "Anda Yakin ?",
             text: "data yang terpilih akan di hapus dan tidak bisadi rstore ulang !",
             type: "warning",
             showCancelButton: true,
             confirmButtonColor: "#DD6B55",
             confirmButtonText: "Ya, Hapus !",
             cancelButtonText: "Tidak, Kembali !",
             closeOnConfirm: true,
             closeOnCancel: true
         },
                 function(isConfirm) {
                     if (isConfirm) {
                       $.ajax({
                            url :'<?= base_url()?>admin/users/delete',
                            type :'POST',
                            dataType: 'json',
                            data: serialize_bulk, 
                        })

                         .done(function(data){
                            if (data.success == true) {
                                document.location.href = data.redirect;
                                    return;
                            } else {
                                toastr['error'](data.message);   
                            }
                        }) 
                    } 
                 });

            return false;

        } else if (bulk.val() == '') {
            swal({
                title: "Upss",
                text: "Pilih salah satu aksi masal terlebih dahulu !",
                type: "warning",
                showCancelButton: false,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Okay!",
                closeOnConfirm: true,
                closeOnCancel: true
            });

            return false;
        }

        return false;

    }); /*end appliy click*/

    const checkAll = $('#check_all');
    const checkboxes = $('input.check');

    checkAll.on('ifChecked ifUnchecked', function(event) {
        if (event.type == 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });

    checkboxes.on('ifChanged', function(event) {
        if (checkboxes.filter(':checked').length == checkboxes.length) {
            checkAll.prop('checked', 'checked');
        } else {
            checkAll.removeProp('checked');
        }
        checkAll.iCheck('update');
    });
                   

});
</script>
