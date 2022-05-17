<script>
import Layout from "../Shared/Layout.vue";
import { Head, Link } from '@inertiajs/inertia-vue3';
import DefaultProjectCard from "./components/DefaultProjectCard.vue";
import VmdInput from "@/components/VmdInput.vue";
import VmdSwitch from "@/components/VmdSwitch.vue";
import setNavPills from "@/assets/js/nav-pills.js";
import setTooltip from "@/assets/js/tooltip.js";

export default {
  name: "profile-overview",
  props: {
    errors: Object,
    company: Object
  },

  data() {
    return {
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
      },
      showMenu: false,
    };
  },
  components: {
    DefaultProjectCard,
    VmdInput,
    VmdSwitch,
    Layout,
    Link,
    Head,
  },

  methods: {
    submit() {
      this.$inertia.post(`/update-company`, this.form,{
       forceFormData: true,
      })
    }
  },

  mounted() {
    this.$store.state.isAbsolute = true;
    setNavPills();
    setTooltip();
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};
</script>

<template>
<Layout>
<div class="container-fluid">
    <div
      class="page-header min-height-300 border-radius-xl mt-4"
      style="
        background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      "
    >
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row gx-4">
        <div class="col-auto">
         
        </div>
        <div class="col-auto my-auto">
          <div class="h-100 d-flex">
             <h5 class="mb-1">Company: {{ company.name }}</h5>
            <Link :href="`/add-user`" class="btn btn-sm btn-info float-end">Add User</Link>
          </div>
          
        </div>
        <div
          class="mx-auto mt-3 col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0"
        >
          
        </div>
      </div>
      <div class="row">
      <form @submit.prevent="submit">
      <input type="hidden" v-model="form.company_id">
        <div class="mt-3 row">
          <div class="col-12 col-md-6 col-xl-4 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
                <ul class="list-group">
                 <li class="px-0 border-0 list-group-item">
                    <vmd-switch
                      id="flexSwitchCheckDefault"
                      class="ps-0 ms-0"
                      name="flexSwitchCheckDefault"
                      label-class="mb-0 text-body text-truncate w-80"
                      checked
                      >Active Company</vmd-switch
                    >
                  </li>
  <li class="px-0 border-0 list-group-item">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Company Name *</label>
  <input type="text" class="form-control form-control-default" v-model="form.company_name">
   </div>
   <p v-if="errors.company_name" :style="{color: ' #FF5252'}">{{ errors.company_name }}</p>
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
                  <p v-if="errors.company_type" :style="{color: ' #FF5252'}">{{ errors.company_type }}</p>
                  </li>
    <li class="px-0 border-0  list-group-item">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Registration Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.reg_number" >
   </div>
    <p v-if="errors.reg_number" :style="{color: ' #FF5252'}">{{ errors.reg_number }}</p>
     </li>
   <li class="px-0 border-0 list-group-item">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Vat Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.vat_number" >
   </div>
     <p v-if="errors.vat_number" :style="{color: ' #FF5252'}">{{ errors.vat_number }}</p>
    </li>
 <li class="px-0 border-0 list-group-item">
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Email Address #1</label>
  <input type="text" class="form-control form-control-default" v-model="form.email_address_1" >
   </div>
                   <p v-if="errors.email_address_1" :style="{color: ' #FF5252'}">{{ errors.email_address_1 }}</p>
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
  <input type="text" class="form-control form-control-default" v-model="form.email_address_2" >
   </div>
                  <p v-if="errors.email_address_2" :style="{color: ' #FF5252'}">{{ errors.email_address_2 }}</p>
                  </li>

                   <li class="px-0 border-0 list-group-item">
 
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Email Address #3</label>
  <input type="text" class="form-control form-control-default" v-model="form.email_address_3" >
   </div>
                  <p v-if="errors.email_address_3" :style="{color: ' #FF5252'}">{{ errors.email_address_3 }}</p>
                  </li>
                  <li class="px-0 border-0 list-group-item">
        
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Telephone Number #1</label>
  <input type="text" class="form-control form-control-default" v-model="form.telephone_number_1" >
   </div>
                  <p v-if="errors.telephone_number_1" :style="{color: ' #FF5252'}">{{ errors.telephone_number_1 }}</p>
                  </li>
                  <li class="px-0 border-0 list-group-item">
 
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Telephone Number #2</label>
  <input type="text" class="form-control form-control-default" v-model="form.telephone_number_2" >
   </div>
                  <p v-if="errors.telephone_number_2" :style="{color: ' #FF5252'}">{{ errors.telephone_number_2 }}</p>
                  </li>
                  <li class="px-0 border-0 list-group-item is-filled">

  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Fax Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.fax_number" >
   </div>
                  <p v-if="errors.fax_number" :style="{color: ' #FF5252'}">{{ errors.fax_number }}</p>
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
                  <p v-if="errors.website" :style="{color: ' #FF5252'}">{{ errors.website }}</p>
                  </li>
                  <li class="px-0 border-0 list-group-item">
        
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Business Address</label>
  <input type="text" class="form-control form-control-default" v-model="form.business_address" >
   </div>
                  <p v-if="errors.business_address" :style="{color: ' #FF5252'}">{{ errors.business_address }}</p>
                  </li>

                  <li class="px-0 border-0 list-group-item">
  
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Postal Code</label>
  <input type="text" class="form-control form-control-default" v-model="form.business_address_postal_code" >
   </div>
                  <p v-if="errors.business_address_postal_code" :style="{color: ' #FF5252'}">{{ errors.business_address_postal_code }}</p>
                  </li>
                  <li class="px-0 border-0 list-group-item">

        <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Postal Address</label>
  <input type="text" class="form-control form-control-default" v-model="form.postal_address" >
   </div>
                  <p v-if="errors.postal_address" :style="{color: ' #FF5252'}">{{ errors.postal_address }}</p>
                  </li>
                  <li class="px-0 border-0 list-group-item null is-filled">
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Postal Code</label>
  <input type="text" class="form-control form-control-default" v-model="form.postal_address_code" >
   </div>
                  <p v-if="errors.postal_address_code" :style="{color: ' #FF5252'}">{{ errors.postal_address_code }}</p>
                  </li>
                </ul>
              </div>
            </div>
            <!-- Tabs content -->

              </div>
            </div>
          </div>
        </div>
        <div class="row ">
   
          <div class="mt-3 row">
          <div class="col-12 col-md-6 col-xl-4 position-relative">
            <div class="card card-plain h-100">
            <div class="p-3 pb-0 card-header" >
                <h6 class="mb-0" >SARS Documents</h6>
              </div>
              <div class="p-3 card-body">
                <ul class="list-group">
            <li class=" list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    SARS Certificate
                    <input @input="form.sars_certificate = $event.target.files[0]" multiple type="file" value="" aria-label="..." />                    
                  </li>
      <progress v-if="form.progress" :value="form.progress.percentage" max="100">
      {{ form.progress.percentage }}%
    </progress>
                  <li class=" list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    SARS Certificate Valid ?
                    <input v-model="form.sars_certificate_valid" class="form-check-input ml-2" type="checkbox" value="" aria-label="..." checked />
                  </li>
                  <li class=" list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    <input v-model="form.sars_certificate_date" type="date"  aria-label="..." />
                    Date
                  </li>
                  <li class=" list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    BEE Certificate
                  </li>
                  <li class=" list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    BEE Status
                    <input v-model="form.bee_status" type="text"  aria-label="..." />
                    </li>
                 
                </ul>
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
          <div class="col-12 col-md-6 col-xl-4 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 pb-0 card-header" >
                <h6 class="mb-0" >GATLA Documents</h6>
              </div>
              <div class="p-3 card-body">
                
                <ul class="list-group">
                   <li class=" list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    GATLA 
                    <input @input="form.gatla_certificate = $event.target.files[0]" multiple type="file" value="" aria-label="..." />                    
                  </li>
                  <li class=" list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    GATLA Valid ?
                    <input v-model="form.gatla_valid" class="form-check-input ml-2" type="checkbox" value="" aria-label="..." checked />
                  </li>
                  <li class=" list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    <input  v-model="form.gatla_date" type="date"  aria-label="..." />
                    Date
                  </li>
                </ul>
                
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
          <div class="col-12 col-md-6 col-xl-4 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 pb-0 card-header" >
                <h6 class="mb-0" >CIPC Documents</h6>
              </div>
              <div class="p-3 card-body">
                
                <ul class="list-group">
                   <li class=" list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    Notice of Incorporation
                    <input @input="form.cipc_notice_of_incorporation = $event.target.files[0]" multiple type="file" aria-label="..." />                    
                  </li>
                  <li class=" list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    Memorandum of Association
                    <input class="form-check-input ml-2" type="file" aria-label="..."  />
                  </li>
                  <li class=" list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    Certificate
                    <input  @input="form.cipc_memorandum_of_incorporation = $event.target.files[0]" multiple type="file"  aria-label="..." />
                  </li>
                   <li class=" list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    Transfer Certificate
                    <input @input="form.transfer_certificate = $event.target.files[0]" multiple type="file"  aria-label="..." />
                  </li>
                </ul>
                
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
        </div>
         
      
        </div>
<button type="submit" class="btn btn-info ms-2">Save</button>

        
</form>
        
      </div>
    </div>
  </div>
  </Layout>
</template>
