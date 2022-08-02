<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link,useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';


export default {
 props: {
    errors: Object,
    view_transfer: Object,
    companies_dropdown: Array,
    success: String,
    error: String,
    tasks: Object,
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
    index_page: Object
  },

  setup (props) {
    let showMenu = false;
    let show_modal = ref(true);
    let options = props.companies_dropdown;

    const form = useForm({
          trading_name: props.view_transfer.licence.trading_name,
          new_company: props.view_transfer.company_id,
          old_company: props.view_transfer.licence.old_company[0].name,
          transfer_date: props.view_transfer.date,
          slug: props.view_transfer.slug,
          licence_id: props.view_transfer.id,
          status: [],
    })

    const documentsForm = useForm({
      document_type: '',
      belong_to: '',
      document_number: '',
      document: null
    })
    
    mergeForm = useForm({
      transfer_id: props.view_transfer.id
    })

        function submit(){
           form.post(`/update-licence-transfer/${props.licence.slug}`, {
           preserveScroll: true,
           onSuccess: () => {
            //  do something..       
           },
          })  
        }

      function submitDocuments(){
          documentsForm.post(`/submit-transfer-documents/${props.view_transfer.id}`, {
          preserveScroll: true,
          onSuccess: () => {
            documentsForm.reset();
           this.show_modal = false;
           let dismiss =  document.querySelector('.modal-backdrop')    
           dismiss.remove();
          },
        })    
        }

        function setDocType(doc_type,belong_to,document_number){
          this.show_modal=true;
          documentsForm.document_type = doc_type
          documentsForm.belong_to = belong_to
          documentsForm.document_number = document_number
        }

      function mergeDocuments(){
        mergeForm.post(`/merge-transfer-documents/`, {
          preserveScroll: true,
          onSuccess: () => {
            //do something
          }
        })
      }

      function assignActiveValue(event){
        this.form.active = event
      }

      function pushData(status_value) {
          if (event.target.checked) {
              if(form.status.includes(status_value)){
                        return;
                        }else{
                          form.status.push(status_value)
                        } 
                }else if(!event.target.checked){
                  form.status.pop()
                }
      }

      
    return {
      pushData,
      mergeDocuments,
      submitDocuments,
      submit,
      options,
      show_modal,
      assignActiveValue,
      documentsForm,
      setDocType,
      form
    }
  },
  components: {
    Layout,
    Multiselect,
    Link
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

//The following are status keys
// 1 => Deposit Paid
// 2 => Collate Transfer Details
// 3 => Client Invoiced
// 4 => Client Paid
// 5 => Transfer Logded
// 6 => Certificate Received
//7 => Transfer Complete And Delivered
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
.fa-cloud-upload{
  cursor: pointer;
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
  <h5>Transfer Info for: {{ view_transfer.licence.trading_name }}</h5>
  
  </div>
  <div class="col-lg-6 col-5 my-auto text-end"></div>
</div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="submit">
<div class="row">
<!-- <input type="hidden" v-model="form.old_company_id"> 
<input type="hidden" v-model="form.licence_id">  -->
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" @input="pushData($event.target.value)"
:checked="view_transfer.status >= 1" type="checkbox" value="1">
<label class="form-check-label text-body text-truncate status-heading">Deposit Quoted </label>
</div>
</div>     

<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" @input="pushData($event.target.value)"
:checked="view_transfer.status >= 2" type="checkbox" value="2">
<label class="form-check-label text-body text-truncate status-heading">Client Invoiced </label>
</div>
</div>   
<hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" @input="pushData($event.target.value)"
:checked="view_transfer.status >= 3" type="checkbox" value="3">
<label class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div>  
<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" @input="pushData($event.target.value)"
:checked="view_transfer.status >= 4" type="checkbox" value="4">
<label class="form-check-label text-body text-truncate status-heading">Collate Transfer Details</label>
</div>
</div> 

  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Previous Licence Holder</label>
    <input type="text" required readonly title="You can`t change this field." class="form-control form-control-default" v-model="form.old_company" >
     </div>
   <div v-if="errors.old_company" class="text-danger">{{ errors.old_company }}</div>
   </div>

   <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="mx-6 mt-2">Transfering To</label>
     </div>
   </div>

  <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled">
     <Multiselect
     v-model="form.new_company"
        placeholder="Current Licence Holder"
        :options="options"
        :searchable="true"
      />
    </div>
 <div v-if="errors.new_company" class="text-danger">{{ errors.new_company }}</div>
</div>
<div class="col-4 columns"></div>
   <div class="col-4 columns">
    <label class="form-label mx-6 " style="border: 2px solid; padding: 5px; border-radius: 5px">Documents Required</label>
   </div>
   <div class="col-4 columns"></div>


<div class="col-4" style="text-align: center;">
<ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="old_transfer_forms !== null">
    <a :href="`/storage/app/public/${old_transfer_forms.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="old_transfer_forms !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ old_transfer_forms.document_name }}</p>
    </div>
    <a v-if="old_transfer_forms !== null" @click="deleteDocument(old_transfer_forms.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('Transfer Forms','Old Licence Holder','A')" data-bs-toggle="modal" data-bs-target="#upload-documents"><i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>

   <div class="col-4 ">
    <label class="form-label mx-6 " style="border: 2px solid; padding: 5px; border-radius: 5px">Transfer Forms</label>
   </div>
<div class="col-4">
<ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="current_transfer_forms !== null">
    <a :href="`/storage/app/public/${current_transfer_forms.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="current_transfer_forms !== null" class="mb-0 text-xs">{{ current_transfer_forms.document_name }}</p>
    </div>
    <a v-if="current_transfer_forms !== null" @click="deleteDocument(current_transfer_forms.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="setDocType('Transfer Forms','Current Licence Holder','A')" data-bs-toggle="modal" data-bs-target="#upload-documents"><i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>





<div class="col-4 columns" style="text-align: center;"><i class="fa fa-times-circle h5" aria-hidden="true"></i></div>
   <div class="col-4 columns">
    <label class="form-label mx-6 " style="border: 2px solid; padding: 5px; border-radius: 5px">Smoking Affidavit</label>
   </div>

   <div class="col-4 columns" style="text-align: center;">
   <ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center" >
    <div class="avatar" v-if="smoking_affidavict !== null">
    <a :href="`/storage/app/public/${smoking_affidavict.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div v-if="smoking_affidavict !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p v-if="smoking_affidavict !== null" class="mb-0 text-xs">{{ smoking_affidavict.document_name }}</p>
    </div>
    <a v-if="smoking_affidavict !== null" @click="deleteDocument(smoking_affidavict.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('Smoking Affidavit','Current Licence Holder','B')" data-bs-toggle="modal" data-bs-target="#upload-documents" >
    <i class="fa fa-cloud-upload h5 " aria-hidden="true"></i></a>
  </li> 
</ul>
 
</div>








  <div class="col-4 columns" style="text-align: center;">
<ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="old_poa_res_docs !== null">
    <a :href="`/storage/app/public/${old_poa_res_docs.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="old_poa_res_docs !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ old_poa_res_docs.document_name }}</p>
    </div>
    <a v-if="old_poa_res_docs !== null" @click="deleteDocument(old_poa_res_docs.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('POA & RES','Old Licence Holder','C')" data-bs-toggle="modal" data-bs-target="#upload-documents">
    <i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
  </div>


   <div class="col-4 columns">
    <label class="form-label mx-6" style="border: 2px solid; padding: 5px; border-radius: 5px">POA &amp; RES</label>
   </div>
   <div class="col-4 columns" style="text-align: center;">
   <ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="current_poa_res_docs !== null">
    <a :href="`/storage/app/public/${current_poa_res_docs.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="current_poa_res_docs !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ current_poa_res_docs.document_name }}</p>
    </div>
    <a v-if="current_poa_res_docs !== null" @click="deleteDocument(current_poa_res_docs.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('POA & RES','Current Licence Holder','C')" data-bs-toggle="modal" data-bs-target="#upload-documents">
    <i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>

   </div>



  <div class="col-4 columns" style="text-align: center;">
  <ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="old_shareholding !== null">
    <a :href="`/storage/app/public/${old_shareholding.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="old_shareholding !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ old_shareholding.document_name }}</p>
    </div>
    <a v-if="old_shareholding !== null" @click="deleteDocument(old_shareholding.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('Shareholding','Old Licence Holder','C')" data-bs-toggle="modal" data-bs-target="#upload-documents">
    <i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
  </div>
   <div class="col-4 columns">
    <label class="form-label mx-6 " style="border: 2px solid; padding: 5px; border-radius: 5px">Shareholding</label>
   </div>
   <div class="col-4 columns" style="text-align: center;">
   <ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="current_shareholding !== null">
    <a :href="`/storage/app/public/${current_shareholding.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="current_shareholding !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ current_shareholding.document_name }}</p>
    </div>
    <a v-if="current_shareholding !== null" @click="deleteDocument(current_shareholding.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('Shareholding','Current Licence Holder','D')" data-bs-toggle="modal" data-bs-target="#upload-documents">
    <i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
   </div>




  <div class="col-4 columns" style="text-align: center;">
   <ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="old_cipc_certificate !== null">
    <a :href="`/storage/app/public/${old_cipc_certificate.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="old_cipc_certificate !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ old_cipc_certificate.document_name }}</p>
    </div>
    <a v-if="old_cipc_certificate !== null" @click="deleteDocument(old_cipc_certificate.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('CIPC Certificate','Old Licence Holder','E')" data-bs-toggle="modal" data-bs-target="#upload-documents">
    <i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>
   <div class="col-4 columns">
    <label class="form-label mx-6 " style="border: 2px solid; padding: 5px; border-radius: 5px">CIPC Certificate</label>
   </div>
   <div class="col-4 columns" style="text-align: center;">
   <ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="current_cipc_certificate !== null">
    <a :href="`/storage/app/public/${current_cipc_certificate.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="current_cipc_certificate !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ current_cipc_certificate.document_name }}</p>
    </div>
    <a v-if="current_cipc_certificate !== null" @click="deleteDocument(current_cipc_certificate.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('CIPC Certificate','Current Licence Holder','E')" data-bs-toggle="modal" data-bs-target="#upload-documents">
    <i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>





