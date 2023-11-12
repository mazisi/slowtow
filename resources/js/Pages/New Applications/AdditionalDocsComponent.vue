<template>
  <div class="container mt-2 text-left">
    
        <div class="row justify-content-center">
            

            <div class="col-4 columns">    
              <div class="input-group input-group-outline null is-filled">
              <label class="form-label">Documents/Information Submitted</label>
              <textarea v-model="doc_form.description" required class="form-control form-control-default" rows="3" ></textarea>
              </div>
              <div v-if="errors.description">{{ errors.description }}</div>
              </div>

              <div class="col-3 columns mb-4">
                <label for="attach-doc" class="btn mb-0 bg-gradient-dark btn-md null null">
                  <input type="file" hidden id="attach-doc">
                  <i class="fas fa-paperclip me-2" aria-hidden="true"></i> Attach Document </label>
                  Doc name here
               </div> 

            <div class="col-md-3 columns mb-4">
              <div class="input-group input-group-outline null is-filled ">
              <label class="form-label">Uploaded Date</label>
              <input v-model="doc_form.uploaded_at" type="date" class="form-control form-control-default">
               </div>
             <div v-if="errors.uploaded_at" class="text-danger">{{ errors.uploaded_at }}</div>
             </div> 
            <div class="col-2 mb-3 text-end">
              <button @click="submit" type="button" class="btn btn-sm btn-info">Submit</button>
            </div>
        </div>
</div>
  <table class="table table-bordered mt-2">
    <thead>
      <tr>
        <th scope="col">Request Description</th>
        <th scope="col">Upload Date</th>
        <th scope="col">Edit</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>1</th>
        <td>Mark</td>
        <td>Otto</td>
      </tr>
           
    </tbody>
  </table>
   
</template>
<style scoped>
.table thead th {
  padding: 0;
  font-weight: 400;
  }
  .upload-btn{
    float: right!important;
  }
  #has-header{
margin-left: 3px;
}
#labelDescription {
          border: none;
          border: 1px solid #ced4da; 
          border-radius: 0;
          resize: none;
      }
</style>

<script>
  import { useForm } from '@inertiajs/inertia-vue3';
  import { Inertia } from '@inertiajs/inertia';
  import Paginate from '../../Shared/Paginate.vue';
  import { toast } from 'vue3-toastify';

  export default{
    props: {
      // tasks: Object,
      errors: Object,
      licence_id: Number,
      success: Object
    },
    setup(props){
      const doc_form = useForm({
            description: '',
            licence_id: props.licence_id,
            uploaded_at: ''     
      })
  
      function submit(){
        doc_form.post(route('submit_additional_doc'), {
            onSuccess: () => {
              if(props.success){
                notify(props.success)
              }else if(props.error){
              notify(props.error)
              }
              doc_form.reset();
            }
        })
      }
  
     

        function deletedoc(id){
        if(confirm('This doc will be deleted. Continue ?')){
          Inertia.delete(`/delete-doc/${id}`, {
             preserveScroll: true,
             onSuccess: () => { 
              if(props.success){
                notify(props.success)
              }else if(props.error){
                notify(props.error)
              }
             },
           }); 
        }
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

        return{
          submit,deletedoc,notify, doc_form
        }
    },
    components:{
      Paginate,
      toast
    }
  }
</script>