<template>
  <div class="row">
    <div class="col-md-7" >
  <button type="button" class="btn btn-outline-success w-95"> {{ docTitle }}</button>
 </div>
  <div class="col-md-1 d-flex">
    <span :class="{ 'd-none': uploadDoc.processing}">
    <label v-if="!hasFile.id" :for="uploadDoc.doc_type + stage">
      <i class="cursor-pointer fa fa-upload h5" aria-hidden="true"></i>
      <!-- <input type="file" @change="upload($event)" accept=".pdf" hidden :id="uploadDoc.doc_type + stage"> -->
    </label>
  </span>

    <span v-if="uploadDoc.progress" >
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

    <a v-if="hasFile.id" :href="`${$page.props.blob_file_path}${hasFile.docPath}`" target="_blank">
    <i class="fa fa-file-pdf h5 upload-icon mx-1"></i></a>
    <i v-if="hasFile.id" @click="deleteDocument(hasFile.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>
   
  </div>
  
</div>
</template>

<script>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { CircleProgressBar } from 'circle-progress.vue';

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

    return {
      upload,uploadDoc, file_has_apostrophe,
      deleteDocument
    }
  },
  components: {
    CircleProgressBar
  }
}
</script>