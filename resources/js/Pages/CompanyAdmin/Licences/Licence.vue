<script>
import Layout from "../../../Shared/Layout.vue";
import { Link, useForm, Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'
import { defineAsyncComponent, ref, watch, computed } from 'vue';
import  common from '../../common-js/common.js';
import Banner from '../../components/Banner.vue';
import useMonths from "@/store/useMonths";

const Paginate = defineAsyncComponent(() =>
  import('../../../Shared/Paginate.vue')
);

export default {
  props: {
    licences: Object,
    success: String,
    error: String,
    errors: Object,
    all_licence_types: Object
  },

  components: {
    Layout,
    Link,
    Head,
    Banner,
    Paginate
  },

  setup(props) {
    const [search_query, status, licence_type, licence_date, province] = getUrlParam();
    const { months } = useMonths();

    const term = search_query ? search_query : ref('');
    const licenceByProvince = ref([]);

    const form = useForm({
      term: term,
      active_status: status ? status : '',
      licence_type: licence_type ? licence_type : '',
      licence_date: licence_date ? licence_date : '',
      province: province ? province : ''
    });

    function limit(string = '', limit = 25) {
      if (string !== '') {
        if (string.length >= limit) {
          return string.substring(0, limit) + '...';
        }
        return string.substring(0, limit);
      }
    }

    function search() {
      form.get(`/company/licences`, {
        replace: true,
        preserveState: true,
        onSuccess: () => {
          filterLicenceTypes();
        },
      });
    }

    function filterLicenceTypes() {
      
      licenceByProvince.value = props.all_licence_types
        .filter(obj => obj.province === form.province);

      console.log(licenceByProvince);
    }

  const redirect = (licence) => {
    let url = '';
    if(licence.type == 'retail'){
     url = `/company/view-my-licences/${licence.slug}`
    }else{
     url = `/company/view-my-wholesale-licences/${licence.slug}?company=1`;
    }
    Inertia.get(url);
  }

    watch(term, _.debounce(function (value) {
      Inertia.get('/licences', {
        term: value,
        active_status: form.active_status,
        licence_type: form.licence_type,
        licence_date: form.licence_date,
        province: form.province
      }, { preserveState: true, replace: true });
    }, 1000));

    const computedProvinces = computed(() => {
      return common.getProvinces();
    });

    Inertia.on('navigate', (event) => {
      Inertia.visit(`${event.detail.page.url}`, { preserveState: true, preserveScroll: true });
    });

    function getUrlParam() {
      const urlParams = new URLSearchParams(window.location.search);
      const search_query = urlParams.get('term');
      const status = urlParams.get('active_status');
      const licence_type = urlParams.get('licence_type');
      const licence_date = urlParams.get('licence_date');
      const province = urlParams.get('province');
      return [
        search_query,
        status,
        licence_type,
        licence_date,
        province
      ];
    }

    return {
      months,
      limit,
      computedProvinces,
      form,
      term,
      search,
      redirect,
      getUrlParam,
      filterLicenceTypes,
      licenceByProvince,
    }
  },
}

</script>

<template>
<Layout>
<div class="container-fluid">
<Banner/>
<div class="card card-body mt-n6">
  <div class="col-12">
      <div class="row">
          <div  class="col-md-12 col-xl-12 col-lg-12">
              <div class="input-group input-group-outline null is-filled">
                  <i class="fa fa-search h4"></i>&nbsp;&nbsp;&nbsp;
                  <input v-model="term" placeholder="Search" type="text" class="form-control form-control-default" :autofocus="true">
              </div>
          </div>

          <div class="col-md-3 col-xl-3 col-lg-3 filters">
              <div class="input-group input-group-outline null is-filled">
                  <select @change="search" v-model="form.active_status" class="form-control form-control-default centered-select">
                      <option :value="''" disabled selected>Active/Inactive Status</option>
                      <option value="All">All</option>
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                  </select>

              </div>
          </div>

          <div class="col-md-3 col-xl-3 col-lg-3 filters">
              <div class="input-group input-group-outline null is-filled">
                  <select @change="search" v-model="form.province" class="form-control form-control-default centered-select" >
                      <option :value="''" disabled selected>Province</option>
                      <option v-for="province in computedProvinces" :key="province" :value=province>{{ province }}</option>
                  </select>
              </div>
          </div>

          <div v-if="form.province" class="col-md-3 col-xl-3 col-lg-3 filters">
              <div class="input-group input-group-outline null is-filled">
                  <select @change="search" v-model="form.licence_type" class="form-control form-control-default centered-select centered-select">
                      <option :value="''" disabled selected>Licence Type</option>
                      <option v-for='licence_type in licenceByProvince' :value=licence_type.id> {{ licence_type.licence_type }}</option>
                  </select>
              </div>
          </div>

          <div class="col-md-3 col-xl-3 col-lg-3 filters">
              <div class="input-group input-group-outline null is-filled">
                  <select @change="search" v-model="form.licence_date" class="form-control form-control-default centered-select">
                      <option :value="''" disabled selected>Licence Date</option>
                      <option v-for="month in months" :value="month.id" :key="month.id">{{ month.name }}</option>
                      
                  </select>
              </div>
          </div>

      </div>
      <div class="my-4">

          <div class="px-0 pb-2">
              <div class="table-responsive p-0">
                  <table class="table table-striped table-hover" style="font-size:85%; width: 100%">
                      <thead>
                      <tr class="text" style="font-size: 16px">
                          <th class="w-10">Act</th>
                          <th class="ps-2 text-center">Trading Name</th>
                          <th class="ps-2 text-center">Licence Number</th>
                          <th class="ps-2 text-center">Licence Date</th>
                          <th class="ps-2 text-center">Licence Type</th>
                          <th class="ps-2 text-center">Company</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr v-if="licences.data?.length > 0" 
                          v-for="licence in licences.data" :key="licence.id">
                          <td v-if="licence.is_licence_active == '1'"><i class="fa fa-check text-success" aria-hidden="true"></i></td>
                          <td v-else><i class="fa fa-times text-danger" aria-hidden="true"></i></td>
                          <td><Link @click="redirect(licence)" data-bs-placement="top" :title="licence.trading_name">{{ licence.trading_name }}</Link></td>
                          <td><Link @click="redirect(licence)" data-bs-placement="top" :title="licence.licence_number ? licence.licence_number : '' ">{{ licence.licence_number ? licence.licence_number : ''}}</Link></td>
                          <td><Link @click="redirect(licence)" data-bs-placement="top" :title="licence.licence_date">{{ licence.licence_date }}</Link></td>
                          <td><Link @click="redirect(licence)" data-bs-placement="top" :title="licence.licence_type.licence_type">{{ licence.licence_type ? licence.licence_type.licence_type : '' }}</Link></td>
                          <td><Link @click="redirect(licence)">{{ licence.belongs_to === 'Individual' ? licence.people.full_name :licence.company.name }}</Link></td>

                      </tr>
                      <tr v-else>
                          <td colspan="6" class="text-center text-danger">No Licences Found.</td>
                      </tr>
                      </tbody>
                  </table>


                  <Paginate v-if="licences.data?.length > 0"
                      :modelName="licences"
                      :modelType="Licences"
                  />

              </div>
          </div>

      </div>
  </div>
</div>
</div>
</Layout>
</template>

<style scoped>
 .filters{
 margin-top: 10px;
}
  .table thead th {
    padding: 0;
    }
 
    #with-thrashed{
      margin-top: 3px;
      margin-left: 3px;
    }
    
</style>