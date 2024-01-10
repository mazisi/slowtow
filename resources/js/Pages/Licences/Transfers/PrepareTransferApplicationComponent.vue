<template>

    <div class="px-3 d-flex mb-2 active w-10">
        <label :for="docType" v-if="!hasFile <= 0" @click="setDocType(stage,docType,belongsTo,orderByNumber)" 
         class="fa fa-upload h5 " aria-hidden="true"></label>
         <a v-if="hasFile" :href="`${$page.props.blob_file_path}${hasFile.document}`" target="_blank">
          <i class="fa fa-file-pdf h5 mx-2 text-danger curser-pointer"></i>
          </a>
       {{ belongsTo }}
          <i v-if="hasFile" @click="deleteDocument(hasFile.id)" 
          class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i> 
        <input type="file" :id="docType" @change="upload($event)" accept=".pdf" hidden>
      </div>
</template>
<script>
import {ref} from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { CircleProgressBar } from 'circle-progress.vue';

export default {

    props: {
    documentModel: Object,
    hasFile: Object,
    errors: Object,
    orderByNumber: Number,
    docType: String,
    success: Object,
    belongsTo:String,
    stage: Number
    },

    setup(props, context){
   
    let file_has_apostrophe = ref(false);
    let stage = ref('');
    let doc_type = ref('');
    let belongs_to = ref('');

    

    const setDocType = (stage, docType, belongsTo, orderByNumber) => {
        console.log(stage, docType, belongsTo, orderByNumber);
        stage = stage;
        doc_type = docType;
        belongs_to = belongsTo;
      };
    console.log('file',uploadDoc)
      const uploadDoc = useForm({
      licence_id: props.documentModel.id,
      doc_type: doc_type,
      document: null,
      belongs_to: belongs_to,
      stage: stage,
    })
    function upload(e){
        uploadDoc.document = e.target.files[0];
        if(e.target.files[0].name.includes("'")){
          this.file_has_apostrophe = true
          return;
        }
        console.log('formss',uploadDoc);
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
      uploadDoc,upload,viewFile,setDocType,
      file_has_apostrophe,deleteDocument
    }
  },
  components: {
    CircleProgressBar
  }
}


</script>