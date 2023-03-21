<template>
<Layout>
<div class="container-fluid">
  <Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row">
<div class="col-lg-9 col-9">
<h6 class="mb-1">Licence Info: {{ licence.trading_name ? licence.trading_name : '' }}</h6>
</div>


<div class="col-lg-3 col-3 my-auto text-end">
<div class="dropdown float-lg-end pe-4">
<a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
<i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i>
</a>
<ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
<li><Link :href="`/renew-licence?slug=${licence.slug }`" class="dropdown-item border-radius-md">Renewals</Link></li>

<li><Link :href="`/transfer-history?slug=${licence.slug }`" class="dropdown-item border-radius-md"> Transfers</Link></li>

<li><Link :href="`/nominations?slug=${licence.slug }`" class="dropdown-item border-radius-md"> Nominations</Link></li>

<li><Link :href="`/alterations?slug=${licence.slug }`" class="dropdown-item border-radius-md"> Alterations</Link></li>

<li><hr class="text-danger"></li>
<li v-if="$page.props.auth.has_slowtow_admin_role" ><button 
  @click="deleteLicence" class="dropdown-item border-radius-md text-danger" >
<i class="fa fa-trash-o cursor-pointer" aria-hidden="true"></i> Delete</button></li>
</ul>
</div>
</div>
</div>
<div class="row">
<div class="mt-3 ">
<form class="row" @submit.prevent="updateLicence">
<div class="col-6 col-md-6 col-xl-6 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">

<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label for="active-checkbox" class="form-check-label mb-0 text-body text-truncate">Active</label>
<input id="active-checkbox" type="checkbox" value="1"
   @input="assignActiveValue($event,1)" :checked="licence.is_licence_active == '1'">
</div>
</div>
<VmdInput/>
<div class="col-md-12 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Trading Name *</label>
<input type="text" required class="form-control form-control-default" v-model="form.trading_name" >
</div>
<div v-if="errors.trading_name" class="text-danger">{{ errors.trading_name }}</div>
</div>

<div class="col-md-12 columns" v-if="licence.belongs_to === 'Company'">
<div class="input-group input-group-outline null is-filled">
<label class="form-label mb-4">Current Company</label>
<input type="text" disabled class="form-control form-control-default" v-model="form.company" >
</div>
<div v-if="errors.company" class="text-danger">{{ errors.company }}</div>
</div>
<div class="col-md-12 columns" v-else-if="licence.belongs_to === 'Person'">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label mb-4">Current Person</label>
  <input type="text" disabled class="form-control form-control-default" v-model="form.person" >
  </div>
  <div v-if="errors.person" class="text-danger">{{ errors.person }}</div>
  </div>

<!-- <div class="col-md-12 columns" v-if="licence.belongs_to === 'Company'">
<div class="input-group input-group-outline null is-filled">
<label class="form-label mb-4">Current Company</label>
<input type="text" @focus="changeCompany" class="form-control form-control-default" v-model="form.company" >
</div>

<div v-if="errors.licence_type" class="text-danger">{{ errors.licence_type }}</div>
</div>

<div class="col-md-12 columns" v-if="licence.belongs_to === 'Person'">
  <Multiselect
  v-model="form.change_company"
  :options="options"
  :searchable="true"
   placeholder="Search Company..."
   class="form-label"
   :disabled="true"
  />
  
  <div v-if="errors.licence_type" class="text-danger">{{ errors.licence_type }}</div>
  </div> -->


<div class="col-md-12 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Licence Type *</label>
<select v-model="form.licence_type" class="form-control form-control-default">
  <option :value="''" disabled selected>Licence Type</option>
<option v-for='licence_dropdown in licence_dropdowns' :value=licence_dropdown.id> {{ licence_dropdown.licence_type }}</option>
</select>
</div>
<div v-if="errors.licence_type" class="text-danger">{{ errors.licence_type }}</div>
</div>

<div class="col-md-12 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Licence Date</label>
<input type="date" class="form-control form-control-default" v-model="form.licence_date">
</div>
<div v-if="errors.licence_date" class="text-danger">{{ errors.licence_date }}</div>
</div>

