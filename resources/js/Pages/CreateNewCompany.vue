<script>
import Layout from "../Shared/Layout.vue";
import DefaultProjectCard from "./components/DefaultProjectCard.vue";
import VmdSwitch from "@/components/VmdSwitch.vue";
import setNavPills from "@/assets/js/nav-pills.js";
import setTooltip from "@/assets/js/tooltip.js";

export default {
  name: "profile-overview",
 props: {
    errors: Object,
  },
  data() {
    return {
       form: {
        company_name: '',
        company_type: '',
        reg_number: '',
        vat_number: '',
        fax_number: '',
        email_address_1: '',
        email_address_2: '',
        email_address_3: '',
        telephone_number_1: '',
        telephone_number_2: '',
        website: '',
        business_address: '',
        business_address_postal_code: '',
        postal_address: '',
        postal_address_code: '',
      },
      showMenu: false,
    };
  },
    methods: {
    submit() {
      this.$inertia.post('/submit-company', this.form)
        .then(() => {
          // code
        })
    }
  },
  components: {
    DefaultProjectCard,
    VmdSwitch,
    Layout,
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
      ">
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row gx-4">
       
        <div class="col-auto my-auto">
          <div class="h-100">
            <h5 class="mb-1">New Company</h5>
          </div>
        </div>
        <div
          class="mx-auto mt-3 col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0"
        >
          
        </div>
      </div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
              <form @submit.prevent="submit">
                <ul class="list-group">
                 <li class="px-0 border-0 list-group-item">
                    <vmd-switch
                      id="flexSwitchCheckDefault"
                      class="ps-0 ms-0 col-6"
                      name="flexSwitchCheckDefault"
                      label-class="mb-0 text-body text-truncate w-80"
                      checked
                      >Active Company</vmd-switch
                    >
                  </li>
                 <li class="px-0 border-0 list-group-item">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Company Name *</label>
  <input id="" type="text" class="form-control form-control-default" v-model="form.company_name" placeholder="" isrequired="true">
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
                <button type="submit" class="btn btn-info ms-2" :style="{float: 'right'}">Create</button>
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
