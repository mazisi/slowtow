<template>

  
    <ul class="list-group">
        <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
          <div class="avatar me-3" v-if="hasFile.docPath">
          <a :href="`${$page.props.blob_file_path}${hasFile.docPath}`" target="_blank">
          <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
          </a>
          </div>
      
          <div class="d-flex align-items-start flex-column justify-content-center">
            <h6 class="mb-0 text-sm">{{ docType }}</h6>
            <p v-if="hasFile.docPath" class="mb-0 text-xs">{{ hasFile.fileName }}</p>
            <p v-if="hasFile.docPath" class="mb-0 text-xs text-dark"></p>
            <p v-if="!hasFile.docPath" class="mb-0 text-xs text-danger">{{ docType }} Not Uploaded</p>
            <p v-if="file_has_apostrophe" class="mb-0 text-danger text-xs">File cannot contain apostrophes.</p>
          </div>
      
          <button v-if="hasFile.docPath" @click="deleteDocument(hasFile.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
          <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
          </button>
      
          <label v-else :for="uploadDoc.doc_type" :class="{ 'd-none': uploadDoc.processing || $page.props.auth.has_company_admin_role}"
          class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
          <i class="fa fa-upload" aria-hidden="true"></i>
          <input type="file" @change="upload($event)" accept=".pdf" hidden :id="uploadDoc.doc_type">
          </label>
          
          <span v-if="uploadDoc.progress" style="margin-left: 5rem;">
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
          file_has_apostrophe=false;
          uploadDoc.document_file = e.target.files[0];
          if(e.target.files[0].name.includes("'")){
            file_has_apostrophe = true
            return;
          }
          context.emit('file-value-changed', uploadDoc);
          e.target.value = '';
          file_has_apostrophe=false;
        }
  
        function deleteDocument(id){
          context.emit('file-deleted', id);
        }
      
      return {
        uploadDoc,upload,max,value,
        file_has_apostrophe,deleteDocument
      }
    },
    components: {
      CircleProgressBar
    }
  }
  </script>