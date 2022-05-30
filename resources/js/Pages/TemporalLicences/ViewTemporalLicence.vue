<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';

export default {
  name: "profile-overview",
 props: {
    errors: Object,
    licence: Object,
    companies: Array,
  },
  data() {
    return {
      showMenu: false,
      form: {
        liquor_licence_number: this.licence.liquor_licence_number,
        premises_description: this.licence.premises_description,
        start_date: this.licence.start_date,
        start_time: this.licence.start_time,
        end_date: this.licence.end_date,
        end_time: this.licence.end_time,
        province: this.licence.province,
        municipality: this.licence.municipality,
        extent_of_financial_honest: this.licence.extent_of_financial_honest,
        company: this.licence.company_id
    },
    options: this.companies,
    };
  },
    methods: {
      submit() {
          this.$inertia.post(`/update-temp-licence/${this.licence.slug}`, this.form)
        },
        deleteLicence(){
          if (confirm('Are you sure you want to delete this temporal licence?')) {
          this.$inertia.post(`/delete-temp-licence/${this.licence.slug}`, this.form)
           }
        }
  },
  components: {
    Layout,
    Multiselect
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

</script>
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
    <h6>Temporal Licence For: {{ licence.company.name }}</h6>
  </div>
  <div class="col-lg-6 col-5 my-auto text-end">
    <button type="button" @click="deleteLicence" class="btn btn-sm btn-danger">Delete</button>
  </div>
</div>

      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="submit">
<div class="row">
                  
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Liquor Licence Number</label>
    <input type="text" required class="form-control form-control-default" v-model="form.liquor_licence_number" >
     </div>
   <div v-if="errors.liquor_licence_number" class="text-danger">{{ errors.liquor_licence_number }}</div>
   </div>
  
 
 <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Start Date</label>
    <input type="date" required class="form-control form-control-default" v-model="form.start_date" >
     </div>
   <div v-if="errors.start_date" class="text-danger">{{ errors.start_date }}</div>
   </div>

   <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Start Time</label>
    <input type="time" required class="form-control form-control-default" v-model="form.start_time" >
    </div>
   <div v-if="errors.start_time" class="text-danger">{{ errors.start_time }}</div>
   </div>

<div class="col-md-4 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">End Date</label>
  <input type="date" class="form-control form-control-default" v-model="form.end_date" >
   </div>
  <div v-if="errors.end_date" class="text-danger">{{ errors.end_date }}</div>
</div>  

<div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">End Time</label>
    <input type="time" required class="form-control form-control-default" v-model="form.end_time" >
    </div>
   <div v-if="errors.end_time" class="text-danger">{{ errors.end_time }}</div>
   </div>

   <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
     <Multiselect
       v-model="form.company"
        placeholder="Search Company..."
        :options="options"
        :searchable="true"
      />
  </div>
  </div>
<div class="col-md-4 columns">
<div class="input-group input-group-outline null is-filled">
  <label class="form-label">Province</label>
  <select v-model="form.province" required class="form-control form-control-default">
    <option value=" ">Select province</option>
    <option value="Eastern Cape">Eastern Cape</option>
    <option value="Free State">Free State</option>
    <option value="Gauteng" selected="">Gauteng</option>
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

<div class="col-md-4 columns">            
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Municipality</label>
  <input type="text" class="form-control form-control-default" v-model="form.municipality">
   </div>
    <div v-if="errors.municipality" class="text-danger">{{ errors.municipality }}</div>
    </div> 
 <div class="col-md-4 columns">                  
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label"> Extent Of Financial Interest By Applicant</label>
  <input type="text" class="form-control form-control-default" v-model="form.extent_of_financial_honest" >
   </div>
  <div v-if="errors.extent_of_financial_honest" class="text-danger">{{ errors.extent_of_financial_honest }}</div>
  </div>

    <div class="col-md-12 columns">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Premises Description</label>
  <input class="form-control form-control-default" v-model="form.premises_description">
  </div>
 <div v-if="errors.premises_description" class="text-danger">{{ errors.premises_description }}</div>
</div>

  
    
  <div><button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}">Save</button></div>
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
  </Layout>
</template>
