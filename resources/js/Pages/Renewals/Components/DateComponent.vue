<template>
    <div :class="'col-md-' + column" class="columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Date{{dated_at}}</label>
            <input type="date" class="form-control form-control-default" v-model="form.dated_at">
        </div>
        <div v-if="errors.dated_at" class="text-danger">{{ errors.dated_at }}</div>
    </div>
    <div class="col-md-1 columns">
        <button v-if="canSave"
                @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import 'vue3-toastify/dist/index.css';
import { defineEmits } from 'vue'
import {toast} from "vue3-toastify";
import useToaster from '../../../store/useToaster';

export default{

    props: {
        stage : String,
        renewal: Object,
        canSave: Boolean,
        errors: Object,
        error: String,
        column: Number,
        success: String,
        dated_at : Date,
    },
    setup(props, context){
        const emit = defineEmits(['date-value-changed'])
      const { notifySuccess, notifyError } = useToaster();

        const form = useForm({
            renewal_id: props.renewal.id,
            stage: props.stage,
            dated_at:  props.dated_at
        })
     
        function updateDate(){
            form.patch(`/update-renewal-date/${props.renewal.slug}`, {
                preserveScroll: true,
                onSuccess: () => {
                    if(props.success){
                        notifySuccess(props.success)
                    }else if(props.error){
                        notifyError(props.error)
                    }
                }
            })
        }


        return {
            form,emit,updateDate
        }
    }
}
</script>
