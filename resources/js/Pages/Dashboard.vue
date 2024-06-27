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
                  v-model="newAppsForm.province"
                  @select="filter('New-Apps')"
                  :taggable="true"
                  placeholder="Filter By Province"
                />
              </div>
            </div>
            <div class="col-3">
              <div class="input-group input-group-outline null is-filled">
                <Multiselect
                  :options="years"
                  v-model="newAppsForm.year"
                  @select="filter('New-Apps')"
                  :taggable="true"
                  placeholder="Filter By Year"
                />
              </div>
            </div>
            <div class="col-3">
              <button style="float: right" class="btn btn-sm btn-primary" type="button" @click="resetFilter('New-Apps')">Reset</button>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-md-12 col-12 mt-4">
              <chart-holder-card title="New Licences" color="">
                <div class="chart">
                  <canvas id="newAppsGraph" width="400" height="120"></canvas>
                </div>
                <p class="text-center text-default">New Licences</p>
              </chart-holder-card>
            </div>
            <div class="row mt-4">
              <div class="col-3">
                <div class="input-group input-group-outline null is-filled">
                  <Multiselect
                    :options="computedProvinces"
                    v-model="renewalForm.province"
                    @select="filter('Renewals')"
                    :taggable="true"
                    placeholder="Filter By Province"
                  />
                </div>
              </div>
              <div class="col-3">
                <div class="input-group input-group-outline null is-filled">
                  <Multiselect
                    :options="years"
                    v-model="renewalForm.year"
                    @select="filter('Renewals')"
                    :taggable="true"
                    placeholder="Filter By Year"
                  />
                </div>
              </div>
              <div class="col-3">
                <button style="float: right" class="btn btn-sm btn-primary" type="button" @click="resetFilter('Renewals')">Reset</button>
              </div>
            </div>
            <div class="col-md-12 col-12 mt-4">
              <chart-holder-card title="Renewals" color="">
                <div class="chart">
                  <canvas id="renewals" width="400" height="120"></canvas>
                </div>
                <p class="text-center text-default">Renewals</p>
              </chart-holder-card>
            </div>
            <div class="col-md-12 col-12 mt-4">
              <div class="row mt-4">
                <div class="col-9">
                  <div class="input-group input-group-outline null is-filled">
                    <Multiselect
                      :options="years"
                      v-model="tempLicenceForm.year"
                      @select="filter('Temp')"
                      :taggable="true"
                      placeholder="Filter By Year"
                    />
                  </div>
                </div>
                <div class="col-3">
                  <button style="float: right" class="btn btn-sm btn-primary" type="button" @click="resetFilter('Temp')">Reset</button>
                </div>
              </div>
              <chart-holder-card title="New Apps" color="">
                <div class="chart">
                  <canvas id="temps" width="400" height="120"></canvas>
                </div>
                <p class="text-center text-default">Temporal Licences</p>
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
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { ref, onMounted, computed, onUnmounted, watch } from "vue";
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
    const newAppsForm = useForm({
      year: '',
      province: 'All Provinces',
      type: 'New-Apps'
    });

    const renewalForm = useForm({
      year: '',
      province: '',
      type: 'renewals'
    });

    const tempLicenceForm = useForm({
      year: '',
      type: 'New-Apps'
    });

    const filter = (type) => {
      switch (type) {
        case 'New-Apps':
          Inertia.get('/slotow-admin-dashboard', {
            province: newAppsForm.province,
            year: newAppsForm.year,
            type: newAppsForm.type
          }, {
            replace: true,
            preserveState: true,
          });
          break;
        case 'Renewals':
          Inertia.get('/slotow-admin-dashboard', {
            province: renewalForm.province,
            year: renewalForm.year,
            type: 'renewals'
          }, {
            replace: true,
            preserveState: true,
          });
          break;
        case 'Temp':
          Inertia.get('/slotow-admin-dashboard', {
            year: tempLicenceForm.year,
            type: 'temps'
          }, {
            replace: true,
            preserveState: true,
          });
          break;
        default:
          break;
      }
    };

    const resetFilter = (type) => {
      
      switch (type) {
        case 'New-Apps':
          newAppsForm.year = '';
          newAppsForm.type = 'New-Apps';
          newAppsForm.province = 'All Provinces';
          break;
        case 'Renewals':
          renewalForm.year = '';
          renewalForm.type = 'Renewals';
          renewalForm.province = 'All Provinces';
          break;
        case 'Temp':
          tempLicenceForm.year = '';
          tempLicenceForm.type = 'New-Apps';
          tempLicenceForm.province = 'All Provinces';
          break;
        default:
          break;
      }
    };

    const computedProvinces = computed(() => {
      return common.getProvinces();
    });

    const newAppsGraphInstance = ref(null);
    const renewalGraphInstance = ref(null);
    const tempsGraphInstance = ref(null);

    const createNewAppsGraph = () => {
      const ctx = document.getElementById('newAppsGraph').getContext('2d');
      if (newAppsGraphInstance.value) newAppsGraphInstance.value.destroy();
      newAppsGraphInstance.value = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          datasets: [{
            label: 'New Apps',
            data: Object.values(props.licences),
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
                  return item.text !== 'New Apps';
                }
              }
            }
          },
        }
      });
    };

    const createRenewalChart = () => {
      const ctx = document.getElementById('renewals').getContext('2d');
      if (renewalGraphInstance.value) renewalGraphInstance.value.destroy();
      renewalGraphInstance.value = new Chart(ctx, {
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
                  return item.text !== 'Renewals';
                }
              }
            }
          },
        }
      });
    };

    const createTempsChart = () => {
      const ctx = document.getElementById('temps').getContext('2d');
      if (tempsGraphInstance.value) tempsGraphInstance.value.destroy();
      tempsGraphInstance.value = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          datasets: [{
            label: 'Temporal Licences',
            data: Object.values(props.tempLicences),
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
                  return item.text !== 'Temporal Licences';
                }
              }
            }
          },
        }
      });
    };

    onMounted(() => {
      createRenewalChart();
      createTempsChart();
      createNewAppsGraph();
    });

    onUnmounted(() => {
      if (newAppsGraphInstance.value) newAppsGraphInstance.value.destroy();
      if (renewalGraphInstance.value) renewalGraphInstance.value.destroy();
      if (tempsGraphInstance.value) tempsGraphInstance.value.destroy();
    });

    watch(() => [props.licences, props.tempLicences, props.renewals], () => {
      if (newAppsGraphInstance.value) newAppsGraphInstance.value.destroy();
      if (renewalGraphInstance.value) renewalGraphInstance.value.destroy();
      if (tempsGraphInstance.value) tempsGraphInstance.value.destroy();
      createRenewalChart();
      createTempsChart();
      createNewAppsGraph();
    }, { deep: true });

    return {
      newAppsForm,
      renewalForm,
      tempLicenceForm,
      computedProvinces,
      resetFilter,
      filter
    };
  },
  components: {
    ChartHolderCard,
    Layout,
    Head,
    Multiselect
  },
};
</script>
<style src="@vueform/multiselect/themes/default.css"></style>