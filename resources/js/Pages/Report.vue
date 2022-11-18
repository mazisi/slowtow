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
             <div class="col-10">
                    <h6>Reports</h6>              
                  </div>
                  <div class="col-2 my-auto text-end">
                    <div class="dropdown float-lg-end pe-4">
                    
                    </div>
                  </div>

           </div>
        </div>

        <div class="row">
          <div class="col-4">
            <button @click="getType('Renewals')"  type="button" class="btn btn-success w-45">Renewals</button>
          </div>
          <div class="col-4">
            <button @click="getType('Transfers')" type="button" class="btn btn-success">Transfers</button>
          </div>
          <div class="col-4">
            <button @click="getType('Nominations')" type="button" class="btn btn-success">Nominations</button>
          </div>

          <div class="col-4">
            <button @click="getType('New-Applications')" type="button" class="btn btn-success w-45">New Applications</button>
          </div>
          <div class="col-4">
            <button @click="getType('Alterations')" type="button" class="btn btn-success">Alterations</button>
          </div>
          <div class="col-4">
            <button @click="getType('Temporal Licence')" type="button" class="btn btn-success w-45">Temporary Applications</button>
          </div>
        </div>
<hr/>

<!-- ################################################ -->

<div class="row" v-if="form.variation">
  <div class="col-4 columns">
    <button type="button" class="btn btn-success w-45">Filter By:</button>
  </div>
 
  <div class="col-8 columns">
    <h5 class="text-center">{{ form.variation }}</h5>
    <Multiselect
            v-model="form.month"           
               :options="months"
               mode="tags"
               :taggable="true"
               placeholder="Month"
             />
  </div>
  <div class="col-4 columns">
    <div class="input-group input-group-outline null is-filled" >
      <select v-model="form.activeStatus" @change="fetchNewAppWithStages" class="form-control form-control-default">
          <option :value="''" disabled selected>Active/Inactive</option>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
       </select>
    </div>
  </div>
  <div v-if="form.variation !== 'Temporal Licence'" class="col-8 columns" >
      <div class="input-group input-group-outline null is-filled" >
        <Multiselect 
          v-model="form.province"          
          :options="provinces"
           mode="tags"
          :taggable="true"
          @select="fetchNewAppWithStages"
          placeholder="Province"/>
      </div>
    </div>
    <div v-if="form.variation !== 'Temporal Licence'" class="col-4">
      <span v-if="form.selectedDates" v-for='(selectedDate, index) in form.selectedDates' :key="selectedDate" class="badge bg-success mx-2">
      {{ selectedDate }} <i @click="removeDate(index)" class="fa fa-times cursor-pointer "></i></span><br>
    </div>

      <div class="col-8 columns" >
        <Datepicker v-model="form.year" yearPicker @update:modelValue="handleDate" />
      </div>
  
  <div v-if="form.variation !== 'Temporal Licence'" class="col-4"></div>
  <div v-if="form.variation !== 'Temporal Licence'" class="col-8">
    <div class="input-group input-group-outline null is-filled">
      <Multiselect
      v-model="form.boardRegion"           
      :options="boardRegion"
       mode="tags"
      :taggable="true"
      @select="fetchNewAppWithStages"
      placeholder="Liquor Board Region"/>
      </div>
  </div>

  <div v-if="form.variation !== 'Temporal Licence'" class="col-4"></div>
  <div v-if="form.variation !== 'Temporal Licence'" class="col-8  mt-3">
    <div class="input-group input-group-outline null is-filled">
      <Multiselect
      v-model="form.licence_types"           
      :options="licenceTypes"
       mode="tags"
      :taggable="true"
      @select="fetchNewAppWithStages"
      placeholder="Licence Type"/>
      </div>
  </div>

  <div class="col-4"></div>
  <div class="col-8 mt-3">
    <div class="input-group input-group-outline null is-filled">
      <select v-model="form.applicant" class="form-control form-control-default">
      <option :value="''" disabled selected>Applicant</option>
      <option value="Company">Company</option>
      <option value="Person">Person</option>
      </select>
      </div>
  </div>

  <div class="col-4"></div>
  <div v-if="form.variation === 'New-Applications'" class="col-8 mt-3">
    <Multiselect
        v-model="form.new_app_stages"           
        :options="new_app_stages"
          mode="tags"
        :taggable="true"
        @select="fetchNewAppWithStages"
        placeholder="Filter By Stage"/>
  </div>

  <div class="float-end mt-4">
    <button @click="exportReport" type="button" class="btn btn-success float-end">Export</button>
  </div>

  
