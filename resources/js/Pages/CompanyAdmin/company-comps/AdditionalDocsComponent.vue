<template>
  <div class="container mt-2 text-left">

    <div class="row justify-content-center">

      <div class="col-4 columns">
        <div class="input-group input-group-outline null is-filled">
          <label class="form-label">Documents/Information Submitted</label>
          <textarea v-model="doc_form.description" required class="form-control form-control-default" rows="1" ></textarea>
        </div>
        <div v-if="errors.description">{{ errors.description }}</div>
      </div>

      <div class="col-3 columns mb-4">

        <label for="attach-doc" class="btn mb-0 bg-gradient-dark btn-md null null">
          <input @change="getFileName" type="file" hidden id="attach-doc">
          <i class="fas fa-paperclip me-2" aria-hidden="true"></i> Attach Document </label>
        <div v-if="errors.document" class="text-danger">{{ errors.document }}</div>
        <div class="text-sm" v-if="file_name">File Selected: <span class="text-success">{{ file_name }}</span></div>
        <p v-if="file_has_apostrophe" class="text-danger text-sm mt-4">
          File cannot contain apostrophe(s).</p>
      </div>

      <div class="col-md-3 columns mb-4">
        <div class="input-group input-group-outline null is-filled ">
          <label class="form-label">Uploaded Date</label>
          <input v-model="doc_form.uploaded_at" type="date" class="form-control form-control-default">
        </div>
        <div v-if="errors.uploaded_at" class="text-danger">{{ errors.uploaded_at }}</div>
      </div>
      <div class="col-2 mb-3 text-end">
        <button @click="submit" type="button" class="btn btn-sm btn-info">
          <span v-if="doc_form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          Submit</button>
      </div>
    </div>
  </div>
  <table class="table table-bordered mt-3">
    <thead>
    <tr>
      <th scope="col">Request Description</th>
      <th scope="col">Uploaded Date</th>
      <th scope="col">View Document</th>
      <th scope="col">Edit</th>
    </tr>
    </thead>
    <tbody>
    <tr v-if="additional_docs?.length > 0" v-for="additional_doc in additional_docs" :key="additional_doc.id">
      <th>{{ additional_doc.description }}</th>
      <td>{{ additional_doc.uploaded_at }}</td>
      <td>
        <a v-if="additional_doc.path" :href="`${$page.props.blob_file_path}${additional_doc.path}`" target="_blank">
          <i class="fa fa-file-pdf text-lg text-danger" aria-hidden="true"></i></a>
      </td>
      <td> <i @click="deleteDocument(additional_doc.id)" class="cursor-pointer fa fa-trash-alt text-lg text-danger" aria-hidden="true"></i>
      </td>
    </tr>
    <tr v-else >
      <td class="text-danger text-center" colspan="6" > You have not uploaded any documents as yet</td>
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
import Paginate from '../../../Shared/Paginate.vue';
import { toast } from 'vue3-toastify';
import { ref } from 'vue';
import useToaster from '../../../store/useToaster'

export default{
  props: {
    additional_docs: Object,
    errors: Object,
    licence_id: Number,
    success: Object
  },
  setup(props){
    const { notifySuccess, notifyError } = useToaster();
    let file_has_apostrophe = ref('');
    let show_file_name = ref(false);
    let file_name = ref('');


    const doc_form = useForm({
      description: '',
      document: null,
      licence_id: props.licence_id,
      uploaded_at: ''
    })



    function getFileName(e){
      if(e.target.files[0].name.includes("'")){
        this.file_has_apostrophe = true
        return;
      }
      show_file_name = true;
      doc_form.document = e.target.files[0];
      this.file_name = e.target.files[0].name;
    }

    function submit(){
      doc_form.post(route('submit_additional_doc'), {
        onSuccess: () => {
          if(props.success){
            notifySuccess(props.success)
          }else if(props.error){
            notifyError(props.error)
          }
          doc_form.reset();
          doc_form.document = '';
        }
      })
    }



    function deleteDocument(id){
      if(confirm('Are you sure ?')){
        Inertia.delete(`/delete-additional-doc/${id}`, {
          preserveScroll: true,
          onSuccess: () => {
            if(props.success){
              notifySuccess(props.success)
            }else if(props.error){
              notifyError(props.error)
            }
          },
        });
      }
    }


    return{
      submit,deleteDocument, doc_form,getFileName,
      show_file_name,file_name,file_has_apostrophe
    }
  },
  components:{
    Paginate,
    toast
  }
}
</script>