<div class="col-md-12 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Licence Number</label>
<input type="text" class="form-control form-control-default" v-model="form.licence_number" >
</div>
<div v-if="errors.licence_number" class="text-danger">{{ errors.licence_number }}</div>
</div>

<div class="col-md-12 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Old Licence Number</label>
<input type="text" class="form-control form-control-default" v-model="form.old_licence_number" >
</div>
<div v-if="errors.old_licence_number" class="text-danger">{{ errors.old_licence_number }}</div>
</div>  


</div>




</div>
</div>
<!-- <hr class="vertical dark" /> -->
</div>
<div class="col-6 col-md-6 col-xl-6 position-relative">
<div class="col-12 columns invisible">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Address Line 1</label>
<input type="text" class="form-control form-control-default">
</div>
</div>  



<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Address Line 1</label>
<input type="text" class="form-control form-control-default" v-model="form.address">
</div>
<div v-if="errors.address" class="text-danger">{{ errors.address }}</div>
</div>  
<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Address Line 2</label>
<input type="text" class="form-control form-control-default" v-model="form.address2">
</div>
<div v-if="errors.address2" class="text-danger">{{ errors.address2 }}</div>
</div> 
<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Address Line 3</label>
<input type="text" class="form-control form-control-default" v-model="form.address3">
</div>
<div v-if="errors.address3" class="text-danger">{{ errors.address3 }}</div>
</div>         
<div class="col-12 columns">                  
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Province</label>
<select class="form-control form-control-default" v-model="form.province" >
<option :value="''" disabled selected >Select Province</option>
<option value="Eastern Cape">Eastern Cape</option>
<option value="Free State">Free State</option>
<option value="Gauteng">Gauteng</option>
<option value="KwaZulu-Natal">KwaZulu-Natal</option>
<option value="Limpopo">Limpopo</option>
<option value="Mpumalanga">Mpumalanga</option>
<option value="Northern Cape">Northern Cape</option>
<option value="North West">North West</option>
<option value="Western Cape">Western Cape</option>
</select>
</div>
<div v-if="errors.province" class="text-danger">{{ errors.province }}</div>
</div>

<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Code</label>
<input  type="text" class="form-control form-control-default" v-model="form.postal_code">
</div>
</div>
</div>

