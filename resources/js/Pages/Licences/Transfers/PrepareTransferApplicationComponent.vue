<template>

    <div class="px-3 d-flex mb-2 active w-10">
        <label :for="docType + belongsTo" v-if="!hasFile.id" @click="setDocType(stage,docType,belongsTo,orderByNumber)" 
         class="fa fa-upload h5 " :class="{ 'd-none': uploadDoc.processing}" aria-hidden="true"></label>
         <a v-if="hasFile.id" :href="`${$page.props.blob_file_path}${hasFile.docPath}`" target="_blank">
          <i class="fa fa-file-pdf h5 mx-2 text-danger curser-pointer"></i>
          </a>

          

          <a v-else-if="original_licence?.document_file"
            :href="`${$page.props.blob_file_path}${original_licence.document_file}`" target="_blank"
            >
        <i class="fa fa-link float-end h5 curser-pointer"></i>
      </a>
   
          <i v-if="hasFile.id" @click="deleteDocument(hasFile.id)" 
          class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i> 
        <input type="file" :id="docType + belongsTo" @change="upload($event)" accept=".pdf" hidden>
        <span v-if="uploadDoc.progress">
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
      </div>

      
</template>
<script>
import {ref} from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { CircleProgressBar } from 'circle-progress.vue';
// import ViewFile from '../../components/slotow-components/ViewFile.vue';

export default {

    props: {
    documentModel: Object,
    hasFile: Object,
    original_licence: Object,
    errors: Object,
    orderByNumber: Number,
    docType: String,
    success: Object,
    belongsTo: String,
    stage: Number
    },

    setup(props, context){
  

    let uploadDoc = useForm({
      licence_id: props.documentModel.id,
      doc_type: props.docType,
      document_file: null,
      belongs_to: null,
      orderByNumber: props.orderByNumber,
      stage: props.stage,
    })
    

    let setDocType = (stage, docType, belongsTo, orderByNumber) => {  
        uploadDoc.stage = stage;
        uploadDoc.doc_type = docType;
         uploadDoc.belongs_to = belongsTo;
      };
    
     
    function upload(e){
        this.uploadDoc.document_file = e.target.files[0];
        if(e.target.files[0].name.includes("'")){
          this.file_has_apostrophe = true
          return;
        }
        console.log('formss',this.uploadDoc);
        context.emit('file-value-changed', this.uploadDoc);
        e.target.value = '';
        this.file_has_apostrophe=false;
      }

      function deleteDocument(id){
        context.emit('file-deleted', id);
      }
        function viewFile(file_path){
          //$page.props.blob_file_path
        }
    return {
      uploadDoc,upload,viewFile,setDocType,
      deleteDocument
    }
  },
  components: {
    CircleProgressBar
  }
}


</script>