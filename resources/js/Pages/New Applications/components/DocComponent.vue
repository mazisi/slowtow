<template>
  <ul class="list-group">
    <li class="px-0  border-0 list-group-item d-flex align-items-center">
      <div class="avatar me-3" v-if="documentModel">
      <a :href="`${$page.props.blob_file_path}${documentModel.document_file}`" target="_blank">
      <i class="fa fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
      </a>
      </div>
  
     <div class=" d-flex align-items-start flex-column justify-content-center">
      <h6 v-if="!documentModel" class="mb-0 text-sm">Document</h6>
      <h6 v-else-if="documentModel" class="mb-0 text-sm limit-file-name">{{ documentModel.document_name }}</h6>
      </div>
  
      <!-- <a v-if="documentModel" @click="deleteDocument(documentModel.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
      <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
      </a>
      <div v-else @click="emitValue(orderByNumber,docType)" 
      class="mb-0 cursor-pointer btn btn-link pe-3 ps-0 ms-4">
      <label :for="docType">
        <i class="fa fa-upload h5" aria-hidden="true"></i>
        <input type="file" hidden :id="docType">
      </label>
      
    </div> -->

    <div class="mb-0 cursor-pointer btn btn-link pe-3 ps-0 ms-4">
    <label :for="uploadDoc.doc_type">
      <i class="fa fa-upload h5" aria-hidden="true"></i>
      <input type="file" @change="upload($event)" accept=".pdf" hidden :id="uploadDoc.doc_type">
    </label>
    
  </div>  
    </li>
    <div v-if="file_has_apostrophe" class="mb-2 text-danger text-sm">File cannot contain apostrophes.</div>
    <div class="col-md-12">
      <progress v-if="uploadDoc.progress" :value="uploadDoc.progress.percentage" max="100">
     {{ uploadDoc.progress.percentage }}%
     </progress>
     </div>
  </ul> 
  
</template>
<style scoped>
.cursor-pointer{
  cursor: pointer;
}
</style>
<script>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default{
  
  props: {
    documentModel: Object,
    errors: Object,
    orderByNumber: Number,
    docType: String,
    success: Object
  },
  setup(props){
    let file_has_apostrophe = ref(false);

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
        submitDocument();
        e.target.value = '';
      }

      // const uploadDoc = useForm({
      //   document_file: null,
      //   doc_type: null ,
      //   num: null,
      //   stage: null,
      //   licence_id: props.licence.id
      // })

      function submitDocument(){
        
        uploadDoc.post('/upload-licence-document', {
          preserveScroll: true,
          onSuccess: () => { 
              file_has_apostrophe=false;
                        if(props.success){
                            notify(props.success)
                         }else if(props.error){
                           notify(props.error)
                         }
            uploadDoc.doc_type = null;
           },
        })
      }

      const notify = (message) => {
          if(props.success){
            toast.success(message, {
            autoClose: 2000,
          });
          
          }else if(props.error){
            toast.error(message, {
            autoClose: 2000,
          });
          }
        }
    return {
      uploadDoc,notify,upload,
      file_has_apostrophe,
      submitDocument,toast
    }
  }
}
</script>