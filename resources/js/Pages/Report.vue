<template>
<Layout>
  <div class="container-fluid">
    <Banner/>
      
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
            <button @click="getType('All')" type="button" class="type btn btn-success w-50">All</button>
          </div>

          <div class="col-4">
            <button @click="getType('Alterations')" type="button" class="type btn btn-success w-50">Alterations</button>
          </div>

          <div class="col-4">
            <button @click="getType('Existing-Licences')" type="button" class="type btn btn-success w-50">Existing Licences</button>
          </div>


          <div class="col-4">
            <button @click="getType('New Applications')" type="button" class="type btn btn-success w-50">New Applications</button>
          </div>

          <div class="col-4">
            <button @click="getType('Nominations')" type="button" class="type btn btn-success w-50">Nominations</button>
          </div>
          
          <div class="col-4">
            <button @click="getType('Renewals')" type="button" class="type btn btn-primary btn-success  w-50" 
            >Renewals</button>
          </div>

          <div class="col-4">
            <button @click="getType('Temporary Licence')" type="button" class="type btn btn-success w-50">Temporary Applications</button>
          </div>

          <div class="col-4">
            <button @click="getType('Transfers')" type="button" class="type btn btn-success w-50">Transfers</button>
          </div>
          
        </div>
<hr/>

<!-- ################################################ -->

<div class="row" v-if="form.variation">
  

  <h5 class="text-center">{{ form.variation }}</h5>   
  <div class="col-6 columns">
     
    <Multiselect
            v-model="form.month_from"           
               :options="months"
               :taggable="true"
               placeholder="From"
             />
  </div>

  <div class="col-6 columns">
     
    <Multiselect
            v-model="form.month_to"           
               :options="months"
               :taggable="true"
               :required="true"
               placeholder="To"
             />
  </div>

  <div v-if="form.variation === 'Renewals'" class="col-6 columns" >
    <Multiselect
        v-model="form.renewal_stages"           
        :options="renewal_stages"
          mode="tags"
        :taggable="true"
        placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'Transfers'" class="col-6 columns" >
    <Multiselect
        v-model="form.transfer_stages"           
        :options="transfer_stages"
          mode="tags"
        :taggable="true"
        placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'Nominations'" class="col-6 columns" >
    <Multiselect
        v-model="form.nomination_stages"           
        :options="nomination_stages"
        mode="tags"
        :taggable="true"
        placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'New Applications'" class="col-6 columns" >
    <Multiselect
    v-model="form.new_app_stages"           
    :options="new_app_stages"
     mode="tags"
    :taggable="true"
    placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'Alterations'" class="col-6 columns" >
    <Multiselect
    v-model="form.alteration_stages"           
    :options="alteration_stages"
     mode="tags"
    :taggable="true"
    placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'Temporary Licence'" class="col-6 columns">
    <Multiselect
    v-model="form.temp_licence_stages"           
    :options="temp_licence_stages"
     mode="tags"
    :taggable="true"
    placeholder="Filter By Stage"/>
  </div>

  <div v-if="form.variation === 'Existing-Licences'" class="col-6 columns">
    <Multiselect
    v-model="form.new_app_stages"           
    :options="new_app_stages"
     mode="tags"
    :taggable="true"
    placeholder="Filter By Stage"/>
  </div>


  
  <!-- <div :class="{ 'col-4': form.variation === 'Renewals' 
  || form.variation === 'Transfers' 
  || form.variation === 'Nominations'
  || form.variation === 'Nominations'
  || form.variation === 'New Applications'//New Apps
  || form.variation === 'Alterations'
  || form.variation === 'Temporary Licence'
  || form.variation === 'Existing-Licences'
   }">
  </div> -->
  
 
  <div v-if="form.variation !== 'Temporary Licence'" class="col-6 columns" >
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





    <!-- <div v-if="form.variation !== 'Temporary Licence'" class="col-4">
      <span v-if="form.selectedDates" v-for='(selectedDate, index) in form.selectedDates' :key="selectedDate" class="badge bg-success mx-2">
      {{ selectedDate }} <i @click="removeDate(index)" class="fa fa-times cursor-pointer "></i></span><br>
    </div> -->

      <div class="col-6 columns" >
        <Multiselect
         v-model="form.year" 
         :options="years"
        :searchable="true"
        placeholder="Select Year"
        />
      </div>
  
  
  <div v-if="form.variation !== 'Temporary Licence'" class="col-6">
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

  
  <div v-if="form.variation !== 'Temporary Licence'" class="col-6 ">
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

  <div v-if="form.variation == 'Temporary Licence'" class="col-6 ">
    <div class="input-group input-group-outline null is-filled">
      <Multiselect
      v-model="form.temp_licence_region"           
      :options="temp_licence_regions"
       mode="tags"
      :taggable="true"
      @select="fetchNewAppWithStages"
      placeholder="Region"/>
      </div>
  </div>

  <div class="col-6" :class="{'mt-3' : form.variation == 'All'}">
    <div class="input-group columns input-group-outline null is-filled">
      <select v-model="form.is_license_complete" class="form-control form-control-default">
      <option :value="''" disabled selected>Choose..</option>
      <option value="Outstanding">Outstanding</option>
      <option value="Complete">Complete</option>
      </select>
      </div>
  </div>

  <div class="col-6" :class="{'mt-3' : form.variation == 'All'}">
    <div class="input-group columns input-group-outline null is-filled">
      <select v-model="form.applicant" class="form-control form-control-default">
      <option :value="''" disabled selected>Applicant</option>
      <option value="Company">Company</option>
      <option value="Person">Person</option>
      </select>
      </div>
  </div>



  <div class="float-end mt-4">
    <button @click="exportReport" :disabled="form.processing || !form.variation" 
    type="button" class="btn btn-success float-end">
    <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    Export</button>
  </div>

  
