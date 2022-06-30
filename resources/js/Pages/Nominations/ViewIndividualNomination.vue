<script setup>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link,useForm } from '@inertiajs/inertia-vue3';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    errors: Object,
    nominees: Array,
    licence: Object,
    success: String,
    error: String,
    nomination: Object,
    client_quoted: Object,//doc
    client_invoiced: Object,//doc
    liquor_board: Object,//doc
    nomination_forms: Object,//doc
    proof_of_payment: Object,//doc
    attorney_doc: Object,//doc
    certified_id_doc: Object,//doc
    police_clearance_doc: Object,//doc
    latest_renewal_doc: Object,//doc
    nomination_logded: Object,//doc
    nomination_issued: Object,//doc
    nomination_delivered: Object,//doc
});
let options = props.nominees;

const updateForm = useForm({
        nomination_year: props.nomination.year,
        nomination_id: props.nomination.id,
        client_paid_date: props.client_paid_date,
        status: [],      
});

const uploadDoc = useForm({
      document: null,
      doc_name: null,
      doc_type: null,
      date: null,
      nomination_id: props.nomination.id    
    })

const nomineeForm = useForm({//This handles attachment of nominees 
        selected_nominess: [],
        nomination_id: props.nomination.id
});

const getDocType = (doc_type) => {
  uploadDoc.doc_type = doc_type
}

function submitDocument(){
      uploadDoc.post('/submit-nomination-document', {
        preserveScroll: true,
        onSuccess: () => uploadDoc.reset(),
      })
    }

function deleteDocument(id){
    if(confirm('Document will be deleted permanently...Continue ??')){
      Inertia.delete(`/delete-nomination-document/${id}`, {
        //
      })
    }
  }

 function computeDocumentDate(date_param){
        return new Date(date_param).toLocaleString().split(',')[0]
    };

const saveNominneesToDatabase = () => {
    nomineeForm.post('/add-selected-nominees', {
        onSuccess: () => nomineeForm.reset(),
    })
}

const removeSelectedNominee = (nominee_id) => {
    Inertia.post(`/detach-nominee/${props.nomination.id}/${nominee_id}`)
}

const updateNomination = () => {
    updateForm.post(`/update-nominee`)
}


const pushData = (status_value) => {
     if (event.target.checked) {
         if(updateForm.status.includes(status_value)){
                   return;
                  }else{
                    updateForm.status.push(status_value)
                  } 
          }else if(!event.target.checked){
            updateForm.status.pop()
          }
}

function alertCompile(){
  alert('We have to get api fo this functionality...Please Confirm')
}
// 1= > Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Payment to the Liquor Board
// 5 => Select nominees
// 6 => Documents Required 
// 7 => Nomination Lodged 
// 8 => Nomination issued
// 9 => Nomination Delievered
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
<div class="page-header min-height-100 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
">
<span class="mask bg-gradient-success opacity-6"></span>
</div>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row">
<div class="col-lg-6 col-7">
<h6>Nomination Info:  {{ nomination.licence.trading_name }}</h6>
</div>
<div class="col-lg-6 col-5 my-auto text-end"></div>
</div>
<div class="row">
<div class="mt-3 row">
<div class="col-12 col-md-12 col-xl-12 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">
<form @submit.prevent="updateNomination">
<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-quoted" type="checkbox" @input="pushData($event.target.value)"
:checked="nomination.status >= '1'" value="1" class="active-checkbox">
<label for="client-quoted" class="form-check-label text-body text-truncate status-heading">Client Quoted</label>
</div>
</div>  

