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
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Created </th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in users" :key="user.id">
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div>
                            <img :src="`https://eu.ui-avatars.com/api/?background=random&amp;name=${user.name}`" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">{{ user.name }}</h6>
                            <p class="text-xs text-secondary mb-0"> {{ user.email.toLowerCase() }} </p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p v-for="role in user.roles" class="text-xs font-weight-bold mb-0">{{ role.name }}</p>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <span v-if="user.is_active" class="badge badge-sm bg-gradient-success">Active</span>
                        <span v-else class="badge badge-sm bg-gradient-warning">Deactivated</span>
                      </td>
                      <td class="align-middle text-center"><span class="text-secondary text-xs font-weight-bold">{{ new Date(user.created_at).toDateString() }}</span></td>
                      <td class="align-middle text-center">
                      <!-- Example split danger button -->
                      <div class="dropdown">
  <button class="btn btn-sm  btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
    Action
  </button>
  <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton2">
    <li><a @click="editUser(user.id,user.name,user.email,user.roles[0].name)" data-bs-toggle="modal" data-bs-target="#edit-user" class="dropdown-item active" href="#!">Edit</a></li>
    <li>
      <a v-if="user.is_active" @click="deActivateuser(user.id, user.is_active)" class="dropdown-item" href="#">Deactivate</a>
      <a v-else @click="deActivateuser(user.id, user.is_active)" class="dropdown-item" href="#">Activate</a></li>
  </ul>
</div>
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

  <div v-if="show_modal" class="modal fade" id="edit-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit {{ editForm.full_name }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form @submit.prevent="updateUser">        
        <div class="modal-body">      
          <div class="row">
            <div class="col-12 columns">
              <div class="input-group input-group-outline null is-filled ">
              <label class="form-label">Full Name</label>
              <input type="text" required class="form-control form-control-default" v-model="editForm.full_name" >
              </div>
              <div v-if="errors.full_name" class="text-danger">{{ errors.full_name }}</div>
              </div>

              <div class="col-12 columns">
                <div class="input-group input-group-outline null is-filled ">
                <label class="form-label">Email</label>
                <input type="email" required class="form-control form-control-default" v-model="editForm.email" >
                </div>
                <div v-if="errors.email" class="text-danger">{{ errors.email }}</div>
                </div>     
           </div>
        </div>
    
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" :disabled="editForm.processing">
           <span v-if="editForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
           Update</button>
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
      users: Object,
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

          const editForm = useForm({
            full_name: '',
            email: '',
            role: '',
            id: ''
          }) 
        //  function deleteLicence(){
        //     if(confirm('Are you sure you want to delete this licence??')){
        //       Inertia.delete(`/delete-licence/${props.licence.slug}`)
        //     }      
        //   }
        
          function submitUser() {
            form.post('/submit-user', {
              onSuccess: () => {
                this.show_modal = false;
                document.querySelector('.modal-backdrop').remove()
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

          function editUser(user_id,name,email,role){
            this.editForm.full_name = name;
            this.editForm.email = email;
            this.editForm.role = role;
            this.editForm.id = user_id;
            this.show_modal = true;
          }

          function updateUser(){
            editForm.patch('/update-user', {
              onSuccess: () => {
                this.show_modal = false
                document.querySelector('.modal-backdrop').remove()
              }
             })
          }

          function deActivateuser(id, status){
            Inertia.post(`/deactivate-user/${id}/${status}`,{
              onSuccess: () => {
                //
              }
            })
          }
  
      return {
        form,
        showMenu,
        show_modal,
        submitUser,
        generatePassword,
        editUser,
        editForm,
        updateUser,
        deActivateuser
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
  
  