<style>
  
  .table thead th {
    padding: 0;
    }
    .upload-btn{
      float: right!important;
    }

</style>
<template>
<Layout>
  <div class="container-fluid">
<div class="page-header min-height-100 border-radius-xl mt-4"
      style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      ">
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
   
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="col-12">
      <div class="row">
      <div class="col-2"></div>
      <div v-if="success" class="col-6">
      <div class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
      <span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
      <span class="text-sm">{{ success }}</span></span><button type="button" class="btn-close d-flex justify-content-center align-items-center" data-bs-dismiss="alert" aria-label="Close">
      <span aria-hidden="true" class="text-lg font-weight-bold">×</span>
      </button>
      </div>
      </div>

      <div v-else-if="error" class="col-6">
      <div class="alert text-white alert-danger alert-dismissible fade show font-weight-light" role="alert">
      <span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
      <span class="text-sm">{{ error }}</span></span><button type="button" class="btn-close d-flex justify-content-center align-items-center" data-bs-dismiss="alert" aria-label="Close">
      <span aria-hidden="true" class="text-lg font-weight-bold">×</span>
      </button>
      </div>
      </div>
<div v-else-if="!error" class="col-6"></div>
      <div class="col-2"></div>
      <div class="col-2">
  <h6>Reporting</h6>
</div>
      </div>

        <div class="">
          <div class=" px-0 pb-2">
          <div class="table-responsive p-0">
<table class="table align-items-center mb-0">
  <thead>
    <tr>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">SELECT A REPORT</th>
      <th class="ps-2 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">REPORT CRITERIA</th>
      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
        <div class="d-flex px-2 py-1">
          <div class="d-flex flex-column justify-content-center">
            <h6 class="mb-0 text-sm">LICENCE RENEWALS PER MONTH</h6>
          </div>
        </div>
      </td>
      <td class="text-center text-sm">
      <div class="input-group input-group-outline null is-filled">
      <label class="r-2" >Select Month  </label>
  <select class="ml-2">
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
      </td>
      <td class="d-flex justify-content-end">
       <button class="btn btn-sm btn-secondary"> Run</button>
      </td>
    </tr>
    <tr>
      <td>
        <div class="d-flex px-2 py-1">
          <div class="d-flex flex-column justify-content-center">
            <h6 class="mb-0 text-sm">UNPAID RENEWALS EXPIRING IN X DAYS</h6>
          </div>
        </div>
      </td>
      <td class="text-center text-sm">
       <div class="input-group input-group-outline null is-filled">
      <label class="r-2" >Days</label>
      <input type="number">
      </div></td>
      <td class="d-flex justify-content-end">
        <button class="btn btn-sm btn-secondary"> Run</button>
      </td>
    </tr>
    <tr>
      <td>
        <div class="d-flex px-2 py-1">
          <div class="d-flex flex-column justify-content-center">
            <h6 class="mb-0 text-sm">UNPAID RENEWALS ALREADY EXPIRED </h6>
          </div>
        </div>
      </td>
      <td class="text-center text-sm"></td>
      <td class="d-flex justify-content-end">
        <button class="btn btn-sm btn-secondary"> Run</button>
      </td>
    </tr>
    <tr>
      <td>
        <div class="d-flex px-2 py-1">
          <div class="d-flex flex-column justify-content-center">
            <h6 class="mb-0 text-sm">ALL PENDING LICENCES</h6>
          </div>
        </div>
      </td>
      
      <td class="text-center text-sm"></td>
      <td class="d-flex justify-content-end">
       <button class="btn btn-sm btn-secondary"> Run</button>
      </td>
    </tr>
    <tr>
      <td>
        <div class="d-flex px-2 py-1">
          <div class="d-flex flex-column justify-content-center">
            <h6 class="mb-0 text-sm">ALL OUSTANDING PROCESSES</h6>
          </div>
        </div>
      </td>
   
      <td class="text-center text-sm"></td>
      <td class="d-flex justify-content-end">
        <button class="btn btn-sm btn-secondary"> Run</button>
      </td>
    </tr>
    <tr>
      <td>
        <div class="d-flex px-2 py-1">
          <div class="d-flex flex-column justify-content-center">
            <h6 class="mb-0 text-sm">COMPANY MEMBERS WITH INVALID DOCUMENTS</h6>
          </div>
        </div>
      </td>
     
      <td class="text-center text-sm"></td>
      <td class="d-flex justify-content-end">
        <button class="btn btn-sm btn-secondary"> Run</button>
      </td>
    </tr>
    <tr>
      <td>
        <div class="d-flex px-2 py-1">
          <div class="d-flex flex-column justify-content-center">
            <h6 class="mb-0 text-sm">NOMINATION REPORTS</h6>
          </div>
        </div>
      </td>
      <td class="text-center text-sm">
      <div class="row">
      <div class="col-6">
      <div class="input-group input-group-outline null is-filled">
      <label class="r-2" >Select Province  	</label>
       <select >
      <option value=""></option>
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
      <div class="col-6">
      <div class="input-group input-group-outline null is-filled">
      <label class="r-2" >Select Type 	</label>
      <select name="type">
      <option value="">All....</option>
        <option value="Client Invoiced">Client Invoiced</option>
        <option value="Paid">Paid</option>
        <option value="Nomination Lodged">Nomination Lodged</option>
        <option value="Signed">Signed</option>
        <option value="Certificate Received">Certificate Received</option>
        <option value="Complete">Complete</option>  
      </select>
      </div></div>
      </div>
      
      </td>
      <td class="d-flex justify-content-end">
        <button class="btn btn-sm btn-secondary"> Run</button>
      </td>
    </tr>
    <tr>
      <td>
        <div class="d-flex px-2 py-1">
          <div class="d-flex flex-column justify-content-center">
            <h6 class="mb-0 text-sm">TRANFERS REPORTS</h6>
          </div>
        </div>
      </td>
     
      <td class="text-center text-sm"></td>
      <td class="d-flex justify-content-end">
       <button class="btn btn-sm btn-secondary"> Run</button>
      </td>
    </tr>
  </tbody>
</table>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  </Layout>
</template>
<script>
import Layout from "../Shared/Layout.vue";
import { useForm ,Link } from '@inertiajs/inertia-vue3';
export default {
  props: {
    contacts: Object,
    success: String,
  },
 components: {
    Layout,
    Link,
    useForm
},
}
</script>