<script>
import Layout from "../Shared/Layout.vue";
import { Head,Link } from '@inertiajs/inertia-vue3';
import DefaultProjectCard from "./components/DefaultProjectCard.vue";

export default {
  name: "profile-overview",
  props: {
    errors: Object,
    company: Object,
    success: String,
    error: String,
  },

  data() {
    return {
      openModal: false,
        form: {
        company_name: this.company.name,
        company_type: this.company.company_type,
        reg_number: this.company.reg_number,
        vat_number: this.company.vat_number,
        fax_number: this.company.fax,
        email_address_1: this.company.email,
        email_address_2: this.company.email1,
        email_address_3: this.company.email2,
        telephone_number_1: this.company.tel_number,
        telephone_number_2: this.company.tel_number1,
        website: this.company.website,
        business_address: this.company.address,
        business_address_postal_code: this.company.business_address_postal_code,
        postal_address: this.company.postal_address,
        postal_address_code: this.company.postal_address_code,
        sars_certificate: null,
        sars_certificate_valid: '',
        bee_status: '',
        gatla_certificate: null,
        gatla_valid: '',
        gatla_date: '',
        cipc_notice_of_incorporation: null,
        cipc_memorandum_of_incorporation: null,
        transfer_certificate: null,
        company_id: this.company.id,
        active: '',
      },
      addUserForm:{//this for modal data
       full_name: '',
       company_admin_email: '',
       company_id: this.company.id,
       slug: this.company.slug,
      },
      showMenu: false,
    };
  },
  components: {
    DefaultProjectCard,
    Layout,
    Link,
    Head,
  },

  methods: {
    submit() {
      this.$inertia.post(`/update-company`, this.form,{
       forceFormData: true,
      })
    },

triggerModal(){
      this.openModal=true
    },
     showModal(){
        this.myModal = new bootstrap.Modal(document.getElementById('add_userModal'), {})
        this.myModal.show()
    },

    addUser(){
      this.$inertia.post('/add-company-admin', this.addUserForm)

        
    }
  },

  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};
</script>
<style>
#active-checkbox{
  margin-top: 3px;
  margin-left: 3px;
}
</style>
<template>
<Layout>
<div class="container-fluid" >
<div class="page-header min-height-100 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      ">
      <span class="mask bg-gradient-success opacity-6"></span>
</div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
 <div class="row gx-4">
<div class="col-auto">

</div>
<div class="row">
<div class="col-lg-6 col-7">
<h6 class="mb-1">Company: {{ company.name }}</h6>
</div>
<div class="col-lg-6 col-5 my-auto text-end">
<div class="dropdown float-lg-end pe-4">
<a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
 <i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i>
</a>
<ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable" style="">
<li><Link :href="`#!`" @click="triggerModal" data-bs-toggle="modal" data-bs-target="#add-user" class="dropdown-item border-radius-md">Add User</Link></li>
<li><Link :href="`/create-licence/${company.slug}`" class="dropdown-item border-radius-md"> Create Licence</Link></li>
<li><a class="dropdown-item border-radius-md text-danger" ><i class="fa fa-trash-o cursor-pointer" aria-hidden="true"></i> Delete</a></li>
</ul>
</div>
</div>
</div>


</div>
      <div class="row">
      <form @submit.prevent="submit">
      <input type="hidden" v-model="form.slug">
        <div class="mt-3 row">
          <div class="col-12 col-md-6 col-xl-4 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
                <ul class="list-group">
                 <li class="px-0 border-0 list-group-item">
                  <div class="form-check form-switch d-flex ps-0 ms-0">
                  <label class="form-check-label ms-3 mb-0 text-body text-truncate">Active Company</label>
                  <input id="active-checkbox" type="checkbox" v-model="form.active">
                  </div>
                  </li>
  <li class="px-0 border-0 list-group-item">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Company Name *</label>
  <input type="text" class="form-control form-control-default" v-model="form.company_name">
   </div>
   <p v-if="errors.company_name" class="text-danger">{{ errors.company_name }}</p>
