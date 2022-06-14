<template>
<Layout>
  <div class="container-fluid">
    <div class="page-header min-height-100 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      ">
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="col-12">
         <div class="row">
  <div class="col-lg-6 col-7">
   <h5>Nominees for: <span v-if="nominations.length > 0">{{ nominations[0].licence.trading_name }}</span></h5>
  </div>
  <div class="col-lg-6 col-5 my-auto text-end">
    <div class="dropdown float-lg-end pe-4">
     <Link :href="`/nominate?slug=${$page.props.slug}`" class="btn btn-sm btn-secondary">New Nomination</Link>
    </div>
  </div>
</div>
        <div class=" my-4">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                      ID
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                    Full Name
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Relationship
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Cell Number
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Certified Id
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Fingerprints
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    SAPS Clearance
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Effective Date
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Terminated
                    </th>

                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Terminated Date
                    </th>

                    <!-- <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Action
                    </th> -->
                 
                  </tr>
                </thead>
                <tbody v-if="$props.nominations.length > 0">
                  <tr v-for="people in nominations[0].people" :key="people.id">
                    <td class="align-middle text-sm"><div class="d-flex px-2 py-1">{{ people.id }}</div></td>
                    <td>
                      <div class="d-flex px-2 py-1">
                       
                        <div class="d-flex flex-column justify-content-left">
                        <inertia-link :href="`/view-nomination/${people.slug}`">
                          <h6 class="mb-0 text-sm">
                         {{ people.name }} {{ people.initials }} {{ people.surname }}
                           </h6>    
                           </inertia-link>                      
                        </div>
                      </div>
                    </td>
                    <td class="text-center">
                    {{ people.pivot.relationship }}
                    </td>
                     <td class="text-center">
                    {{ people.cell_number }}
                    </td>
                     <td class="text-center">{{ people.valid_certified_id }}</td>
                     <td class="text-center">{{ people.valid_fingerprint }}</td>
                     <td class="text-center">{{ people.valid_saps_clearance }}</td>
                     <td class="text-center">{{ new Date(people.created_at).toLocaleString().split(',')[0] }}</td>
                     <td class="text-center">
                     <i v-if="people.pivot.terminated_at" class="fa fa-check text-success" aria-hidden="true"></i>
                     <i v-else class="fa fa-times text-danger" aria-hidden="true"></i>
                     </td>
                     <td class="text-center">
                     <span v-if="people.pivot.terminated_at">{{ new Date(people.pivot.terminated_at).toLocaleString().split(',')[0] }}</span>
                     </td>
                     <!-- <td class="text-center">
                      <inertia-link :href="`/view-company/${people.pivot.slug}`"><i class="fa fa-eye  " aria-hidden="true"></i></inertia-link>
                      </td> -->
                  </tr>
                  
                 
                </tbody>
                <tbody v-else>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>No nominations found</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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

export default {
  name: "dashboard-default",
  props: ['nominations'],
  
  components: {
    Layout,
    Link
    },

};
</script>

