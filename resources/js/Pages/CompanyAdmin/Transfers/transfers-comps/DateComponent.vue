<template>
    <div :class="'col-md-' + column" class="columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Date</label>
            <input type="date" class="form-control form-control-default" :disabled="true" v-model="form.dated_at">
        </div>
        <div v-if="errors.dated_at" class="text-danger">{{ errors.dated_at }}</div>
    </div>
    <div class="col-md-1 columns">
        <!-- <button v-if="shouldShowSaveButton" @click="emitDataToParent" type="button" class="btn btn-sm btn-secondary">Save</button> -->
    </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import 'vue3-toastify/dist/index.css';
import { defineEmits, computed } from 'vue'

export default{

    props: {
        stage : String,
        licence: Object,
        canSave: Boolean,
        errors: Object,
        error: String,
        column: Number,
        success: String,
        dated_at: String
    },
    setup(props, context){
        const emit = defineEmits(['date-value-changed'])
        const form = useForm({
            licence_id: props.licence.id,
            stage: props.stage,
            dated_at: props.dated_at? props.dated_at : null
        })
        // console.log('form',form)

        function emitDataToParent(){
            context.emit('date-value-changed', form);
        }

        const shouldShowSaveButton = computed(() => {
          return !props.dated_at || props.canSave;
        })

        return {
            form,emit,emitDataToParent,shouldShowSaveButton
        }
    }
}
</script>
