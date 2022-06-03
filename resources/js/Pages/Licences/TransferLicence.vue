<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';

export default {
  name: "profile-overview",
 props: {
    errors: Object,
    licence: Object,
    companies_dropdown: Array,
    success: String,
    error: String,

  },
  data() {
    return {
      showMenu: false,
      form: {
      old_company: this.licence.company.name,
      old_company_id: this.licence.company_id,
      new_company: null,
      date: null,
      status: null,
      licence_id: this.licence.id
      },
      options: this.companies_dropdown,
    };
  },
    methods: {
      submit() {
          this.$inertia.post(`/transfer-licence-submit/${this.licence.slug}`, this.form)
          .then(() => {
              
            })
        },
  },
  components: {
    Layout,
    Multiselect
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

</script>
<style>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-top: 3px;
  margin-left: 3px;
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
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="submit">
<div class="row">
<input type="hidden" v-model="form.old_company_id"> 
<input type="hidden" v-model="form.licence_id">                 
  <div class="col-md-6 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Old Company *</label>
    <input type="text" required readonly title="You can`t change this field." class="form-control form-control-default" v-model="form.old_company" >
     </div>
   <div v-if="errors.old_company" class="text-danger">{{ errors.old_company }}</div>
   </div>
  <div class="col-md-6 columns">
  <div class="input-group input-group-outline null is-filled">
     <Multiselect
     v-model="form.new_company"
        placeholder="Search company"
        :options="options"
        :searchable="true"
      />
    </div>
 <div v-if="errors.new_company" class="text-danger">{{ errors.new_company }}</div>
</div>

 <div class="col-md-6 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.date" >
     </div>
   <div v-if="errors.date" class="text-danger">{{ errors.date }}</div>
   </div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
  <label class="form-label">Status</label>
  <select v-model="form.status" required class="form-control form-control-default">
    <option value="">Please review this dropdown values</option>
    <option value="Pending">Pending</option>
    <option value="Completed">Completed</option>
    <option value="Declined">Declined</option>
  </select>
</div>
<div v-if="errors.status" class="text-danger">{{ errors.status }}</div>
</div>
<hr>
<h6 class="text-center">Documents Related Fields</h6>
<div>
  <button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}">Transfer</button></div>
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
