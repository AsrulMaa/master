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
          <div class="table-responsive">
            <table id="table" class="table table-bordered table-hover">
                <thead>
                    <th width="5"><input type="checkbox" class="flat-red toltip" id="check_all" name="check_all" title="check all"></th>
                    <th width="50" >Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                    
                </thead>
            </table>
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
   table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": BASE_URL + "admin/users/request",
            "type": "POST",
            

        },


        //Set column definition initialisation properties.
        "columnDefs": [
            { 
                "targets": [ -1 ], //last column
                "orderable": false, //set not orderable
            },
            { 
                "targets": [ 0 ], //2 last column (photo)
                "orderable": false, //set not orderable
            },
        ],

    });


   //for delete
    $(document).on('click', '#delete', function(){
        var user_id = $(this).attr('data-id');
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
                        data: {user_id:user_id}, 
                    })

                     .done(function(data){
                        console.log(data);
                        if (data.success) {
                                table.ajax.reload(null,false);
                                toastr['success'](data.message);   
                                return;
                            
                            
                        } 
                    }) 
                } 
            })
        
    });


                   

});
</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_detail" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#03904e; color:#fff; ">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title text-center">Detail Data biaya</h3>
            </div>
            <div class="modal-body form">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td width="220px"><strong>Biaya Perjalanan</strong></td>
                              
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <table class="table table-hover">
                                        <tr>
                                            <td width="150px"> 
                                                <label>Pangkat</label>
                                            </td>
                                            <td>
                                                <strong id="pangkat"></strong>
                                            </td>
                                            <td>
                                                <strong id="pangkat_jumlah"></strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="150px"> 
                                                <label>Golongan</label>
                                            </td>
                                            <td>
                                                <strong id="golongan"></strong>
                                            </td>
                                            <td>
                                                <strong id="golongan_jumlah"></strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="150px"> 
                                                <label>Jabatan</label>
                                            </td>
                                            <td>
                                                <strong id="jabatan"></strong>
                                            </td>
                                            <td>
                                                <strong id="jabatan_jumlah"></strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="150px"> 
                                                <label>Kota Tujuan</label>
                                            </td>
                                            <td>
                                                <strong id="kota"></strong>
                                            </td>
                                            <td>
                                                <strong id="kota_jumlah"></strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="150px"> 
                                                <label>Biaya Lain</label>
                                            </td>
                                            <td>
                                                <strong id="biaya_lain"></strong>
                                            </td>
                                            <td>
                                                <strong id="biaya_lain_jumlah"></strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="150px"> 
                                                <label>Total Biaya : </label>
                                            </td>
                                            <td></td>
                                            <td>
                                                <strong id="total_jumlah" data-a-sign="Rp. " data-a-dec="," data-a-sep="."></strong>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <small class="required">*Biaya Pejalanan yang di tentukan untuk 1 kali perjalanan</small>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->