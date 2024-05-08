<template>
  <Layout>
    <Head title="Dashboard" />
    <div class="py-5 container-fluid">
     
  
      <div class="row ">
        <Link :href="`/licences`" class="col-xl-3 col-lg-3">
            <div class="card l-bg-cherry">
              
            </div>
          </Link>
          <Link :href="`/companies`"  class="col-xl-3 col-lg-3">
            <div class="card l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-building"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Companies</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center qty mb-0">
                                1500
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span>9.23% <i class="fa fa-arrow-up"></i></span>
                        </div>
                    </div>
                    <div class="progress mt-1 " data-height="8" style="height: 8px;">
                        <div class="progress-bar l-bg-green" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                    </div>
                </div>
            </div>
          </Link>
        <Link :href="`/people`" class="col-xl-3 col-lg-3">
            <div class="card l-bg-green-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">People</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0 qty">
                                578
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span>10% <i class="fa fa-arrow-up"></i></span>
                        </div>
                    </div>
                    <div class="progress mt-1 " data-height="8" style="height: 8px;">
                        <div class="progress-bar l-bg-orange" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                    </div>
                </div>
            </div>
          </Link>
        <Link :href="`/temp-licences`" class="col-xl-3 col-lg-3">
            <div class="card l-bg-orange-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-clock"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Temporary Licences</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0 qty">
                                110
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span>2.5% <i class="fa fa-arrow-up"></i></span>
                        </div>
                    </div>
                    <div class="progress mt-1 " data-height="8" style="height: 8px;">
                        <div class="progress-bar l-bg-cyan" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                    </div>
                </div>
            </div>
          </Link>
    </div>
    </div>
    </Layout>
  </template>
  <script>
  import Layout from "../Shared/Layout.vue";
  import ChartHolderCard from "./components/ChartHolderCard.vue";
  import MiniStatisticsCard from "./components/MiniStatisticsCard.vue";
  import { Link, Head } from '@inertiajs/inertia-vue3';
  import { Bar } from 'vue-chartjs'
  import * as chartConfig from './dashboard.js'

  ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

  import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js'
  
  export default {
    name: "dashboard",
  
    
    props: {
      licences: Number,
      companies: Number,
      people: Number,
      temp_licences: Number,
      count_group_licences: Object,
      jan: Number,
      feb: Number,
      mar: Number,
      apr: Number,
      may: Number,
      jun: Number,
      jul: Number,
      aug: Number,
      sep: Number,
      oct: Number,
      nov:Number,
      dec: Number
    },
    data() {
      return {
        chartConfig
      };
    },
  
    methods: {
      
    },
  
    mounted() {
    },
    components: {
      ChartHolderCard,
      MiniStatisticsCard,
      Layout,
      Link,
      Bar,
      Head
  },
  };
  </script>
