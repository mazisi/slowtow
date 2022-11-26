<script>
import Layout from "../../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link,useForm } from '@inertiajs/inertia-vue3';


export default {
 props: {
    errors: Object,
    view_transfer: Object,
    old_transfer_forms: Object,
    current_transfer_forms: Object,
    smoking_affidavict: Object,
    old_poa_res_docs: Object,
    current_poa_res_docs: Object,
    old_shareholding: Object,
    current_shareholding: Object,
    old_cipc_certificate: Object,
    current_cipc_certificate: Object,
    company_docs: Object,
    id_docs: Object,
    police_clearance: Object,
    tax_clearance: Object,
    lta_certificate: Object,
    financial_interest: Object,
    landloard_letter: Object,
    representation: Object,
    index_page: Object,
    client_quoted: Object,
    client_invoiced: Object,
    payment_to_liquor_board: Object,
    original_licence: Object,
    transfer_logded: Object,
    activation_fee: Object,
    transfer_issued: Object,
    transfer_delivered: Object,
    latest_renewal: Object,
    scanned_application: Object,
    get_current_company: Object
  },

  setup (props) {
    let showMenu = false;

    const form = useForm({
          trading_name: props.view_transfer.licence.trading_name,
          new_company: props.view_transfer.company_id,
          old_company: props.view_transfer.licence.old_company[0].name,
          transfer_date: props.view_transfer.date,
          lodged_at: props.view_transfer.lodged_at,
          activation_fee_paid_at: props.view_transfer.activation_fee_paid_at,
          issued_at: props.view_transfer.issued_at,
          delivered_at: props.view_transfer.delivered_at,
          payment_to_liquor_board_at: props.view_transfer.payment_to_liquor_board_at,
          slug: props.view_transfer.slug,
          licence_id: props.view_transfer.id,
          status: [],
    })


    return {
      form,
      showMenu
    }
  },
  components: {
    Layout,
    Link,
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};
</script>
<style>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-top: -10px;
  margin-left: 3px;
}
.status-heading{
  font-weight: 700;
}
.fa-upload{
  cursor: pointer;
  }
  .document-names { width: 60%; }

.curser-pointer{ cursor: pointer;}
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
  <h5>Transfer Info for: <Link :href="`/view-licence?slug=${view_transfer.licence.slug}`" class="text-success">{{ view_transfer.licence.trading_name }}</Link></h5>
  <p class="text-sm mb-0">Current Stage: 
    <span class="font-weight-bold ms-1" v-if="view_transfer.status == '1'">Client Quoted</span>
   <span v-if="view_transfer.status == '2'" class="font-weight-bold ms-1">Client Invoiced</span>
   <span v-if="view_transfer.status == '3'" class="font-weight-bold ms-1">Client Paid</span>
   <span v-if="view_transfer.status == '4'" class="font-weight-bold ms-1">Prepare Transfer Application</span>
   <span v-if="view_transfer.status == '5'" class="font-weight-bold ms-1">Payment To The Liquor Board</span>
   <span v-if="view_transfer.status == '6'" class="font-weight-bold ms-1">Scanned Application</span>
   <span v-if="view_transfer.status == '7'" class="font-weight-bold ms-1">Application Logded</span>
   <span v-if="view_transfer.status == '8'" class="font-weight-bold ms-1">Activation Fee Paid</span>
   <span v-if="view_transfer.status == '9'" class="font-weight-bold ms-1">Transfer Issued</span>
   <span v-if="view_transfer.status == '10'" class="font-weight-bold ms-1">Transfer Delivered</span>
 </p>
  </div>
  <div class="col-lg-6 col-5 my-auto text-end">
    <button v-if="$page.props.auth.has_slowtow_user_role"
     @click="deleteTransfer" type="button" class="btn btn-sm btn-danger float-lg-end pe-4"> Delete</button>
  </div>
</div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="submit">
<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox"
:checked="view_transfer.status >= 1" type="checkbox" >
<label class="form-check-label text-body text-truncate status-heading">Client Quoted </label>
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
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="client_quoted !== null" class="mb-0 text-xs">{{ client_quoted.document_name }}</p>
    </div>

  </li>
</ul>  

<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" 
:checked="view_transfer.status >= 2" type="checkbox" >
<label class="form-check-label text-body text-truncate status-heading">Client Invoiced </label>
</div>
</div> 

<ul class=" list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="client_invoiced !== null">
    <a :href="`${$page.props.blob_file_path}${client_invoiced.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="client_invoiced !== null" class="mb-0 text-xs">{{ client_invoiced.document_name }}</p>
    </div>

  </li>
</ul>  

<hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" 
:checked="view_transfer.status >= 3" type="checkbox">
<label class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div>  
<hr>

