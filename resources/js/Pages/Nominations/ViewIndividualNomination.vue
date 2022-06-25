<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link } from '@inertiajs/inertia-vue3';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';


export default {
 props: {
   errors: Object,
    nominees: Array,
    licence: Object,
    success: String,
    error: String,
    nomination: Object

  },
  data() {
    return {
      showMenu: false,
      options: this.nominees,
      form: {
         nomination_date: this.nomination.date,
         licence_id: '',
        nomination_year: this.nomination.year        
      },

      //This handles attachment of nominees 
      nomineeForm: {
      selected_nominess: [],
      nomination_id: this.nomination.id
      },

      //detach nominee
      nomineeForm: {
      selected_nominess: [],
      nomination_id: this.nomination.id
      },
      
    };
  },
    methods: {

      saveNominneesToDatabase() {
          this.$inertia.post(`/add-selected-nominees`, this.nomineeForm)
        },

        removeSelectedNominee(nominee_id){
          this.$inertia.post(`/detach-nominee/${this.nomination.id}/${nominee_id}`)
        },

        submit() {
          this.$inertia.patch(`/update-nominee`, this.form)
        },
        
  },

  
  components: {
    Layout,
    Multiselect,
    Link,
    Datepicker
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};
//The following are status keys:
// 1 => Client Invoiced
// 2 => Nomination Paid
// 3 => Nomination Lodged
// 4 => Certificate Received
// 5 => Nomination Complete And Delivered
</script>
<style>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-top: -10px;
  margin-left: 3px;
}
.status-heading{
  font-weight: 700;
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
<template>
<Layout>
<div class="container-fluid">
    <div class="page-header min-height-100 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      ">
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row">
  <div class="col-lg-6 col-7">
   <h6>Nomination Info:  {{ nomination.licence.trading_name }}</h6>
  
  </div>
  <div class="col-lg-6 col-5 my-auto text-end"></div>
</div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="submit">
<div class="row">
<input type="hidden" v-model="form.slug">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" type="checkbox" @input="pushData($event.target.value)"
:checked="nomination.status >= '1'" value="1">
<label class="form-check-label text-body text-truncate status-heading">Client Invoiced</label>
</div>
</div>     
<hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" type="checkbox" @input="pushData($event.target.value)"
:checked="nomination.status >= '2'" value="2">
<label class="form-check-label text-body text-truncate status-heading">Nomination  Paid</label>
</div>
</div> <hr>
<label>Payment Document</label><hr>

<div class="col-md-10 columns">
  <div class="input-group input-group-outline null is-filled">
     <Multiselect
      v-model="nomineeForm.selected_nominess"
        placeholder="Search Nominees...."
        mode="tags"
        :options="options"
        :searchable="true"
        
        />
    </div>
 <div v-if="errors.people" class="text-danger">{{ errors.people }}</div>
</div>
<div class="col-md-2 columns"><button @click="saveNominneesToDatabase" type="button" class="btn btn-sm btn-secondary">Add</button></div>

<div class="table-responsive mb-2">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                      Full Name
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                    Relationship
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    DOB
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Contact Number
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Certified ID
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                   SAPS CLEARANCE
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Status
                    </th>

                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Action
                    </th>
                    
                  </tr>
                </thead>
             

                 <tbody>
                  <tr v-for="person in nomination.people" :key="person.id" >
                    <td>
                      <div class="d-flex px-2 py-1" >
                       
                    <div class="d-flex flex-column justify-content-left">
                    <Link :href="`/view-person/${person.slug}`"><h6 class="mb-0 text-sm">{{ person.full_name }} </h6></Link>                  
                    </div>
                      </div>
                    </td>
                    <td class="text-center">Relationship</td>
                     <td class="text-center">{{ person.date_of_birth }}</td>
                     <td class="text-center"> {{ person.cell_number }}</td>
                     <td>{{ person.valid_certified_id }}</td>
                      <td class="text-center">{{ person.valid_saps_clearance }}</td>
                      <td class="text-center">Status</td>
                      <td class="text-center">
                      <i @click="removeSelectedNominee(person.id)" 
                      style="cursor: pointer;"
                      class="material-icons-round text-danger fs-10">highlight_off</i>
                      </td>
                  </tr>
                  
                 
                </tbody>
              </table>
 </div>
<hr>
<div class="col-md-6 columns">
    <label class="form-label">Nomination Date </label>
    <Datepicker v-model="form.nomination_year" yearPicker />
 
     <p v-if="errors.nomination_year" class="text-danger">{{ errors.nomination_year }}</p>
  </div>
<hr>
<label>Required Documents</label><hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" type="checkbox" @input="pushData($event.target.value)"
:checked="nomination.status >= '3'" value="3">
<label class="form-check-label text-body text-truncate status-heading">Nomination Lodged</label>
</div>
</div> 
<label>Logded Nomination File</label><hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" type="checkbox" @input="pushData($event.target.value)"
:checked="nomination.status == '4'" value="4">
<label class="form-check-label text-body text-truncate status-heading">Certificate Received</label>
</div>
</div> 


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate"> Nomination Certificate File</label>
</div>
</div> <hr>



<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate">Transfer Certificate File</label>
</div>
</div> <hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" type="checkbox" @input="pushData($event.target.value)"
:checked="nomination.status >= '5'" value="5">
<label class="form-check-label text-body text-truncate status-heading"> Nomination Complete &amp; Delivered</label>
</div>
</div> 

<div>
  <button type="submit" class="btn btn-sm btn-secondary ms-2" :style="{float: 'right'}">Save</button></div>
            </div>
            </form>
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
      <!-- //tasks were here -->
        
        </div>
        
      </div>
    </div>
  </div>
  </Layout>
</template>
