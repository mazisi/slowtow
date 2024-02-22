<template>
    <button type="button" class="btn btn-outline-success document-names">{{ title }} </button>
    
     <label :for="title">
        <i v-if="!hasFile.id"  class="fa fa-upload h5 mx-2 curser-pointer"></i>
    </label> 
    <!-- <div v-if="errors" class="text-danger">{{ errors }}</div> -->

     <i v-if="hasFile.id" @click="deleteDocument(hasFile.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
     <a v-if="hasFile.id" :href="`${$page.props.blob_file_path}${hasFile.docPath}`" target="_blank">
     <i v-if="hasFile.id" class="fa fa-file-pdf h4 text-danger"></i>
    </a> 
    <input :disabled="$page.props.auth.has_company_admin_role" type="file" @change="upload($event)" accept=".pdf" :id="title" hidden>
     <br> 
</template>
<script>
import { CircleProgressBar } from 'circle-progress.vue';
import {ref} from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
    props: {
        docModel: Object,
        hasFile: Object,
        stage: String,
        errors: Object,
        title: String,
        belongsTo: String,
        doc_type: String,
        orderByNum: String
    },
    setup(props, context){
   
   let file_has_apostrophe = ref(false);

   const uploadDoc = useForm({
     licence_id: props.docModel.id,
     doc_type: props.doc_type,
     document_file: null,
     merge_number: props.orderByNum,
     belongs_to: props.belongsTo,
     stage: props.stage
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
      
   return {
     uploadDoc,upload, file_has_apostrophe,deleteDocument
   }
 },
 components: {
   CircleProgressBar
 }
}
</script>