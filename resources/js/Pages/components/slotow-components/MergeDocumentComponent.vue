<template>
  <div class="row">
    <div class="col-md-7" >
  <button type="button" class="btn btn-outline-success w-95"> {{ docTitle }}</button>
 </div>
  <div class="col-md-1 d-flex">
    <label v-if="!hasFile.id" :for="uploadDoc.doc_type">
      <i v-if="isLiquorBoard()" class="cursor-pointer fa fa-link h5" aria-hidden="true"></i>
      <i v-else class="cursor-pointer fa fa-upload h5" aria-hidden="true"></i>
      <input type="file" @change="upload($event)" accept=".pdf" hidden :id="uploadDoc.doc_type">
    </label>
    <a v-if="hasFile.id" :href="`${$page.props.blob_file_path}${hasFile.docPath}`" target="_blank">
    <i class="fa fa-file-pdf h5 upload-icon mx-1"></i></a>
    <i v-if="hasFile.id" @click="deleteDocument(hasFile.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>
   
  </div>
  
</div>
</template>

<script>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { Inertia } from '@inertiajs/inertia';

export default{
  
  props: {
    errors: Object,
    success: String,
    error: String,
    errors: Object,
    docType: String,
    docTitle: String,
    docModel: String,
    stage: Number,
    hasFile: Object,
    mergeNum: Number
  },
  setup(props, context){

    let file_has_apostrophe = ref(false);

    const uploadDoc = useForm({
      licence_id: props.docModel.id,
      doc_type: props.docType,
      document_file: null,
      num: props.mergeNum
    })

    function upload(e){
        uploadDoc.document_file = e.target.files[0];
        if(e.target.files[0].name.includes("'")){
          this.file_has_apostrophe = true
          return;
        }
        context.emit('file-value-changed', uploadDoc);
        e.target.value = '';
        file_has_apostrophe=false;
      }


      function deleteDocument(id){
        context.emit('file-deleted', id);
        }

     

      const isLiquorBoard = () => {
          return props.docType === 'Payment To The Liquor Board2' 
      }
    return {
      upload,uploadDoc, file_has_apostrophe,
      isLiquorBoard,deleteDocument
    }
  }
}
</script>