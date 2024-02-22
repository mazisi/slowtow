<script>
import Layout from "../../../Shared/Layout.vue";
import { Head, Link } from '@inertiajs/inertia-vue3';
import Banner from '../../components/Banner.vue';
import { Inertia } from '@inertiajs/inertia'
import 'vue3-toastify/dist/index.css';
import useAlteration from "../../Alterations/composables/useAlteration";

export default {
    name: "Alterations",
    
    props: {
        errors: Object,
        licence_dropdowns: Object,
        licence: Object,
        alterations: Object,
        success: String,
        error: String,
    },

    data() {
        const { getBadgeStatus } = useAlteration();

        Inertia.on('navigate', (event) => {
          Inertia.visit(`${event.detail.page.url}`,{ preserveState: true, preserveScroll: true})
    })

        return {
            form: {
                alter_date: null,
                alter_status: null
            },
            showMenu: false,
            getBadgeStatus,
            
        };
    },
    components: {
        Layout,
        Link,
        Head,
        Banner,
    },

    methods: {

        limit(string = '', limit = 35) {
            if (string) {
                if (string.length >= limit) {
                    return string.substring(0, limit) + '...'
                }
                return string.substring(0, limit)
            }
        },
        

        getStatus(status_param) {
            return this.getBadgeStatus(status_param);
        },

    },



    beforeUnmount() {
        this.$store.state.isAbsolute = false;
    },
};
</script>
<style>
#active-checkbox {
    margin-top: 3px;
    margin-left: 3px;
}

.columns {
    margin-bottom: 1rem;
}
</style>

<template>
    <Layout>
    
        <Head title="Alterations" />
    
        <div class="container-fluid">
    
            <Banner/>
    
            <div class="card card-body mx-3 mx-md-4 mt-n6">
    
                <div class="row gx-4">
    
    
    
                    <div class="row">
    
                        <div class="col-lg-10 col-10">
    
                            <h6 class="mb-1">Alterations for:
    
                                <Link :href="`/company/view-my-licences?slug=${licence.slug}`">
    
                                <span class="text-success">{{ licence.trading_name ? licence.trading_name: '' }}</span></Link>
    
                            </h6>
    
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
    
                                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alteration Date</th>
    
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Stage</th>
    
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
    
                                                </tr>
    
                                            </thead>
    
                                            <tbody>
    
                                                <tr v-if="alterations?.data.length > 0" 
                                                    v-for="alter in alterations.data" :key="alter.id">
    
    
    
                                                    <td class="text-sm">
    
                                                        <Link :href="`/company/view-company-alteration/${alter.slug}`" style="margin-left: 12px;"> {{ alter.logded_at }}
    
                                                        </Link>
    
                                                    </td>
    
    
    
                                                    <td class="text-sm text-center">
    
                                                        <Link :href="`/company/view-company-alteration/${alter.slug}`">
    
    
    
                                                        <div v-html="getStatus(alter.status)"></div>
    
    
    
                                                        </Link>
    
                                                    </td>
    
    
    
                                                    <td class="text-sm text-center">
    
                                                        <Link :href="`/company/view-company-alteration/${alter.slug}`"><i class="fa fa-eye px-1 text-secondary" aria-hidden="true"></i></Link>
    
    
    
                                                    </td>
    
                                                </tr>

                                                <tr v-else>
                                                    <td colspan="6" class="text-center text-danger">No alterations Found.</td>
                                                </tr>
    
                                            </tbody>
    
                                        </table>
    
                                    </div>
    
    
    
                                </div>
    
    
    
    
                            </div>
    
                        </div>
    
    
    
                    </div>
    
    
    
                </div>
    
            </div>
    
        </div>
    
    
    
    
    
    </Layout>
</template>../Renewals/useAlteration