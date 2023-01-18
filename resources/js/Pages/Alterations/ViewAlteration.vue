<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import Banner from '../components/Banner.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref } from "vue";
import Task from "../Tasks/Task.vue";

export default {
  name: "ViewAlteration",
 props: {
    errors: Object,
    alteration: Object,
    success: String,
    error: String,
    tasks: Object,
    client_invoiced: Object,  //doc
    alteration_letter: Object,   //doc
    site_map_file: Object   //doc


  },
  setup(props) {
      let showMenu = false;
      let show_modal = ref(true);

    const form = useForm({
         alteration_date: props.alteration.date,
         licence_slug: props.alteration.licence.slug,
         slug: props.alteration.slug,
         description: props.alteration.description,
         status: [],
         invoiced_at: props.alteration.invoiced_at      
      })

      const uploadDoc = useForm({
            document: null,
            doc_type: null,
            alteration_id: props.alteration.id    
          })

    function update() {
          form.patch(`/update-alteration`)
        }

    function pushData(event){
      if(this.form.status.includes(event)){
        return;
      }else{
        this.form.status.push(event)
      }
      
    }

    let file_name = ref('');
      function getFileName(e){
        this.uploadDoc.document = e.target.files[0];
        this.file_name = e.target.files[0].name;
      }

      function submitDocument(){
            uploadDoc.post('/submit-alteration-document', {
              preserveScroll: true,
              onSuccess: () => { 
                this.show_modal = false;
                let dismiss = document.querySelector('.modal-backdrop') 
                dismiss.remove();
                uploadDoc.reset();
           },
            })
          }

      function deleteDocument(id){
          if(confirm('Document will be deleted...Continue ??')){
            Inertia.delete(`/delete-alteration-document/${id}`, {
              //
            })
          }
        }

      function getDocType(doc_type) {
        uploadDoc.doc_type = doc_type
        this.show_modal = true
      }

      function getDocumentType(document_path){
        if(document_path){
          return document_path.split('/')[1]
        }
        
      }
     function deleteAlteration(slug, licence_slug=props.alteration.licence.slug){
       if (confirm('Are you sure you want to delete this alteration?')) {
        Inertia.delete(`/delete-altered-licence/${slug}/${licence_slug}`)
      }
    }

    return {
      form,showMenu,show_modal,
      update,update,
      pushData,file_name,
      getFileName,
      submitDocument,
      deleteDocument,
      getDocType,
      uploadDoc,getDocumentType,
      deleteAlteration
    };
  },
    
  components: {
    Layout,
    Multiselect,
    Link,
    Banner,
    Task
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};
//Status keys:
//1 => Client Invoiced
//2 => Client Paid
//3 => Alteration Details Captured
//4 => Alteration Complete
</script>
<style>
.columns{
  margin-bottom: 1rem;
}
.active-checkbox{
  margin-top: -10px;
}
.status-heading{
  font-weight: 700;
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
<template>
<Layout>
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row">
  <div class="col-lg-6 col-7">
   <h6> Alteration Info for: 
    <Link :href="`/view-licence?slug=${alteration.licence.slug}`">
      <span class="text-success">{{ alteration.licence.trading_name }}</span></Link></h6>
  </div>
  <div class="col-lg-6 col-5 my-auto text-end">
    <button v-if="$page.props.auth.has_slowtow_admin_role" 
    @click="deleteAlteration(alteration.slug)" type="button" class="btn btn-sm btn-danger float-lg-end pe-4"> Delete</button>
  </div>
</div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="update">
<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" @input="pushData(1)" type="checkbox" value="1" :checked="alteration.status >= '1'">
<label for="client-invoiced" class="form-check-label text-body text-truncate status-heading">Client Invoiced</label>
</div>
</div>  
<hr/>

<div class="col-9 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="client_invoiced !== null">
    <a :href="`${$page.props.blob_file_path}${client_invoiced.path}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Scanned Invoice</h6>
       <p v-if="client_invoiced !== null" class="mb-0 text-xs">{{ getDocumentType(client_invoiced.path) }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="client_invoiced !== null" @click="deleteDocument(client_invoiced.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Client Invoiced')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li> 
</ul>
</div>
<div class="col-3 columns">
  <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Invoice Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.invoiced_at">
    </div>
     <div v-if="errors.invoiced_at" class="text-danger">{{ errors.invoiced_at }}</div>
</div>
<hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="client-paid" @input="pushData(2)" type="checkbox" value="2" :checked="alteration.status >= '2'">
<label for="client-paid" class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div> 
<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="alteration-details" @input="pushData(3)" type="checkbox" value="3" :checked="alteration.status >= '3'">
<label class="form-check-label text-body text-truncate status-heading" for="alteration-details">Alteration Details Captured</label>
</div>
</div> 
<hr>

  <div class="col-3 columns">
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Alteration Date *</label>
    <input type="date" class="form-control form-control-default" v-model="form.alteration_date">
    </div>
     <div v-if="errors.alteration_date" class="text-danger">{{ errors.alteration_date }}</div>
  </div>

  <div class="col-9 columns">    
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Description</label>
<input type="text" class="form-control form-control-default" v-model="form.description" >
</div>
<div v-if="errors.description" class="text-danger">{{ errors.description }}</div>
</div>


<div class="col-12 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="alteration_letter !== null">
    <a :href="`${$page.props.blob_file_path}${alteration_letter.path}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Alteration Letter</h6>
       <p v-if="alteration_letter !== null" class="mb-0 text-xs">{{ getDocumentType(alteration_letter.path)}}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="alteration_letter !== null" @click="deleteDocument(alteration_letter.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Alteration Letter')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li> 
</ul>
</div>

<div class="col-12 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="site_map_file !== null">
    <a :href="`${$page.props.blob_file_path}${site_map_file.path}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">SiteMap File</h6>
       <p v-if="site_map_file !== null" class="mb-0 text-xs">{{ getDocumentType(site_map_file.path) }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="site_map_file !== null" @click="deleteDocument(site_map_file.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('SiteMap File')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 " aria-hidden="true"></i></a>
  </li> 
</ul>
</div>
<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input style="margin-top: -7px" 
id="active-checkbox" @input="pushData(4)" type="checkbox" value="4" :checked="alteration.status >= '4'">
<label class="form-check-label text-body text-truncate status-heading"> Alteration Complete</label>
</div>
</div> 

<div>
  <button type="submit" class="btn btn-sm btn-secondary ms-2" :style="{float: 'right'}">Save</button></div>
            </div>
            </form>
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
      <Task :tasks="tasks" :model_id="alteration.id" :errors="errors" :model_type="'Alteration'"/>
        
        </div>
        
      </div>
    </div>
  </div>


  <div v-if="show_modal" class="modal fade" id="document-upload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="submitDocument">
      <input type="hidden" v-model="uploadDoc.doc_type">
      <div class="modal-body">      
        <div class="row">
        <div class="col-md-12 columns">
        <label for="licence-doc" class="btn btn-dark w-100" href="">Click To Select File</label>
         <input type="file" @change="getFileName"
         hidden id="licence-doc" accept=".pdf"/>
         <div v-if="errors.document" class="text-danger">{{ errors.document }}</div>
         <div v-if="file_name">File uploaded: <span class="text-success" v-text="file_name"></span></div>
       </div>
       <div class="col-md-12">
          <progress v-if="uploadDoc.progress" :value="uploadDoc.progress.percentage" max="100">
         {{ uploadDoc.progress.percentage }}%
         </progress>
         </div>
         </div>   


      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" :disabled="uploadDoc.processing">
         <span v-if="uploadDoc.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
  </Layout>
</template>
