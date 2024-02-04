<template>
    <div class="row"><div class="col-md-7" >
      <button type="button" class="btn btn-outline-success w-95">{{ docTitle }}</button>
    </div>
    <a  v-if="hasFile.id" :href="`${$page.props.blob_file_path}${hasFile.docPath}`" target="_blank" class="col-md-1">
      <i class="fa fa-link h5 upload-icon col-md-3 disabled"></i>
    </a>
  </div>
</template>
<script>
export default {
    props : {
        hasFile: Object,
        docTitle: String
    }
}
</script>