<div class="col-4 columns" style="text-align: center;">
<i class="fa fa-times-circle h5" aria-hidden="true"></i></div>
   <div class="col-4 columns">
    <label class="form-label mx-6 " style="border: 2px solid; padding: 5px; border-radius: 5px">Company Documents</label>
   </div>
   <div class="col-4 columns" style="text-align: center;">
    <ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="company_docs !== null">
    <a :href="`/storage/app/public/${company_docs.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="company_docs !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ company_docs.document_name }}</p>
    </div>
    <a v-if="company_docs !== null" @click="deleteDocument(company_docs.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('Company Documents','Current Licence Holder','F')" data-bs-toggle="modal" data-bs-target="#upload-documents">
    <i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>



   <div class="col-4 columns" style="text-align: center;"><i class="fa fa-times-circle h5" aria-hidden="true"></i></div>
   <div class="col-4 columns">
    <label class="form-label mx-6 " style="border: 2px solid; padding: 5px; border-radius: 5px">ID Documents</label>
   </div>
   <div class="col-4 columns" style="text-align: center;">
   <ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="id_docs !== null">
    <a :href="`/storage/app/public/${id_docs.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="id_docs !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ id_docs.document_name }}</p>
    </div>
    <a v-if="id_docs !== null" @click="deleteDocument(id_docs.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('ID Documents','Current Licence Holder','G')"  data-bs-toggle="modal" data-bs-target="#upload-documents">
    <i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>
 
 
 
 <div class="col-4 columns" style="text-align: center;"><i class="fa fa-times-circle h5" aria-hidden="true"></i></div>
   <div class="col-4 columns">
    <label class="form-label mx-6 " style="border: 2px solid; padding: 5px; border-radius: 5px">Police Clearances</label>
   </div>
   <div class="col-4 columns" style="text-align: center;">
   <ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="police_clearance !== null">
    <a :href="`/storage/app/public/${police_clearance.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="police_clearance !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ police_clearance.document_name }}</p>
    </div>
    <a v-if="police_clearance !== null" @click="deleteDocument(police_clearance.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('Police Clearances','Current Licence Holder','H')"  data-bs-toggle="modal" data-bs-target="#upload-documents">
    <i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>

   
   
   <div class="col-4 columns" style="text-align: center;"><i class="fa fa-times-circle h5" aria-hidden="true"></i></div>
   <div class="col-4 columns">
    <label class="form-label mx-6 " style="border: 2px solid; padding: 5px; border-radius: 5px">Tax Clearance</label>
   </div>
   <div class="col-4 columns" style="text-align: center;">
   <ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="tax_clearance !== null">
    <a :href="`/storage/app/public/${tax_clearance.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="tax_clearance !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ tax_clearance.document_name }}</p>
    </div>
    <a v-if="tax_clearance !== null" @click="deleteDocument(tax_clearance.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('Tax Clearance','Current Licence Holder','I')"  data-bs-toggle="modal" data-bs-target="#upload-documents">
    <i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>


   <div class="col-4 columns" style="text-align: center;"><i class="fa fa-times-circle h5" aria-hidden="true"></i></div>
   <div class="col-4 columns">
    <label class="form-label mx-6 " style="border: 2px solid; padding: 5px; border-radius: 5px">LTA Certificate</label>
   </div>
   <div class="col-4 columns" style="text-align: center;">
    <ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="lta_certificate !== null">
    <a :href="`/storage/app/public/${lta_certificate.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="lta_certificate !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ lta_certificate.document_name }}</p>
    </div>
    <a v-if="lta_certificate !== null" @click="deleteDocument(lta_certificate.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('LTA Certificate','Current Licence Holder','J')"  data-bs-toggle="modal" data-bs-target="#upload-documents">
    <i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>
   


   <div class="col-4 columns" style="text-align: center;"><i class="fa fa-times-circle h5" aria-hidden="true"></i></div>
   <div class="col-4 columns">
    <label class="form-label mx-6 " style="border: 2px solid; padding: 5px; border-radius: 5px">Financial Interests</label>
   </div>
   <div class="col-4 columns" style="text-align: center;">
   <ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="financial_interest !== null">
    <a :href="`/storage/app/public/${financial_interest.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="financial_interest !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ financial_interest.document_name }}</p>
    </div>
    <a v-if="financial_interest !== null" @click="deleteDocument(financial_interest.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('Financial Interests','Current Licence Holder','K')"  data-bs-toggle="modal" data-bs-target="#upload-documents">
    <i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>





