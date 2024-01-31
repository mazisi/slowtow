<template>
    <div :class="'col-md-' + column" class="columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Date</label>
            <input type="date" class="form-control form-control-default" v-model="form.dated_at">
        </div>
        <div v-if="errors.dated_at" class="text-danger">{{ errors.dated_at }}</div>
    </div>
    <div class="col-md-1 columns">
        <button v-if="canSave"
                @click="emitDataToParent" type="button" class="btn btn-sm btn-secondary">Save</button>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import 'vue3-toastify/dist/index.css';
import { defineEmits } from 'vue'
import {toast} from "vue3-toastify";

export default{

    props: {
        stage : String,
        model: Object,
        canSave: Boolean,
        errors: Object,
        error: String,
        column: Number,
        success: String,
        dated_at : Date,
    },
    setup(props, context){
        const emit = defineEmits(['date-value-changed'])
        const form = useForm({
            model_id: props.model.id,
            stage: props.stage,
            dated_at:  props.dated_at
        })
        const notify = (message) => {
            if(props.success){
                toast.success(message, {
                    autoClose: 2000,
                });
                props.success='';
                props.error=''
            }else if(props.error){
                toast.error(message, {
                    autoClose: 2000,
                });
            }

        }

        function emitDataToParent(){
            context.emit('date-value-changed', form);
        }

        return {
            form,emit,emitDataToParent, notify
        }
    }
}
</script>
