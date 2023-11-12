<template>
<Layout>
  <Head title="Upload Contacts" />
  <div class="container-fluid">
<Banner/>
    
    <div class="card mx-3 mx-md-4 mt-n6">
      <div class="col-12">
  
  <div class=" card-body">
    <div class="p-3 pb-0 card-header" >
          <h6 class="mb-0" >Upload CSV Document</h6>
        </div>
    <div class="card-body px-0 pb-2">
 <form @submit.prevent="submit">
 <ul class="list-group">
  <!-- <div class="d-flex">
  <label>Tick if first row is a header</label>
    <input v-model="form.header" type="checkbox" id="has-header">
  </div> -->
<li class="list-group-item d-flex align-items-center border-0 mb-2 rounded" style="background-color: #f4f6f7;">

  <input @input="form.csv_file = $event.target.files[0]" class="form-control form-control-lg" id="formFileLg" type="file">
<p v-if="errors.csv_file" class="text-danger">{{ errors.csv_file }}</p>
</li>
<progress v-if="form.progress" :value="form.progress.percentage" max="100">
      {{ form.progress.percentage }}%
    </progress>
</ul>
<button type="submit" class="btn btn-sm btn-secondary upload-btn">Upload</button>
</form>










          </div>
          <div class="card-deck">
  <div class="card">
  
    <div class="card-body">
      <div class="container mt-5 text-left">
    <form>
        <div class="row justify-content-center">
            <div class="col-md-3 mb-3">
                <label for="labelDescription" class="form-label">Label Description</label>
                <textarea class="form-control" id="labelDescription" rows="3"></textarea>
            </div>

            <div class="col-md-3 mb-3">
                <label for="referenceLabel" class="form-label">Reference Label</label>
                <input type="text" class="form-control border" id="referenceLabel">
            </div>

            <div class="col-md-3 mb-3">
                <label for="fileInput" class="form-label">File Upload</label>
                <input type="file" class="form-control border" id="fileInput">
            </div>

            <div class="col-md-3 mb-3">
              <br/>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </form>
</div>

      <table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">Document Description</th>
      <th scope="col">Upload Date</th>
      <th scope="col">View Document</th>
      <th scope="col">Edit</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>
   
  </tbody>
</table>





<table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">Document Description</th>
      <th scope="col">Upload Date</th>
      <th scope="col">View Document</th>
      <th scope="col">Edit</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>Bootstrap 4 CDN and Starter Template</td>
          <td>Cristina</td>
          <td> <a href="">Delete</a> </td> 
        </tr>
        <tr>
          <th scope="row">2</th>
          <td>Bootstrap Grid 4 Tutorial and Examples</td>
          <td>Cristina</td>
          <td> <a href="">Delete</a> </td> 
        </tr>

        <tr>
          <th scope="row">3</th>
          <td colspan="2">Bootstrap Flexbox Tutorial and Examples</td>
          <td> <a href="">Delete</a> </td> 
      
        </tr>
      </tbody>
     </table>
    </div>
    
  </div>
  
</div>
        </div>
      </div>
    </div>
  </div>

  </Layout>
</template>

<style scoped>
 
  .table thead th {
    padding: 0;
    font-weight: 400;
    }
    .upload-btn{
      float: right!important;
    }
    #has-header{
  margin-left: 3px;
}
#labelDescription {
            border: none;
            border: 1px solid #ced4da; 
            border-radius: 0;
            resize: none;
        }
</style>
<script>
import Layout from "../../Shared/Layout.vue";
import { useForm, Head } from '@inertiajs/inertia-vue3';
import Banner from '../components/Banner.vue';

export default {
  setup () {
    const form = useForm({
      csv_file: null,
      header: '',
    })

    function submit() {
      form.post('/submit-contacts')
    }

    return { form, submit }
  },
  props: {
    success: String,
    errors: Object
  },
 components: {
    Layout,
    Banner,
    Head
},
}
</script>