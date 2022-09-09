<template>
<Layout>
  <div class="container-fluid">
    <div class="row mb-4">
      <div class="col-lg-12 position-relative z-index-2">
      <h5>Reports</h5>
      <a href="/renewal-export">Export</a>
        <div class="row">
          <div class="col-lg-6 col-md-6 mt-4 mb-4">
            <chart-holder-card
              title="Renewals"
              subtitle="Last Campaign Performance"
              update="Last Licence Renewed 2 days ago"
            >
              <reports-bar-chart
                :chart="{
                  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sep','Oct','Nov','Dec'],
                  datasets: {
                    label: 'Renewals',
                    data: [50, 20, 10, 22, 50, 10, 40, 50,55,15,25,70],
                  },
                }"
              />
            </chart-holder-card>
          </div>
          <div class="col-lg-6 col-md-6 mt-4">
            <chart-holder-card
              title="Daily Sales"
              subtitle="(<span class='font-weight-bolder'>+15%</span>) increase in today sales."
              update="updated 4 min ago"
              color="success"
            >
              <reports-line-chart
                :chart="{
                  labels: [
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
                    label: 'Mobile apps',
                    data: [50, 40, 300, 320, 500, 350, 200, 230, 500],
                  },
                }"
              />
            </chart-holder-card>
          </div>
          <div class="col-lg-6 mt-4">
            <chart-holder-card
              title="Completed Tasks"
              subtitle="Last Campaign Performance"
              update="just updated"
              color="dark"
            >
              <reports-line-chart
                id="tasks-chart"
                :chart="{
                  labels: [
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
                    label: 'Mobile apps',
                    data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
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
export default {
  name: "dashboard-default",
  data() {
    return {
    };
  },
  components: {
    ChartHolderCard,
    ReportsBarChart,
    ReportsLineChart,
    MiniStatisticsCard,
    Layout
},
methods: {
  exportt(){
    this.$inertia.post('export')
  }
}
};
</script>
