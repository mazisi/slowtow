<template>
 
    <button 
    @click="merge('Company')"
    type="button" :style="{float: 'right'}" class="btn btn-sm btn-secondary" >
    <span v-if="mergeForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    <span class="visually-hidden">Loading...</span> Compile Application
    </button>
     <a :href="`/storage/app/public/${licence.merged_document}`" v-if="licence.merged_document" target="_blank" 
     class="ms-2 btn btn-sm btn-secondary">View</a> 

</template>

<script>
export default{
    props:{
        licence: Object,
        mergeForm: Object
    },
    setup(props, context) {

        const merge = () => {
            context.emit('time-to-merge');
        }
        return {
            merge
        }
    }
}
</script>