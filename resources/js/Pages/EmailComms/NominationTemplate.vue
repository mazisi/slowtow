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
<h6 class="mb-1">Licence Info: {{ licence.licence.trading_name }}</h6>
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
import Editor from '@tinymce/tinymce-vue'

export default {
 props: {
    errors: Object,
    licence: Object,
    template: String,
    success: String,
    error: String,
  },
  
  
  setup (props) {   

    const mailForm = useForm({
      mail_body: props.template,
      nomination_slug: props.licence.slug,
    })

    
    function sendMail() {
      mailForm.post('/dispatchNominationMail', {
        onSuccess: () => {
          //do something
        }
        })
    }

    return {
      sendMail,
      mailForm
    }
  },
   components: {
    Layout,
    Link,
    'editor':Editor
  },
  
};

</script>

