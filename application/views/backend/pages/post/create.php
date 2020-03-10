<?php $this->load->view('backend/layouts/_alert'); ?>

 <?= form_open('', [
  'name'    => 'form_blog', 
  'class'   => 'form-horizontal', 
  'id'      => 'form_blog', 
  'enctype' => 'multipart/form-data', 
  'method'  => 'POST'
  ]); ?>
<!-- Main content -->
<?php $this->load->view('fine_upload'); ?>
<section class="content">
    <div class="row" >
        <div class="col-md-8">
            <div class="box box-warning">
                <div class="box-body ">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user-2">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header ">
                            <div class="widget-user-image">
                               
                            </div>
                            <!-- /.widget-user-image -->
                            <h3 class="widget-user-username">Blog</h3>
                            <h5 class="widget-user-desc">New</h5>
                            <hr>
                        </div>
                       
                         
                        <div class="form-group ">
                          
                            <div class="col-sm-12">
                                <?= form_input('title', $input->title, ['class'=> 'form-control',  'autofocus' => true, 'onkeyup' => '', 'id'=>'title']); ?>
                               <?= form_input('slug', $input->slug, ['class'=> 'form-control',  'autofocus' => true, 'id' => 'input-slug', 'style' => 'display:none;']); ?>
	
                                <small class="info help-block"></small>
                                <span class="info help-block"><?= site_url('blog/p/') ?>
                                <span contenteditable="true" class="blog-slug" id="slug"></span> <i class="fa fa-pencil" title="Custom URL"></i></span>
                            </div>
                        </div>
                                                 
                        <div class="form-group ">
                           
                            <div class="col-sm-12">
                                <textarea id="content" name="content" rows="8" cols="120" class="form-control"><?= set_value('Content'); ?></textarea>
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>
                       
                                
                        
                        <div class="message"></div>
                        <div class="row-fluid col-md-12">
                           <button class="btn btn-flat btn-primary btn_save btn_action" id="btn_save" data-stype='stay' title="Simpan Data (Ctrl+s)">
                            <i class="fa fa-save" ></i> Save
                            </button>
                            <a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save" data-stype='back' title="Save and Go to List (Ctrl+d)">
                            <i class="ion ion-ios-list-outline" ></i> Save and Go to List
                            </a>
                            <a class="btn btn-flat btn-default btn_action" id="btn_cancel" title=" Cancel (Ctrl+x)">
                            <i class="fa fa-undo" ></i> Cancel
                            </a>
                            <span class="loading loading-hide">
                            <!-- <img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg">  -->
                            <i>Loading Saving Data</i>
                            </span>
                        </div>
                    </div>
                </div>
                <!--/box body -->
            </div>
            <!--/box -->
        </div>
        <div class="col-md-4">
            <div class="box box box-solid box-blog-right">
                <div class="box-header">
                  <h3>Status</h3>
                </div>
                <div class="box-body ">
                  <div class="form-group ">
                      <label for="status" class="col-sm-3 control-label">Status 
                      </label>
                      <div class="col-sm-9">
                          <select  class="form-control chosen chosen-select" name="status" id="status" data-placeholder="Select Status" >
                              <option value="1">publish</option>
                              <option value="2">draft</option>
                              <option value="3">archive</option>
                              </select>
                      </div>
                  </div>
                  <hr>
             	</div>
            </div>


            <div class="box box box-solid box-blog-right">
                <div class="box-header">
                  <h3>Category</h3>
                </div>
                <div class="box-body ">
                    <!-- Widget: user widget style 1 -->
                    
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                       
                      <div class="clear"></div>
                      <br>
                         

                        <div class="form-group ">
                            <label for="category" class="col-sm-3 control-label">Category 
                            </label>
                            <div class="col-sm-9">
                                <select  class="form-control chosen chosen-select-deselect" name="category" id="category" data-placeholder="Select Category" >
                                    <option value=""></option>
                                     
                                </select>
                            </div>
                        </div>
                        <div class="row"></div>
                        <br>

                        <div class="form-group ">
                            <label for="tags" class="col-sm-3 control-label">Tags 
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="tags" id="tags" placeholder="Holiday,Hunting" value="<?= set_value('tags'); ?>">
                                <small class="info help-block">
                                </small>
                            </div>
                        </div>

                </div>
            </div>

            <div class="box box box-solid box-blog-right">
                <div class="box-header">
                  <h3>Media</h3>
                </div>
                <div class="box-body ">
                    <!-- Widget: user widget style 1 -->
                    
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                       
                      <div class="clear"></div>
                                                
                        <div class="form-group ">
                            <div class="col-sm-12">
                                <div id="blog_image_galery"></div>
                                <div id="blog_image_galery_listed"></div>
                                <small class="info help-block">
                                <b>Extension file must</b> JPG,JPEG,PNG.</small>
                            </div>
                        </div>
                                                 
                </div>
            </div>
        </div>
          

    </div>
</section>

<?= form_close(); ?>

<script type="text/javascript">
$(document).ready(function(){
	 $('#blog_image_galery').fineUploader({
        template: 'qq-template-gallery',
        request: {
            endpoint: BASE_URL + '/administrator/blog/upload_image_file',
            params : {
                '<?= $this->security->get_csrf_token_name(); ?>': '<?=   $this->security->get_csrf_hash(); ?>'
              }
        },
        deleteFile: {
            enabled: true, 
            endpoint: BASE_URL + '/administrator/blog/delete_image_file',
        },
        thumbnails: {
            placeholders: {
                waitingPath: BASE_ASSET  + 'fine-upload/placeholders/waiting-generic.png',
                notAvailablePath: BASE_ASSET  + 'fine-upload/placeholders/not_available-generic.png'
            }
        },
        validation: {
            allowedExtensions: ["jpg","jpeg","png"],
            sizeLimit : 0,
                          
        },
        showMessage: function(msg) {
            toastr['error'](msg);
        },
        callbacks: {
            onComplete : function(id, name, xhr) {
              if (xhr.success) {
                 var uuid = $('#blog_image_galery').fineUploader('getUuid', id);
                 $('#blog_image_galery_listed').append('<input type="hidden" class="listed_file_uuid" name="blog_image_uuid['+id+']" value="'+uuid+'" /><input type="hidden" class="listed_file_name" name="blog_image_name['+id+']" value="'+xhr.uploadName+'" />');
              } else {
                 toastr['error'](xhr.error);
              }
            },
            onDeleteComplete : function(id, xhr, isError) {
              if (isError == false) {
                $('#blog_image_galery_listed').find('.listed_file_uuid[name="blog_image_uuid['+id+']"]').remove();
                $('#blog_image_galery_listed').find('.listed_file_name[name="blog_image_name['+id+']"]').remove();
              }
            }
        }
    }); /*end image galery*/
});
</script>