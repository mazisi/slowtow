<template>
    <Layout>
      <Head title="Appointment of managers" />
    <div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
    <div class="row gx-4">
    <div class="col-auto">
    
    </div>
    <div class="row">
    <div class="col-lg-12">
    <h6 class="mb-1">Appointment of managers For:  <Link :href="`/company/view-my-licences/${licence.slug}`" class="text-success">
      {{ licence.trading_name ? licence.trading_name : '' }}</Link></h6>
    </div>
    </div>
    
    
    </div>
    <div class="row">
    
    <div class="mt-3 row">
    <div class="col-12 position-relative">
     <div class="table-responsive mb-2">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                          Appointment of managers Year
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
                       
                         <Link :href="`/company/view-nomination/${nom.slug}`">
                          <h6 class="mb-0 text-sm">{{ nom.year }}</h6>
                         </Link>                  
                        </div>
                        </td>
                         
                        <td class="text-center">
                          <Link class="ml-1" :href="`/company/view-nomination/${nom.slug}`">
                            <div v-html="getStatus(nom.status)"></div>
                          </Link></td>
                      
    
                          <td class="text-center">
                            <Link :href='`/company/view-nomination/${nom.slug}`'>
                            <i class="fa fa-eye"></i></Link>
                            </td>
                      </tr>
                    
                      <tr v-else>
                        <td colspan="6" class="text-center text-danger">No Appointment of managers found.</td>
                    </tr>
                      </tbody>
                  </table>
                  
                </div>
                <Paginate v-if="nomination_years.data.length > 0"
                  :modelName="nomination_years"
                  :modelType="Nominations"
                  />
                </div>
                
  
    
    </div>
    
    </div>
    </div>
    </div>
    </Layout>
    </template>
    <style src="@vueform/multiselect/themes/default.css"></style>
    <script>
    import Layout from "../../../Shared/Layout.vue";
    import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
    import '@vuepic/vue-datepicker/dist/main.css';
    import Banner from '../../components/Banner.vue';
    import { Inertia } from '@inertiajs/inertia';
    import Paginate from "../../../Shared/Paginate.vue";
    import Multiselect from '@vueform/multiselect';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import { onMounted } from 'vue';
    import useToaster from '../../../store/useToaster'
    import useNomination from '../../Nominations/useNomination'
    
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
        return { year, years,form, submit,toast,getStatus}
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
    