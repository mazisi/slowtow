<template>
    <button type="button" class="btn btn-outline-success document-names">{{title }}</button>
    <a v-if="linkModel" :href="`${$page.props.blob_file_path}${linkModel.document}`" target="_blank">
  <i class="fa fa-link h5 mx-2 curser-pointer"  aria-hidden="true"></i>
  </a>

  <a v-else :disabled="true">
    <i class="fa fa-link h5 mx-2"  aria-hidden="true"></i>
    </a> 
    
    <br>
</template>
<script>
export default{
    props:{
        linkModel: Object,
        title: String
    }
}
</script>