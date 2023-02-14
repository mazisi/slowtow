<script>
import Layout from "../../Shared/Layout.vue";
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import LiquorBoardRequest from "../components/LiquorBoardRequest.vue";
import Banner from '../components/Banner.vue';

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
    licence_logded: Object,//doc
    scanned_app: Object,//doc
    client_invoiced: Object,//doc
    licence_issued: Object,//doc
    client_quoted: Object,//doc
    collate: Object,
    liqour_board: Object,
    licence_delivered: Object,
    company_application_form: Object,
    company_poa: Object,
    company_annexure_b: Object,
    company_annexure_c: Object,
    company_cipc: Object,
    company_id_document: Object,
    company_representations: Object,
    company_landlord_letter: Object,
    company_security_letter: Object,
    company_advert: Object,
    company_plan: Object,
    //individual
    
    individual_application_form: Object,
    power_of_attorney: Object,
    individual_annexure_b: Object,
    individual_annexure_c: Object,
    individual_representations: Object,
    individual_landlord_letter: Object,
    individual_security_letter: Object,
    individual_advert: Object,
    individual_plan: Object,
    get_person_id_document: Object
     
  },

  setup (props) {
    const year = ref(new Date().getFullYear());
    const body_max = ref(100);
    let show_modal = ref(true);  
    let show_file_name = ref(false);
    let file_name = ref('');

    const form = useForm({
      status: [],
      client_paid_at: props.licence.client_paid_at,
      payment_to_liquor_board_at: props.licence.payment_to_liquor_board_at,
      logded_at: props.licence.logded_at,
      issued_at: props.licence.issued_at, 
      delivered_at: props.licence.delivered_at,
      liquor_licence_number: props.licence.liquor_licence_number,
      reg_number: props.licence.company ? props.licence.company.reg_number: '',
      id_number: props.licence.people ? props.licence.people.id_number: '',
      belongs_to: props.licence.belongs_to
     })

    const uploadDoc = useForm({
      document: null,
      doc_type: null,
      person_or_company: null,
      merge_number: null,
      file_name: file_name,
      temp_licence_id: props.licence.id    
    })

    const createTask = useForm({
          body: '',
          model_type: 'Temporal Licence',
          model_id: props.licence.id,
          taskDate: ''     
    })

    function submitTask(){
      createTask.post('/submit-task', {
          onSuccess: () => createTask.reset(),
      })
    }

    function checkBodyLength(){//Monitor task body length..Or just use watcher
          if(this.createTask.body.length > this.body_max){
              this.createTask.body = this.createTask.body.substring(0,this.body_max)
          }
      }

      function submitDocument(){
            uploadDoc.post('/submit-temporal-licence-document', {
              preserveScroll: true,
              onSuccess: () => { 
                this.show_file_name = false;
                this.show_modal = false;
                let dismiss =  document.querySelector('.modal-backdrop')    
                dismiss.remove();
                uploadDoc.reset();
              },
            })
          }

         const mergeForm = useForm({ 
          temporal_licence_id: props.licence.id,
          company_id: props.licence.company_id,
          person_id: props.licence.people_id
          })
          function mergeDocuments(type){
            mergeForm.post(`/merge-temporal-documents/${type}`)
          }

    function getDocType(doc_type,person_or_company,merge_number){
      this.uploadDoc.doc_type = doc_type
      this.uploadDoc.person_or_company = person_or_company
      this.uploadDoc.merge_number = merge_number
      this.show_modal =true  
      this.show_file_name = true; 
    }

    function deleteDocument(id){
        if(confirm('Document will be deleted permanently...Continue ??')){
          Inertia.delete(`/delete-temporal-licence-document/${id}`, {
            //
          })
        }
      }

      function deleteTemporalLicence(){
        if(confirm('Are you sure you want to delete this licence??')){
          Inertia.delete(`/delete-temporal-licence/${props.licence.slug}`, {
            //
          })
        }
      }

    function updateLicence() {
      form.patch(`/update-prepared-temp-app/${props.licence.slug}`, {
        preserveScroll: true,
      })
    }

    function getRenewalYear(date){
      let computed_date = new Date(date).getFullYear();
      return computed_date + 1;    
    }

    function computeDocumentDate(date_param){
        return new Date(date_param).toLocaleString().split(',')[0]
    };

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

      let file_has_apostrophe = ref();
      function getFileName(e){
        this.uploadDoc.document = e.target.files[0];
        this.file_name = e.target.files[0].name;
        this.file_has_apostrophe = this.file_name.includes("'");
      }

    return { year,form,body_max,show_modal,
     updateLicence,file_name,show_file_name,getFileName,
     getRenewalYear, pushData,uploadDoc,
     getDocType, submitDocument,file_has_apostrophe,
     computeDocumentDate,deleteDocument,
     createTask,
     submitTask,
     checkBodyLength,
     mergeDocuments,
     mergeForm,
     deleteTemporalLicence
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

  watch: {
    'form.start_date'() {
      var d = new Date(this.form.start_date);
      d.setDate(d.getDate() - 14);
      this.form.latest_lodgment_date = d.toLocaleString().split(' ')[0].replace(/,/g, '')
    }
  },
  
};
//The following are status keys
// 1 => Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Collate Temporary Licence Documents 
// 5 => Payment To The Liquor Board 
// 6 => Scanned Application
// 7 => Temporary Licence Lodged 
// 8 => Temporary Licence Issued 
// 9 => Temporary Licence Delivered

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
.document-names { width: 50%; }

.curser-pointer{ cursor: pointer;}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
<template>
<Layout>
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row">
  <div class="col-lg-6 col-7">
  <h6 v-if="licence.people === null">Temporary Licence For: 
      <Link :href="`/view-company/${licence.company.slug}`" class="text-success">{{ licence.company.name }}
      </Link>
  </h6>
    <h6 v-else>Temporary Licence For: <Link :href="`/view-person/${licence.people.slug}`" class="text-success">{{ licence.people.full_name }}</Link></h6>
    <p class="text-sm mb-0">Current Stage: 
      <span class="font-weight-bold ms-1" v-if="licence.status == '1'">Client Quoted</span>
     <span v-if="licence.status == '2'" class="font-weight-bold ms-1">Client Invoiced</span>
     <span v-if="licence.status == '3'" class="font-weight-bold ms-1">Client Paid</span>
     <span v-if="licence.status == '4'" class="font-weight-bold ms-1">Collate Temporary Licence Documents </span>
     <span v-if="licence.status == '5'" class="font-weight-bold ms-1">Payment To The Liquor Board</span>
     <span v-if="licence.status == '6'" class="font-weight-bold ms-1">Scanned Application</span>
     <span v-if="licence.status == '7'" class="font-weight-bold ms-1">Temporary Licence Lodged</span>
     <span v-if="licence.status == '8'" class="font-weight-bold ms-1">Temporary Licence Issued</span>
     <span v-if="licence.status == '9'" class="font-weight-bold ms-1">Transfer Issued</span>
     <span v-if="licence.status == '10'" class="font-weight-bold ms-1">Temporary Licence Delivered</span>
   </p>

  </div>
  <div class="col-lg-6 col-5 my-auto text-end">
    <button @click="deleteTemporalLicence" class=" border-radius-md btn-danger" style="border: none">
      <i class="fa fa-trash-o cursor-pointer" aria-hidden="true" ></i> Delete</button>
  </div>
</div>

<div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="updateLicence">
<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-quoted" class="active-checkbox" type="checkbox" 
:checked="licence.status >= 1"
@input="pushData(1)">
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
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="client_quoted !== null" class="mb-0 text-xs">{{ client_quoted.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

    <a v-if="client_quoted !== null" @click="deleteDocument(client_quoted.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Client Quoted')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 text-secondary" aria-hidden="true"></i>
    </a>
  </li>
</ul>    
<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="client-invoiced"  type="checkbox"
@input="pushData(2)" 
:checked="licence.status >= 2">
<label for="client-invoiced" class="form-check-label text-body text-truncate status-heading">Client Invoiced</label>
</div>
</div> 
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="client_invoiced !== null">
    <a :href="`${$page.props.blob_file_path}${client_invoiced.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="client_invoiced !== null" class="mb-0 text-xs">{{ client_invoiced.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

    <a v-if="client_invoiced !== null" @click="deleteDocument(client_invoiced.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Client Invoiced')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 text-secondary" aria-hidden="true"></i>
    </a>
  </li>
</ul>
<hr>

<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="client-paid"  type="checkbox"
@input="pushData(3)" 
:checked="licence.status >= 3">
<label for="client-paid" class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div> 



 <template v-if="licence.client_paid_at == null">
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.client_paid_at">
     </div>
   <div v-if="errors.client_paid_at" class="text-danger">{{ errors.client_paid_at }}</div>
 </div>
 <div class="col-md-1 columns">
  <button class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<template v-else>
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.client_paid_at">
     </div>
   <div v-if="errors.client_paid_at" class="text-danger">{{ errors.client_paid_at }}</div>
 </div>
 <div class="col-md-1 columns" v-if="$page.props.auth.has_slowtow_admin_role">
  <button  class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="prepare" type="checkbox" 
@input="pushData(4)" :checked="licence.status >= 4">
<label for="prepare" class="form-check-label text-body text-truncate status-heading">Prepare Temporary Application </label>
</div>
</div> 


<div class="">

<!-- ===============   Company File Uploads ===========================-->
<div v-if="licence.people_id == null" class="d-flex row">
  <div class="col-sm-2"></div>
  
  <div class="col-sm-5">
    <button type="button" class="btn btn-outline-success document-names">Application Form </button>
     <i v-if="company_application_form === null"
      @click="getDocType('Application Form','Company',1)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="company_application_form !== null" @click="deleteDocument(company_application_form.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
     <a v-if="company_application_form !== null" :href="`${$page.props.blob_file_path}${company_application_form.document}`" target="_blank">
     <i v-if="company_application_form !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br> 



     <button type="button" class="btn btn-outline-success document-names">Proof Of Payment</button>
      <a v-if="liqour_board !== null" :href="`${$page.props.blob_file_path}${liqour_board.document}`" target="_blank">
    <i class="fa fa-link h5 mx-2 curser-pointer"  aria-hidden="true"></i>
    </a>
    
    <a v-else :disabled="true">
    <i class="fa fa-link h5 mx-2"  aria-hidden="true"></i>
    </a> <br>

       <button type="button" class="btn btn-outline-success document-names">POA &amp; RES</button> 
       <i v-if="company_poa == null" @click="getDocType('POA And RES','Company',3)" data-bs-toggle="modal" data-bs-target="#documents" 
         class="fa fa-upload h5 curser-pointer mx-2"></i>
         <i v-if="company_poa !== null" @click="deleteDocument(company_poa.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
     <a v-if="company_poa !== null" :href="`${$page.props.blob_file_path}${company_poa.document}`" target="_blank">
     <i v-if="company_poa !== null" class="fa fa-file-pdf h4 text-danger"></i></a><br> 

       <button type="button" class="btn btn-outline-success document-names">Annexure B & C</button>
        <i v-if="company_annexure_b == null" @click="getDocType('Annexure B','Company',4)" data-bs-toggle="modal" data-bs-target="#documents" 
        class="fa fa-upload h5 mx-2 curser-pointer"></i>
         <i v-if="company_annexure_b !== null" @click="deleteDocument(company_annexure_b.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_annexure_b !== null" :href="`${$page.props.blob_file_path}${company_annexure_b.document}`" target="_blank">
        <i v-if="company_annexure_b !== null" class="fa fa-file-pdf h4 text-danger"></i></a>
     <br> 

        <!-- <button type="button" class="btn btn-outline-success document-names">Annexure C</button>
         <i v-if="company_annexure_c == null" @click="getDocType('Annexure C','Company',5)" data-bs-toggle="modal" data-bs-target="#documents" 
         class="fa fa-upload h5 mx-2 curser-pointer"></i>
        <i v-if="company_annexure_c !== null" @click="deleteDocument(company_annexure_c.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_annexure_c !== null" :href="`${$page.props.blob_file_path}${company_annexure_c.document}`" target="_blank">
        <i v-if="company_annexure_c !== null" class="fa fa-file-pdf h4 text-danger"></i></a><br> -->

        <button type="button" class="btn btn-outline-success document-names"> CIPC Certificate</button>
         <i v-if="company_cipc == null" @click="getDocType('CIPC Certificate','Company',6)" data-bs-toggle="modal" data-bs-target="#documents"
         class="fa fa-upload h5 mx-2 curser-pointer"></i>
         <i v-if="company_cipc !== null" @click="deleteDocument(company_cipc.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_cipc !== null" :href="`${$page.props.blob_file_path}${company_cipc.document}`" target="_blank">
        <i v-if="company_cipc !== null" class="fa fa-file-pdf h4 text-danger"></i></a>
        <br> 
    <div class="col-sm-1"> </div>
  </div>
  
  <div class="col-sm-5">
  <button type="button" class="btn btn-outline-success document-names">ID Dcocument </button>
     <i v-if="company_id_document == null" @click="getDocType('ID Document','Company',7)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="company_id_document !== null" @click="deleteDocument(company_id_document.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_id_document !== null" :href="`${$page.props.blob_file_path}${company_id_document.document}`" target="_blank">
        <i v-if="company_id_document !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br> 


   <button type="button" class="btn btn-outline-success document-names">Representations</button>
  <i v-if="company_representations == null" @click="getDocType('Representations','Company',8)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="company_representations !== null" @click="deleteDocument(company_representations.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_representations !== null" :href="`${$page.props.blob_file_path}${company_representations.document}`" target="_blank">
        <i v-if="company_representations !== null" class="fa fa-file-pdf h4 text-danger"></i></a>
     <br>  


    <button type="button" class="btn btn-outline-success document-names">Landlord Letter</button>
     <i v-if="company_landlord_letter == null" @click="getDocType('Landlord Letter','Company',9)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="company_landlord_letter  !== null" @click="deleteDocument(company_landlord_letter.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_landlord_letter !== null" :href="`${$page.props.blob_file_path}${company_landlord_letter.document}`" target="_blank">
        <i v-if="company_landlord_letter !== null" class="fa fa-file-pdf h4 text-danger"></i></a>
        <br>
     <button type="button" class="btn btn-outline-success document-names">Security Letter</button>
      <i v-if="company_security_letter == null" @click="getDocType('Security Letter','Company',10)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="company_security_letter  !== null" @click="deleteDocument(company_security_letter.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_security_letter !== null" :href="`${$page.props.blob_file_path}${company_security_letter.document}`" target="_blank">
        <i v-if="company_security_letter !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br>


      <button type="button" class="btn btn-outline-success document-names">Advert/Blurb</button>
      <i v-if="company_advert == null" @click="getDocType('Advert/Blurb','Company',11)" 
      data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
    <i v-if="company_advert  !== null" @click="deleteDocument(company_advert.id)" 
    class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_advert !== null" :href="`${$page.props.blob_file_path}${company_advert.document}`" target="_blank">
        <i v-if="company_advert !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br>


    <button type="button" class="btn btn-outline-success document-names">Plan/Maps</button>
    <i v-if="company_plan == null" @click="getDocType('Plan/Maps','Company',12)" 
      data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
    <i v-if="company_plan  !== null" @click="deleteDocument(company_plan.id)" 
    class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="company_plan !== null" :href="`${$page.props.blob_file_path}${company_plan.document}`" target="_blank">
        <i v-if="company_plan !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br> 
  <div class="col-sm-1"> </div>
 
 <div class="d-flex">
  <button v-if="company_application_form !== null
&& company_poa !== null
&& company_annexure_b !== null
// && company_annexure_c !== null
&& company_cipc !== null
&& company_id_document !== null
&& company_representations !== null
&& company_landlord_letter !== null
&& company_security_letter !== null
&& company_advert !== null"

@click="mergeDocuments('Company')" type="button" :disabled="mergeForm.processing" :style="{float: 'right'}" class="btn btn-sm btn-secondary" >
  <span v-if="mergeForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span class="visually-hidden">Loading...</span> Compile Application
</button>
  <a :href="`/storage/app/public/${licence.merged_document}`" 
 v-if="licence.merged_document !== null" target="_blank"  class="ms-2 btn btn-sm btn-secondary" >
  View </a>
 </div>


  </div>
  
</div>





<!-- ===============   Individual File Uploads ===========================-->
<div v-if="licence.company_id == null" class="d-flex row">
  <div class="col-sm-2"></div>
  
  <div class="col-sm-5">
    <button type="button" class="btn btn-outline-success document-names">Application Form </button>
     <i v-if="individual_application_form === null"
      @click="getDocType('Application Form','Individual',1)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="individual_application_form !== null" @click="deleteDocument(individual_application_form.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
     <a v-if="individual_application_form !== null" :href="`${$page.props.blob_file_path}${individual_application_form.document}`" target="_blank">
     <i v-if="individual_application_form !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br> 



     <button type="button" class="btn btn-outline-success document-names">Proof Of Payment</button>
      <a v-if="liqour_board !== null" :href="`${$page.props.blob_file_path}${liqour_board.document}`" target="_blank">
    <i class="fa fa-link h5 mx-2 curser-pointer"  aria-hidden="true"></i>
    </a>
    
    <a v-else :disabled="true">
    <i class="fa fa-link h5 mx-2"  aria-hidden="true"></i>
    </a> <br>

       <button type="button" class="btn btn-outline-success document-names">Power Of Attorney</button> 
       <i v-if="power_of_attorney == null" @click="getDocType('Power Of Attorney','Individual',3)" data-bs-toggle="modal" data-bs-target="#documents" 
         class="fa fa-upload h5 curser-pointer mx-2"></i>
         <i v-if="power_of_attorney !== null" @click="deleteDocument(power_of_attorney.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
     <a v-if="power_of_attorney !== null" :href="`${$page.props.blob_file_path}${power_of_attorney.document}`" target="_blank">
     <i v-if="power_of_attorney !== null" class="fa fa-file-pdf h4 text-danger"></i></a><br> 

       <button type="button" class="btn btn-outline-success document-names">Annexure B & C</button>
        <i v-if="individual_annexure_b == null" @click="getDocType('Annexure B','Individual',4)" data-bs-toggle="modal" data-bs-target="#documents" 
        class="fa fa-upload h5 mx-2 curser-pointer"></i>
         <i v-if="individual_annexure_b !== null" @click="deleteDocument(individual_annexure_b.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="individual_annexure_b !== null" :href="`${$page.props.blob_file_path}${individual_annexure_b.document}`" target="_blank">
        <i v-if="individual_annexure_b !== null" class="fa fa-file-pdf h4 text-danger"></i></a>
     <br> 

        <!-- <button type="button" class="btn btn-outline-success document-names">Annexure C</button>
         <i v-if="individual_annexure_c == null" @click="getDocType('Annexure C','Individual',5)" data-bs-toggle="modal" data-bs-target="#documents" 
         class="fa fa-upload h5 mx-2 curser-pointer"></i>
        <i v-if="individual_annexure_c !== null" @click="deleteDocument(individual_annexure_c.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="individual_annexure_c !== null" :href="`${$page.props.blob_file_path}${individual_annexure_c.document}`" target="_blank">
        <i v-if="individual_annexure_c !== null" class="fa fa-file-pdf h4 text-danger"></i></a><br>  -->

<div v-if="licence.company_id == null">
  <button type="button" class="btn btn-outline-success document-names">ID Dcocument </button>
     <a v-if="get_person_id_document !== null" :href="`${$page.props.blob_file_path}${get_person_id_document.document}`" target="_blank">
        <i  class="fa fa-link h5 mx-2" ></i></a>
    <a v-else :href="`#!`">
        <i  class="fa fa-link h5 mx-2"></i></a> <br> 

    <div class="col-sm-1"> </div>
</div>
      
  </div>
  
  <div class="col-sm-5">
     <button type="button" class="btn btn-outline-success document-names">Representations</button>
  <i v-if="individual_representations == null" @click="getDocType('Representations','Individual',7)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="individual_representations !== null" @click="deleteDocument(individual_representations.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="individual_representations !== null" :href="`${$page.props.blob_file_path}${individual_representations.document}`" target="_blank">
        <i v-if="individual_representations !== null" class="fa fa-file-pdf h4 text-danger"></i></a>
     <br>  


    <button type="button" class="btn btn-outline-success document-names">Landlord Letter</button>
     <i v-if="individual_landlord_letter == null" @click="getDocType('Landlord Letter','Individual',8)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="individual_landlord_letter  !== null" @click="deleteDocument(individual_landlord_letter.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="individual_landlord_letter !== null" :href="`${$page.props.blob_file_path}${individual_landlord_letter.document}`" target="_blank">
        <i v-if="individual_landlord_letter !== null" class="fa fa-file-pdf h4 text-danger"></i></a>
        <br>
     <button type="button" class="btn btn-outline-success document-names">Security Letter</button>
      <i v-if="individual_security_letter == null" @click="getDocType('Security Letter','Individual',9)" data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
     <i v-if="individual_security_letter  !== null" @click="deleteDocument(individual_security_letter.id)" class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="individual_security_letter !== null" :href="`${$page.props.blob_file_path}${individual_security_letter.document}`" target="_blank">
        <i v-if="individual_security_letter !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br>


      <button type="button" class="btn btn-outline-success document-names">Advert/Blurb</button>
      <i v-if="individual_advert == null" @click="getDocType('Advert/Blurb','Individual',10)" 
      data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
    <i v-if="individual_advert  !== null" @click="deleteDocument(individual_advert.id)" 
    class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="individual_advert !== null" :href="`${$page.props.blob_file_path}${individual_advert.document}`" target="_blank">
        <i v-if="individual_advert !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br>


    <button type="button" class="btn btn-outline-success document-names">Plan/Maps</button>
    <i v-if="individual_plan == null" @click="getDocType('Plan/Maps','Individual',11)" 
      data-bs-toggle="modal" data-bs-target="#documents" 
     class="fa fa-upload h5 mx-2 curser-pointer"></i> 
    <i v-if="individual_plan  !== null" @click="deleteDocument(individual_plan.id)" 
    class="fa fa-trash-alt h5 curser-pointer mx-2 text-danger"></i> 
        <a v-if="individual_plan !== null" :href="`${$page.props.blob_file_path}${individual_plan.document}`" target="_blank">
        <i v-if="individual_plan !== null" class="fa fa-file-pdf h4 text-danger"></i></a> <br> 
  <div class="col-sm-1"> </div>
 
  <div class="d-flex">
    <button v-if="individual_application_form !== null
&& get_person_id_document !== null
&& power_of_attorney !== null
&& individual_annexure_b !== null
// && individual_annexure_c !== null
&& individual_representations !== null
&& individual_landlord_letter !== null
&& individual_security_letter !== null
&& individual_advert !== null
&& individual_plan !== null"
@click="mergeDocuments('Individual')" type="button" :disabled="mergeForm.processing" :style="{float: 'right'}" class="btn btn-sm btn-secondary" >
  <span v-if="mergeForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span class="visually-hidden">Loading...</span> Compile Application</button>
    <a :href="`/storage/app/public/${licence.merged_document}`" 
 v-if="licence.merged_document !== null" target="_blank" :style="{float: 'right'}" class="ms-2 btn btn-sm btn-secondary" >
  View </a>
  </div>
 




  </div>
</div>

</div>

<hr>

<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="payment" type="checkbox" @input="pushData(5)"
:checked="licence.status >= 5">
<label for="payment" class="form-check-label text-body text-truncate status-heading">Payment To The Liquor Board</label>
</div>
</div> 


<template v-if="licence.payment_to_liquor_board_at == null">
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.payment_to_liquor_board_at">
     </div>
   <div v-if="errors.payment_to_liquor_board_at" class="text-danger">{{ errors.payment_to_liquor_board_at }}</div>
 </div>
 <div class="col-md-1 columns">
  <button class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<template v-else>
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.payment_to_liquor_board_at">
     </div>
   <div v-if="errors.payment_to_liquor_board_at" class="text-danger">{{ errors.payment_to_liquor_board_at }}</div>
 </div>
 <div class="col-md-1 columns" v-if="$page.props.auth.has_slowtow_admin_role">
  <button  class="btn btn-sm btn-secondary">Save</button>
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
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="liqour_board !== null" class="mb-0 text-xs">{{ liqour_board.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

    <a v-if="liqour_board !== null" @click="deleteDocument(liqour_board.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Payment To The Liquor Board')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 text-secondary" aria-hidden="true"></i>
    </a>
  </li>
</ul>
<hr/>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="issued" type="checkbox" 
@input="pushData($event.target.value)" value="6" :checked="licence.status >= 6">
<label for="issued" class="form-check-label text-body text-truncate status-heading"> Scanned Application </label>
</div>
</div> 


<div class="col-md-6" style="margin-top: -1rem;">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="scanned_app !== null">
    <a :href="`${$page.props.blob_file_path}${scanned_app.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="scanned_app !== null" class="mb-0 text-xs">{{ scanned_app.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="scanned_app !== null" @click="deleteDocument(scanned_app.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a @click="getDocType('Scanned Application')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;" v-else>
    <i class="fa fa-upload h5 text-secondary" aria-hidden="true"></i>
    </a>
  </li>
</ul>
</div>
<hr/>
<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="logded" type="checkbox" 
@input="pushData(7)" :checked="licence.status >= 7">
<label for="logded" class="form-check-label text-body text-truncate status-heading"> Temporary Licence Lodged </label>
</div>
</div> 

<template v-if="licence.logded_at == null">
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.logded_at">
     </div>
   <div v-if="errors.logded_at" class="text-danger">{{ errors.logded_at }}</div>
 </div>
 <div class="col-md-1 columns">
  <button class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<template v-else>
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.logded_at">
     </div>
   <div v-if="errors.logded_at" class="text-danger">{{ errors.logded_at }}</div>
 </div>
 <div class="col-md-1 columns" v-if="$page.props.auth.has_slowtow_admin_role">
  <button  class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<div class="col-md-6" style="margin-top: -1rem;">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="licence_logded !== null">
    <a :href="`${$page.props.blob_file_path}${licence_logded.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="licence_logded !== null" class="mb-0 text-xs">{{ licence_logded.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>
    <a v-if="licence_logded !== null" @click="deleteDocument(licence_logded.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a @click="getDocType('Licence Lodged')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;" v-else>
    <i class="fa fa-upload h5 text-secondary" aria-hidden="true"></i>
    </a>
  </li>
</ul>
</div>
<hr>




<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="issued" type="checkbox"
@input="pushData(8)" :checked="licence.status == 8">
<label for="issued" class="form-check-label text-body text-truncate status-heading"> Temporary Licence Issued</label>
</div>
</div>

<template v-if="licence.issued_at == null">
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.issued_at">
     </div>
   <div v-if="errors.issued_at" class="text-danger">{{ errors.issued_at }}</div>
 </div>
 <div class="col-md-1 columns">
  <button class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<template v-else>
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.issued_at">
     </div>
   <div v-if="errors.issued_at" class="text-danger">{{ errors.issued_at }}</div>
 </div>
 <div class="col-md-1 columns" v-if="$page.props.auth.has_slowtow_admin_role">
  <button  class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="licence_issued !== null">
    <a :href="`${$page.props.blob_file_path}${licence_issued.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="licence_issued !== null" class="mb-0 text-xs">{{ licence_issued.document_name }}</p>
      <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

    <a v-if="licence_issued !== null" @click="deleteDocument(licence_issued.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Licence Issued')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 text-secondary" aria-hidden="true"></i>
    </a>
  </li>
</ul>  
<hr>





<div class="col-md-6 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="delivered" type="checkbox"
@input="pushData($event.target.value)" :checked="licence.status == 9">
<label for="delivered" class="form-check-label text-body text-truncate status-heading"> Temporary Licence Delivered</label>
</div>
</div>

<template v-if="licence.delivered_at == null">
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.delivered_at">
     </div>
   <div v-if="errors.delivered_at" class="text-danger">{{ errors.delivered_at }}</div>
 </div>
 <div class="col-md-1 columns">
  <button class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>

<template v-else>
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.delivered_at">
     </div>
   <div v-if="errors.delivered_at" class="text-danger">{{ errors.delivered_at }}</div>
 </div>
 <div class="col-md-1 columns" v-if="$page.props.auth.has_slowtow_admin_role">
  <button  class="btn btn-sm btn-secondary">Save</button>
 </div>
</template>


<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="avatar me-3" v-if="licence_delivered !== null">
    <a :href="`${$page.props.blob_file_path}${licence_delivered.document}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger" aria-hidden="true"></i>
    </a>
    </div>

   <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Document</h6>
      <p v-if="licence_delivered !== null" class="mb-0 text-xs">{{ licence_delivered.document_name }}</p>
       <p v-else class="mb-0 text-xs text-danger">Document Not Uploaded</p>
    </div>

    <a v-if="licence_delivered !== null" @click="deleteDocument(licence_delivered.id)" class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
    </a>
    <a v-else @click="getDocType('Licence Delivered')" data-bs-toggle="modal" data-bs-target="#documents" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-4" href="javascript:;">
    <i class="fa fa-upload h5 text-secondary" aria-hidden="true"></i>
    </a>
  </li>
</ul>  


<div class="text-danger">
  <div v-if="form.isDirty" class="text-xs d-flex">You have unsaved changes.</div>
  <button :disabled="form.processing" :style="{float: 'right'}" class="btn  btn-primary ms-2" type="submit">
  <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span class="visually-hidden">Loading...</span> Save</button>
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

<hr/>
      <!-- <LiquorBoardRequest 
      :model_type='`Temporal Licence`'
      :model_id="licence.id" 
      :liqour_board_requests="liqour_board_requests"
      /> 
<hr>-->
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
<label class="form-label">New Task<span class="text-danger pl-6"></span></label>
<textarea v-model="createTask.body" class="form-control form-control-default" rows="3" ></textarea>
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
       <input type="hidden" v-model="uploadDoc.person_or_company">
       <input type="hidden" v-model="uploadDoc.merge_number">
      <div class="modal-body">      
        <div class="row">


        <div class="col-md-12 columns">
        <label for="licence-doc" class="btn btn-dark w-100" href="">Click To Select File</label>
         <input type="file" @input="getFileName"
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
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-secondary" :disabled="uploadDoc.processing || file_has_apostrophe">
         <span v-if="uploadDoc.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
  </Layout>
</template>
