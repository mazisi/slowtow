<template>
    <Layout>
      <Head title="Dashboard" />
      <div class="py-5 container-fluid">
       <div class="row">
        <div class="col-4">
          <div class="input-group input-group-outline null is-filled">
          <Multiselect                
          :options="computedProvinces"
           
          :taggable="true"
          placeholder="Filter By Province"/>
          </div>

        </div>

        <div class="col-4">
          <div class="input-group input-group-outline null is-filled">
          <Multiselect                
          :options="years"
           
          :taggable="true"
          placeholder="Filter By Year"/>
          </div>

        </div>

        <div class="col-4">
          <div class="input-group input-group-outline null is-filled">
          <Multiselect                
          :options="months"
           
          :taggable="true"
          placeholder="Filter By Month"/>
          </div>

        </div>

       </div>
       
        <div class="row">
          <div class="col-xl-12 col-lg-12">
              <div class="card l-bg-cherry">
                <Chart type="line" :data="chartData" :options="chartOptions" class="h-30rem" style="height: 574px;"/>
              </div>
            </div>
            <!-- <Link :href="`/companies`"  class="col-xl-4 col-lg-4">
              <div class="card l-bg-blue-dark">
                <Chart type="bar" :data="chartData" :options="chartOptions" />
              </div>
            </Link>
          <Link :href="`/people`" class="col-xl-4 col-lg-4">
              <div class="card l-bg-green-dark">
                <Chart type="bar" :data="chartData" :options="chartOptions" />
              </div>
            </Link>
          <Link :href="`/temp-licences`" class="col-xl-4 col-lg-4">
              <div class="card l-bg-orange-dark">
                <Chart type="bar" :data="chartData" :options="chartOptions" />
              </div>
            </Link> -->
      </div>
      </div>
      </Layout>
    </template>
    <script src="./Charts/barChart.js"></script>
    <style src="@vueform/multiselect/themes/default.css"></style>