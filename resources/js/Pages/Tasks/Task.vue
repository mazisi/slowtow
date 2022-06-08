<script>
import Layout from "../../Shared/Layout.vue";
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
  name: "dashboard-default",
  props: {
    errors: Object,
    success: String,
    error: String
  },
  data() {
     return {
      form: {
        title: '',
        body: '',
        datetime: '',
        priority: '',
      },
    }
  },
  components: {
    Layout,
    Head,
    Link
},
methods: {
    submit() {
      this.$inertia.post('/submit-task',this.form)
    },
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
  <div class="container-fluid">
    <div class="page-header min-height-100 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      ">
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">


 
  <div class="row">
    <div class="col-8">
      <div class="mb-5 ps-3">
        <h6 class="mb-1">Projects</h6>
        <p class="text-sm">Architects design houses</p>
      </div>
      <div class="row">
        <div class="mb-4 col-xl-6 col-md-6 mb-xl-0">
          <div class="card card-blog border border-primary">
            <div class="card-header p-0 mt-n4 mx-3"><h5 class="text-center">Modern</h5></div>
            <div class="p-3 card-body">
              <p class="mb-4 text-sm">As Uber works through a huge amount of internal
                management turmoil.
              </p>
              <div class="d-flex align-items-center justify-content-between">
                <button class="btn btn-sm btn-secondary">Edit</button>
                <div class="mt-2 avatar-group"><button class="btn btn-sm btn-danger">Delete</button></div>
              </div>
            </div>
          </div>
        </div>
        <div class="mb-4 col-xl-6 col-md-6 mb-xl-0">
          <div class="card card-blog card-plain">
            <div class="card-header p-0 mt-n4 mx-3"> <h5>Scandinavian</h5></div>
            <div class="p-3 card-body">
              <p class="mb-4 text-sm">Music is something that every person has his or her own
                specific opinion about.
              </p>
              <div class="d-flex align-items-center justify-content-between">
                <button type="button" class="mb-0 btn btn-sm btn-outline-success">View Project</button>
                <div class="mt-2 avatar-group"><a href="javascript:;" class="avatar avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Nick Daniel" aria-label="Nick Daniel"><img alt="/vue-material-dashboard-2/img/team-3.a539dfc6.jpg" src="/vue-material-dashboard-2/img/team-3.a539dfc6.jpg"></a><a href="javascript:;" class="avatar avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Peterson" aria-label="Peterson"><img alt="/vue-material-dashboard-2/img/team-4.16a9baef.jpg" src="/vue-material-dashboard-2/img/team-4.16a9baef.jpg"></a><a href="javascript:;" class="avatar avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Elena Morison" aria-label="Elena Morison"><img alt="/vue-material-dashboard-2/img/team-1.0e461794.jpg" src="/vue-material-dashboard-2/img/team-1.0e461794.jpg"></a><a href="javascript:;" class="avatar avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Ryan Milly" aria-label="Ryan Milly"><img alt="/vue-material-dashboard-2/img/team-2.90c40d0c.jpg" src="/vue-material-dashboard-2/img/team-2.90c40d0c.jpg"></a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-4">
    <div class="mb-5 ps-3">
        <h6 class="mb-1">Add Task</h6>
        <p class="text-sm">Architects design houses</p>
      </div>
     <form @submit.prevent="submit">
<div class="col-12 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Title</label>
  <input v-model="form.title" type="text" class="form-control form-control-default" >
   </div>
 <div v-if="errors.title" class="text-danger">{{ errors.title }}</div>
 </div>

 <div class="col-12 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Date &amp; Time</label>
  <input v-model="form.datetime" type="datetime-local" class="form-control form-control-default" >
   </div>
 <div v-if="errors.datetime" class="text-danger">{{ errors.datetime }}</div>
 </div>

 <div class="col-12 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Body</label>
  <textarea v-model="form.body" class="form-control form-control-default" ></textarea>
   </div>
 <div v-if="errors.body" class="text-danger">{{ errors.body }}</div>
 </div>

 <div class="col-12 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Priority</label>
  <select v-model="form.priority" class="form-control form-control-default">
  <option value="Low">Low</option>
  <option value="Medium">Medium</option>
  <option value="High">High</option>
  </select>
   </div>

</div>

<button type="submit" class="btn btn-sm btn-secondary ms-2 float-end justify-content-center">Save</button>
</form>
    </div>
  </div>

      </div>
    </div>
  </Layout>
</template>
