<script>
import Layout from "../../Shared/Layout.vue";
import { Head,Link } from '@inertiajs/inertia-vue3';

export default {
  name: "dashboard-default",
 props: {
    tasks: Object,
    errors: Object,
    person: Object,
    isFromViewNominationPage: Boolean,
    isFromViewPersonPage: Boolean
  },
  data() {
    return {
      showMenu: false,
      form: {
      name: this.person.name,
      initials: this.person.initials,
      surname: this.person.surname,
      date_of_birth: this.person.date_of_birth,
      id_number: this.person.id_number,
      passport_number: this.person.passport,
      id_or_passport: this.person.id_or_passport,
      email_address_1: this.person.email_address_1,
      email_adddress_2: this.person.email_adddress_2,
      cell_number: this.person.cell_number,
      fax_number: this.person.fax_number,
      telephone: this.person.telephone,
      postal_code: this.person.postal_code,
      work_address: this.person.work_address,
      work_address_postal_code: this.person.work_address_postal_code,
      home_address: this.person.home_address,
      home_address_postal_code: this.person.home_address_postal_code,
      valid_certified_id: this.person.valid_certified_id,
      valid_saps_clearance: this.person.valid_saps_clearance,
      saps_clearance_valid_until: this.person.saps_clearance_valid_until,
      passport_valid_until: this.person.passport_valid_until,
      valid_fingerprint: this.person.valid_fingerprint,
      fingerprint_valid_until: this.person.fingerprint_valid_until,
      active: this.person.active,
      slug: this.person.slug,
      
    },
     body_max: 255,
      //handle task creation..
       createTask: this.$inertia.form({
        body: '',
        model_type: 'Person',
        model_id: this.person.id,
      }),
    };
  },
    methods: {
       // store task
    submitTask() {
      this.createTask.post('/submit-task',this.createTask)
      this.createTask.body = ''
    },
    checkBodyLength(){
        if(this.createTask.body.length > this.body_max){
            this.createTask.body = this.createTask.body.substring(0,this.body_max)
        }
     },

    deleteTask(task_id){
      if(confirm('Are you sure??')){
        this.$inertia.delete(`/delete-task/${task_id}`)
      }
    },
      submit() {//Update person
          this.$inertia.post(`/update-person`, this.form)
        },

      deletePerson() {
      if (confirm('Are you sure you want to delete this person?')) {
        this.$inertia.post(`/delete-person/${this.person.slug}`)
      }

    },
    terminate(){
      if (confirm('Are you sure you want to terminate this person?')) {
        this.$inertia.post(`/terminate-person/${this.person.nominations[0].pivot.id}/${this.person.slug}`)
      }
    }
  },
  components: {
    Layout,
    Link,
    Head
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
.display-text-length{
  margin-left: 10rem;
  font-size: 14px;
}
</style>

<template>
<Layout>
<div class="container-fluid">
    <div class="page-header min-height-100 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      ">
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row ">
       
        <div class="col-auto my-auto">
          <div class="h-100">
            <h5 class="mb-1">Person: {{ person.name }} {{ person.initials }} {{ person.surname }}</h5>
          </div>
        </div>
        <div class="mx-auto mt-3 col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0">
          
        </div>
      </div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
<form @submit.prevent="submit">
<input type="hidden" v-model="form.slug">
<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate">Active Person</label>
<input v-model="form.active"  type="checkbox" id="active-checkbox" :checked="form.active">
</div>
</div>
                  
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Name *</label>
    <input type="text" required class="form-control form-control-default" v-model="form.name" >
     </div>
   <div v-if="errors.name" class="text-danger">{{ errors.name }}</div>
   </div>
  
  <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Surname *</label>
  <input type="text" required class="form-control form-control-default" v-model="form.surname" >
  </div>
 <div v-if="errors.surname" class="text-danger">{{ errors.surname }}</div>
</div>

 <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Initials</label>
    <input type="text" class="form-control form-control-default" v-model="form.initials" >
     </div>
   <div v-if="errors.initials" class="text-danger">{{ errors.initials }}</div>
   </div>

<div class="col-md-4 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Date of Birth</label>
  <input type="date" class="form-control form-control-default" v-model="form.date_of_birth" >
   </div>
     <div v-if="errors.date_of_birth" class="text-danger">{{ errors.date_of_birth }}</div>
</div>  
<div class="col-md-4 columns">
<div class="input-group input-group-outline null is-filled">
  <label class="form-label">ID or Passport</label>
  <select v-model="form.id_or_passport" required class="form-control form-control-default">
    <option value="i_d">Identity Number</option>
    <option value="passport">Passport</option>
  </select>
</div>
<div v-if="errors.id_or_passport" class="text-danger">{{ errors.id_or_passport }}</div>
</div>

  <div v-if="form.id_or_passport == 'i_d'" class="col-md-4 columns">            
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Identity Number</label>
  <input required type="text" class="form-control form-control-default" v-model="form.id_number" placeholder="Enter ID Number">
   </div>
    <div v-if="errors.id_number" class="text-danger">{{ errors.id_number }}</div>
    </div>              
              
   <div v-if="form.id_or_passport == 'passport'" class="col-md-4 columns">                  
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Passport Number</label>
  <input type="text" required class="form-control form-control-default" v-model="form.passport_number" placeholder="Enter Passport Number">
   </div>
  <div v-if="errors.passport_number" class="text-danger">{{ errors.passport_number }}</div>
  </div>
                  
              
  <div class="col-md-4 columns">    
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Email Address #1</label>
  <input type="email" class="form-control form-control-default" v-model="form.email_address_1" >
   </div>
   <div v-if="errors.email_address_1" class="text-danger">{{ errors.email_address_1 }}</div>
   </div>
                  
                 
              
 <div class="col-md-4 columns">    
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Email Address #2</label>
  <input type="email" class="form-control form-control-default" v-model="form.email_adddress_2" >
   </div>
 <div v-if="errors.email_adddress_2" class="text-danger">{{ errors.email_adddress_2 }}</div>
 </div>
                  
 
<div class="col-md-4 columns">    
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Cellphone Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.cell_number" >
   </div>
   <div v-if="errors.cell_number" class="text-danger">{{ errors.cell_number }}</div>
   </div>
                  
  
 <div class="col-md-4 columns">    
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Fax Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.fax_number" >
   </div>
   <div v-if="errors.fax_number" class="text-danger">{{ errors.fax_number }}</div>
   </div>
                  
              
   <div class="col-md-4 columns">        
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Telephone Number</label>
  <input type="tel" class="form-control form-control-default" v-model="form.telephone" >
   </div>
   <div v-if="errors.telephone" class="text-danger">{{ errors.telephone }}</div>
  </div>
     
  <div class="col-md-4 columns">    
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Home Address </label>
  <input type="text" class="form-control form-control-default" v-model="form.home_address" >
   </div>
   <div v-if="errors.home_address" class="text-danger">{{ errors.home_address }}</div>
  </div>
  
                  
 <div class="col-md-4 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Home Address Postal Code</label>
  <input type="text" class="form-control form-control-default" v-model="form.home_address_postal_code" >
   </div>
 <div v-if="errors.home_address_postal_code" class="text-danger">{{ errors.home_address_postal_code }}</div>
 </div>
 <div class="col-md-4 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Work Address</label>
  <input type="text" class="form-control form-control-default" v-model="form.work_address" >
   </div>
 <div v-if="errors.work_address" class="text-danger">{{ errors.work_address }}</div>
 </div>
 <div class="col-md-4 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Work Address Postal Code</label>
  <input type="text" class="form-control form-control-default" v-model="form.work_address_postal_code" >
   </div>
 <div v-if="errors.work_address_postal_code" class="text-danger">{{ errors.work_address_postal_code }}</div>
 </div>
<hr>
<h6 class="text-center">Documents Related Fields</h6>
<div class="col-md-4 columns">
<div class="input-group input-group-outline null is-filled">
  <label class="form-label">Valid Certified ID?</label>
  <select v-model="form.valid_certified_id" class="form-control form-control-default">
    <option value="no">No</option>
    <option value="yes">Yes</option>
    <option value="requested">Requested</option>
  </select>
</div>
 <div v-if="errors.valid_certified_id" class="text-danger">{{ errors.valid_certified_id }}</div>
</div>
<div class="col-md-4 columns">
<div class="input-group input-group-outline null is-filled">
  <label class="form-label">Valid SAPS Clearance?</label>
  <select v-model="form.valid_saps_clearance" class="form-control form-control-default">
    <option value="no" >No</option>
    <option value="yes">Yes</option>
    <option value="requested">Requested</option>
  </select>
</div>
 <div v-if="errors.valid_saps_clearance" class="text-danger">{{ errors.valid_saps_clearance }}</div>
</div>
<div v-if="form.valid_saps_clearance == 'yes'" class="col-md-4 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">SAPS Clearance Valid Until?</label>
  <input type="date" class="form-control form-control-default" v-model="form.saps_clearance_valid_until" >
   </div>
 <div v-if="errors.saps_clearance_valid_until" class="text-danger">{{ errors.saps_clearance_valid_until }}</div>
 </div>

 <div class="col-md-4 columns">
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Valid FingerPrints?</label>
  <select v-model="form.valid_fingerprint" class="form-control form-control-default">
    <option value="no" >No</option>
    <option value="yes">Yes</option>
    <option value="requested">Requested</option>
  </select>
</div>
 <div v-if="errors.valid_fingerprint" class="text-danger">{{ errors.valid_fingerprint }}</div>
</div>

<div v-if="form.valid_fingerprint == 'yes'" class="col-md-4 columns">
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">FingerPrints Valid Until?</label>
    <input type="date" class="form-control form-control-default" v-model="form.fingerprint_valid_until">
</div>
 <div v-if="errors.valid_fingerprint" class="text-danger">{{ errors.valid_fingerprint }}</div>
</div>

<div v-if="form.id_or_passport == 'passport'" class="col-md-4 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Passport Valid Until?</label>
  <input type="date" class="form-control form-control-default" v-model="form.passport_valid_until" >
   </div>
 <div v-if="errors.passport_valid_until" class="text-danger">{{ errors.passport_valid_until }}</div>
 </div>
 
  <div class="d-flex">
  
  <button @click="deletePerson" type="button" class="btn btn-sm btn-danger">Delete</button>
  
  <button type="submit" class="btn btn-sm btn-secondary ms-2" >Update</button>
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

      <div class="row">
       <div class="col-xl-8">
      <div class="row">
        <div v-for="task in tasks" :key="task.id" class="mb-4 col-xl-6 col-md-6 mb-xl-0">
          <div class="card card-blog border border-success mb-4">
            <div class="p-3 card-body">
              <p class="mb-4 text-sm">{{ task.body.slice(0, 300) }}</p>
              <div class="d-flex align-items-center justify-content-between">
                <div class="avatar-group ">
                <Link :href='`#!`'  @click="deleteTask(task.id)"><i class="fa fa-trash text-danger"></i></Link>
                </div>
              </div>
            </div>
          </div>
        </div>
        <h6 v-if="!tasks" class="text-center">No tasks found.</h6>
      </div>
     
    </div>

    <div class="col-xl-4">
    <div class="ps-3 d-flex">
        <h6>New Task </h6>
        <p class="text-end text-danger display-text-length">{{ body_max - createTask.body.length}}/{{ body_max }}</p>
      </div>
     <form @submit.prevent="submitTask">

 <div class="col-12 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Task Body</label>
  <textarea v-model="createTask.body" @input='checkBodyLength' class="form-control form-control-default" rows="6" ></textarea>
   </div>
 <div v-if="errors.body" class="text-danger">{{ errors.body }}</div>
 </div>


<button type="submit" class="btn btn-sm btn-info ms-2 mt-4 float-end justify-content-center">Save</button>
</form>
    </div>
       </div>
    </div>
  </div>
  </Layout>
</template>