<div class="col-4 columns" style="text-align: center;"><i class="fa fa-times-circle h5" aria-hidden="true"></i></div>
   <div class="col-4 columns">
    <label class="form-label mx-6 " style="border: 2px solid; padding: 5px; border-radius: 5px">Lease/Landlord Letter</label>
   </div>
<div class="col-4 columns" style="text-align: center;">
<ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="landloard_letter !== null">
    <a :href="`/storage/app/public/${landloard_letter.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="landloard_letter !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ landloard_letter.document_name }}</p>
    </div>
    <a v-if="landloard_letter !== null" @click="deleteDocument(landloard_letter.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('Lease/Landlord Letter','Current Licence Holder','L')"  data-bs-toggle="modal" data-bs-target="#upload-documents">
    <i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>


<div class="col-1 columns" style="text-align: center;"></div>
<div class="col-4 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled" >
<label class="form-check-label text-body text-truncate status-heading">Representation</label>
<ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="representation !== null">
    <a :href="`/storage/app/public/${representation.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="representation !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ representation.document_name }}</p>
    </div>
    <a v-if="representation !== null" @click="deleteDocument(representation.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('Representation','Old Licence Holder','M')"  data-bs-toggle="modal" data-bs-target="#upload-documents">
    <i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>
</div>

<div class="col-2  columns"></div>
<div class="col-4 columns" >
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label text-body text-truncate status-heading">Latest Renewal/Licence</label>
<i class="fa fa-link h5 mx-5" aria-hidden="true"></i>
</div>
</div>
<div class="col-1 columns" style="text-align: center;"></div>