<div class="col-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" 
:checked="view_transfer.status >= 4" type="checkbox">
<label class="form-check-label text-body text-truncate status-heading">Prepare Transfer Application</label>
</div>
</div> 
 <div class="col-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Previous Licence Holder</label>
    <input type="text" required readonly class="form-control form-control-default" :value="view_transfer.licence.old_company[0].name" >
     </div>
   <div v-if="errors.old_company" class="text-danger">{{ errors.old_company }}</div>
   </div>

   <div class="col-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="mx-6 mt-2">Transfered To</label>
     </div>
   </div>
   <div class="col-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Current Licence Holder</label>
    <input type="text" required readonly  
    class="form-control form-control-default" :value="get_current_company.name" >
     </div>
   </div>


<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10"> 
    
    <a v-if="old_transfer_forms !== null" target="_blank" :href="`${$page.props.blob_file_path}${old_transfer_forms.document}`">
      <i class="fa fa-file-pdf h5 mx-2 text-danger curser-pointer"></i>
      </a>
     
      
  </div>
  <button type="button" class=" w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Transfer Forms </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
    <a v-if="current_transfer_forms !== null" target="_blank" :href="`${$page.props.blob_file_path}${current_transfer_forms.document}`">
     <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
   </a>
   </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 mb-2 active w-10"> <i class="fa fa-times-circle h5" aria-hidden="true"></i> </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Smoking Affidavit </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10"> 
    <a v-if="smoking_affidavict !== null" target="_blank" :href="`${$page.props.blob_file_path}${smoking_affidavict.document}`">
     <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
   </a>
   </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
         
     <a v-if="old_poa_res_docs !== null" target="_blank" :href="`${$page.props.blob_file_path}${old_poa_res_docs.document}`">
      <i class="fa fa-file-pdf h5 mx-2 text-danger curser-pointer"></i>
    </a>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> POA & Res </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <a v-if="current_poa_res_docs !== null" target="_blank" :href="`${$page.props.blob_file_path}${current_poa_res_docs.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    </div>
</div>


<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
     
     <a v-if="old_shareholding !== null" target="_blank" :href="`${$page.props.blob_file_path}${old_shareholding.document}`">
      <i class="fa fa-file-pdf h5 mx-2 text-danger curser-pointer"></i>
    </a>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Shareholding </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <a v-if="current_shareholding !== null" target="_blank" :href="`${$page.props.blob_file_path}${current_shareholding.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    </div>
</div>


<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
     
     <a v-if="old_cipc_certificate !== null" target="_blank" :href="`${$page.props.blob_file_path}${old_cipc_certificate.document}`">
      <i class="fa fa-file-pdf h5 mx-2 text-danger curser-pointer"></i>
    </a>
    <i v-if="old_cipc_certificate !== null" @click="deleteDocument(old_cipc_certificate.id)" 
    class="fa fa-trash curser-pointer text-danger mx-2 h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> CIPC Certificate </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10"> 
     <a v-if="current_cipc_certificate !== null" target="_blank" :href="`${$page.props.blob_file_path}${current_cipc_certificate.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    </div>
</div>


<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Company Documents </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <a v-if="company_docs !== null" target="_blank" :href="`${$page.props.blob_file_path}${company_docs.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    </div>
</div>



<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> ID Documents </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <a v-if="id_docs !== null" target="_blank" :href="`${$page.props.blob_file_path}${id_docs.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    </div>
</div>



<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Police Clearances </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10"> 
     <a v-if="police_clearance !== null" target="_blank" :href="`${$page.props.blob_file_path}${police_clearance.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    </div>
</div>


<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Tax Clearance </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <a v-if="tax_clearance !== null" target="_blank" :href="`${$page.props.blob_file_path}${tax_clearance.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    </div>
</div>


<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> LTA Certificate </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
     <a v-if="lta_certificate !== null" target="_blank" :href="`${$page.props.blob_file_path}${lta_certificate.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Financial Interests </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10"> 
     <a v-if="financial_interest !== null" target="_blank" :href="`${$page.props.blob_file_path}${financial_interest.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Lease/Landlord Letter </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10"> 
     <a v-if="landloard_letter !== null" target="_blank" :href="`${$page.props.blob_file_path}${landloard_letter.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
     <a v-if="representation !== null" target="_blank" :href="`${$page.props.blob_file_path}${representation.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Representation </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
    </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
    
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Latest Renewal/Licence </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10"> 
     <a v-if="latest_renewal !== null" target="_blank" :href="`${$page.props.blob_file_path}${latest_renewal.document}`">
      <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
    </a>
    </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
        <a v-if="index_page !== null" target="_blank" :href="`${$page.props.blob_file_path}${index_page.document}`">
          <i class="fa fa-file-pdf h5 text-danger curser-pointer"></i>
        </a>
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Index Page </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
    <i class="fa fa-times-circle float-end h5" aria-hidden="true"></i>
    </div>
