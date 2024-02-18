<template>
    <Layout>
      <Head title="Transfers" />
    
      <div class="container-fluid">
        <Banner/>
        <div class="card card-body mx-3 mx-md-4 mt-n6">
          <div class="col-12">
          <div class="row">
      <div class="col-lg-9 col-9">
       <h5>Transfers for: <Link :href="`/view-licence?slug=${licence.slug}`" class="text-success">{{ licence.trading_name ? licence.trading_name : '' }}</Link></h5>
      </div>
      <div class="col-lg-3 col-3 my-auto text-end">
        <div class="dropdown float-lg-end pe-4">
         <Link :href="`/transfer-licence?slug=${$page.props.slug}`" class="btn btn-sm btn-secondary">New Transfer</Link>
        </div>
      </div>
    </div>
            <div class=" my-4">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        Previous Licence Holder
                        </th>
                        <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        Current Licence Holder
                        </th>
                        
                        <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        Stage
                        </th>
                        <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                        View
                        </th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for=" (  transfer, index  ) in transfers.data" :key="index" >
                        <td >
                        <div class="d-flex flex-column">
                            <Link class="ml-1" v-if="transfer.transfered_from === 'Company'" :href="`/view-transfered-licence/${transfer.slug}`">
                              <h6 class="mb-0 text-sm">
                              {{ limit(transfer.old_company.name) }}
                               </h6>    
                            </Link> 
    
                            <Link class="ml-1" v-if="transfer.transfered_from === 'Individual'" :href="`/view-transfered-licence/${transfer.slug}`">
                              <h6 class="mb-0 text-sm">
                              {{ limit(transfer.old_person.full_name) }}
                               </h6>    
                            </Link>                      
                            </div>
                            </td>
                        <td >
                          
                          <Link class="ml-1" v-if="transfer.transfered_to === 'Company'" :href="`/view-transfered-licence/${transfer.slug}`">
                            <h6 class="text-sm">
                            {{ limit(transfer.new_company.name) }}
                          </h6>    
                            </Link>  
                            <Link class="ml-1" v-if="transfer.transfered_to === 'Individual'" :href="`/view-transfered-licence/${transfer.slug}`">
                              <h6 class="text-sm">
                              {{ limit(transfer.new_person.full_name) }}
                            </h6>    
                              </Link>  
                         
                        </td>
                       
              <td >
                <Link class="" :href="`/view-transfered-licence/${transfer.slug}`">
                 <span v-html="getStatus(transfer.status)"></span>
                </Link>
              
              </td>
              
                
              
    
                     <td >
                        <Link class="ml-1" :href="`/view-transfered-licence/${transfer.slug}`">
                        <i class="fa fa-eye  " aria-hidden="true"></i>
                        </Link>
                          
                        </td>
                      </tr>
                      
                     
                    </tbody>
                  </table>
                </div>
              </div>
              <Paginate
                :modelName="transfers"
                :modelType="Transfers"
                />
            </div>
          </div>
        </div>
      </Layout>
    </template>
    <style scoped>
        .ml-1{margin-left: 1.2rem;}
    </style>
    <script>
    import Layout from "../../../Shared/Layout.vue";
    import { Link, Head } from '@inertiajs/inertia-vue3';
    import Banner from '../../components/Banner.vue';
    import Paginate from '../../../Shared/Paginate.vue';
    import { onMounted } from 'vue';
    import { Inertia } from '@inertiajs/inertia'
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import useToaster from '../../../store/useToaster';
    import useTransferStatus from '../../../store/useTransferStatus'
    
    export default {
      name: "Transfers",
      props: {
        transfers: Object,
        licence: Object,
        success: String,
        error: String,
        errors: Object
      },
      setup(props){
        const { notifySuccess, notifyError } = useToaster();
        const { getBadgeStatus } = useTransferStatus();
       
        Inertia.on('navigate', (event) => {
              Inertia.visit(`${event.detail.page.url}`, { preserveState: true, preserveScroll: true });
            })
    
        function limit(string='', limit=25){
              if(string.length >= limit){
              return string.substring(0, limit) + '...'
            }  
              return string.substring(0, limit)
            }
    
    
            function getStatus(statusParam) {
                return getBadgeStatus(statusParam);
            }
    
            onMounted(() => {
              if(props.success){
                notifySuccess(props.success)
              }else if(props.error){
                notifyError(props.error)
              }
            });
    
        return {
          getStatus,
          limit,
          toast
        }
      },
    
      components: {
        Layout,
        Link,
        Head,
        Banner,
        Paginate
        },
    
    };
    </script>
    
    