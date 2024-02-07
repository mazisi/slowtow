<script>
import { Link, Head } from "@inertiajs/inertia-vue3";
import Layout from "../../Shared/Layout.vue";
import Banner from '../components/Banner.vue';
import Paginate from '../../Shared/Paginate.vue';
import useToaster from '../../store/useToaster';
import Navigation from './Navigation.vue';
import  common from '../common-js/common.js';
import useMonths from '../../store/useMonths';

export default {
  name: "email-comms-transfers",
  props: {
    transfers: Object,
    success: String,
    error: String
  },
  data() {
    const { notifySuccess, notifyError } = useToaster();
    const { months } = useMonths();

    const stagesArr = [

      { name: 'Client Quoted With Requirements', value: 100 },
      { name: 'Client Invoiced', value: 200 },
      { name: 'Payment To The Liquor Board', value: 500 },
      { name: 'Transfer Lodged', value: 600 },
      { name: 'Activation Fee Paid', value: 700 },
      { name: 'Transfer Issued', value: 800 },

    ]

    return {
      months,
      stagesArr,
      month: '',
      province: '',
      stage: '',
      notifySuccess,
      notifyError
    }
  },
  components: {
    Layout,
    Link,
    Banner,
    Navigation,
    Head,
    Paginate
},
computed: {
  computedProvinces(){
          return common.getProvinces();
        }
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
        this.$inertia.replace(route('get_licence_transfers',{
              month: this.month,
             province: this.province,
             stage: this.stage,
          }))
        },

    
    },

    

    mounted(){ 
          if(this.success){
            notifySuccess(this.success)
          }else if(this.error){
            notifyError(this.error)
          }
        }
};
</script>
<style>
#with-thrashed{
  margin-top: 3px;
  margin-left: 3px;
}</style>
<template>
<Layout>
  <Head title="EmailComms - Transfers" />
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
<option v-for="stage in stagesArr" :value="stage.value" :key="stage.value">{{ stage.name }}</option>
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
<option v-for="province in computedProvinces" :key="province"  :value=province>{{ province }}</option>
</select>
</div>
</div>
</div>
  <h5 class="text-center">Transfers</h5>
  <div class="table-responsive p-0">
  <table class="table align-items-center mb-0">
    <thead>
      <tr>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Current Trading Name </th>
        <th class="px-0 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Licence Number</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
      </tr>
    </thead>
    <tbody>
    <!-- with_invoiced_status -->
      <tr v-if="transfers.data?.length > 0" v-for="transfer in transfers.data" :key="transfer.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 data-bs-placement="top" :title="transfer.licence.trading_name" class="mb-0 text-sm">{{ limit(transfer.licence.trading_name) }}</h6>
             </div>
          </div>
        </td>
        

        <td class="text-sm">
          <Link :href="`/email-comms/get-mail-template/${transfer.slug}/transfers`">
           <h6 class="mb-0 text-sm">{{ transfer.licence.licence_number ? transfer.licence.licence_number : '' }} </h6>
          </Link>
        </td>

        <td class="align-middle text-center">
        <Link :href="`/email-comms/get-mail-template/${transfer.slug}/transfers`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <Link :href="`/view-transfered-licence/${transfer.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
      <tr v-else>
        <td colspan="6" class="text-center text-danger">No Transfers Found.</td>
    </tr>
   
    </tbody>
  </table>
</div>


  </div>
  <Paginate v-if="transfers.data?.length > 0"
  :modelName="transfers"
  :modelType="Transfers"
  />

</div>


</div>


</div>

  </Layout>
</template>
