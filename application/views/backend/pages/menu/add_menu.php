<style type="text/css">
  .col-md-3.col-sm-4 {
    padding: 10px;
  }
</style>

<!-- Main content -->
<section class="content">
   <div class="row" >
     
      <div class="col-md-12">
         <div class="box box-warning">
            <div class="box-body ">

               <!-- Widget: user widget style 1 -->
               <div class="box box-widget widget-user-2">
                  <!-- Add the bg color to the header using any of the bg-* classes -->
                  <div class="widget-user-header ">
                    
                     <div class="widget-user-image">
                        <img class="img-circle" src="<?= base_url('assets'); ?>/img/add2.png" alt="Menu Avatar">
                     </div>
                     <!-- /.widget-menu-image -->
                     <h3 class="widget-user-username">Menu</h3>
                     <h5 class="widget-user-desc">New Menu</h5>
                  </div>

                 
                   <?= form_open('', [
                      'name'    => 'form_menu', 
                      'class'   => 'form-horizontal', 
                      'id'      => 'form_menu', 
                      'method'  => 'POST'
                    ]); ?>
                  <input type="hidden" value="" name="menu_type_id">
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Menu Type</label>

                        <div class="col-sm-8">
                            <label class="col-md-2 padding-left-0">
                            <input  type="radio" name="category_id" class="flat-green menu_type" value="menu" checked> Admin Menu
                            </label>
                             <label class="col-md-2 padding-left-0">
                            <input  type="radio" name="category_id" class="flat-green menu_type" value="label" > Label
                            </label>
                            <label>
                            <input  type="radio" name="category_id" class="flat-green menu_type" value="landing"> Landing
                            </label>
                            <small class="info help-block">
                             Type Of Menu.
                          </small>
                        </div>
                    </div>

                    <div class="form-group menu-only">
                        <label for="content" class="col-sm-2 control-label">Icon</label>

                        <div class="col-sm-8">
                           <input type="hidden" name="icon" id="icon">
                            
                          <div class="icon-preview">
                            <span class="icon-preview-item"><i class="fa fa-rss fa-lg"></i></span>
                          </div>
                           <br>
                           <br>

                           <a class="btn btn-default btn-select-icon btn-flat">Select Icon</a>

                          
                           
                        </div>
                    </div>


                    <div class="form-group parent_menu">
                        <label for="content" class="col-sm-2 control-label">Parent</label>

                        <div class="col-sm-8">
                           <select  class="form-control chosen  chosen-select-deselect" name="parent" id="parent_menu" tabi-ndex="5" data-placeholder="Select Parent">
                            <option value=""></option>
                            <?php foreach (getParent(null, null) as $row): ?>
                            <option value="<?= $row->id; ?>"  ><?= ucwords($row->label); ?></option>
                            
                            <?php endforeach; ?>  

                           </select>

                            <small class="info help-block">
                             Select one or more groups.
                          </small>
                        </div>
                    </div>

                    <div class="form-group parent_landing">
                        <label for="content" class="col-sm-2 control-label">Parent</label>

                        <div class="col-sm-8">
                          

                           <select  class="form-control chosen  chosen-select-deselect " name="parent" id="parent_landing" tabi-ndex="5" data-placeholder="Select Parent">
                            <option value=""></option>
                            <?php foreach (getParent('type','landing') as $row): ?>
                            <option value="<?= $row->id; ?>"  ><?= ucwords($row->label); ?></option>
                            <?php endforeach; ?>  

                           </select>


                            <small class="info help-block">
                             Select one or more groups.
                          </small>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="label" class="col-sm-2 control-label">Label <i class="required">*</i></label>
                        <div class="col-sm-8">
                           <input type="text" class="form-control" name="label" id="label" placeholder="Label" value="<?= set_value('label'); ?>">
                           <small class="info help-block">The label of menu.</small>
                        </div>
                     </div>


                     <div class="form-group link">
                        <label for="link" class="col-sm-2 control-label">Link <i class="required">*</i></label>
                        <div class="col-sm-8">
                           <input type="text" class="form-control" name="link" id="link" placeholder="Link" value="<?= set_value('link'); ?>">
                           <small class="info help-block">The link of menu <i>Example : admin/blog</i>.</small>
                        </div>
                     </div>


                    

                    <div class="form-group group-privilage">
                        <label for="content" class="col-sm-2 control-label">Group Privilage </label>

                        <div class="col-sm-8">
                           <select  class="form-control chosen chosen-select" name="group[]" id="group" tabi-ndex="5" data-placeholder="Select Groups" multiple="">
                            <option value=""></option>
                            <?php foreach (getRole() as $row): ?>
                            <option value="<?= $row->id; ?>"  ><?= ucwords($row->role); ?></option>
                            
                            <?php endforeach; ?>  
                             
                           </select>
                            <small class="info help-block">
                             
                              group is allowed to access this menu.
                          </small>
                        </div>
                    </div>


                    <div class="message">
                      
                    </div>

                    <div class="row-fluid col-md-7">
                    <button type="button" class="btn btn-flat btn-primary btn_save btn_action" id="btn_save" data-stype='stay' title="save (Ctrl+s)"><i class="fa fa-save" ></i> Save Data</button>
                     <a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save" data-stype='back' title=" (Ctrl+d)"><i class="ion ion-ios-list-outline" ></i> Save and go to list</a>
                     <a class="btn btn-flat btn-default btn_action" id="btn_cancel" title="(Ctrl+x)"><i class="fa fa-undo" ></i> Cancel </a>
                     <span class="loading loading-hide"><img src="<?= base_url('assets'); ?>/img/loading-spin-primary.svg"> <i>Loading saving data...</i></span>
                     </div>
                    
                  <?= form_close(); ?>
               </div>
            </div>
            <!--/box body -->
         </div>
         <!--/box -->

      </div>
   </div>
