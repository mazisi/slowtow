<template>
    <Layout>
        <div class="container-fluid">
            <Banner/>
            <div class="card card-body mx-3 mx-md-4 mt-n6">
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                            <h5>Appointment of managers for: <Link @click="redirect(nominations[0].licence)" href="#!" class="text-success" v-if="nominations.length > 0">
                                {{ nominations[0].licence.trading_name ? nominations[0].licence.trading_name : '' }}</Link></h5>
                        </div>
                        <div class="col-lg-6 col-5 my-auto text-end">
                            <div class="dropdown float-lg-end pe-4">
                                <Link :href="`/nominate?slug=${$page.props.slug}`" class="btn btn-sm btn-secondary">New Appointment of managers</Link>
                            </div>
                        </div>
                    </div>
                    <div class=" my-4">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Full Name
                                    </th>
                                    <!-- <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Relationship
                                    </th> -->
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


                                </tr>
                                </thead>
                                <tbody v-if="$props.nominations.length > 0">
                                <tr v-for="people in nominations[0].people" :key="people.id">
                                    <td>
                                        <div class="d-flex px-2 py-1">

                                            <div class="d-flex flex-column justify-content-left">
                                                <inertia-link :href="`/view-nomination/${nominations[0].slug}`">
                                                    <h6 class="mb-0 text-sm">
                                                        {{ people.full_name }}
                                                    </h6>
                                                </inertia-link>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- <td class="text-center">
                                    {{ people.pivot.relationship }}
                                    </td> -->
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

                                </tr>


                                </tbody>
                                <tbody v-else>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>No Appointment of managers found</td>
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
import { Inertia } from '@inertiajs/inertia';
import Banner from '../components/Banner.vue'
export default {
    name: "dashboard-default",
    props: ['nominations'],

    setup() {
        const redirect = (licence) => {
          let url = '';
        if(licence.type == 'retail'){
           url = `/view-licence?slug=${licence.slug}`
        }else{
           url = `/view-wholesale-licence?slug=${licence.slug}`
        }
          Inertia.get(url);
        }
        return{
            redirect
        }
    },

    components: {
        Layout,
        Link,
        Banner
    }

};
</script>

