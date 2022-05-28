<script>
import Layout from "../../Shared/Layout.vue";
import { Head,Link } from '@inertiajs/inertia-vue3';
import DefaultProjectCard from "./../components/DefaultProjectCard.vue";

export default {
   
  props: {
    errors: Object,
    user: Object,
    success: String,
  },

  data() {
    return {
        form: {
         name: this.user.name ,
         contact: this.user.contact,
         email: this.user.email ,
         old_password: '',
         new_password: '',
         password_confirm: '', 

      },
      showMenu: false,
    };
  },
  components: {
    DefaultProjectCard,
    Layout,
    Link,
    Head,
  },

  methods: {
    submit() {
      this.$inertia.post(`/update-my-password`, this.form)
    },

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
</style>

<template>
  <Layout>
<div class="container-fluid" >
    <div class="page-header min-height-100 border-radius-xl" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      "><span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row gx-4">
        <div class="col-auto">
         
        </div>
        <div class="col-auto my-auto">
          <div class="h-100 d-flex">
             <h5 class="mb-1">New Licence for: {{ user.name }}</h5>
            
          </div>
        </div>
      
      </div>
      <div class="row">
    
        <div class="mt-3 row">
          <div class="col-12 col-md-6 col-xl-4 position-relative">
            <div class="card card-plain h-100">
            <div class="p-3 pb-0 card-header">
                <h6 class="mb-0" >Your Details</h6>
              </div>
              <div class="p-3 card-body">
<form>
              
  <div class="col-md-12 columns">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label"> Name *</label>
  <input type="text" class="form-control form-control-default" v-model="form.name">
   </div>
  <p v-if="errors.name" class="text-danger">{{ errors.name }}</p>
</div>

   <div class="col-md-12 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Email</label>
  <input type="email" class="form-control form-control-default" v-model="form.email">
   </div>
   <p v-if="errors.email" class="text-danger">{{ errors.email }}</p>
    </div>

    <div class="col-md-12 columns">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label"> Contact Number </label>
  <input type="text" class="form-control form-control-default" v-model="form.contact">
   </div>
  <p v-if="errors.contact" class="text-danger">{{ errors.contact }}</p>
</div>
    <button type="submit" disabled class="btn btn-sm btn-secondary ms-2 float-end justify-content-center">
Save
</button>
</form>
</div>
</div>
<hr class="vertical dark" />
</div>
          <div class="mt-4 col-12 col-xl-4 mt-xl-0">
            <div class="card card-plain h-100">
            <div class="p-3 pb-0 card-header">
                <h6 class="mb-0" >Password Reset</h6>
              </div>
              <div class="p-3 card-body">    

            <!-- Tabs content -->
<div class="tab-content" id="ex1-content">
<div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
 <form @submit.prevent="submit">
  <div class="col-md-12 columns">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label"> Old Password *</label>
  <input type="password" class="form-control form-control-default" v-model="form.old_password">
   </div>
  <div v-if="errors.old_password" class="text-danger">{{ errors.old_password }}</div>
</div>

   <div class="col-md-12 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">New Password *</label>
  <input type="password" class="form-control form-control-default" v-model="form.new_password">
   </div>
   <div v-if="errors.new_password" class="text-danger">{{ errors.new_password }}</div>
  </div>

<div class="col-md-12 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Confirm Password *</label>
  <input type="password" class="form-control form-control-default" v-model="form.password_confirm">
   </div>
 </div>
   <div v-if="errors.password_confirm" class="text-danger">{{ errors.password_confirm }}</div>
   <div v-if="success" class="text-danger">{{ success }}</div>
   <button type="submit" class="btn btn-sm btn-secondary ms-2 float-end justify-content-center">
Update
</button>
   </form>
</div>
</div>
            <!-- Tabs content -->

              </div>
            </div>
          </div>
        </div>

        
      </div>
    </div>
  </div>

  </Layout>

</template>
