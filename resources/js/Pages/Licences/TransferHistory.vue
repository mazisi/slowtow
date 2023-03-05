<template>
<Layout>
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
                    <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
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
                  <tr v-for=" (  transfer, index  ) in transfers" :key="index" >
                    <td class="text-center">
                    <div class="d-flex flex-column justify-content-left">
                        <Link v-if="transfer.transfered_from === 'Company'" :href="`/view-transfered-licence/${transfer.slug}`">
                          <h6 class="mb-0 text-sm">
                          {{ limit(transfer.old_company.name) }}
                           </h6>    
                        </Link> 

                        <Link v-if="transfer.transfered_from === 'Person'" :href="`/view-transfered-licence/${transfer.slug}`">
                          <h6 class="mb-0 text-sm">
                          {{ limit(transfer.old_person.full_name) }}
                           </h6>    
                        </Link>                      
                        </div>
                        </td>
                    <td class="text-center">
                      
                      <Link v-if="transfer.transfered_to === 'Company'" :href="`/view-transfered-licence/${transfer.slug}`">
                        <h6 class="text-sm">
                        {{ limit(transfer.new_company.name) }}
                      </h6>    
                        </Link>  
                        <Link v-if="transfer.transfered_to === 'Person'" :href="`/view-transfered-licence/${transfer.slug}`">
                          <h6 class="text-sm">
                          {{ limit(transfer.new_person.full_name) }}
                        </h6>    
                          </Link>  
                     
                    </td>
          <td  class="text-center" v-if="transfer.status == 1"><Link :href="`/view-transfered-licence/${transfer.slug}`">Client Quoted</Link></td>
          <td  class="text-center" v-else-if="transfer.status == 2"><Link :href="`/view-transfered-licence/${transfer.slug}`">Client Invoiced</Link></td>
          <td  class="text-center" v-else-if="transfer.status == 3"><Link :href="`/view-transfered-licence/${transfer.slug}`">Client Paid</Link></td>
          <td  class="text-center" v-else-if="transfer.status == 4"><Link :href="`/view-transfered-licence/${transfer.slug}`">Prepare Transfer Application</Link></td>
          <td  class="text-center" v-else-if="transfer.status == 5"><Link :href="`/view-transfered-licence/${transfer.slug}`">Payment To The Liquor Board</Link></td>
          <td  class="text-center" v-else-if="transfer.status == 6"><Link :href="`/view-transfered-licence/${transfer.slug}`">Scanned Application</Link></td>
          <td  class="text-center" v-else-if="transfer.status == 7"><Link :href="`/view-transfered-licence/${transfer.slug}`">Application Logded</Link></td>
          <td  class="text-center" v-else-if="transfer.status == 8"><Link :href="`/view-transfered-licence/${transfer.slug}`">Activation Fee Paid</Link></td>
          <td  class="text-center" v-else-if="transfer.status == 9"><Link :href="`/view-transfered-licence/${transfer.slug}`">Transfer Issued</Link></td>
          <td  class="text-center" v-else-if="transfer.status == 10"><Link :href="`/view-transfered-licence/${transfer.slug}`">Transfer Delivered</Link></td>
          

  <td class="text-center">
                    <Link :href="`/view-transfered-licence/${transfer.slug}`">
                    <i class="fa fa-eye  " aria-hidden="true"></i>
                    </Link>
                      
                    </td>
                  </tr>
                  
                 
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script>
import Layout from "../../Shared/Layout.vue";
import { Link } from '@inertiajs/inertia-vue3';
import Banner from '../components/Banner.vue';

export default {
  name: "Transfers",
  props: {
    transfers: Object,
    licence: Object,
    success: String,
    error: String,
    errors: Object
  },

  methods: {
    limit(string='', limit=25){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
  },
  
  components: {
    Layout,
    Link,
    Banner
    },

};

//The following are status keys
// 1 => Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Collate Transfer Documents
// 5 => Payment To The Liquor Board
// 6 => Transfer Logded
// 7 => Activation Fee Paid
// 8 => Transfer Issued
// 9 => Transfer Delivered
</script>