</div>

<div v-if="form.variation === 'New-Applications'" class="table-responsive p-0">
  <table class="table align-items-center mb-0">
        <thead>
          <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Trading Name </th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"> Licence Type </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Licence Number </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Liquor Board Region </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Client Quoted</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Deposit Paid</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Client Invoiced </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Prepare New Application</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Payment to the Liquor Board</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Scanned Application</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Application Lodged</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Initial Inspection</th>
            <!-- <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Liquor Board Requests</th> -->
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Final Inspection</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Client Paid</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Activation Fee Paid</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Licence Issued</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Licence Delivered</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="new_application in new_applications" :key="new_application.id">
            <td class="align-left text-center text-sm">
                  <p class="align-left text-xs font-weight-bold mb-0">{{ new_application.trading_name }}</p>
            </td>
            <td>
              <p class="align-left text-xs font-weight-bold mb-0">{{ new_application.licence_type.licence_type }}</p>
            </td>
            <td class="align-left text-center text-sm">
              <p class="text-xs font-weight-bold mb-0">{{ new_application.licence_number }}</p>
            </td>
            <td class="align-left align-left text-center">
              <p class="align-left text-xs font-weight-bold mb-0">{{ new_application.board_region }}</p>
            </td>
            <td class="text-center">
              <p class="align-center text-xs font-weight-bold mb-0">
                <input class="align-center" type="checkbox" :checked="new_application.status >= '1'">
              </p>
             </td>
             <td class="text-center">
              <p class="align-center text-xs font-weight-bold mb-0">
                {{ new_application.deposit_paid_at }}
              </p>
             </td>
    
             <td class="text-center">
              <p class="align-center text-xs font-weight-bold mb-0">
                <span v-if="new_application.status >= '3'">{{ new_application.is_client_invoiced }}</span>
              </p>
             </td>
    
             
             <td class="text-center">
              <p class="align-center text-xs font-weight-bold mb-0">
                <span v-if="new_application.status >= '4'">Application preparation</span>
              </p>
             </td>
    
             <td class="text-center">
              <p v-if="new_application.status >= 5" class="align-center text-xs font-weight-bold mb-0">
                {{ new_application.liquor_board_at }}
              </p>
             </td>
    
             <td class="text-center">
              <p v-if="new_application.status >= 6" class="align-center text-xs font-weight-bold mb-0">
                Application ready for lodgement
              </p>
             </td>
    
             <td class="text-center">
              <p v-if="new_application.status >= 7" class="align-center text-xs font-weight-bold mb-0">
                  {{ new_application.application_lodged_at }}
              </p>
              <p v-if="is_application_logded_doc_uploaded" class="text-xs text-secondary mb-0">Doc Uploaded: <input type="checkbox" 
                :checked="new_application.is_application_logded_doc_uploaded !== null"></p>
             </td>
    
             <td class="text-center">
              {{ new_application.initial_inspection_at }}
             </td>
    
             <!-- <td class="text-center">

              <p v-for="liquor_board_request in new_application.liquor_board_requests ">{{ liquor_board_request.body }}</p>
             </td> -->
    
             <td class="text-center">
              <span v-if="new_application.status >= 10">{{ new_application.final_inspection_at }}</span>
             </td>
    
             <td class="text-center">
              <span v-if="new_application.status >= 11">{{ new_application.activation_fee_requested_at }}</span><br/>
             </td>
    
             <td class="text-center">
              <span v-if="new_application.status >= 12 
                 && new_application.is_finalisation_doc_uploaded !== null">
                 {{ new_application.is_finalisation_doc_uploaded }}
                </span>
             </td>
    
             <td class="text-center">
              <span v-if="new_application.status >= 13">{{ new_application.client_paid_at }}</span>
             </td>
    
             <td class="text-center">
              <span v-if="new_application.status >= 14">{{ new_application.activation_fee_paid_at }}</span>
             </td>
    
             <td class="text-center">
              <span v-if="new_application.status >= 15">{{ new_application.licence_issued_at }}</span>
             </td>
    
             <td class="text-center">
              <span v-if="new_application.status >= 16">{{ new_application.licence_delivered_at }}</span>
             </td>
          </tr>
         
        </tbody>
      </table>
    </div>
