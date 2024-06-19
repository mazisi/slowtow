<template>
<Layout>
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row">
<div class="col-lg-6 col-7">
<h6 class="mb-1">Licence Info: <Link @click="redirect(licence.licence)" href="#!" class="text-success">
  {{ licence.licence.trading_name ? licence.licence.trading_name : '' }}</Link></h6>
</div>

</div>
<form @submit.prevent="sendMail" class="row">

<editor
      :init="{
        plugins: 'Loading...'
      }"
      
      v-model="mailForm.mail_body"
    />
<div class="mt-3 float-end">

  <button type="submit" class="btn btn-secondary float-end" :disabled="mailForm.processing || file_has_apostrophe">
    <span v-if="mailForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    Send</button>
  </div>
</form>

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
import { Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import Banner from '../components/Banner.vue';
import Editor from '@tinymce/tinymce-vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import useToaster from '../../store/useToaster'

export default {
 props: {
    errors: Object,
    licence: Object,
    template: String,
    success: String,
    error: String,
  },
  
  
  setup (props) {  
    const { notifySuccess, notifyError } = useToaster(); 
    const mailForm = useForm({
      mail_body: props.template,
      renewal_slug: props.licence.slug,
    })

    
    function sendMail() {
      mailForm.post('/dispatchRenewalMail', {
        onSuccess: () => {
          if(props.success){
            notifySuccess(props.success)
                  }else if(props.error){
                    notifyError(props.error)
                 }
        }
        })
    }


    const redirect = (licence) => {
          let url = '';
        if(licence.type == 'retail'){
           url = `/view-licence?slug=${licence.slug}`
        }else{
           url = `/view-wholesale-licence?slug=${licence.slug}`
        }
          Inertia.get(url);
        }
    

    return {
      sendMail,
      mailForm,
      redirect,
      toast
    }
  },
   components: {
    Layout,
    Link,
    Banner,
    'editor':Editor
  },
  
};

</script>

