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
    @click="updateRegistrationDate" type="button" class="btn btn-sm btn-secondary">Save</button>
  </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

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
    const form = useForm({
      licence_id: props.licence.id,
      stage: props.stage,
      dated_at: props.dated_at? props.dated_at : null
    })
    // console.log('form',form)

    function updateRegistrationDate(){
        form.patch(`/update-registration-date/${props.licence.id}`, {
          preserveScroll: true,
          onSuccess: () => { 
                    if(props.success){
                        notify(props.success)
                      }else if(props.error){
                        notify(props.error)
                      }
                      },
        })     
      }

      const notify = (message) => {
          if(props.success){
            toast.success(message, {
            autoClose: 2000,
          });
          
          }else if(props.error){
            toast.error(message, {
            autoClose: 2000,
          });
          }
        }

    return {
      form,updateRegistrationDate,toast,notify
    }
  }
}
</script>