<h6 class="text-center">Documents</h6>
<div class="row">
<div class="col-md-6 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="original_lic">
    <a v-if="original_lic" @click="viewFile(original_lic.id)" href="#!">
    <i class="fa fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Original Licence</h6>
      <p v-if="original_lic" class="mb-0 text-xs">
        {{ original_lic.document_name ? removeFilePath(original_lic.document_name) : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <button  v-if="original_lic" @click="deleteDocument(original_lic.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Original-Licence')" type="button" data-bs-toggle="modal" data-bs-target="#licence-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>


  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="duplicate_original_lic">
    <a v-if="duplicate_original_lic" @click="viewFile(duplicate_original_lic.id)" href="#!">
    <i class="fa fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Duplicate Original</h6>
      <p v-if="duplicate_original_lic" class="mb-0 text-xs">
        {{ duplicate_original_lic.document_name ? removeFilePath(duplicate_original_lic.document_name) : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <button  v-if="duplicate_original_lic" @click="deleteDocument(duplicate_original_lic.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Duplicate-Licence')" type="button" data-bs-toggle="modal" data-bs-target="#licence-docs" 
    class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>
</ul>
<hr class="vertical dark" />
</div>

<div class="col-md-6 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="original_lic_delivered">
    <a v-if="original_lic_delivered" @click="viewFile(original_lic_delivered.id)" href="#!" >
    <i class="fa fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Original Licence Delivered</h6>
      <p v-if="original_lic_delivered" class="mb-0 text-xs">{{ original_lic_delivered.document_name ? removeFilePath(original_lic_delivered.document_name) : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>

    <button  v-if="original_lic_delivered" @click="deleteDocument(original_lic_delivered.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Original-Licence-Delivered')" type="button" data-bs-toggle="modal" data-bs-target="#licence-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>


  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="duplicate_original_lic_delivered">
    <a v-if="duplicate_original_lic_delivered" @click="viewFile(duplicate_original_lic_delivered.id)" href="#!" >
    <i class="fa fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Duplicate Original Delivered</h6>
       <p v-if="duplicate_original_lic_delivered" class="mb-0 text-xs">
        {{ duplicate_original_lic_delivered.document_name ? removeFilePath(duplicate_original_lic_delivered.document_name) : '' }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <button  v-if="duplicate_original_lic_delivered" @click="deleteDocument(duplicate_original_lic_delivered.id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Duplicate-Original-Licence-Delivered')" type="button" data-bs-toggle="modal" data-bs-target="#licence-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>
</ul>
</div>
</div>
<div>
<button type="submit" class="btn btn-primary ms-2" :style="{float: 'right'}">Save</button>
</div>
</form>
<hr>

<div class="row">
<h6 class="text-center mb-2 ">Companies Linked To : {{ licence.trading_name ? licence.trading_name : '' }}</h6>
<div class="table-responsive p-0">
<table class="table align-items-center mb-0">
<thead>
<tr>
<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
Active
</th>
<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
Company Name
</th>

<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
Action
</th>

</tr>
</thead>
<tbody>
<tr>
<td v-if="licence.belongs_to ==='Company' && licence.company.active !== null" class="text-sm"><i class="fa fa-check text-info" aria-hidden="true"></i></td>
<td v-else-if="licence.belongs_to ==='Person' && licence.people.active !== null" class="text-sm"><i class="fa fa-check text-info" aria-hidden="true"></i></td>
<td v-else class=" text-sm text-danger"><i class="fa fa-times"></i></td>
<td class="align-middle text-sm">
<Link v-if="licence.belongs_to ==='Company'" :href="`/view-company/${licence.company.slug}`" class="text-sm text-center align-middle">
  <h6 class="mb-0 ">{{ limit(licence.company.name) }}</h6></Link>
  <Link v-else-if="licence.belongs_to ==='Person'" :href="`/view-person/${licence.people.slug}`" class="text-sm text-center align-middle">
    <h6 class="mb-0 ">{{ limit(licence.people.full_name) }}</h6></Link>
</td>
<td class="text-center">
<Link v-if="licence.belongs_to ==='Company'" :href="`/view-company/${licence.company.slug}`"><i class="fa fa-eye" aria-hidden="true"></i></Link>
<Link v-else-if="licence.belongs_to ==='Person'" :href="`/view-person/${licence.people.slug}`"><i class="fa fa-eye" aria-hidden="true"></i></Link>
</td>
</tr>
</tbody>
</table>
</div>
<hr>
</div>


<Task :tasks="tasks" :model_id="licence.id" :success="success" :error="error" :errors="errors" :model_type="'Licence'"/>

</div>

</div>
</div>
</div>


<!-- upload doc -->

<div v-if="show_modal" class="modal fade" id="licence-docs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="uploadOriginalLicenceDoc">
      <input type="hidden" v-model="originalLicenceForm.doc_type">
      <div class="modal-body">      
        <div class="row">
        <div class="col-md-12 columns">
        <label for="licence-doc" class="btn btn-dark w-100" href="">Click To Upload File</label>
         <input type="file" @change="getFileName"
         hidden id="licence-doc" accept=".pdf"/>
         <div v-if="errors.doc" class="text-danger">{{ errors.doc }}</div>
         <div v-if="file_name">File uploaded: <span class="text-success" v-text="file_name"></span></div>
         <p v-if="file_has_apostrophe" class="text-danger text-sm mt-4">Sorry <span class="text-success">{{ file_name }}</span> cannot contain apostrophe(s).Replace apostrophes with backticks.</p>  
       </div>
       <div class="col-md-12">
          <progress v-if="originalLicenceForm.progress" :value="originalLicenceForm.progress.percentage" max="100">
         {{ originalLicenceForm.progress.percentage }}%
         </progress>
         </div>
         </div>
      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" :disabled="originalLicenceForm.processing || file_has_apostrophe">
         <span v-if="originalLicenceForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

</Layout>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>
    .columns{
      margin-bottom: 1rem;
    }
    #active-checkbox{
      margin-left: 3px;
    }

</style>

<style src="@vueform/multiselect/themes/default.css"></style>

<script src="./view_licence.js"></script>