</li>
<li class="px-0 border-0 list-group-item"><div class="input-group input-group-outline is-filled">
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
                  <p v-if="errors.company_type" class="text-danger">{{ errors.company_type }}</p>
                  </li>
    <li class="px-0 border-0  list-group-item">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Registration Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.reg_number" >
   </div>
    <p v-if="errors.reg_number" class="text-danger">{{ errors.reg_number }}</p>
     </li>
   <li class="px-0 border-0 list-group-item">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Vat Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.vat_number" >
   </div>
     <p v-if="errors.vat_number" class="text-danger">{{ errors.vat_number }}</p>
    </li>
 <li class="px-0 border-0 list-group-item">
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Email Address #1</label>
  <input type="email" class="form-control form-control-default" v-model="form.email_address_1" >
   </div>
                   <p v-if="errors.email_address_1" class="text-danger">{{ errors.email_address_1 }}</p>
  </li>
 
                 
                </ul>
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
          <div class="col-12 col-md-6 col-xl-4 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 pb-0 card-header" :style="{visibility: 'hidden'}">
                <h6 class="mb-0" >License Details</h6>
              </div>
              <div class="p-3 card-body">
                
                <ul class="list-group">
                                <li class="px-0 border-0 list-group-item">
                 
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Email Address #2</label>
  <input type="email" class="form-control form-control-default" v-model="form.email_address_2" >
   </div>
                  <p v-if="errors.email_address_2" class="text-danger">{{ errors.email_address_2 }}</p>
                  </li>

                   <li class="px-0 border-0 list-group-item">
 
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Email Address #3</label>
  <input type="email" class="form-control form-control-default" v-model="form.email_address_3" >
   </div>
                  <p v-if="errors.email_address_3" class="text-danger">{{ errors.email_address_3 }}</p>
                  </li>
                  <li class="px-0 border-0 list-group-item">
        
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Telephone Number #1</label>
  <input type="text" class="form-control form-control-default" v-model="form.telephone_number_1" >
   </div>
                  <p v-if="errors.telephone_number_1" class="text-danger">{{ errors.telephone_number_1 }}</p>
                  </li>
                  <li class="px-0 border-0 list-group-item">
 
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Telephone Number #2</label>
  <input type="text" class="form-control form-control-default" v-model="form.telephone_number_2" >
   </div>
                  <p v-if="errors.telephone_number_2" class="text-danger">{{ errors.telephone_number_2 }}</p>
                  </li>
                  <li class="px-0 border-0 list-group-item is-filled">

  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Fax Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.fax_number" >
   </div>
                  <p v-if="errors.fax_number" class="text-danger">{{ errors.fax_number }}</p>
                  </li>
                   
                </ul>
                
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
          <div class="mt-4 col-12 col-xl-4 mt-xl-0">
            <div class="card card-plain h-100">
            <div class="p-3 pb-0 card-header" :style="{visibility: 'hidden'}">
                <h6 class="mb-0" >License Details</h6>
              </div>
              <div class="p-3 card-body">

     

            <!-- Tabs content -->
            <div class="tab-content" id="ex1-content">
              <div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel"
                aria-labelledby="ex1-tab-1">
                <ul class="list-group mb-0">
                  <li class="px-0 border-0 list-group-item is-filled">
 
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Website</label>
  <input type="text" class="form-control form-control-default" v-model="form.website" >
   </div>
                  <p v-if="errors.website" class="text-danger">{{ errors.website }}</p>
                  </li>
                  <li class="px-0 border-0 list-group-item">
        
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Business Address</label>
  <input type="text" class="form-control form-control-default" v-model="form.business_address" >
   </div>
                  <p v-if="errors.business_address" class="text-danger">{{ errors.business_address }}</p>
                  </li>

                  <li class="px-0 border-0 list-group-item">
  
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Postal Code</label>
  <input type="text" class="form-control form-control-default" v-model="form.business_address_postal_code" >
   </div>
                  <p v-if="errors.business_address_postal_code" class="text-danger">{{ errors.business_address_postal_code }}</p>
                  </li>
                  <li class="px-0 border-0 list-group-item">

        <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Postal Address</label>
  <input type="text" class="form-control form-control-default" v-model="form.postal_address" >
   </div>
                  <p v-if="errors.postal_address" class="text-danger">{{ errors.postal_address }}</p>
                  </li>
                  <li class="px-0 border-0 list-group-item null is-filled">
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Postal Code</label>
  <input type="text" class="form-control form-control-default" v-model="form.postal_address_code" >
   </div>
                  <p v-if="errors.postal_address_code" class="text-danger">{{ errors.postal_address_code }}</p>
                  </li>
                </ul>
              </div>
            </div>
            <!-- Tabs content -->

              </div>
            </div>
          </div>
        </div>
      
<div class="float-end">
<button type="submit" class="btn btn-secondary ms-2">Save</button>
</div>

        
</form>
        
      </div>
    </div>
  </div>

  <div class="modal fade" id="add-user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
  <form @submit.prevent="addUser">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Add {{ company.name }} Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <input type="hidden" v-model="addUserForm.slug">
          <div class="input-group input-group-outline null is-filled">
          <label class="form-label">Full Name</label>
          <input type="text" required class="form-control form-control-default" v-model="addUserForm.full_name" >
          </div>
          <p v-if="errors.full_name" class="text-danger">{{ errors.full_name }}</p>
          <div class="input-group input-group-outline null is-filled mt-3 mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" required class="form-control form-control-default"  v-model="addUserForm.company_admin_email">
          </div>
          <p v-if="errors.company_admin_email" class="text-danger">{{ errors.company_admin_email }}</p>

      <div v-if="success" class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
      <span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
      <span class="text-sm">{{ success }}</span></span>
      <button type="button" class="btn-close d-flex justify-content-center align-items-center" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="text-lg font-weight-bold">×</span>
      </button></div>
      
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-secondary">Save</button>
        </div>
       </div>
        </form>
  </div>
</div>
  </Layout>
</template>
