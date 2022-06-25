<script>
import { Link } from "@inertiajs/inertia-vue3";
import Layout from "../../Shared/Layout.vue";

export default {
  name: "dashboard-default",
  props: {
    with_renewal_received_status: Object,
    with_client_invoiced_status: Object,
    with_client_paid_status: Object,
    with_renewal_processed_status: Object,
    with_issued_statuses: Object,
    with_renewal_complete_status: Object,
    success: String,
    error: String
  },
  data() {
    return {
      month: null,
      province: null,
    }
  },
  components: {
    Layout,
    Link
},
methods: {
     filter(){
        this.$inertia.replace(route('email_comms',{
             month: this.month,
             province: this.province,
          }))
     },

    getRenewalYear(date){
      let computed_date = new Date(date).getFullYear();
      return computed_date + 1;    
    },

    //On navigation click get renewal data
    getLicenceRenewals(){
      this.$inertia.get('/email-comms');
    },

    //On navigation click get transfer data
    getLicenceTransfers(){
      this.$inertia.get('/email-comms/transfers');
    },
    getNominations(){
      this.$inertia.get('/email-comms/nominations');
    },
    // getAlterations(){
    //   this.$inertia.get('/email-comms/nominations');
    // }

      
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
  <div class="container-fluid">
    <div class="page-header min-height-100 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      ">
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
  <ul class="nav nav-pills mb-3 pt-3" id="pills-tab" role="tablist">

  <li class="nav-item" role="presentation">
    <button @click="getLicenceRenewals" class="nav-link btn btn-secondary btn-outline-success text-white active" id="Renewals" 
    data-bs-toggle="pill" data-bs-target="#renewals" 
    type="button" role="tab" aria-controls="renewals" aria-selected="true">Renewals</button>
  </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  
  <li class="nav-item" role="presentation">
    <button @click="getLicenceTransfers" class="nav-link btn btn-secondary text-white ml-4" id="Transfers" data-bs-toggle="pill" data-bs-target="#transfers" 
    type="button" role="tab" aria-controls="transfers" aria-selected="false">Transfers</button>
  </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <li class="nav-item" role="presentation">
    <button @click="getNominations" class="nav-link btn btn-secondary text-white" id="Nominations" data-bs-toggle="pill" data-bs-target="#nominations" 
    type="button" role="tab" aria-controls="nominations" aria-selected="false">Nominations</button>
  </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

  <li class="nav-item" role="presentation">
    <button class="nav-link btn btn-secondary text-white" id="Alterations" data-bs-toggle="pill" data-bs-target="#alterations" 
    type="button" role="tab" aria-controls="alterations" aria-selected="false">Alterations</button>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">


  <div class="tab-pane fade show active" id="renewals" role="tabpanel" aria-labelledby="Renewals">
  <div class="row">
  <div class="col-3 columns">                  
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Filter By Month</label>
<select @change="filter" class="form-control form-control-default" v-model="month">
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

<div class="col-3 columns">                  
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Filter By Province</label>
<select @change="filter" class="form-control form-control-default" v-model="province">
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
  <h6 class="text-danger">Client Quoted</h6>
  <table class="table align-items-center mb-0">
    <thead>
      <tr>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Current Trading Name </th>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"> Comms Status </th>
        <!-- <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Status </th> -->
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Process Date </th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
      </tr>
    </thead>
    <tbody>
    <!-- with_invoiced_status -->
      <tr v-for="with_renewal_received in with_renewal_received_status" :key="with_renewal_received.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_renewal_received.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_renewal_received.date).getFullYear() }}/{{ this.getRenewalYear(with_renewal_received.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
        <!-- <td class="align-middle text-center text-sm">
          <span v-if="with_renewal_received.status == 'Invoiced'" class="badge bg-dark text-default">{{ with_renewal_received.status }}</span>
        <span v-if="with_renewal_received.status == 'Paid'" class="badge bg-info text-default">{{ with_renewal_received.status }}</span>
        <span v-if="with_renewal_received.status == 'Get Client Docs'" class="badge bg-light text-default">{{ with_renewal_received.status }}</span>
        <span v-if="with_renewal_received.status == 'Awaiting Liquor Board'" class="badge bg-warning text-default">{{ with_renewal_received.status }}</span>
        <span v-if="with_renewal_received.status == 'Issued'" class="badge bg-secondary text-default">{{ with_renewal_received.status }}</span>
        <span v-if="with_renewal_received.status == 'Complete'" class="badge bg-success text-default">{{ with_renewal_received.status }}</span>
        </td> -->
        <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_renewal_received.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_renewal_received.slug}/renewals`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <Link :href="`/view-licence-renewal/${with_renewal_received.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
      
   
    </tbody>
  </table>
</div>
<hr>



<div class="table-responsive p-0">
  <h6 class="text-danger">Client Invoiced</h6>
  <table class="table align-items-center mb-0">
    <thead>
      <tr>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Current Trading Name </th>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"> Comms Status </th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Process Date </th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="with_client_invoiced in with_client_invoiced_status" :key="with_client_invoiced.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_client_invoiced.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_client_invoiced.date).getFullYear() }}/{{ this.getRenewalYear(with_client_invoiced.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_client_invoiced.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_client_invoiced.slug}/renewals`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <Link :href="`/view-licence-renewal/${with_client_invoiced.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
     
   
    </tbody>
  </table>
</div>

<hr>
<div class="table-responsive p-0">
  <h6 class="text-danger">Client Paid</h6>
  <table class="table align-items-center mb-0">
    <thead>
      <tr>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Current Trading Name </th>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"> Comms Status </th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Process Date </th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
      </tr>
    </thead>
    <tbody>
    <!-- <h6 v-if="$props.with_client_paides.length <= 0" class="text-center">No licences awaiting client docs</h6> -->
      <tr v-for="with_client_paid in with_client_paid_status" :key="with_client_paid.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_client_paid.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_client_paid.date).getFullYear() }}/{{ this.getRenewalYear(with_client_paid.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_client_paid.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_client_paid.slug}/renewals`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>
         <Link :href="`/view-licence-renewal/${with_client_paid.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
      
   
    </tbody>
  </table>
</div>

<hr>
<div class="table-responsive p-0">
  <h6 class="text-danger">Payment To Liquor Board</h6>
  <table class="table align-items-center mb-0">
    <thead>
      <tr>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Current Trading Name </th>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"> Comms Status </th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Process Date </th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
      </tr>
    </thead>
    <tbody>
       <tr v-for="with_renewal_processed in with_renewal_processed_status" :key="with_renewal_processed.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_renewal_processed.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_renewal_processed.date).getFullYear() }}/{{ this.getRenewalYear(with_renewal_processed.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_renewal_processed.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_renewal_processed.slug}/renewals`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>
         <Link :href="`/view-licence-renewal/${with_renewal_processed.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
   
    </tbody>
  </table>
</div>

<hr>
<div class="table-responsive p-0">
  <h6 class="text-danger">Renewal Complete &amp; Delivered</h6>
  <table class="table align-items-center mb-0">
    <thead>
      <tr>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Current Trading Name </th>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"> Comms Status </th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Process Date </th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="with_renewal_complete in with_renewal_complete_status" :key="with_renewal_complete.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_renewal_complete.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_renewal_complete.date).getFullYear() }}/{{ this.getRenewalYear(with_renewal_complete.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_renewal_complete.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_renewal_complete.slug}/renewals`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <Link :href="`/view-licence-renewal/${with_renewal_complete.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
   
    </tbody>
  </table>
</div>


  </div>

  <div class="tab-pane fade" id="transfers" role="tabpanel" aria-labelledby="Transfers">
  <div class="text-center">
  <div class="spinner-grow text-success" role="status">
  <span class="visually-hidden">Loading...</span>
</div>
  </div>
 
  </div>



</div>


</div>


</div>

  </Layout>
</template>
