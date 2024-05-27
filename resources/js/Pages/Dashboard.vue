<template>
  <Layout>
    <Head title="Dashboard" />
    <div class="py-4 container-fluid mt-2">
      <div class="row mb-4">
        <div class="col-lg-12 position-relative z-index-2">
          <div class="row">
            <div class="col-3">
              <div class="input-group input-group-outline null is-filled">
              <Multiselect                
              :options="computedProvinces"
               v-model="form.province"
               @select="filter"
              :taggable="true"
              placeholder="Filter By Province"/>
              </div>
    
            </div>
    
            <div class="col-3">
              <div class="input-group input-group-outline null is-filled">
              <Multiselect                
              :options="years"
               v-model="form.year"
               @select="filter"
              :taggable="true"
              placeholder="Filter By Year"/>
              </div>
    
            </div>
    
            <div class="col-3">
              <div class="input-group input-group-outline null is-filled">
              <select @change="filter" class="form-control form-control-default is-filled" v-model="form.month">
                <option :value="''" disabled :selected="true">Filter By Month</option>
                <option v-for="month in months" :key="month.id" :value="month.id">{{ month.name }}</option>
              </select>
              </div>
    
            </div>
            <div class="col-3">
              <button class="btn btn-sm btn-primary" type="button" @click="resetFilter">Reset</button>
    
            </div>
    
           </div>
        
          <div class=" row mt-4">
            <div class="col-12 col-12 mt-4">
              <chart-holder-card
                title="Licences"
                color="success"
              >
                <reports-line-chart
                  :chart="{
                    labels: [
                      'Jan',
                      'Feb',
                      'Mar',
                      'Apr',
                      'May',
                      'Jun',
                      'Jul',
                      'Aug',
                      'Sep',
                      'Oct',
                      'Nov',
                      'Dec',
                    ],
                    datasets: {
                      label: 'Licences',
                      licences: licences,
                      tempLicences: tempLicences,
                      renewals: renewals,
                      data: licences,
                    },
                  }"
                />
              </chart-holder-card>
            </div>
          </div>
        </div>
      </div>
  
     
    </div>
    </Layout>
  </template>
  <script>
  import Layout from "../Shared/Layout.vue";
  import ChartHolderCard from "./components/ChartHolderCard.vue";
  import ReportsBarChart from "@/examples/Charts/ReportsBarChart.vue";
  import ReportsLineChart from "@/examples/Charts/ReportsLineChart.vue";
  import MiniStatisticsCard from "./components/MiniStatisticsCard.vue";
  import { Link, Head,useForm } from '@inertiajs/inertia-vue3';
  import { ref, onMounted, computed, reactive } from "vue";
  import Multiselect from '@vueform/multiselect';
  import common from "./common-js/common";
  import { Inertia } from "@inertiajs/inertia";
 
  
  export default {
    name: "dashboard",
    props: {
      years: Array,
      licences: Array,
      renewals: Array,
      tempLicences: Array
    },
    setup(props) {

      const months = [
        {id:1, name:'January'},
        {id:2, name:'February'},
        {id:3, name:'March'},
        {id:4, name:'April'},
        {id:5, name:'May'},
        {id:6, name:'June'},
        {id:7, name:'July'},
        {id:8, name:'August'},
        {id:9, name:'September'},
        {id:10, name:'October'},
        {id:11, name:'November'},
        {id:12, name:'December'},
    ]

    const form = useForm({
      year: new Date().getFullYear(),
      month: new Date().getMonth() + 1,
      province: 'Gauteng'
    })


        const resetFilter = () => {
        form.year = ''
        form.month = ''
        form.province = ''
        Inertia.get('/slotow-admin-dashboard', {
          
        })

      }

      const computedProvinces = computed(() => {
        return common.getProvinces();
      })


      
      const filter = () => {
        Inertia.get('/slotow-admin-dashboard', {
            province: form.province,
            year: form.year,
            month: form.month
        },{
          replace: true,
          preserveState: true,
          onSuccess: () => {
            // console.log('Props changed:', props);
          }
        })
      }

      return {
        months,
        form,
        computedProvinces,
        resetFilter,
        filter
      };
    },
  
    components: {
      ChartHolderCard,
      ReportsBarChart,
      ReportsLineChart,
      MiniStatisticsCard,
      Layout,
      Link,
      Multiselect,
      Head
  },
  };
  </script>
      <style src="@vueform/multiselect/themes/default.css"></style>