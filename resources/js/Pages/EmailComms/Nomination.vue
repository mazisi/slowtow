<script>
import { Link } from "@inertiajs/inertia-vue3";
import Layout from "../../Shared/Layout.vue";

export default {
  name: "dashboard-default",
  props: {
    with_client_quoted_status: Object,
    with_client_invoiced_status: Object,
    with_client_paid_status: Object,
    with_payment_to_liquor_board_status: Object,
    with_select_nominees_status: Object,
    with_documents_required_status: Object,
    with_nomination_logded_status: Object,
    with_nomination_issued_status: Object,
    with_delivered_status: Object

    // 1= > Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Payment to the Liquor Board
// 5 => Select nominees
// 6 => Documents Required 
// 7 => Nomination Lodged 
// 8 => Nomination issued
// 9 => Nomination Delievered
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
    },
    getNominations(){
      this.$inertia.get('/email-comms/nominations');
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
    <button @click="getLicenceTransfers" class="nav-link btn btn-secondary text-white ml-4" id="Transfers" data-bs-toggle="pill" data-bs-target="#transfers" 
    type="button" role="tab" aria-controls="transfers" aria-selected="false">Transfers</button>
  </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <li class="nav-item" role="presentation">
    <button @click="getNominations" class="nav-link btn btn-secondary text-white active" id="Nominations" data-bs-toggle="pill" data-bs-target="#nominations" 
    type="button" role="tab" aria-controls="nominations" aria-selected="false">Nominations</button>
  </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

  <li class="nav-item" role="presentation">
    <button class="nav-link btn btn-secondary text-white" id="Alterations" data-bs-toggle="pill" data-bs-target="#alterations" 
    type="button" role="tab" aria-controls="alterations" aria-selected="false">Alterations</button>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">


  <div class="tab-pane fade show active" id="renewals" role="tabpanel" aria-labelledby="Renewals">
  <h5 class="text-center"> Nominations</h5>
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
      <tr v-for="status in with_client_quoted_status" :key="status.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ status.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(status.date).getFullYear() }}/{{ this.getRenewalYear(status.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
        <!-- <td class="align-middle text-center text-sm">
          <span v-if="status.status == 'Invoiced'" class="badge bg-dark text-default">{{ status.status }}</span>
        <span v-if="status.status == 'Paid'" class="badge bg-info text-default">{{ status.status }}</span>
        <span v-if="status.status == 'Get Client Docs'" class="badge bg-light text-default">{{ status.status }}</span>
        <span v-if="status.status == 'Awaiting Liquor Board'" class="badge bg-warning text-default">{{ status.status }}</span>
        <span v-if="status.status == 'Issued'" class="badge bg-secondary text-default">{{ status.status }}</span>
        <span v-if="status.status == 'Complete'" class="badge bg-success text-default">{{ status.status }}</span>
        </td> -->
        <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ status.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${status.slug}/nominations`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <Link :href="`/view-nomination/${status.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
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
      <tr v-for="status in with_client_invoiced_status" :key="status.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ status.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(status.date).getFullYear() }}/{{ this.getRenewalYear(status.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ status.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${status.slug}/nominations`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <Link :href="`/view-nomination/${status.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
     
   
    </tbody>
  </table>
</div>

<hr>
<div class="table-responsive p-0">
  <h6 class="text-danger"> Client Paid </h6>
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
      <tr v-for="status in with_client_paid_status" :key="with_nomination_logded.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ status.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(status.date).getFullYear() }}/{{ this.getRenewalYear(status.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ status.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${status.slug}/nominations`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        <Link :href="`/view-nomination/${status.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
      
   
    </tbody>
  </table>
</div>

<hr>
<div class="table-responsive p-0">
  <h6 class="text-danger">Payment To The Liquor Board</h6>
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
       <tr v-for="status in with_payment_to_liquor_board_status" :key="status.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ status.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(status.date).getFullYear() }}/{{ this.getRenewalYear(status.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ status.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${status.slug}/nominations`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
         <Link :href="`/view-nomination/${status.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
   
    </tbody>
  </table>
</div>

<hr>
<div class="table-responsive p-0">
  <h6 class="text-danger">Document Required</h6>
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
       <tr v-for="status in with_documents_required_status" :key="status.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ status.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(status.date).getFullYear() }}/{{ this.getRenewalYear(status.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ status.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${status.slug}/nominations`" class="text-secondary text-center font-weight-bold text-xs"> 
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
  <h6 class="text-danger">Nomination Logded</h6>
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
      <tr v-for="status in with_nomination_logded_status" :key="status.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ status.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(status.date).getFullYear() }}/{{ this.getRenewalYear(status.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ status.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${status.slug}/nominations`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
       <Link :href="`/view-nomination/${status.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
   
    </tbody>
  </table>
</div>

<hr>
<div class="table-responsive p-0">
  <h6 class="text-danger">Nomination Issued</h6>
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
      <tr v-for="status in with_nomination_issued_status" :key="status.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ status.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(status.date).getFullYear() }}/{{ this.getRenewalYear(status.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ status.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${status.slug}/nominations`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
       <Link :href="`/view-nomination/${status.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
   
    </tbody>
  </table>
</div>

<hr>
<div class="table-responsive p-0">
  <h6 class="text-danger">Nomination Delivered</h6>
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
      <tr v-for="status in with_delivered_status" :key="status.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ status.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(status.date).getFullYear() }}/{{ this.getRenewalYear(status.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ status.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${status.slug}/nominations`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
       <Link :href="`/view-nomination/${status.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
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

  <div class="tab-pane fade" id="nominations" role="tabpanel" aria-labelledby="Nominations">
   <div class="text-center">
  <div class="spinner-grow text-success" role="status">
  <span class="visually-hidden">Loading...</span>
</div>
  </div></div>

   <div class="tab-pane fade" id="alterations" role="tabpanel" aria-labelledby="Alterations">
   <div class="text-center">
  <div class="spinner-grow text-success" role="status">
  <span class="visually-hidden">Loading...</span>
</div>
  </div></div>

</div>


</div>


</div>

  </Layout>
</template>