<div class="col-1 columns" style="text-align: center;"></div>
<div class="col-4 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled" >
<label class="form-check-label text-body text-truncate status-heading">Index Page</label>
<ul class="list-group" style="margin-top: -1rem;">
  <li class="px-0 border-0 list-group-item d-flex align-items-center">
    <div class="avatar" v-if="index_page !== null">
    <a :href="`/storage/app/public/${index_page.document}`" target="_blank">
    <i class="fas fa-file-pdf h5 text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div  v-if="index_page !== null" class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
       <p class="mb-0 text-xs">{{ index_page.document_name }}</p>
    </div>
    <a v-if="index_page !== null" @click="deleteDocument(index_page.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else style="margin-left: 7.8rem;" @click="setDocType('Index Page','Old Licence Holder','N')"  data-bs-toggle="modal" data-bs-target="#upload-documents">
    <i class="fa fa-cloud-upload h5" aria-hidden="true"></i></a>
  </li> 
</ul>
</div>
</div>

<div class="col-2 columns"></div>
<div class="col-4 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label text-body text-truncate status-heading">Proof Of Payment</label>
<i class="fa fa-link h5 " style="margin-left: 5.5rem;"></i>
</div>
</div>

<div>
  <button :style="{float: 'right'}" type="submit" class="btn btn-secondary ms-2" :disabled="mergeForm.processing">
  <span v-if="mergeForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Compile And Merge</button>
