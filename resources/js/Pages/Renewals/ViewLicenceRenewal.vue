<script>
import Layout from "../../Shared/Layout.vue";
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import LiquorBoardRequest from "../components/LiquorBoardRequest.vue";
import Banner from '../components/Banner.vue'

import { ref } from 'vue';

export default {
  props: {
    liqour_board_requests: Object,
    tasks: Object,
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    success: String,
    error: String,
    renewal: Object,
    client_invoiced: Object,//doc
    renewal_issued: Object,//doc
    client_quoted: Object,//doc
    renewal_doc: Object,
    liqour_board: Object
  },

  setup (props) {
    const year = ref(new Date().getFullYear());
    const body_max = ref(100);
    let show_modal = ref(true);  

    const form = useForm({
      year: props.renewal.date,
      licence_id: props.renewal.id,
      status: [],
      client_paid_at: props.renewal.client_paid_at,
      payment_to_liquor_board_at: props.renewal.payment_to_liquor_board_at,
      renewal_issued_at: props.renewal.renewal_issued_at,
      renewal_delivered_at: props.renewal.renewal_delivered_at,
      renewal_id: props.renewal.id,
      client_invoiced_at: props.renewal.client_invoiced_at
     })

    const uploadDoc = useForm({
      document: null,
      doc_type: null,
      date: null,
      renewal_id: props.renewal.id    
    })

    const createTask = useForm({
          body: '',
          model_type: 'Licence Renewal',
          model_id: props.renewal.id,
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

    function getDocType(doc_type){
      this.uploadDoc.doc_type = doc_type 
      this.show_modal =true   
    }

    function deleteDocument(id){
        if(confirm('Document will be deleted...Continue ??')){
          Inertia.delete(`/delete-renewal-document/${id}`, {
            //
          })
        }
      }

      function limit(string='', limit = 25) {
        if(string){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
        }

    function submitDocument(){
      uploadDoc.post('/submit-renewal-document', {
        preserveScroll: true,
        onSuccess: () => { 
          this.show_modal = false;
          document.querySelector('.modal-backdrop').remove()
          uploadDoc.reset();
         },
      })
    }

    function updateRenewal() {
      form.patch('/update-renewal', {
        preserveScroll: true,
      })
    }

    function getRenewalYear(date){
      let computed_date = new Date(date).getFullYear();
      return computed_date + 1;    
    }

      function deleteRenewal(){
        if(confirm('Are you sure you want to delete this licence renewal??')){
          Inertia.delete(`/delete-licence-renewal/${props.renewal.licence.slug}/${props.renewal.slug}`)
        }
      }

    

    function pushData(status_value){
         if (event.target.checked) {
            if(this.form.status.includes(status_value)){
                return;
              }else{
                this.form.status.push(status_value)
              } 
          }else if(!event.target.checked){
          // alert('unticked')
          }
      }

      let file_name = ref('');
      function getFileName(e){
        this.uploadDoc.document = e.target.files[0];
        this.file_name = e.target.files[0].name;
      }

    return { year,form,body_max,show_modal,getFileName, 
      file_name,
     updateRenewal,
     getRenewalYear, pushData,uploadDoc,
     getDocType, submitDocument,
     deleteDocument,
     createTask,
     submitTask,
     checkBodyLength,
     deleteRenewal,
     limit
     }
  },
   components: {
    Layout,
    Link,
    Head,
    Datepicker,
    LiquorBoardRequest,
    Banner
  },
  
};
//The following are status keys
// 1 => Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Payment To Liquor Board
// 5 => Renewal Issued
// 6 => Renewal Delivered

</script>
<style>
.columns{
  margin-bottom: 1rem;
}
.active-checkbox{
  margin-top: -10px;
  margin-left: 3px;
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
   <h6>Process Renewal for: {{ renewal.date  }}/{{ getRenewalYear(renewal.date)  }}
    <Link :href="`/view-licence?slug=${renewal.licence.slug}`" class="text-success">: {{ limit(renewal.licence.trading_name) }}</Link></h6>
    <p class="text-sm mb-0">Current Stage: 
       <span class="font-weight-bold ms-1" v-if="renewal.status == '1'">Client Quoted</span>
      <span v-if="renewal.status == '2'" class="font-weight-bold ms-1">Client Invoiced</span>
      <span v-if="renewal.status == '3'" class="font-weight-bold ms-1">Client Paid</span>
      <span v-if="renewal.status == '4'" class="font-weight-bold ms-1">Payment To The Liquor Board</span>
      <span v-if="renewal.status == '5'" class="font-weight-bold ms-1">Renewal Issued</span>
      <span v-if="renewal.status == '6'" class="font-weight-bold ms-1">Renewal Delivered</span>
    </p>
   
  </div>
  <div class="col-lg-6 col-5 my-auto text-end">
    <button v-if="$page.props.auth.has_slowtow_admin_role" @click="deleteRenewal" type="button" class="btn btn-sm btn-danger float-lg-end pe-4"> Delete</button>
  </div>
</div>

<div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="updateRenewal">

<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-quoted" class="active-checkbox" type="checkbox" 
:checked="renewal.status >= '1'"
@input="pushData(1)" value="1">
<label for="client-quoted" class="form-check-label text-body text-truncate status-heading">Client Quoted</label>
</div>
</div> 
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="client_quoted !== null">
    <a :href="`${$page.props.blob_file_path}${client_quoted.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 v-if="client_quoted == null" class="mb-0 text-sm">Document</h6>
      <h6 v-if="client_quoted !== null" class="mb-0 text-sm">{{ client_quoted.document_name }}</h6>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

    <a v-if="client_quoted !== null" @click="deleteDocument(client_quoted.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Client Quoted')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i>
    </a>
  </li>
</ul>    
<hr>


<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="client-invoiced"  type="checkbox" value="2"
@input="pushData(2)" 
:checked="renewal.status >= '2'">
<label for="client-invoiced" class="form-check-label text-body text-truncate status-heading">Client Invoiced</label>
</div>
</div>

<div class="col-md-4 columns">    
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Date</label>
  <input type="date" class="form-control form-control-default" v-model="form.client_invoiced_at" >
  </div>
  <div v-if="errors.client_invoiced_at" class="text-danger">{{ errors.client_invoiced_at }}</div>
</div>

<div class="col-md-2 columns">    
  <button class="btn btn-secondary btn-sm">Save</button>
</div>

<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="client_invoiced !== null">
    <a :href="`${$page.props.blob_file_path}${client_invoiced.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 v-if="client_invoiced == null" class="mb-0 text-sm">Document</h6>
      <h6 v-if="client_invoiced !== null" class="mb-0 text-sm">{{ client_invoiced.document_name }}</h6>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

    <a v-if="client_invoiced !== null" @click="deleteDocument(client_invoiced.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Client Invoiced')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i>
    </a>
  </li>
</ul>
<hr>

<div class="col-md-5 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="client-paid" type="checkbox" 
@input="pushData(3)" value="3" :checked="renewal.status >= '3'">
<label for="client-paid" class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div> 
<div class="col-md-1 columns"></div>
<template v-if="renewal.client_paid_at == null">
   <div class="col-md-4 columns" >
      <div class="input-group input-group-outline null is-filled ">
      <label class="form-label">Date</label>
      <input type="date" class="form-control form-control-default" v-model="form.client_paid_at">
       </div>
     <div v-if="errors.client_paid_at" class="text-danger">{{ errors.client_paid_at }}</div>
     </div> 
     
  <div class="col-md-1 columns">
      <button type="submit" class="btn btn-sm btn-secondary">Save</button>
  </div>
</template>

<template v-else>
  <div class="col-md-4 columns" >
     <div class="input-group input-group-outline null is-filled ">
     <label class="form-label">Date</label>
     <input type="date" class="form-control form-control-default" v-model="form.client_paid_at">
      </div>
    <div v-if="errors.client_paid_at" class="text-danger">{{ errors.client_paid_at }}</div>
    </div> 
    
 <div class="col-md-1 columns" v-if="$page.props.auth.has_slowtow_admin_role">
     <button type="submit" class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

   <hr>

<div class="col-md-5 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="payment" type="checkbox" @input="pushData(4)" value="4"
:checked="renewal.status >= '4'">
<label for="payment" class="form-check-label text-body text-truncate status-heading">Payment To The Liquor Board</label>
</div>
</div> 

<div class="col-md-1 columns"></div>

   <template v-if="renewal.payment_to_liquor_board_at == null">
    <div class="col-md-4 columns" >
       <div class="input-group input-group-outline null is-filled ">
       <label class="form-label">Date</label>
       <input type="date" class="form-control form-control-default" v-model="form.payment_to_liquor_board_at">
        </div>
      <div v-if="errors.payment_to_liquor_board_at" class="text-danger">{{ errors.payment_to_liquor_board_at }}</div>
      </div> 
      
   <div class="col-md-1 columns">
       <button type="submit" class="btn btn-sm btn-secondary">Save</button>
   </div>
 </template>
 
 <template v-else>
   <div class="col-md-4 columns" >
      <div class="input-group input-group-outline null is-filled ">
      <label class="form-label">Date</label>
      <input type="date" class="form-control form-control-default" v-model="form.payment_to_liquor_board_at">
       </div>
     <div v-if="errors.payment_to_liquor_board_at" class="text-danger">{{ errors.payment_to_liquor_board_at }}</div>
     </div> 
     
  <div class="col-md-1 columns" v-if="$page.props.auth.has_slowtow_admin_role">
      <button type="submit" class="btn btn-sm btn-secondary">Save</button>
  </div>
 </template>


<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="liqour_board !== null">
    <a :href="`${$page.props.blob_file_path}${liqour_board.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 v-if="liqour_board == null" class="mb-0 text-sm">Document</h6>
      <h6 v-if="liqour_board !== null" class="mb-0 text-sm">{{ liqour_board.document_name }}</h6>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

    <a v-if="liqour_board !== null" @click="deleteDocument(liqour_board.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Payment To The Liquor Board')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i>
    </a>
  </li>
</ul>
<hr>

<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="issued" type="checkbox" 
@input="pushData(5)" value="5" :checked="renewal.status >= 5">
<label for="issued" class="form-check-label text-body text-truncate status-heading"> Renewal Issued</label>
</div>
</div> 



   <template v-if="renewal.renewal_issued_at == null">
    <div class="col-md-4 columns" >
       <div class="input-group input-group-outline null is-filled ">
       <label class="form-label">Date</label>
       <input type="date" class="form-control form-control-default" v-model="form.renewal_issued_at">
        </div>
      <div v-if="errors.renewal_issued_at" class="text-danger">{{ errors.renewal_issued_at }}</div>
      </div> 
      
   <div class="col-md-1 columns">
       <button type="submit" class="btn btn-sm btn-secondary">Save</button>
   </div>
 </template>
 
 <template v-else>
   <div class="col-md-4 columns" >
      <div class="input-group input-group-outline null is-filled ">
      <label class="form-label">Date</label>
      <input type="date" class="form-control form-control-default" v-model="form.renewal_issued_at">
       </div>
     <div v-if="errors.renewal_issued_at" class="text-danger">{{ errors.renewal_issued_at }}</div>
     </div> 
     
  <div class="col-md-1 columns" v-if="$page.props.auth.has_slowtow_admin_role">
      <button type="submit" class="btn btn-sm btn-secondary">Save</button>
  </div>
 </template>

<!-- <div class="col-md-6 columns">
<Datepicker v-model="form.year" yearPicker />
<p v-if="errors.year" class="text-danger">{{ errors.year }}</p>
</div> -->

<div class="col-md-6" style="margin-top: -1rem;">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="renewal_issued !== null">
    <a :href="`${$page.props.blob_file_path}${renewal_issued.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 v-if="renewal_issued == null" class="mb-0 text-sm">Document</h6>
      <h6 v-if="renewal_issued !== null" class="mb-0 text-sm">{{ renewal_issued.document_name }}</h6>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="renewal_issued !== null" @click="deleteDocument(renewal_issued.id)" 
      class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a @click="getDocType('Renewal Issued')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;" v-else>
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i>
    </a>
  </li>
</ul>
</div>
<hr>




<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="delivered" type="checkbox" value="6"
@input="pushData(6)" :checked="renewal.status == '6'">
<label for="delivered" class="form-check-label text-body text-truncate status-heading"> Renewal Delivered</label>
</div>
</div>

   <template v-if="renewal.renewal_delivered_at == null">
    <div class="col-md-4 columns" >
       <div class="input-group input-group-outline null is-filled ">
       <label class="form-label">Date</label>
       <input type="date" class="form-control form-control-default" v-model="form.renewal_delivered_at">
        </div>
      <div v-if="errors.renewal_delivered_at" class="text-danger">{{ errors.renewal_delivered_at }}</div>
      </div> 
      
   <div class="col-md-1 columns">
       <button type="submit" class="btn btn-sm btn-secondary">Save</button>
   </div>
 </template>
 
 <template v-else>
   <div class="col-md-4 columns" >
      <div class="input-group input-group-outline null is-filled ">
      <label class="form-label">Date</label>
      <input type="date" class="form-control form-control-default" v-model="form.renewal_delivered_at">
       </div>
     <div v-if="errors.renewal_delivered_at" class="text-danger">{{ errors.renewal_delivered_at }}</div>
     </div> 
     
  <div class="col-md-1 columns" v-if="$page.props.auth.has_slowtow_admin_role">
      <button type="submit" class="btn btn-sm btn-secondary">Save</button>
  </div>
 </template>


<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="renewal_doc !== null">
    <a :href="`${$page.props.blob_file_path}${renewal_doc.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 v-if="renewal_doc == null" class="mb-0 text-sm">Document</h6>
      <h6 v-if="renewal_doc !== null" class="mb-0 text-sm">{{ renewal_doc.document_name }}</h6>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

    <a v-if="renewal_doc !== null" @click="deleteDocument(renewal_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Renewal Delivered')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i>
    </a>
  </li>
</ul>  

<div class="text-danger">
  <div v-if="form.isDirty" class="text-xs d-flex">You have unsaved changes.</div>
  <button :disabled="form.processing" :style="{float: 'right'}" class="btn btn-primary ms-2" type="submit">
  <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span class="visually-hidden">Loading...</span> Save</button>
</div>
</div>
</form>
  </div>
</div>
</div>
      <!-- //tasks were here -->
        
</div>

</div>
<hr>

<!-- <LiquorBoardRequest 
:model_type='`Licence Renewal`'
:model_id="renewal.id" 
:liqour_board_requests="liqour_board_requests"
/> 
<hr/>
-->
<div class="row">
<h6 class="text-center">Notes</h6>
<div class="col-xl-8">
<div class="row">
<div v-for="task in tasks" :key="task.id" class="mb-4 col-xl-12 col-md-12 mb-xl-0">
<div class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
<span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
<span class="text-sm">{{ task.body }}</span>
</span>
<!-- <button @click="deleteTask(task.id)" type="button" class="btn-close d-flex justify-content-center align-items-center" 
data-bs-dismiss="alert" aria-label="Close">
<i class="far fa-trash-alt me-2" aria-hidden="true"></i></button> -->
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
<div v-if="errors.body" class="text-danger">{{ errors.body }}</div>
</div>

<button :disabled="createTask.processing" class="btn btn-sm btn-secondary ms-2 mt-1 float-end justify-content-center" type="submit">
  <span v-if="createTask.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  Save
</button>
</form>
</div>
</div>


</div>
</div>

<div v-if="show_modal" class="modal fade" id="documents" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
