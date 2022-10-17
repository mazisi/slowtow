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
                    <h6>Licence Renewals</h6>              
                  </div>
                  <div class="col-2 my-auto text-end">
                    <div class="dropdown float-lg-end pe-4">
                    <a href="/renewal-export" class="btn btn-sm btn-success">Export</a>
                    </div>
                  </div>

           </div>
        </div>

        <div class="row">
          <div class="col-4">
            <button @click="getType('Renewals')" type="button" class="btn btn-success w-45">Renewals</button>
          </div>
          <div class="col-4">
            <button @click="getType('Transfers')" type="button" class="btn btn-success">Transfers</button>
          </div>
          <div class="col-4">
            <button @click="getType('Nominations')" type="button" class="btn btn-success">Nominations</button>
          </div>

          <div class="col-4">
            <button @click="getType('New-App')" type="button" class="btn btn-success w-45">New Applications</button>
          </div>
          <div class="col-4">
            <button @click="getType('Alterations')" type="button" class="btn btn-success">Alterations</button>
          </div>
          <div class="col-4">
            <button @click="getType('Alterations')" type="button" class="btn btn-success w-45">Temporary Applications</button>
          </div>
<hr/>
<div v-if="form.variation" class="row">
  <div class="col-4">
    <button type="button" class="btn btn-success w-45">Filter By:</button>
  </div>
  <div class="col-3">
    <div class="input-group input-group-outline null is-filled">
        <Multiselect
        v-model="form.month"           
           :options="months"
           mode="tags"
           :taggable="true"
           placeholder="Month"
         />
      </div>
  </div>
  
  <div class="col-3">
    
    <div class="input-group input-group-outline null is-filled">
      <Datepicker v-model="form.year" yearPicker @update:modelValue="handleDate" />
      </div>
    </div>
  <div class="col-2">
    <span v-if="form.selectedDates" v-for='(selectedDate, index) in form.selectedDates' :key="selectedDate" class="badge bg-success">
      {{ selectedDate }} <i @click="removeDate(index)" class="fa fa-times cursor-pointer"></i></span><br></div>
  <div class="col-2">
    <div class="input-group input-group-outline null is-filled">
      <select v-model="form.activeStatus" class="form-control form-control-default">
      <option :value="''" disabled selected>Active/Inactive</option>
      <option value="Active">Active</option>
      <option value="Inactive">Inactive</option>
      </select>
      </div>
  </div>
  <div class="col-2"></div>
  <div class="col-3">
    <div class="input-group input-group-outline null is-filled">
      <Multiselect 
      v-model="form.province"          
      :options="provinces"
       mode="tags"
      :taggable="true"
      placeholder="Province"/>
      </div>
  </div>
  <div class="col-3">
    <div class="input-group input-group-outline null is-filled">
      <Multiselect
      v-model="form.boardRegion"           
      :options="boardRegion"
       mode="tags"
      :taggable="true"
      placeholder="Liquor Board Region"/>
      </div>
  </div>
  <div class="col-2"></div>
  <div class="col-2"> </div>
  <div class="col-2"></div>
  <div class="col-3  mt-3">
    <div class="input-group input-group-outline null is-filled">
      <Multiselect
      v-model="form.licence_types"           
      :options="licenceTypes"
       mode="tags"
      :taggable="true"
      placeholder="Licence Type"/>
      </div>
  </div>
  <div class="col-3 mt-3">
    <div class="input-group input-group-outline null is-filled">
      <select v-model="form.applicant" class="form-control form-control-default">
      <option :value="''" disabled selected>Applicant</option>
      <option value="Company">Company</option>
      <option value="Person">Person</option>
      </select>
      </div>
  </div>

  <!-- <div v-if="form.applicant ==='Company'" class="col-3 mt-3">
    <div class="input-group input-group-outline null is-filled">
      <Multiselect
      v-model="form.company"           
      :options="companies"
       mode="tags"
      :taggable="true"
      :searchable="true"
      placeholder="Search Company"/>
      </div>
  </div>

  <div v-if="form.applicant ==='Person'" class="col-3 mt-3">
    <div class="input-group input-group-outline null is-filled">
      <Multiselect
      v-model="form.person"           
      :options="people"
       mode="tags"
      :taggable="true"
      :searchable="true"
      placeholder="Search Company"/>
      </div>
  </div> -->
  <div class="col-9"></div>
  <div class="col-3 mt-4">
    <button @click="exportReport" type="button" class="btn btn-success">Export</button>
  </div>
</div>

</div>
  </div>
          
          
</div>

  </Layout>
</template>
<script>
import Layout from "../Shared/Layout.vue";
import { Link } from '@inertiajs/inertia-vue3';
import { useForm } from '@inertiajs/inertia-vue3';
import Multiselect from '@vueform/multiselect';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import {ref} from 'vue'

export default {
  props: {
    licenceTypes: Object,
    companies: Object,
    people: Object,
    emails: Object,
    success: String,
    error: String,
    errors: Object,
  },

  setup(props) {
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
      licence_types: []
    })
    
  function getType(type){
     form.variation=type;
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
  companies
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