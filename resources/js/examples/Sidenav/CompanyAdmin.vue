<template>
    <li class="nav-item" v-if="$page.props.currentRoute == 'view_my_licences' || $page.props.currentRoute == 'company_registration'">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'company_registration'}"
         :href="`/company/registration?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">app_registration</i>
        </div>
        <span class="nav-link-text ms-1">Registration</span>
        
        </Link>
      </li>

      <li class="nav-item">
        <Link v-if="$page.props.currentRoute == 'view_my_temp_licence'" data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link"
        :class="{ active:  $page.props.currentRoute == 'view_my_temp_licence'}"
        :href="`/company/process-temp-application?slug=${$page.props.slug}`">
       <div class="text-center d-flex align-items-center justify-content-center me-2">
       <i class="material-icons-round opacity-10 fs-5">account_tree</i>
       </div>
       <span class="nav-link-text ms-1">Process Application</span>

       </Link>
      </li>

      <li class="nav-item" v-if="$page.props.currentRoute == 'view_my_licences'">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'my_renewals'}"
         :href="`/company/my-renewals?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">autorenew</i>
        </div>
        <span class="nav-link-text ms-1">Renewals</span>
        
        </Link>
      </li>

      <li v-if="$page.props.currentRoute == 'view_my_licences'"
            class="nav-item">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'my_transfer_history'}"
         :href="`/company/my-transfer-history?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">group</i>
        </div>
        <span class="nav-link-text ms-1">Transfers</span>
        
        </Link>
      </li>

      <li v-if="$page.props.currentRoute == 'view_my_licences'
             || $page.props.currentRoute == 'my_nominations'
             || $page.props.currentRoute == 'view_nomination'"
            class="nav-item">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'my_nominations' 
        || $page.props.currentRoute == 'view_nomination'}"
         :href="`/company/my-nominations?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">move_down</i>
        </div>
        <span class="nav-link-text ms-1">Nominations</span>
        
        </Link>
      </li>

      <li v-if="$page.props.currentRoute == 'view_my_licences'
             || $page.props.currentRoute == 'company_alterations'
             || $page.props.currentRoute == 'view_company_alteration'"
            class="nav-item">
        <Link data-bs-toggle="" aria-controls="" aria-expanded="false" class="nav-link" 
         :class="{ active:  $page.props.currentRoute == 'company_alterations' 
        || $page.props.currentRoute == 'view_company_alteration'}"
         :href="`/company/my-alterations?slug=${$page.props.slug}`">
        <div class="text-center d-flex align-items-center justify-content-center me-2">
        <i class="material-icons-round opacity-10 fs-5">settings_suggest</i>
        </div>
        <span class="nav-link-text ms-1">Alterations</span>
        
        </Link>
      </li>


      <div v-if="show_modal" class="modal fade" id="edit-password" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Update Your Password</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times text-danger"></i></button>
            </div>
            <form @submit.prevent="updatePassword">        
            <div class="modal-body">      
              <div class="row">
                <div class="col-12 columns">
                  <div class="input-group input-group-outline null is-filled ">
                  <label class="form-label">New Password</label>
                  <input type="password" required class="form-control form-control-default" v-model="form.password" >
                  </div>
                    <div v-if="errors" class="text-danger">{{ errors.password }}</div>
                  </div>
    
                  <div class="col-12 columns">
                    <div class="input-group input-group-outline null is-filled ">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" required class="form-control form-control-default" v-model="form.password_confirmation" >
                    </div>
                    </div>     
               </div>
            </div>
        
            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-sm btn-primary" :disabled="form.processing">
               <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
               Update</button>
            </div>
            </form>
          </div>
        </div>
      </div>
</template>
<script>
import { computed,ref } from 'vue';
import { usePage,useForm,Link } from '@inertiajs/inertia-vue3';
import useToaster from '../../store/useToaster';

export default{

  props:{
        success: String,
        error: String,
        errors: Object
      },
    setup(props) {
      let show_modal = ref(true);
      const { notifySuccess, notifyError } = useToaster();

      const form = useForm({
         password: '',
         password_confirmation: ''      
      });

      const toggleModal = () => {
        show_modal = true
      }

      const updatePassword = () => {
        if(form.password != form.password_confirmation){
          notifyError('Passwords do not match')
          return;
        }
        form.post('/update-my-password', {
        onSuccess: () => {
            show_modal = false
            document.querySelector('.modal-backdrop').remove()
            notifySuccess('Password Updated Successfully')
         },
      })

    }


    const user = computed(() => usePage().props.value.auth.user)
    return { user, form,updatePassword,show_modal,toggleModal }
  },
    components: {
        Link
    }
}
</script>

<style scoped>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-left: 3px;
}

</style>