</div>
<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" @input="pushData($event.target.value)"
:checked="view_transfer.status >= 5" type="checkbox" value="5">
<label class="form-check-label text-body text-truncate status-heading">Payment To The Liquor Board</label>
</div>
</div>  
<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" @input="pushData($event.target.value)"
:checked="view_transfer.status >= 6" type="checkbox" value="6">
<label class="form-check-label text-body text-truncate status-heading">Transfer Logded</label>
</div>
</div>  
<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" @input="pushData($event.target.value)"
:checked="view_transfer.status >= 7" type="checkbox" value="7">
<label class="form-check-label text-body text-truncate status-heading">Activation Fee Paid</label>
</div>
</div>  
<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" @input="pushData($event.target.value)"
:checked="view_transfer.status >= 8" type="checkbox" value="8">
<label class="form-check-label text-body text-truncate status-heading">Transfer Issued</label>
</div>
</div> 
<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" @input="pushData($event.target.value)"
:checked="view_transfer.status >= 9" type="checkbox" value="9">
<label class="form-check-label text-body text-truncate status-heading">Transfer Delivered</label>
</div>
</div> 

<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" @input="pushData($event.target.value)"
:checked="view_transfer.status >= 10" type="checkbox" value="10">
<label class="form-check-label text-body text-truncate status-heading"> Transfer Logded</label>
</div>
</div> 


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" @input="pushData($event.target.value)"
:checked="view_transfer.status >= 11" type="checkbox" value="11">
<label class="form-check-label text-body text-truncate status-heading"> Certificate Received</label>
</div>
</div> 

<div>
<div v-if="form.isDirty">There are unsaved changes.</div>
  <button :style="{float: 'right'}" type="submit" class="btn btn-secondary ms-2" :disabled="form.processing">
  <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Create</button>
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


  <div v-if="show_modal" class="modal" id="upload-documents" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="submitDocuments">
      <input type="hidden" v-model="documentsForm.document_type">
      <input type="hidden" v-model="documentsForm.belong_to">
      <input type="hidden" v-model="documentsForm.document_number">
      
      <div class="modal-body">      
        <div class="row">

        <div class="col-md-12 columns">
        <label for="licence-doc" class="btn btn-dark w-100" href="">Click To Upload File</label>
         <input type="file" @input="documentsForm.document = $event.target.files[0]"
         hidden id="licence-doc" accept=".pdf"/>
         <div v-if="errors.document" class="text-danger">{{ errors.document }}</div>
       </div>
       <div class="col-md-12">
          <progress v-if="documentsForm.progress" :value="documentsForm.progress.percentage" max="100">
         {{ documentsForm.progress.percentage }}%
         </progress>
         </div>
         </div>   
      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-secondary" :disabled="documentsForm.processing">
         <span v-if="documentsForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
  </Layout>
</template>
