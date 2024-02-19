<template>
    <Layout>
        <Head title="Renewals" />
        <div class="container-fluid">
            <Banner/>
            <div class="card card-body mx-3 mx-md-4 mt-n6">
                <div class="row gx-4">
                    <div class="col-auto">

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <h6 class="mb-1">
                                <Link :href="`/company/view-my-licences/${licence.slug}`" class="text-success">{{ licence.trading_name }}</Link>
                                Renewals</h6>
                        </div>



                    </div>


                </div>
                <div class="row">

                    <div class="mt-3 row">
                        <div class="col-12 col-md-6 col-xl-12 position-relative">
                            <div class="card card-plain h-100">
                                <div class="p-3 card-body">

                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                            <tr>
                                                <th class="ps-2 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Renewal Year</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Renewal Status</th>
                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">View</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-if="renewals.data?.length > 0" v-for="renewal in renewals.data" :key="renewal.id">
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <Link :href="`/company/view-my-licence-renewal/${renewal.slug}`"><h6 class="text-center">{{ renewal.date }}</h6></Link>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="align-middle text-center text-sm">
                                                    <Link :href="`/company/view-my-licence-renewal/${renewal.slug}`">
                                                        <span class="badge text-default" v-html="getStatus(renewal.status)"></span>
                                                    </Link>
                                                </td>
                                                <td class="align-middle text-end" >
                                                    <div class="d-flex justify-content-center">
                                                        <Link :href="`/company/view-my-licence-renewal/${renewal.slug}`"><i class="fa fa-eye px-1 text-secondary" aria-hidden="true"></i></Link>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr v-else>
                                                <td colspan="6" class="text-center text-danger">No renewals found.</td>
                                            </tr>

                                            </tbody>
                                        </table>

                                    </div>
                                    <Paginate v-if="renewals.data?.length > 0"
                                        :modelName="renewals"
                                        :modelType="Renewals"
                                    />
                                </div>
                            </div>
                            <hr class="vertical dark" />
                        </div>

                    <!--  -->
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
import { Inertia } from '@inertiajs/inertia';
import Banner from '../../components/Banner.vue';
import Multiselect from '@vueform/multiselect';
import Paginate from "@/Shared/Paginate.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { onMounted } from 'vue';
import useToaster from '../../../store/useToaster'
import useRenewals from '../../Renewals/useRenewals'


import { ref } from 'vue';

export default {
    props: {
        errors: Object,
        licence_dropdowns: Object,
        licence: Object,
        success: String,
        error: String,
        renewals: Object,
        years: Object
    },

    setup (props) {
        const year = ref(new Date().getFullYear());
        let years = props.years;
        const { notifySuccess, notifyError } = useToaster();
        const { getBadgeStatus } = useRenewals();


        const form = useForm({
            year: null,
            licence_id: props.licence.id
        })

        Inertia.on('navigate', (event) => {
          Inertia.visit(`${event.detail.page.url}`,{ preserveState: true, preserveScroll: true})
    })



        function limit(string='', limit = 25) {
            if(string){
                if(string.length >= limit){
                    return string.substring(0, limit) + '...'
                }
                return string.substring(0, limit)
            }
        }

        function getStatus(status) {
            return getBadgeStatus(status);
          }

     

        return { year,years,form, limit,toast,getStatus }
    },
    components: {
        Layout,
        Link,
        Head,
        Multiselect,
        Banner,
        Paginate
    },

};
</script>
