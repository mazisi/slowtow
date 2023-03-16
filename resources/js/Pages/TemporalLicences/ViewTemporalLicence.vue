<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import Banner from '../components/Banner.vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

export default {
 name: "ViewTemporalLicence",
 props: {
    errors: Object,
    success: String,
    licence: Object,
    people: Array
  },
  setup(props) {
   
      let showMenu = ref(false);
      let options = props.companies;
      let persons = props.people;
      const form = useForm({
          slug: props.licence.slug,
          event_name: props.licence.event_name,
          start_date: props.licence.start_date,
          end_date: props.licence.end_date,
          address: props.licence.address,
          application_type: props.licence.application_type,
          liquor_licence_number: props.licence.liquor_licence_number,
          reg_number: props.licence.company ? props.licence.company.reg_number: '',
          id_or_passport: props.licence.people ? props.licence.people.id_or_passport: '',
          belongs_to: props.licence.belongs_to,
          company_name: props.licence.company ? props.licence.company.name : '',
          person: props.licence.people ? props.licence.people.full_name : '',
          latest_lodgment_date: props.licence.latest_lodgment_date
      })
    
  
    function computeLogdementDate(){
      var d = new Date(props.licence.start_date);
      d.setDate(d.getDate() - 14);
      return form.latest_lodgment_date = d.toLocaleString().split(' ')[0].replace(/,/g, '')
    }

 
    function update() {
         form.patch(`/update-temp-licence`, {
          onSuccess: () => { 
           notify(props.success)
         },
          })
        }

        function deleteTempLicence(){
          if(confirm('Are you sure you want to delete this temporary licence??')){
            Inertia.delete(`/delete-temporal-licence/${this.licence.slug}`)
          }      
        }

        const notify = (message) => {
          toast(message, {
            autoClose: 2000,
           });
        }

    return {
      showMenu,
      options,
      persons,
      form,notify,
      computeLogdementDate,
      update,
      deleteTempLicence
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
<style scoped>
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
            <h5 class="mb-1">View Temporary Licence</h5>
          </div>
        </div>
        <div class="mx-auto mt-3 col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0">
          <button v-if="$page.props.auth.has_slowtow_admin_role" 
           @click="deleteTempLicence" type="button" class="btn btn-sm btn-danger float-lg-end pe-4"> Delete</button>
        </div>
      </div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="update">
<div class="row">
  
  

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
    <input type="date" required class="form-control form-control-default" v-model="form.start_date" @input="computeLogdementDate">
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
  <label class="form-label">Licence Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.liquor_licence_number" >
   </div>
 <div v-if="errors.liquor_licence_number" class="text-danger">{{ errors.liquor_licence_number }}</div>
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


  <div v-if="form.belongs_to =='Company'" class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Company Name</label>
    <input type="text" disabled class="form-control form-control-default" v-model="form.company_name" >
     </div>
   <div v-if="errors.company_name" class="text-danger">{{ errors.company_name }}</div>
   </div>

   <div v-if="form.belongs_to =='Person'" class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Person Name</label>
    <input type="text" disabled class="form-control form-control-default" v-model="form.person" >
     </div>
   <div v-if="errors.person" class="text-danger">{{ errors.person }}</div>
   </div>

   <div class="col-md-4 columns" v-if="form.belongs_to === 'Company'">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Registration Number</label>
    <input type="text" disabled class="form-control form-control-default" v-model="form.reg_number">
     </div>
   <div v-if="errors.reg_number" class="text-danger">{{ errors.reg_number }}</div>
   </div>
  
   <div class="col-md-4 columns" v-else>
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">ID Number</label>
    <input type="text" disabled class="form-control form-control-default" v-model="form.id_or_passport">
     </div>
   <div v-if="errors.id_or_passport" class="text-danger">{{ errors.id_or_passport }}</div>
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