</div>

<div v-if="form.variation === 'New Applications'" class="table-responsive p-0">
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
                  <p class="align-left text-xs font-weight-bold mb-0">{{ limit(new_application.trading_name) }}</p>
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
                <span v-if="new_application.status >= 3">{{ new_application.is_client_invoiced }}</span>
              </p>
             </td>
    
             
             <td class="text-center">
              <p class="align-center text-xs font-weight-bold mb-0">
                <span v-if="new_application.status >= 4">Application preparation</span>
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

  .btnSecondary{
    background-color: #6c757d!important;
  }
  .btnSuccess{
    background-color: #4caf50;
  }
  .btnPrimary{
    background-color: #0d6efd!important;
  }
</style>
<script>
import Layout from "../Shared/Layout.vue";
import { useForm, Link } from '@inertiajs/inertia-vue3';
import Multiselect from '@vueform/multiselect';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import Banner from './components/Banner.vue';
import { ref } from 'vue'

export default {
  props: {
    licenceTypes: Object,
    companies: Object,
    new_applications: Object,
    years: Object,
    people: Object,
    emails: Object,
    success: String,
    error: String,
    errors: Object,
  },

  setup(props) {
  let isActive = ref(false);
  const btnSecondary = ref('btn-secondary');
  const btnSuccess = ref('btn-success');
  const btnPrimary = ref('btn-primary');
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

     "1" : "Client Quoted",
     "2" : "Deposit Invoiced",
     "3": "Deposit Paid",
     "4": "Payment to the Liquor Board",
     "5": "Prepare New Application",
     "6": "Scanned Application",
     "7": "Application Lodged",
     "8": "Initial Inspection",
     "9": "Liquor Board Requests",
     "10": "Final Inspection",
     "11": "Activation Fee Requested",
     "12": "Client Finalisation Invoice",
     "13": "Client Paid",
     "14": "Activation Fee Paid",
     "15": "Licence Issued",
     "16": "Licence Delivered",

}
    let years = props.years;
    const provinces = ['Eastern Cape','Free State','Gauteng','KwaZulu-Natal','Limpopo','Mpumalanga','Northern Cape','North West','Western Cape'];

    const boardRegion = ['Eastern Cape','Free State','Gauteng Ekurhuleni','Gauteng Johannesburg',
                        'Gauteng Sedibeng','Gauteng Tshwane',
                        'KwaZulu-Natal','Limpopo','Mpumalanga',
                        'Gauteng West Rand','Northern Cape','North West','Western Cape'];
                        
    let licenceTypes = props.licenceTypes;
    let companies = props.companies;
    let people = props.people;

    const date = ref('');
   
    const form = useForm({
      variation: null,
      activeStatus: '',
      month_from: '',
      month_to: '',
      year: '',
      applicant: '',
      is_license_complete: '',
      person: [],
      company: [],
      province: [],
      selectedDates:[],
      boardRegion: [],
      licence_types: [],
      new_app_stages: [],
      renewal_stages: [],
      transfer_stages: [],
      nomination_stages: [],
      alteration_stages: [],
      temp_licence_stages: [],
      temp_licence_region: []
    })

    const renewal_stages = {
    "1": "Client Quoted",
    "2" : "Client Invoiced",
    "3" : "Client Paid",
    "4":  "Payment To Liquor Board",
    "5": "Renewal Issued",
    "6": "Renewal Delivered"
    }

    const transfer_stages = {
      "1" : "Client Quoted",
      "2" : "Client Invoiced",
      "3" : "Client Paid",
      "4" : "Prepare Transfer Application",
      "5" : "Payment To The Liquor Board",
      "6" : "Scanned Application",
      "7" : "Application Logded",
      "8" : "Activation Fee Paid",
      "9" : "Transfer Issued",
      "10" : "Transfer Delivered"
    }

    const nomination_stages = {
      "1" : "Client Quoted",
      "2" : "Client Invoiced",
      "3" : "Client Paid",
      "4" : "Payment to the Liquor Board",
      "5" : "Select nominees",
      "6" : "Prepare Nomination Application", 
      "7" : "Scanned Application",
      "8" : "Nomination Lodged", 
      "9" : "Nomination Issued", 
      "10" : "Nomination Delivered" 
    }

    const alteration_stages = {
        "1" : "Client Quoted",
        "2" : "Client Invoiced",
        "3" : "Client Paid", 
        "4" : "Prepare Alterations Application",
        "5" : "Payment to the Liquor Board",
        "6" : "Alterations Lodged",
        "7" : "Alterations Certificate Issued",
        "8" : "Alterations Delivered",

    }
    const temp_licence_regions = {
      "Ekurhuleni": "Ekurhuleni",
      "Johannesburg": "Johannesburg",
      "Sedibeng" : "Sedibeng",
      "Tshwane" : "Tshwane",
      "West Rand" : "West Rand"
  }

    const temp_licence_stages = {
     "1" : "Client Quoted",
     "2" : "Client Invoiced",
     "3" : "Client Paid",
     "4" : "Collate Temporary Licence Documents ",
     "5" : "Payment To The Liquor Board ",
     "6" : "Scanned Application",
     "7" : "Temporary Licence Lodged",  
     "8" : "Temporary Licence Issued", 
     "9" : "Temporary Licence Delivered", 
    }
    function addClass(type){
      if(type){

      }
      let element = document.querySelectorAll('.type');
    
    }


  function getType(type){
     form.variation=type;
     addClass(type);
     this.isActive = true;
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
    let url =
    `/export-report?variation=${form.variation}&month_from=${form.month_from}
    &month_to=${form.month_to}&year=${form.year}&applicant=${form.applicant}
    &person=${form.person}&company=${form.company}&province=${form.province}
    &selectedDates=${form.selectedDates}&boardRegion=${form.boardRegion}
    &licence_types=${form.licence_types}&new_app_stages=${form.new_app_stages}
    &renewal_stages=${form.renewal_stages}&transfer_stages=${form.transfer_stages}
    &nomination_stages=${form.nomination_stages}&alteration_stages=${form.alteration_stages}
    &temp_licence_stages=${form.temp_licence_stages}&temp_licence_region=${form.temp_licence_region}
    &activeStatus=${form.activeStatus}&is_licence_complete=${form.is_license_complete}`
    
    window.open(url,'_blank');
     
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

   function limit(string='', limit = 25) {
        if(string){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
        }
      

return{
  limit,
  renewal_stages,
  transfer_stages,
  nomination_stages,
  alteration_stages,
  temp_licence_stages,
  temp_licence_regions,
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
  isActive,
  btnSecondary,
  btnSuccess,
  btnPrimary,
  years,
}
},
 components: {
    Layout,
    Link,
    Multiselect,
    Datepicker,
    Banner
},


}
</script>
<style src="@vueform/multiselect/themes/default.css"></style>