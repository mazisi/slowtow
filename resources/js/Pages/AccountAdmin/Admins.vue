<template>
  <Layout>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-body px-0 pb-2">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6 class="mx-2">Slotow Admins</h6>
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                  <a @click="show_modal = true" data-bs-toggle="modal" data-bs-target="#add-user" href="#!" class="float-end btn btn-dark">
                    <i class="material-icons-round">person_add_alt</i>
                   </a>
                </div>
              </div>
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Full Name </th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"> Function </th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Status </th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Employed </th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div><img src="/vue-material-dashboard-2/img/team-2.90c40d0c.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1"></div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">John Michael</h6>
                            <p class="text-xs text-secondary mb-0"> john@creative-tim.com </p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">Manager</p>
                        <p class="text-xs text-secondary mb-0">Organization</p>
                      </td>
                      <td class="align-middle text-center text-sm"><span class="badge badge-sm bg-gradient-success">Online</span></td>
                      <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">23/04/18</span></td>
                      <td class="align-middle"><a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user"> Edit </a></td>
                    </tr>
                 
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
  
  <!-- upload doc -->
  
  <div v-if="show_modal" class="modal fade" id="add-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form @submit.prevent="submitUser">        
        <div class="modal-body">      
          <div class="row">
            <div class="col-12 columns">
              <div class="input-group input-group-outline null is-filled ">
              <label class="form-label">Full Name</label>
              <input type="text" required class="form-control form-control-default" v-model="form.full_name" >
              </div>
              <div v-if="errors.full_name" class="text-danger">{{ errors.full_name }}</div>
              </div>

              <div class="col-12 columns">
                <div class="input-group input-group-outline null is-filled ">
                <label class="form-label">Email</label>
                <input type="email" required class="form-control form-control-default" v-model="form.email" >
                </div>
                <div v-if="errors.email" class="text-danger">{{ errors.email }}</div>
                </div>                
                <div class="col-12 columns">
                  <div class="input-group input-group-outline null is-filled ">
                  <label class="form-label">Function/Role</label>
                  <select class="form-control form-control-default" v-model="form.role">
                    <option value="SuperAdmin">SuperAdmin</option>
                    <option value="Admin">Admin</option>
                    <option value="Test">Test</option>
                  </select>
                  </div>
                  <div v-if="errors.role" class="text-danger">{{ errors.role }}</div>
                </div>

                <div class="col-9 columns">
                  <div class="input-group input-group-outline null is-filled ">
                  <label class="form-label">Password</label>
                  <input type="text" class="form-control form-control-default" v-model="form.password" >
                  </div>
                  <div v-if="errors.password" class="text-danger">{{ errors.password }}</div>
                </div>
                <div class="col-3 columns">
                  <button type="button" @click="generatePassword" class="btn btn-sm btn-secondary">Generate</button>
                </div>
           </div>
        </div>
    
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" :disabled="form.processing">
           <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
           Save</button>
        </div>
        </form>
      </div>
    </div>
  </div>
   
  </Layout>
  </template>
  <style scoped>
      .columns{
        margin-bottom: 1rem;
      }
      #active-checkbox{
        margin-left: 3px;
      }
  
  </style>
  
  
  <script>
  import Layout from "../../Shared/Layout.vue";
  import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
  import { Inertia } from '@inertiajs/inertia'
  import { ref } from 'vue'
  
  export default {
   props: {
      errors: Object,
      admins: Object,
      success: String,
      error: String
    },
    
    
    setup (props) {
          let showMenu = ref(false);
          let show_modal = ref(true); 
  
      const form = useForm({
        full_name: '',
        email: '',
        role: '',
        password: '' 
      }) 
        //  function deleteLicence(){
        //     if(confirm('Are you sure you want to delete this licence??')){
        //       Inertia.delete(`/delete-licence/${props.licence.slug}`)
        //     }      
        //   }
        
          function submitUser() {
            form.post('/submit-user', {
              onSuccess: () => {
                this.show_modal = false
                form.reset('body','full_name','email','role')
              }
             })
          }
  
          function generatePassword(){
            let chars = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            let passwordLength = 8;

            if(this.form.password == ''){
              for (var i = 0; i <= passwordLength; i++) {
                  var randomNumber = Math.floor(Math.random() * chars.length);
                  this.form.password += chars.substring(randomNumber, randomNumber +1);
             }
            }else{
              this.form.password = '';
              for (var i = 0; i <= passwordLength; i++) {
                  var randomNumber = Math.floor(Math.random() * chars.length);
                  this.form.password += chars.substring(randomNumber, randomNumber +1);
               }
            }
            
          }
  
      return {
        form,
        showMenu,
        show_modal,
        submitUser,
        generatePassword
      }
    },
     components: {
      Layout,
      Link,
      Head,
      Inertia
    },
    
  };
  </script>
  
  