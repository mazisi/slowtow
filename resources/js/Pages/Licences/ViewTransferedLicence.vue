<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link } from '@inertiajs/inertia-vue3';


export default {
  name: "profile-overview",
 props: {
    errors: Object,
    view_transfer: Object,
    companies_dropdown: Array,
    success: String,
    error: String,
    tasks: Object

  },
  data() {
    return {
      showMenu: false,
      form: {
       trading_name: this.view_transfer.licence.trading_name,
      new_company: this.view_transfer.licence.company.name,
      old_company: this.view_transfer.licence.old_company[0].name,
      transfer_date: this.view_transfer.date,
      status: this.view_transfer.status,
      slug: this.view_transfer.slug,
      licence_slug: this.view_transfer.licence.slug,
      licence_id: this.view_transfer.id,
      status: this.view_transfer.status,
      
      },
      options: this.companies_dropdown,
      body_max: 100,

      //Now handle task creation..
       createTask: this.$inertia.form({
        body: '',
        model_type: 'Transfer',
        model_id: this.view_transfer.id,
        taskDate: ''
      }),
    };
  },
    methods: {
      submit() {
          this.$inertia.post(`/transfer-licence-submit/${this.view_transfer.slug}`, this.form)
          .then(() => {
              
            })
        },
         // store task
    submitTask() {
      this.createTask.post('/submit-task',this.createTask)
      this.createTask.body = ''
    },

    deleteTask(task_id){//delete task
      if(confirm('Are you sure??')){
        this.$inertia.delete(`/delete-task/${task_id}`)
      }
    },
      checkBodyLength(){//Monitor task body length..
        if(this.createTask.body.length > this.body_max){
            this.createTask.body = this.createTask.body.substring(0,this.body_max)
        }
     },
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

</script>
<style>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-top: 3px;
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
   <h5>New Transfer for: {{ view_transfer.licence.trading_name }}</h5>
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
<input type="hidden" v-model="form.old_company_id"> 
<input type="hidden" v-model="form.licence_id"> 
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="radio" :checked="view_transfer.status == 'Deposit Paid'">
<label class="form-check-label text-body text-truncate status-heading">Deposit Paid</label>
</div>
</div>     
<hr>
<div class="col-md-12 columns">
<div class="form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate">Scanned Deposit Invoice</label>
</div>
</div> 

<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" value="Collate Transfer Details" type="radio" :checked="view_transfer.status == 'Collate Transfer Details'">
<label class="form-check-label text-body text-truncate status-heading">Collate Transfer Details</label>
</div>
</div> 

  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Transfered From Company *</label>
    <input type="text" required readonly title="You can`t change this field." class="form-control form-control-default" v-model="form.old_company" >
     </div>
   <div v-if="errors.old_company" class="text-danger">{{ errors.old_company }}</div>
   </div>
  <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled">
     <Multiselect
     v-model="form.new_company"
        placeholder="Transfer to...."
        :options="options"
        :searchable="true"
      />
    </div>
 <div v-if="errors.new_company" class="text-danger">{{ errors.new_company }}</div>
</div>

 <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Transfer Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.transfer_date">
     </div>
   <div v-if="errors.date" class="text-danger">{{ errors.date }}</div>
   </div>

<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="radio" :checked="view_transfer.status == 'Client Invoiced'" value="Client Invoiced">
<label class="form-check-label text-body text-truncate status-heading">Client Invoiced</label>
</div>
</div> 


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate">Invoice</label>
</div>
</div> <hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="radio" value="Client Paid" :checked="view_transfer.status == 'Client Paid'">
<label class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div> 


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate">????</label>
</div>
</div> <hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="radio" value="Transfer Logded" :checked="view_transfer.status == 'Transfer Logded'">
<label class="form-check-label text-body text-truncate status-heading"> Transfer Logded</label>
</div>
</div> 


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate">Logded Transfer File</label>
</div>
</div> <hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="radio" value="Certificate Received" :checked="view_transfer.status == 'Certificate Received'">
<label class="form-check-label text-body text-truncate status-heading"> Certificate Received</label>
</div>
</div> 


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate">Transfer Certificate File</label>
</div>
</div> <hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="radio" value="Transfer Complete And Delivered" :checked="view_transfer.status == 'Transfer Complete And Delivered'">
<label class="form-check-label text-body text-truncate status-heading"> Transfer Complete &amp; Delivered</label>
</div>
</div> 


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate">Transfer Certificate File</label>
</div>
</div> <hr>
<div>
  <button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}">Save</button></div>
            </div>
            </form>
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
      <!-- //tasks were here -->
        
        </div>
        
      </div>

      <div class="row"><hr>
      <h6 class="text-center">Notes</h6>
       <div class="col-xl-8">
      <div class="row">
        <div v-for="task in tasks" :key="task.id" class="mb-4 col-xl-12 col-md-12 mb-xl-0">
         <div class="alert text-white alert-secondary alert-dismissible fade show font-weight-light" role="alert">
         <span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
         <span class="text-sm">{{ task.body }}</span>
         </span>
         <button @click="deleteTask(task.id)" type="button" class="btn-close d-flex justify-content-center align-items-center" 
         data-bs-dismiss="alert" aria-label="Close">
         <span aria-hidden="true" class="text-lg font-weight-bold">×</span></button>
         </div>
        </div>
        <h6 v-if="!tasks" class="text-center">No tasks found.</h6>
      </div>
     
    </div>

    <div class="col-xl-4">
  <form @submit.prevent="submitTask">
<div class="col-md-12 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" required class="form-control form-control-default" v-model="createTask.taskDate" >
     </div>
   <div v-if="errors.taskDate" class="text-danger">{{ errors.taskDate }}</div>
   </div>

 <div class="col-12 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">New Task<span class="text-danger pl-6">
   ({{ body_max - createTask.body.length}}/{{ body_max }})</span></label>
  <textarea v-model="createTask.body" @input='checkBodyLength' class="form-control form-control-default" rows="3" ></textarea>
   </div>
 <div v-if="errors.body" class="text-danger">{{ errors.body }}</div>
 </div>


<button type="submit" class="btn btn-sm btn-secondary ms-2 mt-4 float-end justify-content-center">Save</button>
</form>
    </div>
  </div>
    </div>
  </div>
  </Layout>
</template>