</div>
</div>





</Layout>
</template>
<style scoped>
.columns{
  margin-bottom: 1rem;
}
</style>
<script>
import Layout from "../Shared/Layout.vue";
import { useForm, Link } from '@inertiajs/inertia-vue3';
import Multiselect from '@vueform/multiselect';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import {ref} from 'vue'

export default {
  props: {
    licenceTypes: Object,
    companies: Object,
    new_applications: Object,
    people: Object,
    emails: Object,
    success: String,
    error: String,
    errors: Object,
  },

  setup(props) {
  let isActive = false;
  const months = {
    "1": "January",
    "2" : "February",
    "3" : "March",
    "4":   "April",
    "5": "May",
    "6": "June",
    "7": "July",
    "8": "August",
    "9": "September",
    "10": "October",
    "11": "November",
    "12": "December",
}

const new_app_stages = {
    "1": "Client Quoted",
    "2" : "Deposit Paid",
    "3" : "Client Invoiced",
    "4":  "Prepare New Application",
    "5": "Payment to the Liquor Board",
    "6": "Scanned Application",
    "7": "Application Lodged",
    "8": "Initial Inspection",
    "9": "Liquor Board Requests",
    "10": "Final Inspection",
    "11": "Activation Fee Requested",
    "12": "Client Finalisation Invoice",
    "13": "Client Paid",
    "14": "Activation Fee Paid",
}

    const provinces = ['Eastern Cape','Free State','Gauteng','KwaZulu-Natal','Limpopo','Mpumalanga','Northern Cape','North West','Western Cape'];
    const boardRegion = ['Eastern Cape','Free State','Gauteng','KwaZulu-Natal','Limpopo','Mpumalanga','Northern Cape','North West','Western Cape'];
    let licenceTypes = props.licenceTypes;
    let companies = props.companies;
    let people = props.people;

    const date = ref();
    
    const form = useForm({
      variation: null,
      activeStatus: '',
      month: [],
      year: '',
      applicant: '',
      person: [],
      company: [],
      province: [],
      selectedDates:[],
      boardRegion: [],
      licence_types: [],
      new_app_stages: []
    })
    
  function getType(type){
     form.variation=type;
     isActive = true,
     form.reset('activeStatus','month','year','applicant','person','company',
     'province','month','selectedDates','boardRegion','licence_types','new_app_stages')
   }

   const handleDate = (modelData) => {
          date.value = modelData;
       form.selectedDates.push(date.value)
   }

   const removeDate = (index) => {
       form.selectedDates.splice(index, 1)
   }

   const exportReport = () => {
    form.post(`/export-report`, {
           preserveScroll: true,
           onSuccess: () => {
            //
           },
          })  
       
   }

   const fetchNewAppWithStages = () => {
    if(form.variation === 'New-App'){
      form.get(`/reports`, {
            preserveScroll: true,
            onSuccess: () => {
              //
            },
            replace: true,
            preserveState: true
            })
    } 
       
   }

return{
  getType,
  form,
  months,
  provinces,
  boardRegion,
  licenceTypes,
  handleDate,
  removeDate,
  exportReport,
  people,
  companies,
  new_app_stages,
  fetchNewAppWithStages,
  isActive
}
},
 components: {
    Layout,
    Link,
    Multiselect,
    Datepicker
},


}
</script>
<style src="@vueform/multiselect/themes/default.css"></style>