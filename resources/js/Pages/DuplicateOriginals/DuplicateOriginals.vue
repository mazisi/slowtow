<template>
  <Layout>
    <Head title="Duplicate Originals" />
  <div class="container-fluid">
  <Banner/>
  <div class="card card-body mx-3 mx-md-4 mt-n6">
  <div class="row gx-4">
  <div class="col-auto">
  
  </div>
  <div class="row">
  <div class="col-lg-12">
  <h6 class="mb-1">Duplicated Originals For:  <Link :href="`/view-licence?slug=${licence.slug}`" class="text-success">
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
                    <tr v-if="originals_years.data" v-for="original_year in originals_years.data" :key="original_year.id" >
                      <td>
                        <div style="margin-left: 20px;">                       
                     
                       <Link :href="`/view-duplicate-original/${original_year.slug}`">
                        <h6 class="mb-0 text-sm">{{ original_year.year }}</h6>
                       </Link>                  
                      </div>
                      </td>
                       
                      <td class="text-center"  v-if="original_year.status == 1"><Link class="ml-1 badge bg-dark text-default" :href="`/view-duplicate-original/${original_year.slug}`">Client Quoted</Link></td>
                      <td class="text-center"  v-else-if="original_year.status == 2"><Link class="ml-1 badge bg-info text-default" :href="`/view-duplicate-original/${original_year.slug}`">Client Invoiced</Link></td>
                      <td class="text-center"  v-else-if="original_year.status == 3"><Link class="ml-1 badge bg-light text-dark" :href="`/view-duplicate-original/${original_year.slug}`">Client Paid</Link></td>
                      <td class="text-center"  v-else-if="original_year.status == 4"><Link class="ml-1 badge bg-warning text-default" :href="`/view-duplicate-original/${original_year.slug}`">Payment to the Liquor Board</Link></td>
                      <td class="text-center"  v-else-if="original_year.status == 5"><Link class="ml-1 badge bg-secondary text-default" :href="`/view-duplicate-original/${original_year.slug}`">Select Nominees</Link></td>
                      <td class="text-center"  v-else-if="original_year.status == 6"><Link class="ml-1 badge bg-success text-default" :href="`/view-duplicate-original/${original_year.slug}`">Prepare Nomination Application</Link></td>
                      <td class="text-center"  v-else-if="original_year.status == 7"><Link class="ml-1" :href="`/view-duplicate-original/${original_year.slug}`">Scanned Application</Link></td>
                      <td class="text-center"  v-else-if="original_year.status == 8"><Link class="ml-1" :href="`/view-duplicate-original/${original_year.slug}`">Nomination Lodged</Link></td>
                      <td class="text-center"  v-else-if="original_year.status == 9"><Link class="ml-1" :href="`/view-duplicate-original/${original_year.slug}`">Nomination Issued</Link></td>
                      <td class="text-center"  v-else-if="original_year.status == 10"><Link class="ml-1" :href="`/view-duplicate-original/${original_year.slug}`">Nomination Delivered</Link></td>
                      <td class="text-center"  v-else></td>
  
                        <td class="text-center">
                          <Link :href='`/view-duplicate-original/${original_year.slug}`'>
                          <i class="fa fa-eye"></i></Link>
                          </td>
                    </tr>
                    <tr v-else>
                      <p class="text-center text-danger text-sm">Empty.</p>
                    </tr>
                    </tbody>
                </table>
                
              </div>
              <Paginate 
                v-if="originals_years"
                :modelName="originals_years"
                :modelType="'Duplicate-Originals'"
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
  
  
  import { ref } from 'vue';
  
  export default {
    props: {
      errors: Object,
      licence: Object,
      years: Object,
      success: String,
      error: String,
      originals_years: Object
    },
  
    setup (props) {
      const year = ref(new Date().getFullYear());
      let years = props.years
      const { notifySuccess, notifyError } = useToaster();
  
      const form = useForm({
        year: null,
        licence_id: props.licence.id
      })
  
      Inertia.on('navigate', (event) => {
            Inertia.visit(`${event.detail.page.url}`,{ preserveState: true, preserveScroll: true})
      })
  
      function submit() {
        form.post('/submit-duplicate-original', {
          onSuccess: () => { 
              if(props.success){
                notifySuccess(props.success)
                    form.reset('year');
                 }else if(props.error){
                  notifyError(props.error)
              }
           }
        })
      }
  
     
          
          onMounted(() => {
            if(props.success){
              notifySuccess(props.success)
            }else if(props.error){
              notifyError(props.error)
            }
  
           });
      return { year, years,form, submit,toast}
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
  