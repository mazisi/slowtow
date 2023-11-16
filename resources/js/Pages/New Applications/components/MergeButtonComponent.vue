<template>
  <button 
      @click="mergeDocs"
       type="button" 
       class="btn btn-success w-65">
       {{ title }}
      </button>
       <a v-if="licence.merged_document !== null" 
       :href="`/storage/app/public/${licence.merged_document}`" 
       target="_blank" class="btn btn-success w-20 mx-2">View</a>
</template>

<script>

export default{
  
  props: {
    errors: Object,
    success: Object,
    title: String,
    licence: String
  },
  setup(props, context){
    function mergeDocs(event) {
      context.emit('stage-value-changed', event, props.stageValue);
    }

    return {
      mergeDocs
    }
  }
}
</script>