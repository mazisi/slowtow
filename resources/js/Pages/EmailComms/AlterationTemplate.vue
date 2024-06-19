<template>
<Layout>
<div class="container-fluid">
<h6 class="mb-1">
  <Link @click="redirect(licence.licence)" href="#!" class="text-success">Licence Info: {{ licence.licence.trading_name ? licence.licence.trading_name : ''}}
  </Link></h6>

<Banner/>
<div class="card-body mx-4 mx-md-4 mt-n6">
<div class="row">
<div class="col-12">
</div>

</div>

<form @submit.prevent="sendMail" class="row">

<editor
      :init="{
        plugins: 'lists link image table code help wordcount'
      }"
      v-model="mailForm.mail_body"
    />
<div class="mt-3 float-end">
<button type="submit" :style="{float: 'right'}" class="btn btn-secondary" :disabled="mailForm.processing">
<span v-if="mailForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
Send
</button>
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
import { Inertia } from '@inertiajs/inertia'
import Editor from '@tinymce/tinymce-vue';
import Banner from '../components/Banner.vue';
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
      alteration_slug: props.licence.slug,
    })

    
    function sendMail() {
      mailForm.post('/dispatch-alteration-mail', {
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
      redirect
    }
  },
   components: {
    Layout,
    Link,
    Banner,
    toast,
    'editor':Editor
  },
  
};

</script>

