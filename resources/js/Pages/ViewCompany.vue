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
<h6 class="mb-1">Company Info: {{ company.name }}</h6>
</div>
<div class="col-lg-6 col-5 my-auto text-end">
<div class="dropdown float-lg-end pe-4">
<a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
<i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i>
</a>
<ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable" style="">
<li><Link :href="`#!`" @click="triggerModal" data-bs-toggle="modal" data-bs-target="#add-user" class="dropdown-item border-radius-md">Add User</Link></li>

<li><a class="dropdown-item border-radius-md text-danger" ><i class="fa fa-trash-o cursor-pointer" aria-hidden="true"></i> Delete</a></li>
</ul>
</div>
</div>
</div>



<div class="row">
<div class="mt-3 ">
<form class="row" @submit.prevent="submit">
<input type="hidden" v-model="form.slug">
<div class="col-8 col-md-8 col-xl-8 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">
<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label ms-3 mb-0 text-body text-truncate">Active Company</label>
<input id="active-checkbox" type="checkbox" :checked="form.active == '1'" @input="assignActiveValue($event.target.value)">
</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Company Name *</label>
<input type="text" class="form-control form-control-default" v-model="form.company_name">
</div>
<div v-if="errors.company_name" class="text-danger">{{ errors.company_name }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Company Type </label>
<select class="form-control form-control-default" v-model="form.company_type" isrequired="true">
<option value="-1">&lt;-- Please select an option --&gt;</option>
<option value="Association">Association</option>
<option value="Close Corporation">Close Corporation</option>
<option value="Foreign Company">Foreign Company</option>
<option value="Non Resident Company">Non Resident Company</option>
<option value="Partnership">Partnership</option>
<option value="Private Company">Private Company</option>
<option value="Public Company">Public Company</option>
<option value="Section 21">Section 21</option>
<option value="Sole Proprietor">Sole Proprietor</option>
<option value="Trust">Trust</option>
</select>
</div>
<div v-if="errors.company_type" class="text-danger">{{ errors.company_type }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Registration Number</label>
<input type="text" class="form-control form-control-default" v-model="form.reg_number" >
</div>
<div v-if="errors.reg_number" class="text-danger">{{ errors.reg_number }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Vat Number</label>
<input type="text" class="form-control form-control-default" v-model="form.vat_number" >
</div>
<div v-if="errors.vat_number" class="text-danger">{{ errors.vat_number }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Email Address #1</label>
<input type="email" class="form-control form-control-default" v-model="form.email_address_1" >
</div>
<div v-if="errors.email_address_1" class="text-danger">{{ errors.email_address_1 }}</div>
</div>  
<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Email Address #2</label>
<input type="email" class="form-control form-control-default" v-model="form.email_address_2" >
</div>
<div v-if="errors.email_address_2" class="text-danger">{{ errors.email_address_2 }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Email Address #3</label>
<input type="email" class="form-control form-control-default" v-model="form.email_address_3" >
</div>
<div v-if="errors.email_address_3" class="text-danger">{{ errors.email_address_3 }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Telephone Number #1</label>
<input type="text" class="form-control form-control-default" v-model="form.telephone_number_1" >
</div>
<div v-if="errors.telephone_number_1" class="text-danger">{{ errors.telephone_number_1 }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Telephone Number #2</label>
<input type="text" class="form-control form-control-default" v-model="form.telephone_number_2" >
</div>
<div v-if="errors.telephone_number_2" class="text-danger">{{ errors.telephone_number_2 }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Website</label>
<input type="url" class="form-control form-control-default" v-model="form.website" >
<Link @click="redirectToWebsite(company.website)" href="#!" class="input-group-text text-primary" id="addon-wrapping">
<i class="material-icons-round opacity-10 fs-5">open_in_new</i></Link>
</div>
<div v-if="errors.website" class="text-danger">{{ errors.website }}</div>
</div>



</div>

</div>
</div>
<hr class="vertical dark" />
</div>

<div class="col-4 col-md-4 col-xl-4" style="margin-top: 3.4rem;">
<div class="row">
<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Business Address</label>
<input type="text" class="form-control form-control-default" v-model="form.business_address" >
</div>
<div v-if="errors.business_address" class="text-danger">{{ errors.business_address }}</div>
</div>           
<div class="col-6 columns">                  
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Province</label>
<select class="form-control form-control-default" v-model="form.business_province" >
<option value="Eastern Cape">Eastern Cape</option>
<option value="Free State">Free State</option>
<option value="Gauteng">Gauteng</option>
<option value="KwaZulu-Natal">KwaZulu-Natal</option>
<option value="Limpopo">Limpopo</option>
<option value="Mpumalanga">Mpumalanga</option>
<option value="Northern Cape">Northern Cape</option>
<option value="North West">North West</option>
<option value="Western Cape">Western Cape</option>
</select>
</div>
<div v-if="errors.business_province" class="text-danger">{{ errors.business_province }}</div>
</div>

<div class="col-6 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Code</label>
<input  type="text" class="form-control form-control-default" v-model="form.business_address_postal_code">
</div>

</div>

<hr>

<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Address</label>
<input type="text" class="form-control form-control-default" v-model="form.postal_address" >
</div>
<div v-if="errors.postal_address" class="text-danger">{{ errors.postal_address }}</div>
</div>           
<div class="col-6 columns">                  
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Province</label>
<select class="form-control form-control-default" v-model="form.postal_province" >
<option value="Eastern Cape">Eastern Cape</option>
<option value="Free State">Free State</option>
<option value="Gauteng">Gauteng</option>
<option value="KwaZulu-Natal">KwaZulu-Natal</option>
<option value="Limpopo">Limpopo</option>
<option value="Mpumalanga">Mpumalanga</option>
<option value="Northern Cape">Northern Cape</option>
<option value="North West">North West</option>
<option value="Western Cape">Western Cape</option>
</select>
</div>
<div v-if="errors.postal_province" class="text-danger">{{ errors.postal_province }}</div>
</div>

<div class="col-6 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Code</label>
<input  type="text" class="form-control form-control-default" v-model="form.postal_code">
</div>

</div>




</div>
</div>
<div>
<button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}">Save</button>
</div>
</form>
<hr>
</div>
</div>



<div class="row">
<h5 class="text-center text-decoration-underline">People Linked To : {{ company.name }}</h5>
<div class="table-responsive p-0">
<table class="table align-items-center mb-0">
<thead>
  <tr>
    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
    Full Name
    </th>
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
    ID Number
    </th>
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
    Position
    </th>

    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
    Action
    </th>
    
  </tr>
</thead>
<tbody>
  <tr v-for="person in company.consultants" :key="person.id">
    <td>
      <div class="d-flex px-2 py-1">
    
    <div class="d-flex flex-column">
      <Link :href="`/view-consultant/${person.slug}`"><h6 class="mb-0 text-sm">{{ person.first_name }} {{ person.last_name }}</h6></Link>                          
    </div>
      </div>
    </td>
    <td class="text-center">{{ person.identity_number }}</td>
    <td class="text-center">{{ person.pivot.position }}</td>
    <td class="text-center">
    <button @click="unlinkPerson(person.first_name + ' ' + person.last_name,person.pivot.id )" type="button" class="btn btn-secondary btn-sm">Unlink</button>
    </td>
    
  </tr>
  
  
</tbody>
</table>
</div>
<hr>
<h6 class="text-center text-decoration-underline">Notes</h6>
</div>



<div class="row">
<div class="col-xl-8">
<div class="row">
<div v-for="task in tasks" :key="task.id" class="mb-4 col-xl-12 col-md-12 mb-xl-0">
<div class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
<span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
<span class="text-sm">{{ task.body }}</span>
</span>
<button @click="deleteTask(task.id)" type="button" class="btn-close d-flex justify-content-center align-items-center" 
data-bs-dismiss="alert" aria-label="Close">
<i class="far fa-trash-alt me-2" aria-hidden="true"></i></button>
<p style=" font-size: 12px"><i class="fa fa-clock-o" ></i> {{ new Date(task.date).toLocaleString().split(',')[0] }}</p>
</div>
</div>
<h6 v-if="!tasks" class="text-center">No tasks found.</h6>
</div>

</div>

<div class="col-xl-4">
<form @submit.prevent="submitTask">
<div class="col-md-12" style="margin-bottom: 1rem;">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Date</label>
<input type="date" required class="form-control form-control-default" v-model="createTask.taskDate" >
</div>
<div v-if="errors.taskDate" class="text-danger">{{ errors.taskDate }}</div>
</div>
<div class="col-12 columns">    
<div class="input-group input-group-outline null is-filled">
<label class="form-label">New Task<span class="text-danger pl-6">{{ body_max - createTask.body.length}}/{{ body_max }}</span></label>
<textarea v-model="createTask.body" @input='checkBodyLength' class="form-control form-control-default" rows="3" ></textarea>
</div>
<div v-if="errors.body" class="text-danger">{{ errors.body }}</div>
</div>

<button :disabled="createTask.processing" :style="{float: 'right'}" class="btn btn-sm btn-secondary ms-2 mt-4 float-end justify-content-center" type="submit">
  <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span class="visually-hidden">Loading...</span> Submit</button>

</form>
</div>
</div>
</div>
</div>
</Layout>
</template>
<style>
  #active-checkbox{
    margin-top: 3px;
    margin-left: 3px;
  }
  .columns{
      margin-bottom: 1rem;
    }
</style>

<script>
import Layout from "../Shared/Layout.vue";
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';

export default {
 props: {
    tasks: Object,
    errors: Object,
    company: Object,
    success: String,
    error: String,
 },  
  
  setup (props) {
    let showMenu = false;
    let openModal = false;
    let body_max = 100;

    const form = useForm({
          company_name: props.company.name,
            company_type: props.company.company_type,
            reg_number: props.company.reg_number,
            vat_number: props.company.vat_number,
            email_address_1: props.company.email,
            email_address_2: props.company.email1,
            email_address_3: props.company.email2,
            telephone_number_1: props.company.tel_number,
            telephone_number_2: props.company.tel_number1,
            website: props.company.website,
            business_address: props.company.business_address,
            business_province: props.company.business_province,
            business_address_postal_code: props.company.business_address_postal_code,
            postal_address: props.company.postal_address,
            postal_province: props.company.postal_province,
            postal_code: props.company.postal_code,
            sars_certificate: null,
            sars_certificate_valid: '',
            bee_status: '',
            gatla_certificate: null,
            gatla_valid: '',
            gatla_date: '',
            cipc_notice_of_incorporation: null,
            cipc_memorandum_of_incorporation: null,
            transfer_certificate: null,
            company_id: props.company.id,
            active: props.company.active,
            province: props.company.business_province 
    })

      const createTask = useForm({
          body: '',
          taskDate: null,
          model_type: 'Company',
          model_id: props.company.id,
      })

    function submit() {
      form.post('/update-company', {
        preserveScroll: true,
      })
      
    }
            // store task
      function submitTask() {
        createTask.post('/submit-task',{
          ///do something
        })
        createTask.reset()
      }

      function deleteTask(task_id){
        if(confirm('Are you sure??')){
          createTask.delete(`/delete-task/${task_id}`,{
          ///do something
        })
        }
      }

      function checkBodyLength(){//Monitor task body length..
          if(this.createTask.body.length > this.body_max){
              this.createTask.body = this.createTask.body.substring(0,this.body_max)
          }
      }

      function unlinkPerson(full_name,id){
        if(confirm(full_name + ' will be removed from this company...Continue..??')){
          createTask.delete(`/unlink-consultant/${id}`,{
          ///do something
        })
        }
      }

      function addUser(){
        this.$inertia.post('/add-company-admin', this.addUserForm)
      } 

      function assignActiveValue(event){
        this.form.active = event
      }

      function redirectToWebsite(url){
        if(url.includes('https://') || url.includes('http://')){
          window.open(url, "_blank");
        }else{
          window.open('https://' + url, "_blank");
        }
        
      }
    return { submit,submitTask,deleteTask,
             checkBodyLength,addUser,
            unlinkPerson,assignActiveValue,
             redirectToWebsite,body_max, createTask,form }
  },
   components: {
    Layout,
    Link,
    Head
  },
  
};
</script>