</div>

<div class="d-flex justify-content-center w-100">
  <div class="px-3 d-flex mb-2 active w-10">
   
     </div>
  <button type="button" class="w-30 px-3 mb-2 btn bg-gradient-success ms-2"> Proof Of Payment </button>
  <div class="px-3 mb-2 ms-2 d-flex  w-10">
    <a v-if="payment_to_liquor_board !== null" :href="`${$page.props.blob_file_path}${payment_to_liquor_board.document}`" target="_blank">
    <i class="fa fa-link float-end h5 "></i>
 </a>
    
    </div>
</div>

<hr>



<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox"
:checked="view_transfer.status >= 5" type="checkbox">
<label class="form-check-label text-body text-truncate status-heading">Payment To The Liquor Board</label>
</div>
</div> 

<div class="col-md-5 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.payment_to_liquor_board_at">
     </div>
   <div v-if="errors.payment_to_liquor_board_at" class="text-danger">{{ errors.payment_to_liquor_board_at }}</div>
   </div>


<ul class=" list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="payment_to_liquor_board !== null">
    <a :href="`${$page.props.blob_file_path}${payment_to_liquor_board.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="payment_to_liquor_board !== null" class="mb-0 text-xs">{{ payment_to_liquor_board.document_name }}</p>
    </div>

  </li>
</ul>  


<hr>
<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox"
:checked="view_transfer.status >= 6" type="checkbox">
<label class="form-check-label text-body text-truncate status-heading">Scanned Application</label>
</div>
</div>
<ul class=" list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="scanned_application !== null">
    <a :href="`${$page.props.blob_file_path}${scanned_application.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="scanned_application !== null" class="mb-0 text-xs">{{ scanned_application.document_name }}</p>
    </div>

  </li>
</ul>  
<hr/>

<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox"
:checked="view_transfer.status >= 6" type="checkbox">
<label class="form-check-label text-body text-truncate status-heading">Application Logded</label>
</div>
</div>

<div class="col-md-5 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.lodged_at">
     </div>
   <div v-if="errors.lodged_at" class="text-danger">{{ errors.lodged_at }}</div>
   </div>
<ul class=" list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="transfer_logded !== null">
    <a :href="`${$page.props.blob_file_path}${transfer_logded.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="transfer_logded !== null" class="mb-0 text-xs">{{ transfer_logded.document_name }}</p>
    </div>

  </li>
</ul>  
<hr>

<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox"
:checked="view_transfer.status >= 7" type="checkbox">
<label class="form-check-label text-body text-truncate status-heading">Activation Fee Paid</label>
</div>
</div>

<div class="col-md-5 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.activation_fee_paid_at">
     </div>
   <div v-if="errors.activation_fee_paid_at" class="text-danger">{{ errors.activation_fee_paid_at }}</div>
   </div>
<ul class=" list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="activation_fee !== null">
    <a :href="`${$page.props.blob_file_path}${activation_fee.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="activation_fee !== null" class="mb-0 text-xs">{{ activation_fee.document_name }}</p>
    </div>

  </li>
</ul>  
<hr>


<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox"
:checked="view_transfer.status >= 8" type="checkbox">
<label class="form-check-label text-body text-truncate status-heading">Transfer Issued</label>
</div>
</div> 

<div class="col-md-5 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.issued_at">
     </div>
   <div v-if="errors.issued_at" class="text-danger">{{ errors.issued_at }}</div>
   </div>

<ul class=" list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="transfer_issued !== null">
    <a :href="`${$page.props.blob_file_path}${transfer_issued.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="transfer_issued !== null" class="mb-0 text-xs">{{ transfer_issued.document_name }}</p>
    </div>

  </li>
</ul>  
<hr>

<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox"
:checked="view_transfer.status >= 9" type="checkbox">
<label class="form-check-label text-body text-truncate status-heading">Transfer Delivered</label>
</div>
</div> 

<div class="col-md-5 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.delivered_at">
     </div>
   <div v-if="errors.delivered_at" class="text-danger">{{ errors.delivered_at }}</div>
   </div>
<ul class=" list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="transfer_delivered !== null">
    <a :href="`${$page.props.blob_file_path}${transfer_delivered.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="transfer_delivered !== null" class="mb-0 text-xs">{{ transfer_delivered.document_name }}</p>
    </div>

  </li>
</ul>  
<hr>    </div>
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


  </Layout>
</template>
