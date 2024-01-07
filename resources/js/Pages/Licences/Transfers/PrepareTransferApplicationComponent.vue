<template>

    <div class="px-3 d-flex mb-2 active w-10">
        <i v-if="!hasFile <= 0" @click="setDocType(stage,docType,belongsTo,orderByNumber)" 
         class="fa fa-upload h5 " aria-hidden="true"></i>
         <a v-if="hasFile" :href="`${$page.props.blob_file_path}${hasFile.document}`" target="_blank">
          <i class="fa fa-file-pdf h5 mx-2 text-danger curser-pointer"></i>
          </a>
          
          <i v-if="hasFile" @click="deleteDocument(hasFile.id)" 
          class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i> 
        
      </div>
</template>
<script>
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
    }
}
</script>