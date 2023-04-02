
<script src="./view_licence.js"></script>
<template>
<Layout>

  <Head title="View Licence" />
  
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

<CheckBoxInputComponent 
      :column="'col-12'"
      :label="'Active'"
      :isChecked="licence.is_licence_active == '1'"
      :value="1"
      @input="assignActiveValue($event,1)" 
  />

<TextInputComponent 
  v-model="form.trading_name" 
  :column="'col-md-12'" 
  :label="'Trading Name *'" 
  :value="form.trading_name"
  :errors="errors.trading_name"
  :input_id="trading_name"
/>



<TextInputComponent v-if="licence.belongs_to === 'Company'"
  :inputType="'text'"
  :required="true"
  :disabled="true"
  v-model="form.company" 
  :column="'col-md-12'" 
  :label="'Current Company'" 
  :value="form.company"
  :errors="errors.company"
  :input_id="company"
/>

<TextInputComponent v-else-if="licence.belongs_to === 'Person'"
  v-model="form.person" 
  :column="'col-md-12'" 
  :label="'Current Person'" 
  :value="form.person"
  :errors="errors.person"
  :input_id="person"
  :disabled="true"
/>


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

  <LicenceTypeDropDownComponent 
    :dropdownList="licence_dropdowns" 
    :label="'Licence Type *'" 
    :defaultDisabledText="'Select Licence Type'"
    :column="'col-md-12'"
    :value="form.licence_type"
    v-model="form.licence_type"
    :errors="errors.licence_type"
    :input_id="licence_type"
    :required="true"
  />

  <TextInputComponent
    v-model="form.licence_date" 
    :column="'col-md-12'" 
    :label="'Licence Date'" 
    :value="form.licence_date"
    :errors="errors.licence_date"
    :input_id="licence_date"
    :inputType="'date'"
/>

<TextInputComponent
    v-model="form.licence_number" 
    :column="'col-md-12'" 
    :label="'Licence Number'" 
    :value="form.licence_number"
    :errors="errors.licence_number"
    :input_id="licence_number"
    :inputType="'text'"
    :required="true"
/>

<TextInputComponent
    v-model="form.old_licence_number" 
    :column="'col-md-12'" 
    :label="'Old Licence Number'" 
    :value="form.old_licence_number"
    :errors="errors.old_licence_number"
    :input_id="old_licence_number"
    :inputType="'text'"
/>


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



<TextInputComponent
    v-model="form.address" 
    :column="'col-12'" 
    :label="'Address Line 1'" 
    :value="form.address"
    :errors="errors.address"
    :input_id="address"
    :inputType="'text'"
/>

<TextInputComponent
    v-model="form.address2" 
    :column="'col-12'" 
    :label="'Address Line 2'" 
    :value="form.address2"
    :errors="errors.address2"
    :input_id="address2"
    :inputType="'text'"
/>

<TextInputComponent
    v-model="form.address3" 
    :column="'col-12'" 
    :label="'Address Line 3'" 
    :value="form.address3"
    :errors="errors.address3"
    :input_id="address3"
    :inputType="'text'"
/>

<ProvinceSelectDropdownComponent 
  :provinceList="computedProvinces" 
  :label="'Province'" 
  :defaultDisabledText="'Select province..'"
  :column="'col-12'"
  :value="form.province"
  v-model="form.province"
  :errors="errors.province"
  :required="true"
/>

<TextInputComponent
    v-model="form.postal_code" 
    :column="'col-12'" 
    :label="'Postal Code'" 
    :value="form.postal_code"
    :errors="errors.postal_code"
    :input_id="postal_code"
    :inputType="'text'"
/>

</div>

<h6 class="text-center">Documents</h6>
<div class="row">
<div class="col-md-6 columns">
<ul class="list-group">
 
 <DocumentListComponent
    :licence_id="licence.id"
    :documentModel="original_lic"
    :documentTitle="'Original-Licence'"
    :success="success"
    :error="error"
    :errors="errors.document_file"
 />

 <DocumentListComponent
    :licence_id="licence.id"
    :documentModel="duplicate_original_lic"
    :documentTitle="'Duplicate-Licence'"
    :success="success"
    :error="error"
    :errors="errors.document_file"
 />

</ul>
<hr class="vertical dark" />
</div>

<div class="col-md-6 columns">
<ul class="list-group">

  <DocumentListComponent
  :licence_id="licence.id"
  :documentModel="original_lic_delivered"
  :documentTitle="'Original-Licence-Delivered'"
  :success="success"
  :error="error"
  :errors="errors.document_file"
/>
  
<DocumentListComponent
:licence_id="licence.id"
:documentModel="duplicate_original_lic_delivered"
:documentTitle="'Duplicate-Original-Licence-Delivered'"
:success="success"
:error="error"
:errors="errors.document_file"
/>

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


<Task 
:tasks="tasks" 
:model_id="licence.id" 
:success="success" 
:error="error" 
:errors="errors" 
:model_type="'Licence'"
/>

</div>

</div>
</div>
</div>


<!-- upload doc -->

</Layout>
</template>

<style scoped>
    .columns{
      margin-bottom: 1rem;
    }
    #active-checkbox{
      margin-left: 3px;
    }

</style>

<style src="@vueform/multiselect/themes/default.css"></style>


