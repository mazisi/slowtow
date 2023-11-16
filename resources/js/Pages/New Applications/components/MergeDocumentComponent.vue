<template>
  <div class="row">
    <div class="col-md-7" >
  <button type="button" class="btn btn-outline-success w-95"> {{ docTitle }}</button>
 </div>
  <div class="col-md-1 d-flex">
    <i v-if="docModel" 
    @click="getDocType(stage,docType,mergeNum)" data-bs-toggle="modal" data-bs-target="#documents" 
    class="fa fa-upload h5 upload-icon" ></i>
    <a v-else :href="`${$page.props.blob_file_path}${gba_application_form.document_file}`" target="_blank">
    <i class="fa fa-file-pdf h5 upload-icon"></i></a>
    <i v-if="gba_application_form !== null" @click="deleteDocument(gba_application_form.id)" class="fa fa-trash h5 text-danger upload-icon mx-1" ></i>
    
  </div>
</div>
</template>

<script>

export default{
  
  props: {
    errors: Object,
    model_id: Number,
    success: Object,
    docType: String,
    docTitle: String,
    docModel: String,
    stage: Number,
    mergeNum: Number
  },
  setup(props, context){
    function emitValue(event) {
      context.emit('stage-value-changed', event, props.stageValue);
    }

    return {
      emitValue
    }
  }
}
</script>