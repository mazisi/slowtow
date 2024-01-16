<template>
    
        <button type="button" class="btn btn-sm btn-secondary mx-2">Compile &amp; Merge</button>

        <a v-if="nomination.merged_document"
           :href="`/storage/app/public/${nomination.merged_document}`"
           target="_blank" class="btn btn-sm btn-secondary">
           View
        </a>
    

</template>
<script>
export default {
    props:{
       nomination: Object
    }
}
</script>