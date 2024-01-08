<template>
    <button type="button" class="btn btn-outline-success document-names">{{ title }} </button>
     <i v-if="!docModel"
      @click="getDocType(4,'Application Form','Company',1)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="docModel" @click="deleteDocument(docModel.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
     <a v-if="docModel" :href="`${$page.props.blob_file_path}${docModel.document}`" target="_blank">
     <i v-if="docModel" class="fa fa-file-pdf h4 text-danger"></i>
    </a> 
     <br> 
</template>
<script>
export default {
    props: {
        docModel: Object,
        title: String
    }
}
</script>