<div class="col-md-12 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="client_quoted !== null">
    <a :href="`/storage/${client_quoted.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="client_quoted !== null" class="mb-0 text-xs">{{ client_quoted.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="client_quoted !== null" @click="deleteDocument(client_quoted.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Client Quoted')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>

<hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-paid" type="checkbox" @input="pushData($event.target.value)"
:checked="nomination.status >= '2'" value="2" class="active-checkbox">
<label for="client-paid" class="form-check-label text-body text-truncate status-heading">Client Invoiced</label>
</div>
</div>

<div class="col-md-12 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="client_invoiced !== null">
    <a :href="`/storage/${client_invoiced.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="client_invoiced !== null" class="mb-0 text-xs">{{ client_invoiced.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="client_invoiced !== null" @click="deleteDocument(client_invoiced.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Client Invoiced')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i></a>
  </li>
</ul>
</div>

<hr>
<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-paid" type="checkbox" @input="pushData($event.target.value)"
:checked="nomination.status >= '3'" value="3" class="active-checkbox">
<label for="client-paid" class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div>

 <div class="col-md-6 columns">
  <div class="input-group input-group-outline null is-filled ">
  <label class="form-label">Date</label>
  <input type="date" class="form-control form-control-default" 
    v-model="updateForm.client_paid_date" >
  </div>
  <div v-if="errors.client_paid_date" class="text-danger">{{ errors.client_paid_date }}</div>
 </div>
<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="liquor-board" type="checkbox" @input="pushData($event.target.value)"
:checked="nomination.status >= '4'" value="4" class="active-checkbox">
<label for="liquor-board" class="form-check-label text-body text-truncate status-heading">Payment To The Liquor Board</label>
</div>
</div>

<div class="col-md-12 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="liquor_board !== null">
    <a :href="`/storage/${liquor_board.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="liquor_board !== null" class="mb-0 text-xs">{{ liquor_board.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
      <p v-if="liquor_board !== null" class="mb-0 text-xs">Date: {{ computeDocumentDate(liquor_board.date) }}</p>
    </div>
    <a v-if="liquor_board !== null" @click="deleteDocument(liquor_board.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Payment To The Liquor Board')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>
<hr>



<div class="col-md-11 d-flex columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="Select nominees" type="checkbox" @input="pushData($event.target.value)"
:checked="nomination.status >= '5'" value="5" class="active-checkbox">
</div>

<label for="Select nominees" class="form-check-label text-body text-truncate status-heading">Select Nominees To Add/Terminate</label>
</div>
<div class="col-md-1">
<button type="button" data-bs-toggle="modal" data-bs-target="#pop-modal" class="btn btn-sm btn-secondary">Add</button>
</div>

<div class="table-responsive mb-2">
<table class="table align-items-center mb-0">
<thead>
<tr>
<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
Full Name
</th>
<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
DOB
</th>
<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
ID Number
</th>

<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
Action
</th>

</tr>
</thead>
<tbody>
<tr v-for="person in nomination.people" :key="person.id" >
<td>
<div class="d-flex px-2 py-1" >

<div class="d-flex flex-column justify-content-left">
<Link :href="`/view-person/${person.slug}`"><h6 class="mb-0 text-sm">{{ person.full_name }} </h6></Link>                  
</div>
</div>
</td>
<td class="text-center">{{ person.date_of_birth }}</td>
<td class="text-center">{{ person.id_number }}</td>
<td class="text-center">
<i @click="removeSelectedNominee(person.id)" 
style="cursor: pointer;"
class="material-icons-round text-danger fs-10">highlight_off</i>
</td>
</tr>


</tbody>
</table>
</div>
<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="documents-required" type="checkbox" @input="pushData($event.target.value)"
:checked="nomination.status >= '6'" value="6" class="active-checkbox">
<label for="documents-required" class="form-check-label text-body text-truncate status-heading">Documents Required </label>
</div>
</div>

<div class="col-md-4 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="nomination_forms !== null">
    <a :href="`/storage/${nomination_forms.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Signed Nomination Forms </h6>
       <p v-if="nomination_forms !== null" class="mb-0 text-xs">{{ nomination_forms.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="nomination_forms !== null" @click="deleteDocument(nomination_forms.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Nomination Forms Signed')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>

<div class="col-md-4 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="proof_of_payment !== null">
    <a :href="`/storage/${proof_of_payment.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Proof Of Payment</h6>
       <p v-if="proof_of_payment !== null" class="mb-0 text-xs">{{ proof_of_payment.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="proof_of_payment !== null" @click="deleteDocument(proof_of_payment.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Proof of Payment')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i></a>
  </li>  
</ul>
</div>


<div class="col-md-4 columns">
<ul class="list-group">
   <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="attorney_doc !== null">
    <a :href="`/storage/${attorney_doc.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Power Of Attorney And Resolution Signed</h6>
       <p v-if="attorney_doc !== null" class="mb-0 text-xs">{{ attorney_doc.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="attorney_doc !== null" @click="deleteDocument(attorney_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Power of Attorney')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i></a>
  </li>  
</ul>
</div>

<div class="col-md-4 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="certified_id_doc !== null">
    <a :href="`/storage/${certified_id_doc.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">ID Documents certified</h6>
       <p v-if="certified_id_doc !== null" class="mb-0 text-xs">{{ certified_id_doc.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="certified_id_doc !== null" @click="deleteDocument(certified_id_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Certified ID')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i></a>
  </li>  
</ul>
</div>

<div class="col-md-4 columns">
<ul class="list-group">
   <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="police_clearance_doc !== null">
    <a :href="`/storage/${police_clearance_doc.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Police Clearances Certified</h6>
       <p v-if="police_clearance_doc !== null" class="mb-0 text-xs">{{ police_clearance_doc.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="police_clearance_doc !== null" @click="deleteDocument(police_clearance_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Police Clearances')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i></a>
  </li>  
</ul>
</div>

<div class="col-md-4 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="latest_renewal_doc !== null">
    <a :href="`/storage/${latest_renewal_doc.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Latest renewal/ licence</h6>
       <p v-if="latest_renewal_doc !== null" class="mb-0 text-xs">{{ latest_renewal_doc.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="latest_renewal_doc !== null" @click="deleteDocument(latest_renewal_doc.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Latest Renewal/Licence')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i></a>
  </li>  
</ul>
</div>

<div class="text-end">
<button type="button" @click="alertCompile" class="btn btn-sm btn-secondary">Compile &amp; Merge</button>
</div>
<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="nomination-logded" type="checkbox" @input="pushData($event.target.value)"
:checked="nomination.status >= '7'" value="7" class="active-checkbox">
<label for="nomination-logded" class="form-check-label text-body text-truncate status-heading">Nomination Lodged</label>
</div>
</div>

<div class="col-md-12 columns">
<ul class="list-group">
 <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="nomination_logded !== null">
    <a :href="`/storage/${nomination_logded.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="nomination_logded !== null" class="mb-0 text-xs">{{ nomination_logded.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
      <p v-if="nomination_logded !== null" class="mb-0 text-xs">Date: {{ computeDocumentDate(nomination_logded.date) }}</p>
    </div>
    <a v-if="nomination_logded !== null" @click="deleteDocument(nomination_logded.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Nomination Lodged')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i></a>
  </li>  
</ul>
</div>
<hr>




<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="nomination-issued" type="checkbox" @input="pushData($event.target.value)"
:checked="nomination.status >= '8'" value="8" class="active-checkbox">
<label for="nomination-issued" class="form-check-label text-body text-truncate status-heading">Nomination Issued</label>
</div>
</div>

<div class="col-md-12 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="nomination_issued !== null">
    <a :href="`/storage/${nomination_issued.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="nomination_issued !== null" class="mb-0 text-xs">{{ nomination_issued.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="nomination_issued !== null" @click="deleteDocument(nomination_issued.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Nomination Issued')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i></a>
  </li>  
</ul>
</div>
<hr>




<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="nomination-delivered" type="checkbox" @input="pushData($event.target.value)"
:checked="nomination.status >= '9'" value="9" class="active-checkbox">
<label for="nomination-delivered" class="form-check-label text-body text-truncate status-heading"> Nomination Delivered</label>
</div>
</div> 

<div class="col-md-12 columns">
<ul class="list-group">
 <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="nomination_delivered !== null">
    <a :href="`/storage/${nomination_delivered.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="nomination_delivered !== null" class="mb-0 text-xs">{{ nomination_delivered.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
      <p v-if="nomination_delivered !== null" class="mb-0 text-xs">Date: {{ computeDocumentDate(nomination_delivered.date) }}</p>
    </div>
    <a v-if="nomination_delivered !== null" @click="deleteDocument(nomination_delivered.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Nomination Delivered')" data-bs-toggle="modal" data-bs-target="#document-upload" class="mb-0 btn btn-link pe-3 ps-0 ms-auto" href="javascript:;">
    <i class="fa fa-upload h5 text-success" aria-hidden="true"></i></a>
  </li>   
</ul>
</div>
<hr>

<div class="col-md-6 columns">
<label class="form-label">Nomination Year </label>
<Datepicker v-model="updateForm.nomination_year" yearPicker />
<p v-if="errors.nomination_year" class="text-danger">{{ errors.nomination_year }}</p>
</div>

<div>
<button type="submit" :style="{float: 'right'}" class="btn btn-primary" :disabled="updateForm.processing">
<span v-if="updateForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
Save</button>
</div>
</div>
</form>
</div>
</div>
<hr class="vertical dark" />
</div>
<!-- //tasks were here -->

</div>

</div>
</div>
</div>

<div class="modal fade" id="pop-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Select person(s) To Nominate</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="saveNominneesToDatabase">
      <div class="modal-body">      
        <div class="row">
        <div class="col-md-12 columns">
<div class="input-group input-group-outline null is-filled">
<Multiselect
v-model="nomineeForm.selected_nominess"
placeholder="Search..."
mode="tags"
:options="options"
:searchable="true"
/>
</div>
<div v-if="errors.selected_nominess" class="text-danger">{{ errors.selected_nominess }}</div>
</div>
</div>   

  <div class="col-md-12" v-if="message">
   <div class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
   <span class="alert-icon"><i class=""></i></span>
   <span class="alert-text"> 
   <span class="text-sm">{{ message }}</span></span>
   <button type="button" class="btn-close d-flex justify-content-center align-items-center"
    data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="text-lg font-weight-bold">×</span>
    </button>
    </div>
    </div>
      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" :disabled="nomineeForm.processing">
         <span v-if="nomineeForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="document-upload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload <span class="text-success">{{ uploadDoc.doc_type }}</span> Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="submitDocument">
      <input type="hidden" v-model="uploadDoc.doc_type">
      <div class="modal-body">      
        <div class="row">
        <div class="col-md-12 columns">
        <div class="input-group input-group-outline null is-filled ">
        <label class="form-label">Document Name</label>
        <input type="text" required class="form-control form-control-default" 
         v-model="uploadDoc.doc_name" >
        </div>
        <div v-if="errors.doc_name" class="text-danger">{{ errors.doc_name }}</div>
        </div>

        <div class="col-md-12 columns" v-if="uploadDoc.doc_type !== 'Client Quoted'
        ">
        <div class="input-group input-group-outline null is-filled ">
        <label class="form-label">Date</label>
        <input type="date" required class="form-control form-control-default" 
         v-model="uploadDoc.date" >
        </div>
        <div v-if="errors.date" class="text-danger">{{ errors.date }}</div>
        </div>

        <div class="col-md-12 columns">
        <label for="licence-doc" class="btn btn-dark w-100" href="">Click To Select File</label>
         <input type="file" @input="uploadDoc.document = $event.target.files[0]"
         hidden id="licence-doc" accept=".pdf"/>
         <div v-if="errors.document" class="text-danger">{{ errors.document }}</div>
       </div>
       <div class="col-md-12">
          <progress v-if="uploadDoc.progress" :value="uploadDoc.progress.percentage" max="100">
         {{ uploadDoc.progress.percentage }}%
         </progress>
         </div>
         </div>   

  <div class="col-md-12" v-if="message">
   <div class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
   <span class="alert-icon"><i class=""></i></span>
   <span class="alert-text"> 
   <span class="text-sm">{{ message }}</span></span>
   <button type="button" class="btn-close d-flex justify-content-center align-items-center"
    data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="text-lg font-weight-bold">×</span>
    </button>
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


