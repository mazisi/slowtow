<template>
  <button 
      :disabled="!allDocumentsUploaded"
      @click="mergeDocs"
       type="button" 
       class="btn btn-success w-65">
       {{ title }}
      </button>
       <a v-if="licence.merged_document" 
       :href="`/storage/app/public/${licence.merged_document}`" 
       target="_blank" class="btn btn-success w-20 mx-2">View</a>
</template>

<script>
import { computed } from 'vue';

export default{
  
  props: {
    errors: Object,
    success: Object,
    title: String,
    licence: Object
  },
  setup(props, context){
    function mergeDocs(event) {
      context.emit('stage-value-changed', event, props.stageValue);
    }
    let docCount =  props.licence.documents.filter(doc => doc.num !== null && doc.num !== '');
    const allDocumentsUploaded = computed(() => {
      return (
        docCount ? docCount.length >= 26 : ''
      );
    })

    return {
      mergeDocs,allDocumentsUploaded
    }
  }
}
</script>