</section>
<!-- /.content -->

<div class="modal fade " tabindex="-1" role="dialog" id="modalIcon">
  <div class="modal-dialog full-width " role="document">
    <div class="modal-content">
     
      <div class="modal-body">
       <?php $this->load->view('backend/pages/menu/partial_icon'); ?>

      </div>
      <div class="modal-footer">
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Page script -->
<script>
  $(document).ready(function() {
    $('.parent_landing').hide();
    

    $('input[type="radio"].flat-green').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
    });

    /*icon*/


    $('.btn-select-icon').click(function(event) {
      event.preventDefault();

      $('#modalIcon').modal('show');
    });

    $('.icon-container').click(function(event) {
       $('#modalIcon').modal('hide');
       var icon = $(this).find('.icon-class').html();

       icon = $.trim(icon);

       $('#icon').val(icon);

       $('.icon-preview-item .fa').attr('class', 'fa fa-lg '+icon);
    });

    $('#icon_color').change(function(event) {
      $('.icon-preview-item').attr('class', 'icon-preview-item '+$(this).val());
    });

    $('#find-icon').keyup(function(event) {
      $('.icon-container').hide();
      var search = $(this).val();

      $('.icon-class').each(function(index, el) {
        var str = $(this).html();
        var patt = new RegExp(search);
        var res = patt.test(str);

        if (res) {
          $(this).parent('.icon-container').show();
        }
      });
    });

    $('.category-icon').each(function(index) {
      $('#category-icon-filter').append('<option value="'+$(this).attr('id')+'">'+$(this).attr('id')+'</option>');
    });

    $('#category-icon-filter').change(function(event) {
      var type = $('#category-icon-filter').val();
      $('.category-icon').hide();
      $('.category-icon#'+type).show();

      if (type == 'all') {
        $('.category-icon').show();
      }
    });

    /*end*/

    var menu_type = $('.menu_type');

    menu_type.on('ifClicked', function(event) {
        var type = $(this).val();
       updateMenuType(type);
    });

    function updateMenuType(type) {
        if (type == 'menu') {
            $('.menu-only').show();
            $('.parent_landing').hide();
            $('.parent_menu').show();
            $('.link').show();

            $('.group-privilage').show();
        } else if(type == 'landing'){
            $('.group-privilage').hide();
            $('.parent_menu').hide();
            $('.parent_landing').show();
            $('.link').show();
            $('.menu-only').show();

        }else if (type == 'label') {
            $('.parent_menu').hide();
            $('.parent_landing').hide();
            $('.link').hide();
            $('.menu-only').hide();
            $('.group-privilage').show();
        }else {
            $('.menu-only').hide();
            $('.group-privilage').show();
            $('.parent_menu').show();
            $('.link').show();


        }

    }

    $('#btn_cancel').click(function() {
        swal({
                title: "Anda yakin  ?",
                text: "Anda akan Kembali Ke Halaman Menu Manajemen",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya",
                cancelButtonText: "No, Cancel",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    window.location.href = "<?= site_url('admin/menus') ?>";
                }
            });

        return false;
    }); /*end btn cancel*/

    $('.btn_save').click(function() {
        $('.message').hide();

        var form_menu = $('#form_menu');
        var data_post = form_menu.serializeArray();
        var save_type = $(this).attr('data-stype');

        $('.loading').show();
        data_post.push({
            name: 'save_type',
            value: save_type
        });

         $.ajax({
                url: '<?= base_url('admin/menus/save_data') ?>',
                type: 'POST',
                dataType: 'json',
                data: data_post,
            })
            .done(function(res) {

                if (res.success) {

                    if (save_type == 'back') {
                        window.location.href = res.redirect;
                        return;
                    } else if(save_type == 'stay') {
                      console.log(res.message);
                      $('.message').printMessage({
                        message: res.message
                      });
                      $('.message').fadeIn();
                      $('form input[type!=radio], form textarea, form select').val('');
                      $('#parent_landing').trigger('chosen:updated');
                      console.log(save_type);
                    }
                    

                } else {
                    $('.message').printMessage({
                        message: res.message,
                        type: 'warning'
                    });
                    $('.message').fadeIn();
                }

            })
            .fail(function() {
                $('.message').printMessage({
                    message: 'Error save data',
                    type: 'warning'
                });
            })
            .always(function() {
                $('.loading').hide();
                $('html, body').animate({
                    scrollTop: $(document).height()
                }, 3000);
            });

        return false;
       
    }); /*end btn save*/

}); /*end doc ready*/
</script>
