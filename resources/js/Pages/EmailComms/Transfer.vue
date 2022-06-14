<script>
import { Link } from "@inertiajs/inertia-vue3";
import Layout from "../../Shared/Layout.vue";

export default {
  name: "dashboard-default",
  props: {
    with_transfer_statuses: Object,
    with_paid_statuses: Object,
    with_get_client_docs_statuses: Object,
    with_awaiting_liquor_board_statuses: Object,
    with_issued_statuses: Object,
    with_complete_statuses: Object
  },
  data() {
    return {
      term: '',
      withThrashed: '', 
      active_status: ''  
    }
  },
  components: {
    Layout,
    Link,
},
methods: {
     search(){
        this.$inertia.replace(route('companies',{
          term: this.term,
          withThrashed: this.withThrashed,
          active_status: this.active_status
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
  <div class="container-fluid">
    <div class="page-header min-height-100 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      ">
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
  <ul class="nav nav-pills mb-3 pt-3" id="pills-tab" role="tablist">

  <li class="nav-item" role="presentation">
    <button @click="getLicenceRenewals" class="nav-link btn btn-secondary text-white " id="Renewals" data-bs-toggle="pill" data-bs-target="#renewals" 
    type="button" role="tab" aria-controls="renewals" aria-selected="true">Renewals</button>
  </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  
  <li class="nav-item" role="presentation">
    <button @click="getLicenceTransfers" class="nav-link btn btn-secondary text-white ml-4 active" id="Transfers" data-bs-toggle="pill" data-bs-target="#transfers" 
    type="button" role="tab" aria-controls="transfers" aria-selected="false">Transfers</button>
  </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <li class="nav-item" role="presentation">
    <button class="nav-link btn btn-secondary text-white" id="Nominations" data-bs-toggle="pill" data-bs-target="#nominations" 
    type="button" role="tab" aria-controls="nominations" aria-selected="false">Nominations</button>
  </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

  <li class="nav-item" role="presentation">
    <button class="nav-link btn btn-secondary text-white" id="Alterations" data-bs-toggle="pill" data-bs-target="#alterations" 
    type="button" role="tab" aria-controls="alterations" aria-selected="false">Alterations</button>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">


  <div class="tab-pane fade show active" id="renewals" role="tabpanel" aria-labelledby="Renewals">
  <h5 class="text-center">Transfers</h5>
  <div class="table-responsive p-0">
  <h6 class="text-danger">Invoiced</h6>
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
      <tr v-for="with_transfer_status in with_transfer_statuses" :key="with_transfer_status.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_transfer_status.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_transfer_status.date).getFullYear() }}/{{ this.getRenewalYear(with_transfer_status.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
        <!-- <td class="align-middle text-center text-sm">
          <span v-if="with_transfer_status.status == 'Invoiced'" class="badge bg-dark text-default">{{ with_transfer_status.status }}</span>
        <span v-if="with_transfer_status.status == 'Paid'" class="badge bg-info text-default">{{ with_transfer_status.status }}</span>
        <span v-if="with_transfer_status.status == 'Get Client Docs'" class="badge bg-light text-default">{{ with_transfer_status.status }}</span>
        <span v-if="with_transfer_status.status == 'Awaiting Liquor Board'" class="badge bg-warning text-default">{{ with_transfer_status.status }}</span>
        <span v-if="with_transfer_status.status == 'Issued'" class="badge bg-secondary text-default">{{ with_transfer_status.status }}</span>
        <span v-if="with_transfer_status.status == 'Complete'" class="badge bg-success text-default">{{ with_transfer_status.status }}</span>
        </td> -->
        <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_transfer_status.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_transfer_status.slug}/renewals`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <a href="javascript:;" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </a>
        </td>
      </tr>
      
   
    </tbody>
  </table>
</div>
<hr>



<div class="table-responsive p-0">
  <h6 class="text-danger">Paid</h6>
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
      <tr v-for="with_paid_status in with_paid_statuses" :key="with_paid_status.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_paid_status.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_paid_status.date).getFullYear() }}/{{ this.getRenewalYear(with_paid_status.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_paid_status.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_paid_status.slug}/renewals`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <a href="javascript:;" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </a>
        </td>
      </tr>
     
   
    </tbody>
  </table>
</div>

<hr>
<div class="table-responsive p-0">
  <h6 class="text-danger">Awaiting Client Docs </h6>
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
    <!-- <h6 v-if="$props.with_get_client_docs_statuses.length <= 0" class="text-center">No licences awaiting client docs</h6> -->
      <tr v-for="with_get_client_docs_status in with_get_client_docs_statuses" :key="with_get_client_docs_status.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_get_client_docs_status.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_get_client_docs_status.date).getFullYear() }}/{{ this.getRenewalYear(with_get_client_docs_status.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_get_client_docs_status.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_get_client_docs_status.slug}/renewals`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <a href="javascript:;" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </a>
        </td>
      </tr>
      
   
    </tbody>
  </table>
</div>

<hr>
<div class="table-responsive p-0">
  <h6 class="text-danger">Awaiting Liquor Board</h6>
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
       <tr v-for="with_awaiting_liquor_board_status in with_awaiting_liquor_board_statuses" :key="with_awaiting_liquor_board_status.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_awaiting_liquor_board_status.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_awaiting_liquor_board_status.date).getFullYear() }}/{{ this.getRenewalYear(with_awaiting_liquor_board_status.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_awaiting_liquor_board_status.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_awaiting_liquor_board_status.slug}/renewals`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <a href="javascript:;" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </a>
        </td>
      </tr>
   
    </tbody>
  </table>
</div>

<hr>
<div class="table-responsive p-0">
  <h6 class="text-danger">Issued</h6>
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
       <tr v-for="with_issued_status in with_issued_statuses" :key="with_issued_status.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_issued_status.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_issued_status.date).getFullYear() }}/{{ this.getRenewalYear(with_issued_status.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_issued_status.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_issued_status.slug}/renewals`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <a href="javascript:;" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </a>
        </td>
      </tr>
   
    </tbody>
  </table>
</div>

<hr>
<div class="table-responsive p-0">
  <h6 class="text-danger">Complete</h6>
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
      <tr v-for="with_complete_status in with_complete_statuses" :key="with_complete_status.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_complete_status.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_complete_status.date).getFullYear() }}/{{ this.getRenewalYear(with_complete_status.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_complete_status.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_complete_status.slug}/renewals`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <a href="javascript:;" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </a>
        </td>
      </tr>
   
    </tbody>
  </table>
</div>


  </div>

  <div class="tab-pane fade" id="transfers" role="tabpanel" aria-labelledby="Transfers">
  <h5>Transfers</h5>
  This is some placeholder content the Contact tab's associated content. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling. 
  You can use it with tabs, pills, and any other .nav-powered navigation.
  </div>

  <div class="tab-pane fade" id="nominations" role="tabpanel" aria-labelledby="Nominations">
   <h5>Nominations</h5>
  This is some placeholder content the Contact tab's associated content. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility
   and styling. You can use it with tabs, pills, and any other .nav-powered navigation.</div>

   <div class="tab-pane fade" id="alterations" role="tabpanel" aria-labelledby="Alterations">
   <h5>Alterations</h5>
  Alterations is some placeholder content the Contact tab's associated content. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility
   and styling. You can use it with tabs, pills, and any other .nav-powered navigation.</div>

</div>


</div>


</div>

  </Layout>
</template>
