<template>
  <ul class="list-group">
    <li class="px-0  border-0 list-group-item d-flex align-items-center">
      <div class="avatar me-3" v-if="hasFile.docPath">
      <a  data-bs-toggle="modal" data-bs-target="#view-file" target="_blank">
      <i class="fa fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
      </a>

      <!-- <a :href="`${$page.props.blob_file_path}${hasFile.docPath}`" target="_blank">
        <i class="fa fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
        </a> -->
      </div>
     
     <div class=" d-flex align-items-start flex-column justify-content-center">
      <!-- <h6 v-if="!hasFile" class="mb-0 text-sm">Document</h6> -->
      <h6 v-if="hasFile.fileName" class="mb-0 text-sm limit-file-name">{{ hasFile.fileName }}</h6>
      </div>
  
       <a v-if="hasFile.id" @click="deleteDocument(hasFile.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
         <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
      </a>
      <div v-else 
       class="mb-0  btn btn-link pe-3 ps-0 ms-4" :class="{ 'd-none': uploadDoc.processing}">
      <label :for="uploadDoc.doc_type">
        <i class="cursor-pointer fa fa-upload h5" aria-hidden="true"></i>
        <input type="file" @change="upload($event)" accept=".pdf" hidden :id="uploadDoc.doc_type">
      </label>
      <div v-if="uploadDoc.processing" class="spinner-border text-danger spinner-border-sm" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <div v-if="file_has_apostrophe" class="mb-2 text-danger text-sm">File cannot contain apostrophes.</div>
    </div>
  </li>
  
  </ul>

 <div v-if="uploadDoc.progress">
  <CircleProgressBar  
  :value="uploadDoc.progress.percentage"  
  :max="100"  
  percentage  
 rounded
 :size="60"
 :colorFilled="'#4caf50'"
 :animationDuration="'0.7s'">
</CircleProgressBar>
 </div>

  <ViewFile 
  :filePath="hasFile"
  />


</template>
<style scoped>
.cursor-pointer{
  cursor: pointer !important;
}
.progress-bar {
    height: 15px !important;
}
</style>
<script>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import ViewFile from './ViewFile.vue';
import { CircleProgressBar } from 'circle-progress.vue';


export default{
  
  props: {
    documentModel: Object,
    hasFile: Object,
    errors: Object,
    orderByNumber: Number,
    docType: String,
    success: Object
  },
  setup(props, context){
   
    let file_has_apostrophe = ref(false);
    const blob = ref()
    let max = ref(100)
    const value = ref(55)

    const uploadDoc = useForm({
      licence_id: props.documentModel.id,
      doc_type: props.docType,
      document_file: null
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
        function viewFile(file_path){
          //$page.props.blob_file_path
        }
    return {
      uploadDoc,upload,viewFile,max,value,
      file_has_apostrophe,deleteDocument
    }
  },
  components: {
    ViewFile,
    CircleProgressBar
  }
}
</script>