<template>
    <Layout>
      <Head title="Dashboard" />
      <div class="py-5 container-fluid">
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
       
        <div class="row">
          <div class="col-xl-12 col-lg-12">
              <div class="card l-bg-cherry">
                <Chart type="line" :data="chartData" :options="chartOptions" class="h-30rem" style="height: 574px;"/>
              </div>
            </div>
          
      </div>
      </div>
      </Layout>
    </template>
    <script src="./Charts/barChart.js"></script>
    <style src="@vueform/multiselect/themes/default.css"></style>