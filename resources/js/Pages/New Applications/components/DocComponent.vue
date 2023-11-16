<template>
  <ul class="list-group">
    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
      <div class="avatar me-3" v-if="documentModel">
      <a :href="`${$page.props.blob_file_path}${documentModel.document_file}`" target="_blank">
      <i class="fa fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
      </a>
      </div>
  
     <div class=" d-flex align-items-start flex-column justify-content-center">
      <h6 v-if="!documentModel" class="mb-0 text-sm">Document</h6>
      <h6 v-else-if="documentModel" class="mb-0 text-sm limit-file-name">{{ documentModel.document_name }}</h6>
      </div>
  
      <a v-if="documentModel" @click="deleteDocument(documentModel.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
      <i class="fa fa-trash text-danger h5" aria-hidden="true"></i>
      </a>
      <a v-else @click="emitValue(orderByNumber,docType)" data-bs-toggle="modal" data-bs-target="#documents" 
      class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
      <i class="fa fa-upload h5 " aria-hidden="true"></i>
      </a>
    </li>
  </ul> 
</template>
<script>

export default{
  
  props: {
    documentModel: Object,
    errors: Object,
    orderByNumber: Number,
    docType: String,
    success: Object
  },
  setup(props, context){
    function emitValue(event) {
      context.emit('pick-document', event, props.docType);
    }

    return {
      emitValue
    }
  }
}
</script>