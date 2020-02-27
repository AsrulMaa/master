<link rel="stylesheet" type="text/css" href="<?= BASE_ASSET; ?>libraries/nestable/nesteable.css">
<link rel="stylesheet" href="<?= BASE_ASSET; ?>libraries/m-switch/css/style.css">
<script src="<?= BASE_ASSET; ?>libraries/m-switch/js/jquery.mswitch.js" type="text/javascript"></script>

<!-- Main content -->
<section class="content">
   <div class="row" >
      <div class="col-md-4">
         <div class="box box-warning">
           <div class="box-header with-border">
                 <h3 class="box-title ">Menu</h3>
           </div>
            <div class="box-body ">
               <!-- Widget: user widget style 1 -->
               
               <?php foreach (db_get_all_data('menu_categories') as $row): ?>
               <div class="menu-type-wrapper clickable" data-id="<?= url_title($row->label) ?>">
                 <span data-href="<?= site_url('admin/menus/'.url_title($row->label)); ?>" class="clickable btn-block menu-type btn-group">
                    <?= (ucwords($row->label)); ?>
                  
                 </span>
                 <?php if ($row->label != 'admin menu'): ?>
                   <a class="menu-type-action remove-data" data-href="<?= base_url('administrator/menu_type/delete/'.$row->id); ?>" href="javascript:void()">
                     <i class="fa fa-trash"></i>
                  </a>
                  <?php else: ?>
                    <a class="menu-type-action" href="javascript:void()">
                      &nbsp;
                    </a>
                 <?php endif ?>
                 
               </div>
               <?php endforeach; ?> 
               <br>
               <a href="<?= site_url('admin/menu_type/add'); ?>" class="btn btn-block btn-add btn-add-menu btn-flat" title="add menu type (Ctrl+r)"><i class="fa fa-plus-square-o"></i> Tambah Type Menu</a>
            </div>
            <!--/box body -->
         </div>
         <!--/box -->
      </div>
      <div class="col-md-6">
         <div class="box box-warning">
              <!-- Widget: user widget style 1 -->
             <div class="box-header with-border">
                  
                   <h3 class="box-title pull-left">Menu <?= (str_replace('-', ' ', $this->uri->segment(4))); ?></h3>
             </div>
            <div class="box-body ">
               <div class="message">
                <div class="callout callout-info btn-flat">
                  # double click menu to active or inactive
                </div>
               </div>
               <!-- Widget: user widget style 1 -->
               <div style="margin: 15px 0px 15px 0px !important;">
                
                <a class="btn btn-flat btn-default btn_add_new" id="btn_add_new" title="add new menu (Ctrl+a)" href="<?= site_url('admin/menus/create/'. $this->uri->segment(4)); ?>"><i class="fa fa-plus-square-o" ></i>  Tambah baru</a>
             
                <span class="loading loading-hide"><img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg"> <i>Load saving data...</i></span>
             </div>
              <div class="dd" id="nestable">
                 
              </div>
              <div class="nestable-output"></div>
               </div>
              
            </div>
            <!--/box body -->
         </div>
      </div>
   </div>
</section>

<script src="<?= BASE_ASSET; ?>libraries/nestable/jquery.nestable.js"></script>
<script>
$(document).ready(function() {
    $('.remove-data').click(function() {
        var url = $(this).attr('data-href');
        swal({
                title: "Are you sure?",
                text: "data to be deleted can not be restored!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    document.location.href = url;
                }
            });

        return false;
    }); /*end remove data click*/

    var timeout;
    $('.dd').on('change', function() {
        clearTimeout(timeout);
        timeout = setTimeout(updateOrderMenu, 2000);
    });

    function updateOrderMenu(ignoreMessage) {
            $('.loading').removeClass('loading-hide');
            var shownotif = true;
            var menu = $('.dd').nestable('serialize');

            if (typeof shownotif == 'undefined') {
                var shownotif = true;
            }


            if (typeof ignoreMessage == 'undefined') {
                var ignoreMessage = false;
            }

            $.ajax({
                    url: BASE_URL + 'administrator/menu/save_ordering',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        'menu': menu,
                        '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
                    },
                })
                .done(function(res) {
                    if (res.success) {
                        $('.sidebar-menu').html(res.menu);
                        if (shownotif) {
                            if (!ignoreMessage) {
                              toastr['success'](res.message);
                            }
                        }
                    } else {
                        if (shownotif) {
                            if (!ignoreMessage) {
                              toastr['warning'](res.message);
                            }
                        }
                    }
                })
                .fail(function() {
                    if (!ignoreMessage) {
                      toastr['warning']('Error save data please try again later');
                    }
                })
                .always(function() {
                    $('.loading').addClass('loading-hide');
                });
        }
        // activate Nestable for list 1
    $('#nestable').nestable({
        group: 1
    });


    $('.clickable').on('click', function() {
        var id = $(this).attr('data-id');
        $(id).addClass('active');
        if (id != id) {
        $("div").find("[data-id='" + id + "']").removeClass('active'); 

      } else {
          $("div").find("[data-id='" + id + "']").addClass('active'); 

      }
    }); /*end clickable click*/

     $(".m_switch_check:checkbox").mSwitch({
          onRender:function(elem){
              changeSharingDashboard(elem.val(), 'dont_update');
              if (elem.val() == 0){
                  $.mSwitch.turnOff(elem);
              }else{
                  $.mSwitch.turnOn(elem);
              }
          },
          onTurnOn:function(elem){
             changeSharingDashboard(1, 'update');
          },
          onTurnOff:function(elem){
             changeSharingDashboard(0, 'update');
          }
      });



      function setMenuActive(id, status) {
        var data = [];

         data.push({
            name: csrf,
            value: token
        });
        data.push({
            name: 'status',
            value: status
        });
        data.push({
            name: 'id',
            value: id
        });

        $.ajax({
                url: BASE_URL + '/administrator/menu/set_status',
                type: 'POST',
                dataType: 'JSON',
                data: data,
            })
            .done(function(data) {
                if (data.success) {
                    toastr['success'](data.message);
                    updateOrderMenu(true)
                } else {
                    toastr['warning'](data.message);
                }

            })
            .fail(function() {
                toastr['error']('Error update status');
            });
      }


      $('.menu-toggle-activate').dblclick(function(event) {
        event.stopPropagation();
        var status = $(this).data('status');
        var id = $(this).data('id');

        switch (status) {
          case undefined : case 0 :
          $(this).removeClass('menu-toggle-activate_inactive');
          $(this).data('status', 1)
          setMenuActive(id,  1);
          break;
          case 1 :
          $(this).addClass('menu-toggle-activate_inactive');
          $(this).data('status', 0)
          setMenuActive(id,  0);
          break;
        }
      });

}); /*end doc ready*/
</script>