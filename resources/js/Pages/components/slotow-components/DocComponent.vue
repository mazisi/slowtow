<template>
  <ul class="list-group">
    <li class="px-0  border-0 list-group-item d-flex align-items-center">
      <div class="avatar me-3" v-if="hasFile.docPath">
      <!-- <a  data-bs-toggle="modal" data-bs-target="#view-file" target="_blank">
      <i class="fa fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
      </a> -->

       <a class="cursor-pointer" :href="`${$page.props.blob_file_path}${hasFile.docPath}`" target="_blank">
        <i class="fa fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
        </a> 
      </div>
     
     <div class=" d-flex align-items-start flex-column justify-content-center">
      <!-- <h6 v-if="!hasFile" class="mb-0 text-sm">Document</h6> -->
      <h6 v-if="hasFile.fileName" class="mb-0 text-sm limit-file-name">{{ hasFile.fileName }}</h6>
      </div>
  
       <a v-if="hasFile.id" @click="deleteDocument(hasFile.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
         <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
      </a>
      <div v-if="!hasFile.id" 
       class="mb-0  btn btn-link pe-3 ps-0 ms-4" >
       <!-- Original Licence stage will display a combined PDF document. The documents uploaded
          for Stages 9, 10, 11 and 13 must be merged into one and saved under this
          stage. The order of the documents should be Stage 13  Stage 11  Stage
          10  Stage 9.
         -->
      <span :class="{ 'd-none': uploadDoc.processing}">
        <label v-if="orderByNumber !== 1400" :for="uploadDoc.doc_type + uploadDoc.orderByNumber">
        <i class="cursor-pointer fa fa-upload h5" aria-hidden="true"></i>
        <input type="file" @change="upload($event)" accept=".pdf" hidden :id="uploadDoc.doc_type + uploadDoc.orderByNumber">
      </label> </span>   
      
      <span v-if="uploadDoc.progress" style="margin-top: -2rem;">
  <CircleProgressBar  
      :value="uploadDoc.progress.percentage"  
      :max="100"  
      percentage  
      rounded
      :size="30"
      :colorFilled="'#4caf50'"
      :animationDuration="'0.7s'">
    </CircleProgressBar>
</span>
      
      <div v-if="file_has_apostrophe" class="mb-2 text-danger text-sm">File cannot contain apostrophes.</div>
    </div>
  </li>
 
  </ul>





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
import { CircleProgressBar } from 'circle-progress.vue';


export default{
  
  props: {
    documentModel: Object,
    hasFile: Object,
    errors: Object,
    orderByNumber: Number,
    stage: Number,
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
      stage: props.stage,
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
    CircleProgressBar
  }
}
</script>