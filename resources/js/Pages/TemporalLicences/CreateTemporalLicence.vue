<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import Banner from '../components/Banner.vue';

export default {
  name: "profile-overview",
 props: {
    errors: Object,
    companies: Array,
    people: Array
  },
  data() {
    return {
      showMenu: false,
      form: {
        event_name: '',
        start_date: null,
        end_date: null,
        company: null,
        person: null,
        belongs_to: '',
        latest_lodgment_date: null,
        address: '',
        application_type: ''
    },
    options: this.companies,
    persons: this.people,
    };
  },
  watch: {
    'form.start_date'() {
      var d = new Date(this.form.start_date);
      d.setDate(d.getDate() - 14);
      this.form.latest_lodgment_date = d.toLocaleString().split(' ')[0].replace(/,/g, '')
    }
  },
  methods: {
      submit() {
          this.$inertia.post(`/submit-temp-licence`, this.form)
        }
  },
  components: {
    Layout,
    Multiselect,
    Banner
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

</script>
<style src="@vueform/multiselect/themes/default.css"></style>
<style>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-top: 3px;
  margin-left: 3px;
}
.form-control {
  border-color: #4caf50 !important;
}
</style>

<template>
<Layout>
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row ">
       
        <div class="col-auto my-auto">
          <div class="h-100">
            <h5 class="mb-1">New Temporal Licence</h5>
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
<div class="row">
                  
  <div class="col-md-4 columns d-none">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Liquor Licence Number</label>
    <input type="text" class="form-control form-control-default" v-model="form.liquor_licence_number" >
     </div>
   <div v-if="errors.liquor_licence_number" class="text-danger">{{ errors.liquor_licence_number }}</div>
   </div>
  

   <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Event Name</label>
    <input type="text" class="form-control form-control-default" v-model="form.event_name" >
     </div>
   <div v-if="errors.event_name" class="text-danger">{{ errors.event_name }}</div>
   </div>
 
 <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Event Start Date</label>
    <input type="date" required class="form-control form-control-default" v-model="form.start_date" >
     </div>
   <div v-if="errors.start_date" class="text-danger">{{ errors.start_date }}</div>
   </div>


<div class="col-md-4 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Event Ending Date</label>
  <input type="date" class="form-control form-control-default" v-model="form.end_date" >
   </div>
  <div v-if="errors.end_date" class="text-danger">{{ errors.end_date }}</div>
</div>  

<div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled ">
  <label class="form-label">Latest Lodgment Date</label>
  <input type="text" disabled class="form-control form-control-default" v-model="form.latest_lodgment_date" >
   </div>
   <div v-if="errors.latest_lodgment_date" class="text-danger">{{ errors.latest_lodgment_date }}</div>
 </div>

 
 <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled ">
  
  <select class="form-control form-control-default" v-model="form.address" >
    <option :value="''" disabled selected >Event Address Region</option>
    <option value="Ekurhuleni">Ekurhuleni</option>
      <option value="Johannesburg">Johannesburg</option>
      <option value="Sedibeng">Sedibeng</option>
      <option value="Tshwane">Tshwane</option>
      <option value="West Rand">West Rand</option>
  </select>
   </div>
 <div v-if="errors.address" class="text-danger">{{ errors.address }}</div>
 </div>

 <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled ">
  
  <select class="form-control form-control-default" v-model="form.application_type" >
    <option :value="''" disabled selected >Application Type</option>
    <option value="Off-Consumption">Off-Consumption</option>
    <option value="On-Consumption">On-Consumption</option>
  </select>
   </div>
 <div v-if="errors.application_type" class="text-danger">{{ errors.application_type }}</div>
 </div>

<div class="col-md-4 columns">
<div class="input-group input-group-outline null is-filled">
  <label class="form-label">Company or Individual?</label>
  <select v-model="form.belongs_to" required class="form-control form-control-default">
    <option :value="''" disabled selected>Company or Individual?</option>
    <option value="Company">Company</option>
    <option value="Person">Person</option>
    </select>
</div>
<div v-if="errors.belongs_to" class="text-danger">{{ errors.belongs_to }}</div>
</div>

   <div class="col-md-4 columns" v-if="form.belongs_to =='Company'">
    <div class="input-group input-group-outline null is-filled ">
     <Multiselect
       v-model="form.company"
        placeholder="Search Company..."
        :options="options"
        :searchable="true"
      />
  </div>
  <div v-if="errors.company" class="text-danger">{{ errors.company }}</div>
  </div>

  <div class="col-md-4 columns" v-if="form.belongs_to =='Person'">
    <div class="input-group input-group-outline null is-filled ">
     <Multiselect
       v-model="form.person"
        placeholder="Search Person..."
        :options="persons"
        :searchable="true"
      />
  </div>
  <div v-if="errors.person" class="text-danger">{{ errors.person }}</div>
  </div>


  
    
  <div><button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}">Create</button></div>
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
