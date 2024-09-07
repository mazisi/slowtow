<script>
import { Link,Head } from "@inertiajs/inertia-vue3";
import Layout from "../../Shared/Layout.vue";
import Banner from '../components/Banner.vue';
import Paginate from '../../Shared/Paginate.vue';
import common from '../common-js/common.js';
import useAlteration from '../Alterations/composables/useAlteration';
import useToaster from '../../store/useToaster';
import Navigation from './Navigation.vue';
import useMonths from '../../store/useMonths'


export default {
  name: "Alterations",
  props: {
    alterations: Object,
    errors: Object,
    error: String,
    success: String
  },
  data() {
    const { getBadgeStatus } = useAlteration();
    const { notifySuccess, notifyError } = useToaster();
    const { months } = useMonths();

    const stages = [
      { name: 'Client Quoted', value: 100 },
      { name: 'Client Invoiced', value: 200 },
      { name: 'Client Paid', value: 300 },
      { name: 'Prepare Alterations Application', value: 400 },
      { name: 'Payment to the Liquor Board', value: 500 },
      { name: 'Alterations Lodged', value: 600 },
      { name: 'Alterations Certificate Issued', value: 700 },
      { name: 'Alterations Delivered', value: 800 },
    ];

    return {
      month: '',
      province: '',
      stage: '',
      getBadgeStatus,
      notifySuccess,
      notifyError ,months,
      stages
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
  limit(string='', limit = 25) {
        if(string){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
     }, 
     
     filter(){
        this.$inertia.replace(route('get_alterations',{
             month: this.month,
             province: this.province,
             stage: this.stage,
          }))
        },

    getRenewalYear(date){
      let computed_date = new Date(date).getFullYear();
      return computed_date + 1;    
    },


    getStatus(status){
      return this.getBadgeStatus(status);

    }
  },
    mounted(){ 
          if(this.success){
            this.notifySuccess(this.success)
          }else if(this.error){
            this.notifyError(this.error)
          }
        },

  computed: {
    computedProvinces() {
      return common.getProvinces();
    },
    
    computedBoardRegions() {
      return common.getBoardRegions();
    }
  },


};
</script>
<style>
#with-thrashed{
  margin-top: 3px;
  margin-left: 3px;
}</style>
<template>
<Layout>
  <Head title="EmailComms - Appointment of managers" />
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
<option v-for="stage in stages" :key="stage.value" :value="stage.value">{{ stage.name }}</option>

</select>
</div>

</div>
  <div class="col-4 columns">     
<div class="input-group input-group-outline null is-filled">
<select v-model="month" @change="filter" class="form-control form-control-default" >
<option :value="''" disabled selected>Filter By Month</option>
<option v-for="month in months" :value="month.id" :key="month.id">{{ month.name }}</option>
</select>
</div>

</div>

<div class="col-4 columns">                  
<div class="input-group input-group-outline null is-filled">
<select @change="filter" class="form-control form-control-default" v-model="province">
<option :value="''" disabled selected>Filter By Province</option>
<option v-for='province in computedProvinces' :key="province" :value=province> {{ province }}</option>
</select>
</div>
</div>
</div>
  <h5 class="text-center"> Alterations</h5>
  <div class="table-responsive p-0">
  <table class="table align-items-center mb-0">
    <thead>
      <tr>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Trading Name </th>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"> Licence Number</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Licence Date </th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Stage </th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email Action</th>
      </tr>
    </thead>
    <tbody>
      <tr v-if="alterations.data?.length > 0" v-for="alteration in alterations.data" :key="alteration.id">
      
        <td>
            <div class="d-flex flex-column justify-content-center">
              <h6 data-bs-placement="top" :title="alteration.licence.trading_name" class="mb-0 text-sm">
                <Link :href="`/view-alteration/${alteration.slug}`">{{ limit( alteration.licence.trading_name) }}</Link></h6>
          </div>
        </td>
        <td>
          
          <p class="text-xs font-weight-bold mb-0">{{ alteration.licence.licence_number ? alteration.licence.licence_number : '' }}</p>
        </td>
        <td class="align-middle font-weight-bold text-center text-sm">
          <span>{{ new Date(alteration.licence.licence_date).toISOString().split('T')[0] }}</span>
        </td>
         <td class="align-middle text-center">
            <div v-html="getStatus(alteration.status)"></div>
          </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/get-mail-template/${alteration.slug}/alterations`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <Link :href="`/view-alteration/${alteration.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
      
   
    </tbody>
  </table>
</div>

  </div>

  <Paginate v-if="alterations.data?.length > 0"
  :modelName="alterations"
  :modelType="Alterations"
  />

</div>


</div>


</div>

  </Layout>
</template>
../Renewals/useAlteration