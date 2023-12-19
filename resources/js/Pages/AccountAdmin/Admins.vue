<style scoped>
.card-box{
  background:#DCDCDC !important;
}
        .columns{
          margin-bottom: 1rem;
        }
        #active-checkbox{
          margin-left: 3px;
        }
    
        .card-box {
          padding: 20px;
          border-radius: 3px;
          margin-bottom: 30px;
          background-color: #fff;
      }
      
      .social-links li a {
          border-radius: 50%;
          color: rgba(121, 121, 121, .8);
          display: inline-block;
          height: 30px;
          line-height: 27px;
          border: 2px solid rgba(121, 121, 121, .5);
          text-align: center;
          width: 30px
      }
      
      .social-links li a:hover {
          color: #797979;
          border: 2px solid #797979
      }
      .thumb-lg {
          height: 88px;
          width: 88px;
      }
      .img-thumbnail {
          padding: .25rem;
          background-color: #4caf50;
          border: 1px solid #dee2e6;
          border-radius: .25rem;
          max-width: 100%;
          height: auto;
      }
      .text-pink {
          color: #ff679b!important;
      }
      .btn-rounded {
          border-radius: 2em;
      }
      .text-muted {
          color: #000!important;
      }
      h4 {
          line-height: 22px;
          font-size: 18px;
      }
    </style>
    <template>
  <Layout>

    <Head title="All Users" />
  <div class="container-fluid">
  
  <Banner/>
  
  <div class="card card-body mx-3 mx-md-4 mt-n6">   
    <div class="content">
      <div class="container">
          <div class="row">
              <div class="col-sm-4">
                <h5 class="mx-2">Slotow Admins</h5>
              </div>
              <!-- end col -->
          </div>
          <!-- end row -->
    
   
          <div class="row">
            <div class="col-lg-9">
              <div class="row">
                <!-- <swiper
    :slides-per-view="3"
    :space-between="50"
    @swiper="onSwiper"
    @slideChange="onSlideChange">
    
    
  </swiper> -->
              <div class="col-lg-4" v-for="user in users.data" :key="user.id">
                  <div class="text-center card-box">
                      <div class="member-card pt-2 pb-2">
                          <div class="thumb-lg member-thumb mx-auto">

                            <!-- <img v-if="user.picture" :src="`${$page.props.blob_file_path}${user.picture}`" class="rounded-circle img-thumbnail" alt="profile-image"> -->
                            <img :src="`https://eu.ui-avatars.com/api/?background=random&amp;name=${user.name}`" class="rounded-circle img-thumbnail" alt="profile-image">
                          </div>
                          <div class="">
                              <h4>{{ user.name }}</h4>                              

                               <template v-for="role in user.roles" :key="role.id">

                                  <p v-if="role.name === 'slowtow-admin'" class="text-muted">Super Admin <span>| </span>
                                  <span>
                                    <a href="#" class="text-pink">{{ user.email }}</a>
                                  </span>
                                </p>

                                <p v-else-if="role.name === 'slowtow-user'" class="text-muted">Admin <span>| </span>
                                  <span>
                                    <a href="#" class="text-pink">{{ user.email }}</a>
                                  </span>
                                </p>

                                <p v-else class="text-muted">User <span>| </span>
                                  <span>
                                    <a href="#" class="text-pink">{{ user.email }}</a>
                                  </span>
                                </p>
                               
                              <div class="text-sm text-muted">Last Activity:</div>
                              <div class="text-sm text-muted">{{ getMomentDate(user.last_activity_at) }}</div>
                            </template>
                          </div>
                          <button @click="editUser(user)" type="button" class="btn bg-gradient-dark mt-3 btn-rounded waves-effect w-md waves-light">
                            Edit
                          </button>
                          
                          
                      </div>
                  </div>
                  
              </div>
              <Paginate
              :modelName="users"
              :modelType="Users"
              />
            </div>
          
              </div>

              <div class="col-lg-3">
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
                          <option :value="''">Select Role..</option>
                          <option value="slowtow-admin">Super Admin</option>
                          <option value="slowtow-user">Admin</option>
                          <option value="client">Client</option>
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
                      <button type="button" @click="submitUser" class="btn bg-gradient-dark" :disabled="form.processing">
                        <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                        </span>
                        {{ form.id ? 'Update' : 'Create' }} User
                        </button>
                 </div>
              </div>
          </div>

          
      </div>
      <!-- container -->
      
  </div>
  </div>
  </div>


  </Layout>
  </template>
  
  <style>
  .filters{
   margin-top: 10px;
  }
    .table thead th {
      padding: 0;
      }
   
    #with-thrashed{
      margin-top: 3px;
      margin-left: 3px;
    }
  </style>
  
  <script>
  import Layout from "../../Shared/Layout.vue";
  import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
  import { Inertia } from '@inertiajs/inertia';
  import { ref } from 'vue';
  import Banner from '../components/Banner.vue';
  import Paginate from '../../Shared/Paginate.vue';
  import { toast } from 'vue3-toastify';
   import 'vue3-toastify/dist/index.css';
   import moment from 'moment';
   import { Swiper,SwiperSlide  } from 'swiper/vue';
   import 'swiper/css';
   import useToaster from "@/store/useToaster";
  
  export default {
   props: {
      errors: Object,
      users: Object,
      success: String,
      error: String
    },
    
    
    setup (props) {
      const { notifySuccess, notifyError } = useToaster();
          let showMenu = ref(false);
          let show_modal = ref(true); 
          let file_name = ref('');
          let file_has_apostrophe = ref(''); 
  
          const form = useForm({
            full_name: '',
            email: '',
            role: '',
            password: '',
            id: '',
            profilePic: null
          }) 

          function submitUser() {
            form.post('/submit-user', {
              onSuccess: () => {
                if(props.success){
                  notifySuccess(props.success)
                    }else if(props.error){
                      notifyError(props.error)
                    }
                form.reset('body','full_name','email','role');
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


          function editUser(user){
            form.full_name = user.name;
            form.email = user.email;
            form.role = user.roles[0].name;
            form.id = user.id;
            
          }

          function getMomentDate(date){
            let now = moment();
            const date_param = moment(date, 'YYYY-MM-DD HH:mm:ss');
            return moment.duration(moment(date_param).diff(now)).humanize(true);
          }

          function deActivateuser(id, status){
            Inertia.post(`/deactivate-user/${id}/${status}`,{
              onSuccess: () => {
                if(props.success){
                  notifySuccess(props.success)
                    }else if(props.error){
                      notifyError(props.error)
                    }
              }
            })
          }

          function deleteUser(name='', user_id){
            if(confirm(`${name} will be deleted permanently. Continue ?`)){
                Inertia.patch(`/delete-user/${user_id}`,{
                onSuccess: () => {
                  if(props.success){
                    notifySuccess(props.success)
                    }else if(props.error){
                      notifyError(props.error)
                    }
                }
                })
             }
          }


          function getFileName(e){
            this.form.profilePic = e.target.files[0];
            this.file_name = e.target.files[0].name;
            this.file_has_apostrophe = this.file_name.includes("'");
          }

      
        const onSwiper = (swiper) => {
        console.log(swiper);
      };
      const onSlideChange = () => {
        console.log('slide change');
      };
  
      return {
        onSwiper,
        onSlideChange,
        toast,
        form,
        deleteUser,
        file_name,
        getFileName,
        file_has_apostrophe,
        showMenu,
        show_modal,
        submitUser,
        generatePassword,
        editUser,
        deActivateuser,
        getMomentDate
      }
    },
     components: {
      Layout,
      Link,
      Head,
      Banner,
      Paginate,
      Swiper,
      SwiperSlide
      
    },
    
  };
  </script>