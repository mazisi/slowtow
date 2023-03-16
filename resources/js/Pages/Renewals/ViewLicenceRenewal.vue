<script>
import Layout from "../../Shared/Layout.vue";
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import LiquorBoardRequest from "../components/LiquorBoardRequest.vue";
import Banner from '../components/Banner.vue';
import Task from "../Tasks/Task.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

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
    let show_modal = ref(true); 
    let show_file_name = ref(false); 
    let file_name = ref('');

    const form = useForm({
      year: props.renewal.date,
      licence_id: props.renewal.id,
      status: [],
      unChecked: false,
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
      stage: null,
      renewal_id: props.renewal.id    
    })

   
  

    function getDocType(stage=null,doc_type){
      this.show_file_name = true;
      this.uploadDoc.doc_type = doc_type;
      this.uploadDoc.stage = stage;
      this.show_modal =true   
    }

    function deleteDocument(id){
        if(confirm('Document will be deleted...Continue ??')){
          Inertia.delete(`/delete-renewal-document/${id}`, {
            onSuccess: () => {
              if(props.success){
                    notify(props.success)
                      }else if(props.error){
                        notify(props.error)
                      }
          }
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
      uploadDoc.post('/submit-renewal-documents', {
        preserveScroll: true,
        onSuccess: () => { 
          this.show_file_name = false;
          this.show_modal = false;
          document.querySelector('.modal-backdrop').remove();
          if(props.success){
                   notify(props.success)
                    }else if(props.error){
                      notify(props.error)
            }
          uploadDoc.reset();
         },
      })
    }

    function updateRenewal() {
      form.patch('/update-renewal', {
        preserveScroll: true,
        onSuccess: () => { 
            if(props.success){
                   notify(props.success)
                    }else if(props.error){
                      notify(props.error)
            }
         }
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

    

      function pushData(e,status_value){
          if (e.target.checked) {
            this.form.status[0] = status_value;
            this.form.unChecked = false;
          }else if(!e.target.checked){
            this.form.unChecked = true
            this.form.status[0] = e.target.value;
          }
          }

          function updateDate(){
            form.patch(`/update-renewal-date/${props.renewal.slug}`, {
             preserveScroll: true,
             onSuccess: () => { 
               if(props.success){
                   notify(props.success)
                    }else if(props.error){
                      notify(props.error)
                    }
           }
           }) 
          }

      let file_has_apostrophe = ref();
      function getFileName(e){
        this.uploadDoc.document = e.target.files[0];
        this.file_name = e.target.files[0].name;
        this.file_has_apostrophe = this.file_name.includes("'");
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

    return { year,form,show_modal,getFileName, notify,
      file_name,show_file_name,file_has_apostrophe,
     updateRenewal,updateDate,
     getRenewalYear, pushData,uploadDoc,
     getDocType, submitDocument,
     deleteDocument,
     deleteRenewal,
     limit,toast
     }
  },
   components: {
    Layout,
    Link,
    Head,
    Datepicker,
    LiquorBoardRequest,
    Banner,
    Task
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
<style scoped>
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

.limit-file-namee{
  width: 90px; 
  overflow: hidden;
  height: 16px
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
<template>
<Layout>
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row">
  <div class="col-lg-9 col-9">
   <h6>Process Renewal for: {{ renewal.date  }}/{{ getRenewalYear(renewal.date)  }}
    <Link :href="`/view-licence?slug=${renewal.licence.slug}`" class="text-success">: {{ renewal.licence.trading_name }}</Link></h6>
    <p class="text-sm mb-0">Current Stage: 
       <span class="font-weight-bold ms-1" v-if="renewal.status == '1'">Client Quoted</span>
      <span v-if="renewal.status == '2'" class="font-weight-bold ms-1">Client Invoiced</span>
      <span v-if="renewal.status == '3'" class="font-weight-bold ms-1">Client Paid</span>
      <span v-if="renewal.status == '4'" class="font-weight-bold ms-1">Payment To The Liquor Board</span>
      <span v-if="renewal.status == '5'" class="font-weight-bold ms-1">Renewal Issued</span>
      <span v-if="renewal.status == '6'" class="font-weight-bold ms-1">Renewal Delivered</span>
      <span v-else class="font-weight-bold ms-1"></span>
    </p>
   
  </div>
  <div class="col-lg-3 col-3 my-auto text-end">
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
@input="pushData($event,1)" value="1">
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
      <p v-if="client_quoted !== null" class="mb-0 text-sm">{{ client_quoted.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

    <a v-if="client_quoted !== null" @click="deleteDocument(client_quoted.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType(1,'Client Quoted')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i>
    </a>
  </li>
</ul>    
<hr>


<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="client-invoiced"  type="checkbox" value="2"
@input="pushData($event,2)" 
:checked="renewal.status >= '2'">
<label for="client-invoiced" class="form-check-label text-body text-truncate status-heading">Client Invoiced</label>
</div>
</div>


<template v-if="renewal.client_invoiced_at == null">
  <div class="col-md-4 columns" >
     <div class="input-group input-group-outline null is-filled ">
     <label class="form-label">Date</label>
     <input type="date" class="form-control form-control-default" v-model="form.client_invoiced_at">
      </div>
    <div v-if="errors.client_invoiced_at" class="text-danger">{{ errors.client_invoiced_at }}</div>
    </div> 
    
 <div class="col-md-1 columns">
     <button :disabled="!form.client_invoiced_at" @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<template v-else>
 <div class="col-md-4 columns" >
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.client_invoiced_at">
     </div>
   <div v-if="errors.client_invoiced_at" class="text-danger">{{ errors.client_invoiced_at }}</div>
   </div> 
   
<div class="col-md-1 columns" v-if="$page.props.auth.has_slowtow_admin_role">
    <button :disabled="!form.client_invoiced_at" @click="updateDate"  
    type="button" class="btn btn-sm btn-secondary">Save</button>
</div>
</template>

<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="client_invoiced !== null">
    <a :href="`${$page.props.blob_file_path}${client_invoiced.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 v-if="client_invoiced == null" class="mb-0 text-sm">Document</h6>
      <p v-if="client_invoiced !== null" class="mb-0 text-xs limit-file-namee">{{ client_invoiced.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

    <a v-if="client_invoiced !== null" @click="deleteDocument(client_invoiced.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType(2,'Client Invoiced')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i>
    </a>
  </li>
</ul>
<hr>

<div class="col-md-5 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="client-paid" type="checkbox" 
@input="pushData($event,3)" value="3" :checked="renewal.status >= '3'">
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
      <button :disabled="!form.client_paid_at" @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
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
     <button :disabled="!form.client_paid_at" @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

   <hr>

<div class="col-md-5 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="payment" type="checkbox" @input="pushData($event,4)" value="4"
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
       <button :disabled="!form.payment_to_liquor_board_at" @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
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
      <button :disabled="!form.payment_to_liquor_board_at" @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
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
      <p v-if="liqour_board !== null" class="mb-0 text-sm limit-file-namee">{{ liqour_board.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

    <a v-if="liqour_board !== null" @click="deleteDocument(liqour_board.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType(4,'Payment To The Liquor Board')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i>
    </a>
  </li>
</ul>
<hr>

<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="issued" type="checkbox" 
@input="pushData($event,5)" value="5" :checked="renewal.status >= 5">
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
       <button :disabled="!form.renewal_issued_at" @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
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
      <button :disabled="!form.renewal_issued_at" @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
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
      <p v-if="renewal_issued !== null" class="mb-0 text-sm limit-file-namee">{{ renewal_issued.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="renewal_issued !== null" @click="deleteDocument(renewal_issued.id)" 
      class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a @click="getDocType(5,'Renewal Issued')" data-bs-toggle="modal" data-bs-target="#documents" 
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
@input="pushData($event,6)" :checked="renewal.status == '6'">
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
       <button :disabled="!form.renewal_delivered_at" @click="updateDate" type="button" class="btn btn-sm btn-secondary">Save</button>
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
      <button :disabled="!form.renewal_delivered_at" @click="updateDate"  
      type="button" class="btn btn-sm btn-secondary">Save</button>
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
      <p v-if="renewal_doc !== null" class="mb-0 text-sm limit-file-namee">{{ renewal_doc.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

    <a v-if="renewal_doc !== null" @click="deleteDocument(renewal_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType(6,'Renewal Delivered')" data-bs-toggle="modal" data-bs-target="#documents" 
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
<Task :tasks="tasks" :model_id="renewal.id" :errors="errors" :model_type="'Licence Renewal'"/>


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
        <label for="licence-doc" class="btn btn-dark w-100">Click To Select File</label>
         <input type="file" @change="getFileName"
         hidden id="licence-doc" accept=".pdf"/>
         <div v-if="errors.document" class="text-danger">{{ errors.document }}</div>
         <div v-if="file_name && show_file_name">File uploaded: <span class="text-success" v-text="file_name"></span></div>
         <p v-if="file_has_apostrophe" class="text-danger text-sm mt-4">Sorry <span class="text-success">{{ file_name }}</span> cannot contain apostrophe(s).Replace apostrophes with backticks.</p>  
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
        <button type="submit" class="btn btn-primary" :disabled="uploadDoc.processing || file_has_apostrophe">
         <span v-if="uploadDoc.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      
      </form>
    </div>
  </div>
</div>
  </Layout>
</template>
