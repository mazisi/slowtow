<template>
    <Layout>
      <Head title="Dashboard" />
      <div class="py-5 container-fluid">
       
    
        <div class="row ">
          <Link :href="`/company/licences`" class="col-xl-3 col-lg-3">
              <div class="card l-bg-cherry">
                  <div class="card-statistic-3 p-4">
                      <div class="card-icon card-icon-large"><i class="fas fa-wine-glass"></i>"></div>
                      <div class="mb-4">
                          <h5 class="card-title mb-0 licences">Licences</h5>
                      </div>
                      <div class="row align-items-center mb-2 d-flex">
                          <div class="col-8">
                              <h2 class="d-flex align-items-center qty mb-0">
                                  {{  licences }}
                              </h2>
                          </div>
                          
                      </div>
                  </div>
              </div>
            </Link>
            <Link :href="`/company/my-companies`"  class="col-xl-3 col-lg-3">
              <div class="card l-bg-blue-dark">
                  <div class="card-statistic-3 p-4">
                      <div class="card-icon card-icon-large"><i class="fas fa-building"></i></div>
                      <div class="mb-4">
                          <h5 class="card-title mb-0">Companies</h5>
                      </div>
                      <div class="row align-items-center mb-2 d-flex">
                          <div class="col-8">
                              <h2 class="d-flex align-items-center qty mb-0">
                                  {{ companies }}
                              </h2>
                          </div>
                      </div>
                  </div>
              </div>
            </Link>
          <Link :href="`/company/my-people`" class="col-xl-3 col-lg-3">
              <div class="card l-bg-green-dark">
                  <div class="card-statistic-3 p-4">
                      <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                      <div class="mb-4">
                          <h5 class="card-title mb-0">People</h5>
                      </div>
                      <div class="row align-items-center mb-2 d-flex">
                          <div class="col-8">
                              <h2 class="d-flex align-items-center mb-0 qty">
                                  {{ people }}
                              </h2>
                          </div>
                      </div>
                  </div>
              </div>
            </Link>
          <Link :href="`/company/my-temp-licences`" class="col-xl-3 col-lg-3">
              <div class="card l-bg-orange-dark">
                  <div class="card-statistic-3 p-4">
                      <div class="card-icon card-icon-large"><i class="fas fa-clock"></i></div>
                      <div class="mb-4">
                          <h5 class="card-title mb-0">Temporary Licences</h5>
                      </div>
                      <div class="row align-items-center mb-2 d-flex">
                          <div class="col-8">
                              <h2 class="d-flex align-items-center mb-0 qty">
                                  {{ temp_licences }}
                              </h2>
                          </div>
                      </div>
                  </div>
              </div>
            </Link>
      </div>
      </div>
      </Layout>
    </template>
    <script>
    import Layout from "../../Shared/Layout.vue";
    import ChartHolderCard from "../components/ChartHolderCard.vue";
    import MiniStatisticsCard from "../components/MiniStatisticsCard.vue";
    import { Link, Head } from '@inertiajs/inertia-vue3';
    
    export default {
      name: "dashboard",
    
      
      props: {
        licences: Number,
        companies: Number,
        people: Number,
        temp_licences: Number

      },
      data() {
        return {
         
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
        Head
    },
    };
    </script>
    <style scoped>
    .card {
      background-color: #fff;
      border-radius: 10px;
      border: none;
      position: relative;
      margin-bottom: 30px;
      box-shadow: 0 0.46875rem 2.1875rem rgba(90,97,105,0.1), 0 0.9375rem 1.40625rem rgba(90,97,105,0.1), 0 0.25rem 0.53125rem rgba(90,97,105,0.12), 0 0.125rem 0.1875rem rgba(90,97,105,0.1);
    }
    .qty{
      color: #fff;
    }
    .l-bg-cherry {
      background: linear-gradient(to right, #493240, #f09) !important;
      color: #fff;
    }
    
    .l-bg-blue-dark {
      background: linear-gradient(to right, #373b44, #4286f4) !important;
      color: #fff;
    }
    .card-title{
      color: #fff;
    }
    .l-bg-green-dark {
      background: linear-gradient(to right, #0a504a, #38ef7d) !important;
      color: #fff;
    }
    
    .l-bg-orange-dark {
      background: linear-gradient(to right, #a86008, #ffba56) !important;
      color: #fff;
    }
    
    .card .card-statistic-3 .card-icon-large .fas, .card .card-statistic-3 .card-icon-large .far, .card .card-statistic-3 .card-icon-large .fab, .card .card-statistic-3 .card-icon-large .fal {
      font-size: 110px;
    }
    
    .card .card-statistic-3 .card-icon {
      text-align: center;
      line-height: 50px;
      margin-left: 15px;
      color: #000;
      position: absolute;
      right: -5px;
      top: 20px;
      opacity: 0.1;
    }
    
    .l-bg-cyan {
      background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
      color: #fff;
    }
    
    .l-bg-green {
      background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
      color: #fff;
    }
    
    .l-bg-orange {
      background: linear-gradient(to right, #f9900e, #ffba56) !important;
      color: #fff;
    }
    
    .l-bg-cyan {
      background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
      color: #fff;
    }
    </style>