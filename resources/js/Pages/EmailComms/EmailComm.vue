<script>
import { Link, Head} from "@inertiajs/inertia-vue3";
import Layout from "../../Shared/Layout.vue";
import Banner from '../components/Banner.vue';
import Paginate from '../../Shared/Paginate.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import useToaster from '../../store/useToaster';
import Navigation from './Navigation.vue';

export default {
  name: "Emmail-Comms",

  props: {
    renewals: Object,
    success: String,
    error: String
  },
  data() {
    const { notifySuccess, notifyError } = useToaster();

    const stages = [
      { name: 'Client Quoted', value: 100 },
      { name: 'Client Invoiced', value: 200 },
      { name: 'Payment To The Liquor Board', value: 400 },
      { name: 'Renewal Issued', value: 500 },
    ];

    return {
      stages,
      month: '',
      province: '',
      stage: '',
      isActive: false,
      notifySuccess,
      notifyError
    }
  },
  components: {
    Layout,
    Link,
    Banner,
    Paginate,
    Navigation,
    Head
},
methods: {
     filter(){
        this.$inertia.replace(route('email_comms',{
             month: this.month,
             province: this.province,
             stage: this.stage,
          }))
     },

     navigateTo(to){
      //switch
      switch (to) {
        case 'Renewals':
          this.getLicenceRenewals();
          break;
         case 'Transfers':
          this.getLicenceTransfers();
          break;
         case 'Nominations':
          this.getNominations();
          break;
         case 'Temporary Licences':
          this.getTemporaryLicences();
          break;
         case 'Alterations':
          this.getAlterations();
          break;
         case 'New Applications':
          this.getNewApps();
          break;

        default:
          break;
      }
     },
    getRenewalYear(date){
      let computed_date = new Date(date).getFullYear();
      return computed_date + 1;    
    },

    

    limit(string='', limit = 25) {
        if(string){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
        },


    },

    getTemporaryLicences(){
      this.$inertia.get('/email-comms/temp-licences');
    },

    getAlterations(){
      this.$inertia.get('/email-comms/alterations');
    },


    mounted(){ 
          if(this.success){
            this.notifySuccess(this.success)
          }else if(this.error){
            this.notifyError(this.error)
          }
        }
};
</script>
<style>
#with-thrashed{
  margin-top: 3px;
  margin-left: 3px;
}
.active{
    color: #000;
    background-color: #fff;
    border-color: #fff;
}
</style>
<template>
<Layout>
  <Head title="EmailComms - Renewals" />
  <div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
    
  <Navigation/>
<div class="tab-content" id="pills-tabContent">


  <div class="tab-pane fade show active" id="renewals" role="tabpanel" aria-labelledby="Renewals">
  <div class="row">
   <div class="col-4 columns">                  
<div class="input-group input-group-outline null is-filled">
<select v-model="stage" @change="filter" class="form-control form-control-default">
<option :value="''" disabled selected>Filter By Stage</option>
<option v-for="stage in stages" :value="stage.value" :key="stage.id">{{ stage.name }}</option>

</select>
</div>

</div>
  <div class="col-4 columns">                  
<div class="input-group input-group-outline null is-filled">
<select @change="filter" class="form-control form-control-default" v-model="month">
<option :value="''" disabled selected>Filter By Month</option>
<option value="1">January</option>
<option value="2">February</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select>
</div>

</div>

<div class="col-4 columns">                  
<div class="input-group input-group-outline null is-filled">
<select @change="filter" class="form-control form-control-default" v-model="province">
<option :value="''" disabled selected>Filter By Province</option>
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
</div>
</div>
  <h5 class="text-center">Renewals</h5>
  <div class="table-responsive p-0">
  <table class="table align-items-center mb-0">
    <thead>
      <tr>
        <th class="pl-5 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Current Trading Name </th>
        <th class="px-0 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Licence Number</th>
        <th class="pl-0 text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
      </tr>
    </thead>
    <tbody>
    <!-- with_invoiced_status -->
      <tr v-if="renewals.data" v-for="renewal in renewals.data" :key="renewal.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <Link :href="`/email-comms/get-mail-template/${renewal.slug}/renewals`">
                <h6 class="mb-0 text-sm">
                  {{ renewal.licence ? limit(renewal.licence.trading_name) : '' }}</h6>
                </Link>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(renewal.date).getFullYear() }}/{{ this.getRenewalYear(renewal.date)  }} </p>
            </div>
          </div>
        </td>
        
        <td class="text-sm">
          <Link :href="`/email-comms/get-mail-template/${renewal.slug}/renewals`" >
           <h6 class="mb-0 text-sm">{{ renewal.licence ? renewal.licence.licence_number : ''}} </h6>
          </Link>
        </td>
        
        <td class="align-middle text-center">
        <Link :href="`/email-comms/get-mail-template/${renewal.slug}/renewals`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>


        <Link :href="`/view-licence-renewal/${renewal.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>

      <tr v-else >
        <td><p class="text-center text-danger">No data found.</p></td>
      </tr>
      
   
    </tbody>
  </table>
</div>
<hr>

  </div>


  <Paginate
  :modelName="renewals"
  :modelType="EmailComms"
  />


</div>


</div>


</div>

  </Layout>
</template>
