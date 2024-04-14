<script>
import { useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import useToaster from '../../store/useToaster';
import { CircleProgressBar } from 'circle-progress.vue';

export default {
    props: [
        'doc_model', 'licence', 'errors', 'error', 'success',
        'doc_model',
        'doc_type','doc_title'
    ],
    setup(props) {
    const { notifySuccess, notifyError } = useToaster();
        let file_has_apostrophe = false;
        const originalLicenceForm = useForm({
          document_file: null,
          licence_id: props.licence.id,
          doc_type: null,
       })
        function removeFilePath(file_name){
        if(file_name.includes('mrnlabs')){
          let getFileName = file_name.split('/');
            return  getFileName[1];
          }  
            return file_name;
      }

      function upload(e, doc_type){
            originalLicenceForm.document_file = e.target.files[0];
            this.file_name = e.target.files[0].name;
            file_has_apostrophe=false;
            originalLicenceForm.doc_type=doc_type

            if(e.target.files[0].name.includes("'")){
              this.file_has_apostrophe = true
              return;
            }

            originalLicenceForm.post(`/upload-licence-document`, {
              preserveScroll: true,
              onSuccess: () => { 
                    if(props.success){
                     
                       notifySuccess(props.success)
                        }else if(props.error){
                          notifyError(props.error)
                    }
             },
            })
            e.target.value = '';  
          }

          function deleteDocument(id){
          if(confirm('Document will be deleted permanently!! Continue??')){
            Inertia.delete(`/delete-licence-document/${id}`,{
              onSuccess: () => { 
                if(props.success){
                   notifySuccess(props.success)
                    }else if(props.error){
                      notifyError(props.error)
                    }
               },
            })
          }
        }
      
        return {
            removeFilePath,
            originalLicenceForm,
            upload,deleteDocument,
            file_has_apostrophe
        }
    },
    components: {
        CircleProgressBar
    }
}
</script>
<template>
<template v-if="doc_model">
    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="doc_model">
    <a v-if="doc_model" :href="`${$page.props.blob_file_path}${doc_model.document_file}`" target="_blank">
    <i class="fa fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">{{ doc_title }}</h6>
      <p v-if="doc_model" class="mb-0 text-xs">
        {{ doc_model.document_name ? removeFilePath(doc_model.document_name) : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <!-- <button @click="deleteDocument(doc_model.id)" type="button" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button> -->
  </li>

</template>

<template v-else>
<div class="d-flex">
    <h6 class="mb-0 text-sm">{{ doc_title }}</h6>
      <p v-if="doc_model" class="mb-0 text-xs d-block">
        {{ doc_model.document_name ? removeFilePath(doc_model.document_name) : '' }}</p>
      <!-- <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p> -->

    <label :for="doc_type"
    type="button" :class="{ 'd-none': originalLicenceForm.processing}" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
        <i class="fa fa-upload" aria-hidden="true"></i>
        <input type="file" @change="upload($event,doc_type)" accept=".pdf" hidden :id="doc_type">
    </label>

    

    <span v-if="originalLicenceForm.progress" >
        <CircleProgressBar
            :value="originalLicenceForm.progress.percentage"
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
</template>