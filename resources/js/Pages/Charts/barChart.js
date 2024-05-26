import Layout from "../../Shared/Layout.vue";
import { Link, Head, useForm } from '@inertiajs/inertia-vue3';
import Chart from 'primevue/chart';
import Steps from "primevue/steps";
import common from "../common-js/common";
import Multiselect from '@vueform/multiselect';
import 'primevue/resources/themes/aura-light-green/theme.css'
import { ref, onMounted, computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
export default {
  name: "Dashboard",
  
  props: {
    years: Object,
    licences: Object,
    renewals: Object,
    tempLicences: Object
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

    
    const chartData = ref();
    const chartOptions = ref();

    onMounted(() => {
      chartData.value = setChartData();
      chartOptions.value = setChartOptions();
    });

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
            chartData.value = setChartData();
            chartOptions.value = setChartOptions();
          }
        })
      }

      const resetFilter = () => {
        form.year = ''
        form.month = ''
        form.province = ''
        Inertia.get('/slotow-admin-dashboard', {
          
        })

      }
  
  const setChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [
            {
                label: 'New Licences',
                data: Object.values(props.licences),
                fill: false,
                tension: 0.4,
                borderColor: documentStyle.getPropertyValue('--cyan-500')
            },
            {
                label: 'Renewals',
                data: Object.values(props.renewals),
                fill: false,
                // borderDash: [5, 5],
                tension: 0.4,
                borderColor: documentStyle.getPropertyValue('--orange-500')
            },
            {
                label: 'Temporal Licences',
                data: Object.values(props.tempLicences),
                fill: true,
                borderColor: documentStyle.getPropertyValue('--gray-500'),
                tension: 0.4,
                backgroundColor: 'rgba(107, 114, 128, 0.2)'
            }
        ]
    };
};
const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
    console.log(textColorSecondary)
    const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.6,
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder
                }
            },
            y: {
                ticks: {
                    color: textColorSecondary,
                    // stepSize: 100,
                },
                grid: {
                    color: surfaceBorder
                }
            }
        }
    };
}

    return {
      chartData,
      chartOptions,
      setChartData,
      computedProvinces,
      months,
      form,
      filter,
      resetFilter
    };
  },

  methods: {
  },

 

  components: {
    Layout,
    Link,
    Head,
    Multiselect,
    Chart
},
};

