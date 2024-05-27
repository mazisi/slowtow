<template>
    <div class="chart">
      <canvas :id="id" width="400" height="150"></canvas>
    </div>
  </template>
  <script>
  import Chart from "chart.js/auto";
  import { Inertia } from "@inertiajs/inertia";
  import { onMounted } from 'vue'
  
  export default {
    name: "ReportsLineChart",
    props: {
      id: {
        type: String,
        default: "line-chart",
      },
      height: {
        type: [Number, String],
        default: "570",
      },
      chart: {
        type: Object,
        required: true,
        labels: Array,
        datasets: {
          type: Object,
          label: String,
          data: Array,
        },
      },
    },

    setup(props) {

      Inertia.on('success', (event) => {
        console.log(`Inertia props: ${event.detail}`)
      })

      onMounted(() => {
        alert('hello')
        var ctx = document.getElementById(props.id).getContext("2d");
  
  let chartStatus = Chart.getChart(props.id);
  if (chartStatus != undefined) {
    chartStatus.destroy();
  }
  console.log('props', props)
  const licences = Object.values(props.chart.datasets.licences);
  const renewals = Object.values(props.chart.datasets.renewals);
  const tempLicences = Object.values(props.chart.datasets.tempLicences);
  console.log('licences', props.chart.datasets.licences)
  console.log('renewals', props.chart.datasets.renewals)
  console.log('Temps', props.chart.datasets.tempLicences)
 
    new Chart(ctx, {
      
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            
            datasets: [
                {
                    label: 'New Licences',
                    data: licences,
                    borderColor: 'rgba(255, 255, 255, .8)',
                    borderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Temporal Licences',
                    data: tempLicences,
                    borderColor: 'rgb(255, 25, 192)',
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Renewals',
                    color: 'white',
                    data: renewals,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
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
        });




      
    return{

    }
  }
  };
  </script>