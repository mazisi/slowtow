<script>
import { Link } from "@inertiajs/inertia-vue3";
import Layout from "../../Shared/Layout.vue";

export default {
  name: "dashboard-default",
  props: {
    with_nomination_paid_status: Object,
    with_client_invoiced_status: Object,
    with_nomination_logded_status: Object,
    with_certificate_received_status: Object,
    with_complete_status: Object
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
  <h6 class="text-danger">Client Invoiced</h6>
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
        <!-- <td class="align-middle text-center text-sm">
          <span v-if="with_client_invoiced.status == 'Invoiced'" class="badge bg-dark text-default">{{ with_client_invoiced.status }}</span>
        <span v-if="with_client_invoiced.status == 'Paid'" class="badge bg-info text-default">{{ with_client_invoiced.status }}</span>
        <span v-if="with_client_invoiced.status == 'Get Client Docs'" class="badge bg-light text-default">{{ with_client_invoiced.status }}</span>
        <span v-if="with_client_invoiced.status == 'Awaiting Liquor Board'" class="badge bg-warning text-default">{{ with_client_invoiced.status }}</span>
        <span v-if="with_client_invoiced.status == 'Issued'" class="badge bg-secondary text-default">{{ with_client_invoiced.status }}</span>
        <span v-if="with_client_invoiced.status == 'Complete'" class="badge bg-success text-default">{{ with_client_invoiced.status }}</span>
        </td> -->
        <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_client_invoiced.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_client_invoiced.slug}/nominations`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <Link :href="`/view-nomination/${with_client_invoiced.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
      
   
    </tbody>
  </table>
</div>
<hr>



<div class="table-responsive p-0">
  <h6 class="text-danger">Nomination Paid</h6>
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
      <tr v-for="with_nomination_paid in with_nomination_paid_status" :key="with_nomination_paid.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_nomination_paid.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_nomination_paid.date).getFullYear() }}/{{ this.getRenewalYear(with_nomination_paid.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_nomination_paid.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_nomination_paid.slug}/nominations`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <Link :href="`/view-nomination/${with_nomination_paid.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
     
   
    </tbody>
  </table>
</div>

<hr>
<div class="table-responsive p-0">
  <h6 class="text-danger"> Nomination Lodged </h6>
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
      <tr v-for="with_nomination_logded in with_nomination_logded_status" :key="with_nomination_logded.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_nomination_logded.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_nomination_logded.date).getFullYear() }}/{{ this.getRenewalYear(with_nomination_logded.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_nomination_logded.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_nomination_logded.slug}/nominations`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        <Link :href="`/view-nomination/${with_nomination_logded.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
      
   
    </tbody>
  </table>
</div>

<hr>
<div class="table-responsive p-0">
  <h6 class="text-danger">Certificate Received</h6>
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
       <tr v-for="with_certificate_received in with_certificate_received_status" :key="with_certificate_received.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_certificate_received.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_certificate_received.date).getFullYear() }}/{{ this.getRenewalYear(with_certificate_received.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_certificate_received.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_certificate_received.slug}/nominations`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
         <Link :href="`/view-nomination/${with_certificate_received.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
   
    </tbody>
  </table>
</div>

<hr>
<div class="table-responsive p-0">
  <h6 class="text-danger">Certificate Received</h6>
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
       <tr v-for="with_certificate_received in with_certificate_received_status" :key="with_certificate_received.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_certificate_received.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_certificate_received.date).getFullYear() }}/{{ this.getRenewalYear(with_certificate_received.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_certificate_received.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_certificate_received.slug}/nominations`" class="text-secondary text-center font-weight-bold text-xs"> 
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
  <h6 class="text-danger">Nomination Complete And Delivered</h6>
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
      <tr v-for="with_complete in with_complete_status" :key="with_complete.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">{{ with_complete.licence.trading_name }}</h6>
              <p class="text-xs mb-0"> 
              Renewal For: {{ new Date(with_complete.date).getFullYear() }}/{{ this.getRenewalYear(with_complete.date)  }} </p>
            </div>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">????</p>
        </td>
                <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ with_complete.date }}</span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/send-mail/${with_complete.slug}/nominations`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
       <Link :href="`/view-nomination/${with_complete.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
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
