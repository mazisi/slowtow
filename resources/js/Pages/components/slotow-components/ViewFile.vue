<template>
    <div class="modal fade" id="view-file" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          
          <div class="modal-body">      
            <div class="row">
              <iframe :src="`${$page.props.blob_file_path}${filePath.docPath}`" frameborder="0" width="100%" height="600px"></iframe>
          </div>
        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            
          </div>
        
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {

    props: {
      filePath: Object
      },

    setup(){
      // let url = 'https://slotowstorage.blob.core.windows.net/mrnlabs/4872...ID___Jacques_Victor_Marais.pdf';
      return{}
      
    }
      
  };
  </script>

  <style>
  .btn-close {
    color: red;
    background: 0 !important;
  }   
</style>