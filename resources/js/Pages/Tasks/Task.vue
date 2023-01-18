<template>
  <div class="row">
    <h6 class="text-center">Notes</h6>
    <div class="col-xl-8">
    <div class="row">
    <div v-for="task in tasks" :key="task.id" class="mb-4 col-xl-12 col-md-12 mb-xl-0">
    <div class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
    <span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
    <span class="text-sm">{{ task.body }}</span>
    </span>
    <p style=" font-size: 12px"><i class="fa fa-clock-o" ></i> {{ new Date(task.created_at).toLocaleString() }}</p>
    </div>
    </div>
    <h6 v-if="!tasks" class="text-center">No notes found.</h6>
    </div>
    
    </div>
    
    <div class="col-xl-4">
    <form @submit.prevent="submitTask">
    <div class="col-md-12 columns">
    <label class="form-check-label text-body text-truncate status-heading">New Note:
    <span><i class="fa fa-clock-o mx-2" aria-hidden="true"></i>{{ new Date().toISOString().split('T')[0] }}</span></label>
    </div>
    
    <div class="col-12 columns">    
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">New Task<span class="text-danger pl-6">
    ({{ body_max - createTask.body.length}}/{{ body_max }})</span></label>
    <textarea v-model="createTask.body" @input='checkBodyLength' class="form-control form-control-default" rows="3" ></textarea>
    </div>
    <div v-if="errors.model_id">{{ errors.model_id }}</div>
    </div>
    <button :disabled="createTask.processing" class="btn btn-sm btn-secondary ms-2 mt-1 float-end justify-content-center" type="submit">
      <span v-if="createTask.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      Save
    </button>
    </form>
    </div>
    </div>
  
</template>
<script>
  import { ref } from 'vue';
  import { useForm } from '@inertiajs/inertia-vue3';
  export default{
    props: {
      tasks: Object,
      errors: Object,
      model_id: Number,
      model_type: String
    },
    setup(props){
      const body_max = ref(100);
      const createTask = useForm({
            body: '',
            model_type: props.model_type,
            model_id: props.model_id,
            taskDate: ''     
      })
  
      function submitTask(){
        createTask.post('/submit-task', {
            onSuccess: () => createTask.reset(),
        })
      }
  
      function checkBodyLength(){//Monitor task body length..
            if(this.createTask.body.length > this.body_max){
                this.createTask.body = this.createTask.body.substring(0,this.body_max)
            }
        }
        return{
          createTask,submitTask,checkBodyLength,body_max
        }
    }
  }
</script>