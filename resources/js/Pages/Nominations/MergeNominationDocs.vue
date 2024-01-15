<template>
    
        <button :disabled="mergeCount <= 0" @click="mergeNomination" type="button" class="btn btn-sm btn-secondary mx-2">Compile &amp; Merge</button>

        <a v-if="nomination.merged_document"
           :href="`/storage/app/public/${nomination.merged_document}`"
           target="_blank" class="btn btn-sm btn-secondary">
           View
        </a>
    

</template>
<script>
import { defineEmits } from 'vue'

export default {
    props:{
       nomination: Object,
       mergeCount: Object
    },
    setup(props, context){
      defineEmits(['mergeDocs']);

     const mergeNomination = () => {
        context.emit('mergeDocs')
      }

      return {
        mergeNomination
      }
    }
}
</script>