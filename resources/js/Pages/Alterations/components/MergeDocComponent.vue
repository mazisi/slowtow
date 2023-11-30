<template>
    <ul class="list-group">
        <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
        <div class="me-3" v-if="hasFile.id">
        <a v-if="hasFile" :href="`${$page.props.blob_file_path}${hasFile.path}`" target="_blank">
        <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
        </a>    
        </div>
        <div class="d-flex align-items-start flex-column justify-content-center">
          <h6 class="mb-0 text-sm">{{ docTitle }} </h6>
          <p v-if="hasFile.id" class="mb-0 text-xs limit-file-name">
            {{ hasFile.document_name }}</p>
          <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
        </div>
        <button  v-if="hasFile.id" @click="deleteDocument(hasFile.path)" type="button" class="invisible  mb-0 btn btn-link pe-10 ps-0 ms-auto">
        <i class="fa fa-trash-o text-danger invisible h6" aria-hidden="true"></i>
        </button>
    
        <!-- <button v-else @click="getDocType(stage,docType,mergeNum)" type="button" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-10 ps-0 ms-auto">
        <i class="fa fa-upload" aria-hidden="true"></i>
        </button> -->

        <label class="pe-10 ps-0 ms-auto" v-if="!hasFile.id" :for="uploadDoc.doc_type">
          <i class="fa fa-upload" aria-hidden="true"></i>
            <input type="file" @change="upload($event)" accept=".pdf" hidden :id="uploadDoc.doc_type">
          </label>
      </li>
    </ul>
</template>
<style scoped>
.me-3 {
  margin-right: -1rem !important;
}
</style>
<script>
import { ref } from'vue'
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";

export default{
    props: {
    errors: Object,
    success: String,
    error: String,
    errors: Object,
    docType: String,
    docTitle: String,
    docModel: Object,
    stage: Number,
    hasFile: Object,
    mergeNum: Number
  },


setup(props){

    let file_has_apostrophe = ref();
    let file_name = ref('')

    const uploadDoc = useForm({
            document: null,
            doc_type: props.docType,
            doc_number: null,
            stage: null,
            file_name: file_name,
            alteration_id: props.docModel.id,
        });

    function submitDocument(){
            uploadDoc.post('/submit-alteration-document', {
              preserveScroll: true,
              onSuccess: () => { 
                this.uploadDoc.reset();
                this.show_file_name = false;
                document.querySelector('.modal-backdrop').remove();
                 if(props.success){
                   notify(props.success)
                 }else if(props.error){
                  notify(props.error)
                 }
                
           },
            })
          }

    function deleteDocument(id){
          if(confirm('Document will be deleted...Continue ??')){
            Inertia.delete(`/delete-alteration-document/${id}`, {
              onSuccess: () => { 
               if(props.success){
                  notify(props.success)
                }else if(props.error){
                  notify(props.error)
                }
              },
            })
          }
        }

      function getDocType(stage=null,doc_type, doc_number='') {
        uploadDoc.doc_type = doc_type;
        uploadDoc.doc_number = doc_number;
        uploadDoc.stage = stage;
        this.show_modal = true
      }

      function getDocumentType(document_path){
        if(document_path){
          return document_path.split('/')[1]
        }
        
      }

      function mergeDocuments(){
      Inertia.post(`/merge-alteration-documents/${props.alteration.id}`, {
              //
      })
    }

    return{
        uploadDoc,
        file_has_apostrophe,
        getDocType,
        getDocumentType,
        mergeDocuments,
        submitDocument,
        deleteDocument
    }
    }

}
</script>