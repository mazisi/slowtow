<template>
<Layout>
  <Head title="Nominations" />
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row gx-4">
<div class="col-auto">

</div>
<div class="row">
<div class="col-lg-12">
<h6 class="mb-1">Nominations For:  <Link @click="redirect(licence)" class="text-success">
  {{ licence.trading_name ? licence.trading_name : '' }}</Link></h6>
</div>
</div>


</div>
<div class="row">

<div class="mt-3 row">
<div class="col-9 position-relative">
 <div class="table-responsive mb-2">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                      Nomination Year
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Current Status
                    </th>

                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                      View
                      </th>
                  </tr>
                </thead>
                 <tbody>
                  <tr v-if="nomination_years.data.length > 0" v-for="nom in nomination_years.data" :key="nom.id" >
                    <td>
                      <div style="margin-left: 20px;">                       
                   
                     <Link :href="`/view-nomination/${nom.slug}`">
                      <h6 class="mb-0 text-sm">{{ nom.year }}</h6>
                     </Link>                  
                    </div>
                    </td>
                     
                    <td class="text-center">
                      <Link class="ml-1" :href="`/view-nomination/${nom.slug}`">
                        <div v-html="getStatus(nom.status)"></div>
                      </Link></td>
                  

                      <td class="text-center">
                        <Link :href='`/view-nomination/${nom.slug}`'>
                        <i class="fa fa-eye"></i></Link>
                        </td>
                  </tr>
                
                  <tr v-else>
                    <td colspan="6" class="text-center text-danger">No nominations found.</td>
                </tr>
                  </tbody>
              </table>
              
            </div>
            <Paginate v-if="nomination_years.data.length > 0"
              :modelName="nomination_years"
              :modelType="Nominations"
              />
            </div>
            
<div class="col-3 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">
<form @submit.prevent="submit" class="mt-4">
<!-- <Datepicker v-model="form.year" yearPicker /> -->

  <div class="input-group input-group-outline null is-filled">
     <Multiselect
     v-model="form.year"
        placeholder="Select Year"
        :options="years"
        :searchable="true"
      />
    </div>
 <div v-if="errors.year" class="text-danger">{{ errors.year }}</div>
 
<button class="btn btn-sm btn-secondary mt-3 float-end justify-content-center" type="submit" >
  <span v-if="form.processing" class="spinner-border mt-1 spinner-border-sm" role="status" aria-hidden="true"></span>
  Submit
</button>
</form>

</div>
</div>

</div>

</div>

</div>
</div>
</div>
</Layout>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
<script>
import Layout from "../../Shared/Layout.vue";
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import '@vuepic/vue-datepicker/dist/main.css';
import Banner from '../components/Banner.vue';
import { Inertia } from '@inertiajs/inertia';
import Paginate from "../../Shared/Paginate.vue";
import Multiselect from '@vueform/multiselect';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { onMounted } from 'vue';
import useToaster from '../../store/useToaster'
import useNomination from './useNomination'

import { ref } from 'vue';

export default {
  props: {
    errors: Object,
    licence: Object,
    years: Object,
    success: String,
    error: String,
    nomination_years: Object
  },

  setup (props) {
    const { notifySuccess, notifyError } = useToaster();
    const { getBadgeStatus } = useNomination();

    const year = ref(new Date().getFullYear());
    let years = props.years

    const form = useForm({
      year: null,
      licence_id: props.licence.id
    })

    Inertia.on('navigate', (event) => {
          Inertia.visit(`${event.detail.page.url}`,{ preserveState: true, preserveScroll: true})
    })

    const redirect = (licence) => {
          let url = '';
        if(licence.type == 'retail'){
           url = `/view-licence?slug=${licence.slug}`
        }else{
           url = `/view-wholesale-licence?slug=${licence.slug}`
        }
          Inertia.get(url);
        }

    function submit() {
      form.post('/submit-nomination', {
        onFinish: () => form.reset('password'),
        onSuccess: () => { 
            if(props.success){
                  notifySuccess(props.success)
               }else if(props.error){
                  notifyError(props.error)
            }
         }
      })
    }


         function getStatus(status_param) {
            return getBadgeStatus(status_param);
        }
        
        onMounted(() => {
          if(props.success){
            notifySuccess(props.success)
          }else if(props.error){
            notifyError(props.error)
          }

         });
    return { year, years,form, redirect, submit,toast,getStatus}
  },
   components: {
    Layout,
    Link,
    Head,
    Banner,
    Paginate,
    Multiselect
  },
  
};
</script>
