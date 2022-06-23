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
<h6 class="mb-1">Licence Info: {{ licence.trading_name }}</h6>
</div>


<div class="col-lg-6 col-5 my-auto text-end">
<div class="dropdown float-lg-end pe-4">
<a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
<i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i>
</a>
<ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
<li><Link :href="`/renew-licence?slug=${licence.slug }`" class="dropdown-item border-radius-md">Renewals</Link></li>

<li><Link :href="`/transfer-history?slug=${licence.slug }`" class="dropdown-item border-radius-md"> Transfers</Link></li>

<li><Link :href="`/nominations?slug=${licence.slug }`" class="dropdown-item border-radius-md"> Nominations</Link></li>

<li><Link :href="`/alterations?slug=${licence.slug }`" class="dropdown-item border-radius-md"> Alterations</Link></li>

<li><hr class="text-danger"></li>
<li><button @click="deleteLicence" class="dropdown-item border-radius-md text-danger" >
<i class="fa fa-trash-o cursor-pointer" aria-hidden="true"></i> Delete</button></li>
</ul>
</div>
</div>
</div>
<div class="row">
<div class="mt-3 ">
<form class="row" @submit.prevent="updateLicence">
<div class="col-8 col-md-8 col-xl-8 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">

<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate">Active</label>
<input id="active-checkbox" type="checkbox" value="1"
@input="assignActiveValue($event.target.value)" :checked="licence.is_licence_active == '1'">
</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Trading Name *</label>
<input type="text" required class="form-control form-control-default" v-model="form.trading_name" >
</div>
<div v-if="errors.trading_name" class="text-danger">{{ errors.trading_name }}</div>
</div>

<div class="col-md-6 columns" v-if="show_current_company">
<div class="input-group input-group-outline null is-filled">
<label class="form-label mb-4">Current Company</label>
<input type="text" @focus="changeCompany" class="form-control form-control-default" v-model="form.company" >
</div>
<div v-if="errors.licence_type" class="text-danger">{{ errors.licence_type }}</div>
</div>

<div class="col-md-6 columns" v-if="change_company">
<Multiselect
v-model="form.change_company"
:options="options"
:searchable="true"
placeholder="Change Company"
class="form-label"
/>
<!-- <i class="fa fa-times"></i> -->
<div v-if="errors.licence_type" class="text-danger">{{ errors.licence_type }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Licence Type *</label>
<select v-model="form.licence_type" class="form-control form-control-default">
<option v-for='licence_dropdown in licence_dropdowns' :value=licence_dropdown.id> {{ licence_dropdown.licence_type }}</option>
</select>
</div>
<div v-if="errors.licence_type" class="text-danger">{{ errors.licence_type }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Licence Number</label>
<input type="text" class="form-control form-control-default" v-model="form.licence_number" >
</div>
<div v-if="errors.licence_number" class="text-danger">{{ errors.licence_number }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Old Licence Number</label>
<input type="text" class="form-control form-control-default" v-model="form.old_licence_number" >
</div>
<div v-if="errors.old_licence_number" class="text-danger">{{ errors.old_licence_number }}</div>
</div>  
<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Original Issue Of Licence Date</label>
<input type="date" class="form-control form-control-default" v-model="form.licence_date">
</div>
<div v-if="errors.licence_date" class="text-danger">{{ errors.licence_date }}</div>
</div>

</div>

</div>
</div>
<hr class="vertical dark" />
</div>

<div class="col-4 col-md-4 col-xl-4" style="margin-top: 3.4rem;">

<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Address</label>
<input type="text" class="form-control form-control-default" v-model="form.address">
</div>
<div v-if="errors.address" class="text-danger">{{ errors.address }}</div>
</div>           
<div class="col-12 columns">                  
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Province</label>
<select class="form-control form-control-default" v-model="form.province" >
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
<div v-if="errors.province" class="text-danger">{{ errors.province }}</div>
</div>

<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Code</label>
<input  type="text" class="form-control form-control-default" v-model="form.postal_code">
</div>

</div>

</div>
<div>
<button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}">Save</button>
</div>
</form>
<hr>

<div class="row">
<h6 class="text-center mb-2 ">Companies Linked To : {{ licence.trading_name }}</h6>
<div class="table-responsive p-0">
<table class="table align-items-center mb-0">
<thead>
<tr>
<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
Active
</th>
<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
Company Name
</th>

<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
Action
</th>

</tr>
</thead>
<tbody>
<tr>
<td v-if="licence.company.active !== null" class=" text-sm">Active</td>
<td v-else class=" text-sm text-danger">InActive</td>
<td class="align-middle text-sm">
<Link class="text-sm text-center align-middle" :href="`/view-company/${licence.company.slug}`"><h6 class="mb-0 ">{{ licence.company.name }}</h6></Link>
</td>
<td class="text-center">
<Link :href="`/view-company/${licence.company.slug}`" class="btn btn-secondary btn-sm">View</Link>
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
<div class="col-md-12 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Date</label>
<input type="date" required  class="form-control form-control-default" v-model="createTask.taskDate" >
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
</div>
</div>
</Layout>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>
    .columns{
      margin-bottom: 1rem;
    }
    #active-checkbox{
      margin-top: 3px;
      margin-left: 3px;
    }
</style>

<style src="@vueform/multiselect/themes/default.css"></style>

<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link } from '@inertiajs/inertia-vue3';

export default {
 props: {
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    success: String,
    error: String,
    tasks: Object,
    companies: Object
  },
  data() {
    return {
         showMenu: false,
         body_max: 100,
         options: this.companies,
         show_current_company: true,
         change_company: false,

         form: {
         trading_name: this.licence.trading_name,
         licence_type: this.licence.licence_type,
         is_licence_active: this.licence.is_licence_active,
         licence_number: this.licence.licence_number,
         old_licence_number: this.licence.old_licence_number,
         licence_date: this.licence.licence_date,
         postal_code: this.licence.postal_code,
         address: this.licence.address,
         province: this.licence.province,
         company: this.licence.company.name,
         company_id: this.licence.company.id,
         change_company: '',
        },
        
      //Now handle task creation..
       createTask: this.$inertia.form({
          body: '',
          model_type: 'Licence',
          model_id: this.licence.id,
          taskDate: new Date().getDate()+'/'+(new Date().getMonth()+1)+'/'+new Date().getFullYear()
       }),
      
    };
  },
  methods: {
        changeCompany(){
          this.show_current_company=false,
          this.change_company=true
        },
        updateLicence() {
      // this.loading=true;
         this.$inertia.patch(`/update-licence/${this.licence.slug}`, this.form)
        },

        deleteLicence(){
          if(confirm('Are you sure you want to delete this licence??')){
            this.$inertia.delete(`/delete-licence/${this.licence.slug}`)
          }      
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
        assignActiveValue(event){
          this.form.is_licence_active = event
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

</script>

