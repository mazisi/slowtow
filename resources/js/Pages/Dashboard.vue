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
              <button style="float: right" class="btn btn-sm btn-primary" type="button" @click="resetFilter">Reset</button>
    
            </div>
    
           </div>
        
          <div class=" row mt-4">
            <div class="col-12 col-12 mt-4">
              <chart-holder-card
                title="Licences"
                color="">

              <div class="chart">
                <canvas id="lineChart" width="400" height="100"></canvas>
              </div>
              </chart-holder-card>
            </div>
          </div>


          <div class=" row mt-4">
            <div class="col-md-6 col-6 mt-4">
              <chart-holder-card
                title="Renewals"
                color="">

              <div class="chart">
                <canvas id="barGraph" width="400" height="170"></canvas>
              </div>
              <p class="text-center text-default">Renewals</p>
              </chart-holder-card>
            </div>

            <div class="col-md-6 col-6 mt-4">
              <chart-holder-card
                title="New Apps"
                color="">

              <div class="chart">
                <canvas id="polarArea" width="400" height="170"></canvas>
              </div>
              <p class="text-center text-default">New Apps</p>
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
  import { ref, onMounted, computed, onUnmounted , watch} from "vue";
  import Multiselect from '@vueform/multiselect';
  import common from "./common-js/common";
  import { Inertia } from "@inertiajs/inertia";
  import Chart from "chart.js/auto";
 
  
  export default {
    name: "dashboard",
    props: {
      years: Array,
      licences: Array,
      renewals: Array,
      tempLicences: Array
    },
    setup(props) {

      const types = [
        {id:0, name:'All'},
        {id:1, name:'New Licences'},
        {id:2, name:'Renewals'},
        {id:3, name:'Temporal Licence'},
    ]

    const form = useForm({
      year: new Date().getFullYear(),
      type: 0,
      province: 'Gauteng'
    })


        const resetFilter = () => {
        form.year = ''
        form.type = ''
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
            type: form.type
        },{
          replace: true,
          preserveState: true,
          onSuccess: () => {
            // console.log('Props changed:', props);
          }
        })
      }





      const lineChartInstance = ref(null);
      const barGraphInstance = ref(null);
      const polarGraphInstance = ref(null);

      const createBarChart = () => {
        const ctx = document.getElementById('barGraph').getContext('2d');
        barGraphInstance.value = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
          label: 'Renewals',
          data: Object.values(props.renewals),
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(255, 205, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(201, 203, 207, 0.2)'
          ],
          borderColor: [
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)',
            'rgb(201, 203, 207)'
          ],
          borderWidth: 1
        }]
    },
    options: {
        plugins: {
            legend: {
                labels: {
                    filter: function(item) {
                        // Hide the label 'Renewals'
                        return item.text !== 'Renewals';
                    }
                }
            }
        },
      }
  });
       };

const createLineChart = () => {
  const ctx = document.getElementById('lineChart').getContext('2d');
  lineChartInstance.value = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      datasets: [
        {
          label: 'New Licences',
          data: Object.values(props.licences),
          borderColor: 'rgba(76, 175, 80)',
          borderWidth: 2,
          pointRadius: 4,
          fill: true,
          tension: 0.4
        },
        {
          label: 'Temporal Licences',
          data: Object.values(props.tempLicences),
          borderColor: 'rgb(75, 192, 192)',
          borderWidth: 2,
          tension: 0.4,
          fill: false
        },
        {
          label: 'Renewals',
          color: 'white',
         data: Object.values(props.renewals),
          borderColor: 'rgba(233, 30, 99, 1)',
          borderWidth: 2,
          tension: 0.4,
          fill: false
        }
      ]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            color: 'white',
            stepSize: 10, // Adjust the step size as needed
            max: 150     // Adjust the max value as needed
          }
        },
        x: {
          ticks: {
            color: 'white'
          }
        }
      }
    }
  });
};


const createPolarChart = () => {
        const ctx = document.getElementById('polarArea').getContext('2d');
        polarGraphInstance.value = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
              label: 'New Apps',
              data: Object.values(props.licences),
              backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(75, 192, 192)',
                'rgb(255, 205, 86)',
                'rgb(201, 203, 207)',
                'rgb(54, 162, 235)'
              ]
        }]
    },
    options: {
        plugins: {
            legend: {
                labels: {
                    filter: function(item) {
                        return item.text !== 'New Apps';
                    }
                }
            }
        },
      }
  });
       };

onMounted(() => {
  createLineChart();
  createBarChart();
  createPolarChart();
});

onUnmounted(() => {
  if (lineChartInstance.value) {
    lineChartInstance.value.destroy();
    barGraphInstance.value.destroy();
  }
});

watch(() => [props.licences, props.tempLicences, props.renewals], () => {
    if (lineChartInstance.value) {
      lineChartInstance.value.destroy();
      barGraphInstance.value.destroy();
      polarGraphInstance.value.destroy();
    }
    createLineChart();
    createBarChart();
    createPolarChart();
  },
  { deep: true }
);

      return {
        types,
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