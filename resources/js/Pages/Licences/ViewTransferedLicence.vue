<script>
import Layout from "../../Shared/Layout.vue";

export default {
  name: "profile-overview",
 props: {
    errors: Object,
    view_transfer: Object
  },
  data() {
    return {
      showMenu: false,
      form: {
      trading_name: this.view_transfer.licence.trading_name,
      new_company: this.view_transfer.licence.company.name,
      old_company: this.view_transfer.licence.old_company[0].name,
      transfer_date: this.view_transfer.date,
      status: this.view_transfer.status,
      slug: this.view_transfer.slug,
      licence_slug: this.view_transfer.licence.slug
    }
    };
  },
    methods: {
      submit() {
          this.$inertia.patch(`/update-licence-transfer`, this.form)
        },

        deleteTransfer(slug,licence_slug){
          if(confirm('Are you sure you want to delete this licence transfer??')){
            this.$inertia.delete(`/delete-licence-transfer/${slug}/${licence_slug}`)
          }
        }
  },
  components: {
    Layout,
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

</script>
<style scoped>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-top: 3px;
  margin-left: 3px;
}
.delete-icon{
  height: 4rem;
}
</style>

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
<h5 class="mb-1">View Licence Transfer For: {{ view_transfer.licence.trading_name }}</h5>
</div>


<div class="col-lg-6 col-5 my-auto text-end">
<div class="dropdown float-lg-end pe-4">

<button @click="deleteTransfer(form.slug,form.licence_slug)" class="btn btn-sm btn-danger">Delete</button>

</div>
</div>
</div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="submit">
<div class="row">

  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Licence Trading Name </label>
    <input type="text" disabled class="form-control form-control-default" v-model="form.trading_name" >
     </div>
   <div v-if="errors.trading_name" class="text-danger">{{ errors.trading_name }}</div>
   </div>
  
  <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">New Company *</label>
  <input type="text" disabled class="form-control form-control-default" v-model="form.new_company" >
  </div>
 <div v-if="errors.new_company" class="text-danger">{{ errors.new_company }}</div>
</div>

 <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Old Company</label>
    <input type="text" disabled class="form-control form-control-default" v-model="form.old_company" >
     </div>
   <div v-if="errors.old_company" class="text-danger">{{ errors.old_company }}</div>
   </div>

<div class="col-md-4 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Transfer Date</label>
  <input type="date" required class="form-control form-control-default" v-model="form.transfer_date" >
   </div>
     <div v-if="errors.transfer_date" class="text-danger">{{ errors.transfer_date }}</div>
</div>  


  <div class="col-md-4 columns">            
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Transfer Status</label>
  <select class="form-control form-control-default" v-model="form.status">
  <option value="Pending">Pending</option>
  <option value="Completed">Completed</option>
  <option value="Declined">Declined</option>
  </select>
   </div>
    <div v-if="errors.status" class="text-danger">{{ errors.status }}</div>
    </div>              
              

<hr>
<h6 class="text-center">Documents </h6>
<p class="text-center text-success">Coming Soon... </p>
  <div><button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}">Save</button></div>
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
