<template>
<Layout>
<div class="container-fluid">
<div class="page-header min-height-100 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
">
<span class="mask bg-gradient-success opacity-6"></span>
</div>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row">
<div class="col-lg-6 col-7">
<h6 class="mb-1">Company Info: {{ company.name }}</h6>
</div>
<div class="col-lg-6 col-5 my-auto text-end">
<div class="dropdown float-lg-end pe-4">
<a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
<i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i>
</a>
<ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable" style="">
<li><Link :href="`#!`" @click="triggerModal" data-bs-toggle="modal" data-bs-target="#add-user" class="dropdown-item border-radius-md">Add User</Link></li>

<li><a class="dropdown-item border-radius-md text-danger" ><i class="fa fa-trash-o cursor-pointer" aria-hidden="true"></i> Delete</a></li>
</ul>
</div>
</div>
</div>



<div class="row">
<div class="mt-3 ">
<form class="row" @submit.prevent="submit">
<input type="hidden" v-model="form.slug">
<div class="col-8 col-md-8 col-xl-8 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">
<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label ms-3 mb-0 text-body text-truncate">Active Company</label>
<input id="active-checkbox" type="checkbox" :checked="form.active == '1'" 
@input="assignActiveValue($event.target.value)" value="1">
</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Company Name *</label>
<input type="text" class="form-control form-control-default" v-model="form.company_name">
</div>
<div v-if="errors.company_name" class="text-danger">{{ errors.company_name }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Company Type </label>
<select class="form-control form-control-default" v-model="form.company_type">
<option value="Association">Association</option>
<option value="Close Corporation CC">Close Corporation  CC</option>
<option value="Individual">Individual</option>
<option value="Non-profit Organization (NPO)">Non-profit Organization (NPO)</option>
<option value="Partnership">Partnership</option>
<option value="Private Company  (Proprietary) Limited">Private Company  (Proprietary) Limited</option>
<option value="Public Company">Public Company</option>
<option value="Sole Proprietor">Sole Proprietor</option>
<option value="Sole Proprietor">Sole Proprietor</option>
<option value="Trust">Trust</option>
</select>
</div>
<div v-if="errors.company_type" class="text-danger">{{ errors.company_type }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Registration Number</label>
<input type="text" class="form-control form-control-default" v-model="form.reg_number" >
</div>
<div v-if="errors.reg_number" class="text-danger">{{ errors.reg_number }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Vat Number</label>
<input type="text" class="form-control form-control-default" v-model="form.vat_number" >
</div>
<div v-if="errors.vat_number" class="text-danger">{{ errors.vat_number }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Email Address #1</label>
<input type="email" class="form-control form-control-default" v-model="form.email_address_1" >
</div>
<div v-if="errors.email_address_1" class="text-danger">{{ errors.email_address_1 }}</div>
</div>  
<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Email Address #2</label>
<input type="email" class="form-control form-control-default" v-model="form.email_address_2" >
</div>
<div v-if="errors.email_address_2" class="text-danger">{{ errors.email_address_2 }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Email Address #3</label>
<input type="email" class="form-control form-control-default" v-model="form.email_address_3" >
</div>
<div v-if="errors.email_address_3" class="text-danger">{{ errors.email_address_3 }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Phone Number 1</label>
<input type="text" class="form-control form-control-default" v-model="form.telephone_number_1" >
</div>
<div v-if="errors.telephone_number_1" class="text-danger">{{ errors.telephone_number_1 }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Phone Number 2</label>
<input type="text" class="form-control form-control-default" v-model="form.telephone_number_2" >
</div>
<div v-if="errors.telephone_number_2" class="text-danger">{{ errors.telephone_number_2 }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Website</label>
<input type="url" class="form-control form-control-default" v-model="form.website" >
<Link @click="redirectToWebsite(company.website)" href="#!" class="input-group-text text-primary" id="addon-wrapping">
<i class="material-icons-round opacity-10 fs-5">open_in_new</i></Link>
</div>
<div v-if="errors.website" class="text-danger">{{ errors.website }}</div>
</div>

<h6 class="text-center">Company Documents</h6>

<div class="col-md-6 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="contrib_cert !== ''">
    <a v-for="doc in contrib_cert" :key="doc.id" :href="`/storage/app/public/${doc.document_file}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">Contribution Certificate</h6>
      <p v-if="contrib_cert.length > 0" class="mb-0 text-xs">Name: {{ contrib_cert[0].document_name }}</p>
      <p v-if="contrib_cert.length > 0" class="mb-0 text-xs fst-italic">Expiry Date: {{ new Date(contrib_cert[0].expiry_date).toLocaleString().split(',')[0] }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>

    <button  v-if="contrib_cert.length > 0" @click="deleteDocument(contrib_cert[0].id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('Contribution-Certificate')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>


  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="bee_cert !== ''">
    <a v-for="doc in bee_cert" :key="doc.id" :href="`/storage/app/public/${doc.document_file}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>
    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">BEE Certificate</h6>
      <p v-if="bee_cert.length > 0" class="mb-0 text-xs">Name: {{ bee_cert[0].document_name }}</p>
      <p v-if="bee_cert.length > 0" class="mb-0 text-xs fst-italic">Expiry Date: {{ new Date(bee_cert[0].expiry_date).toLocaleString().split(',')[0] }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>

    <button  v-if="bee_cert.length > 0" @click="deleteDocument(bee_cert[0].id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>
   
    <button v-else @click="getDocType('BEE-Certificate')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>
</ul>
</div>

<div class="col-md-6 columns">
<ul class="list-group">
  <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="cipc_cert !== ''">
    <a v-for="doc in cipc_cert" :key="doc.id" :href="`/storage/app/public/${doc.document_file}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">CIPC Certificate</h6>
       <p v-if="cipc_cert.length > 0" class="mb-0 text-xs">Name: {{ cipc_cert[0].document_name }}</p>
       <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <button  v-if="cipc_cert.length > 0" @click="deleteDocument(cipc_cert[0].id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>

    <button v-else @click="getDocType('CIPC-Certificate')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>

 <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
    <div class="me-3" v-if="lta_cert !== ''">
    <a v-for="doc in lta_cert" :key="doc.id" :href="`/storage/app/public/${doc.document_file}`" target="_blank">
    <i class="fas fa-file-pdf text-lg text-danger me-1 " aria-hidden="true"></i><br>
    </a>    
    </div>

    <div class="d-flex align-items-start flex-column justify-content-center">
      <h6 class="mb-0 text-sm">LTA Certificate</h6>
      <p v-if="lta_cert.length > 0" class="mb-0 text-xs">Name: {{ lta_cert[0].document_name }}</p>
      <p v-if="lta_cert.length > 0" class="mb-0 text-xs fst-italic">Expiry Date: {{ new Date(lta_cert[0].expiry_date).toLocaleString().split(',')[0] }}</p>
      <p v-else class="mb-0 text-xs text-danger fst-italic">Document Not Uploaded.</p>
    </div>
    <button  v-if="lta_cert.length > 0" @click="deleteDocument(lta_cert[0].id)" type="button" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
    </button>
    <button v-else @click="getDocType('LTA-Certificate')" type="button" data-bs-toggle="modal" data-bs-target="#company-docs" class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
    <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
  </li>
</ul>
</div>

</div>

</div>
</div>
<hr class="vertical dark" />
</div>

<div class="col-4 col-md-4 col-xl-4" style="margin-top: 3.4rem;">
<div class="row">
<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Business Address Line 1</label>
<input type="text" class="form-control form-control-default" v-model="form.business_address" >
</div>
<div v-if="errors.business_address" class="text-danger">{{ errors.business_address }}</div>
</div>  

<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Business Address Line 2</label>
<input type="text" class="form-control form-control-default" v-model="form.business_address2" >
</div>
<div v-if="errors.business_address2" class="text-danger">{{ errors.business_address2 }}</div>
</div> 

<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Business Address Line 3</label>
<input type="text" class="form-control form-control-default" v-model="form.business_address3" >
</div>
<div v-if="errors.business_address3" class="text-danger">{{ errors.business_address3 }}</div>
</div> 

<div class="col-6 columns">                  
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Province</label>
<select class="form-control form-control-default" v-model="form.business_province" >
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
<div v-if="errors.business_province" class="text-danger">{{ errors.business_province }}</div>
</div>

<div class="col-6 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Code</label>
<input  type="text" class="form-control form-control-default" v-model="form.business_address_postal_code">
</div>

</div>

<hr>

<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Address Line 1</label>
<input type="text" class="form-control form-control-default" v-model="form.postal_address" >
</div>
<div v-if="errors.postal_address" class="text-danger">{{ errors.postal_address }}</div>
</div>  

<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Address Line 2</label>
<input type="text" class="form-control form-control-default" v-model="form.postal_address2" >
</div>
<div v-if="errors.postal_address2" class="text-danger">{{ errors.postal_address2 }}</div>
</div> 
<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Address Line 3</label>
<input type="text" class="form-control form-control-default" v-model="form.postal_address3" >
</div>
<div v-if="errors.postal_address3" class="text-danger">{{ errors.postal_address }}</div>
</div> 

<div class="col-6 columns">                  
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Province</label>
<select class="form-control form-control-default" v-model="form.postal_province" >
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
<div v-if="errors.postal_province" class="text-danger">{{ errors.postal_province }}</div>
</div>

<div class="col-6 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Code</label>
<input  type="text" class="form-control form-control-default" v-model="form.postal_code">
</div>

</div>
</div>
</div>
<div>
<button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}">Save</button>
</div>
</form>
<hr>
</div>
</div>

<div class="row">
<div class="col-sm-12 col-lg-4"></div>
<div class="col-sm-12 col-lg-4">
<h6 class="text-center">Licences Linked To : {{ company.name }}</h6>
</div>


<div class="table-responsive p-0">
<table class="table align-items-center mb-0">
<thead>
  <tr>
    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
    Trading Name
    </th>
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
    Licence Number
    </th>
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
    Licence Date
    </th>

    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
    View
    </th>
    
  </tr>
</thead>
<tbody>
  <tr v-for="licence in company.licences" :key="licence.id">
    <td>
      <div class="d-flex px-2">
    
    <div class="d-flex flex-column">
      <Link :href="`/view-licence?slug=${licence.slug }`"><h6 class="mb-0 text-sm">{{ licence.trading_name }}</h6></Link>                          
    </div>
      </div>
    </td>
    <td class="text-center">{{ licence.licence_number }}</td>
    <td class="text-center">{{ licence.licence_date }}</td>
    <td class="text-center">
    <Link :href="`/view-licence?slug=${licence.slug }`" class="mx-2 ms-2 justify-content-center">
    <i class="fa fa-eye"></i></Link>
    </td>
    
  </tr>
  
  
</tbody>
</table>
</div>
<hr>
</div>

<div class="row">
<div class="col-sm-12 col-lg-4"></div>
<div class="col-sm-12 col-lg-4">
<h6 class="text-center">People Linked To : {{ company.name }}</h6>
</div>
<div class="col-sm-12 col-lg-4 text-end">
<button @click="show_modal = true" data-bs-toggle="modal" data-bs-target="#add-people" type="button" class="btn btn-sm btn-secondary ms-2 float-end justify-content-center">Add</button></div>

<div class="table-responsive p-0">
<table class="table align-items-center mb-0">
<thead>
  <tr>
    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
    Full Name
    </th>
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
    Position
    </th>
  
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
    Action
    </th>
    
  </tr>
</thead>
<tbody>
  <tr v-for="person in company.people" :key="person.id">
    <td>
      <div class="d-flex px-2">
    
    <div class="d-flex flex-column">
      <Link :href="`/view-consultant/${person.slug}`"><h6 class="mb-0 text-sm">{{ person.full_name }}</h6></Link>                          
    </div>
      </div>
    </td>
    <td class="text-center">{{ person.pivot.position }}</td>
    <td class="text-end float-end ">
    <div class="d-flex float-end ">
    <a data-bs-toggle="modal" data-bs-target="#get-modal-data" 
    @click="getPersonEditModalData(person.pivot.position, person.pivot.director, person.full_name, person.pivot.shareholder,person.pivot.id)" type="button" 
    class="mx-2 ms-2 float-end justify-content-center"><i class="fas fa-edit"></i></a>
    <a @click="unlinkPerson(person.full_name, person.pivot.id )" type="button" 
    class=" ms-2 float-end text-danger justify-content-center"><i class="fas fa-unlink"></i></a>
    </div>
    </td>
    
  </tr>
  
  
</tbody>
</table>
</div>
<hr>
<h6 class="text-center text-decoration-underline">Notes</h6>
</div>



<div class="row">
<div class="col-xl-8">
<div class="row">
<div v-for="task in tasks" :key="task.id" class="mb-4 col-xl-12 col-md-12 mb-xl-0">
<div class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
<span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
<span class="text-sm">{{ task.body }}</span>
</span>
<p style=" font-size: 12px"><i class="fa fa-clock-o" ></i> {{ new Date(task.created_at).toLocaleString().split(',')[0] }}</p>
</div>
</div>
<h6 v-if="!tasks" class="text-center">No tasks found.</h6>
</div>

</div>

<div class="col-xl-4">
<form @submit.prevent="submitTask">
<div class="col-md-12 columns">
<label class="form-check-label text-body text-truncate status-heading">New Note:
<span><i class="fa fa-clock-o mx-2" aria-hidden="true"></i>{{ new Date().toISOString().split('T')[0] }}</span></label>
</div>
<div class="col-12 columns">    
<div class="input-group input-group-outline null is-filled">
<label class="form-label">New Task<span class="text-danger pl-6">{{ body_max - createTask.body.length}}/{{ body_max }}</span></label>
<textarea v-model="createTask.body" @input='checkBodyLength' class="form-control form-control-default" rows="3" ></textarea>
</div>
<div v-if="errors.body" class="text-danger">{{ errors.body }}</div>
</div>

<button :disabled="createTask.processing" :style="{float: 'right'}" class="btn btn-sm btn-secondary ms-2 mt-4 float-end justify-content-center" type="submit">
  <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span class="visually-hidden">Loading...</span> Submit</button>

</form>
</div>
</div>

</div>
</div>

<div class="modal" id="company-docs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="submitDocuments">
      <input type="hidden" v-model="documentsForm.doc_type">
      <div class="modal-body">      
        <div class="row">
        <div class="col-md-12 columns">
        <div class="input-group input-group-outline null is-filled ">
        <label class="form-label">Document Name</label>
        <input type="text" required class="form-control form-control-default" 
         v-model="documentsForm.doc_name" >
        </div>
        <div v-if="errors.doc_name" class="text-danger">{{ errors.doc_name }}</div>
        </div>

        <div class="col-md-12 columns" v-if="documentsForm.doc_type !== 'CIPC-Certificate'">
        <div class="input-group input-group-outline null is-filled ">
        <label class="form-label">Expiry Date</label>
        <input type="date" required class="form-control form-control-default" 
         v-model="documentsForm.expiry_date" >
        </div>
        <div v-if="errors.expiry_date" class="text-danger">{{ errors.expiry_date }}</div>
        </div>

        <div class="col-md-12 columns">
        <label for="licence-doc" class="btn btn-dark w-100" href="">Click To Upload File</label>
         <input type="file" @input="documentsForm.document = $event.target.files[0]"
         hidden id="licence-doc" accept=".pdf"/>
         <div v-if="errors.document" class="text-danger">{{ errors.document }}</div>
       </div>
       <div class="col-md-12">
          <progress v-if="documentsForm.progress" :value="documentsForm.progress.percentage" max="100">
         {{ documentsForm.progress.percentage }}%
         </progress>
         </div>
         </div>   

  <div class="col-md-12" v-if="success">
   <div class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
   <span class="alert-icon"><i class=""></i></span>
   <span class="alert-text"> 
   <span class="text-sm">{{ success }}</span></span>
   <button type="button" class="btn-close d-flex justify-content-center align-items-center"
    data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="text-lg font-weight-bold">×</span>
    </button>
    </div>
    </div>
      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" :disabled="documentsForm.processing">
         <span v-if="documentsForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      </form>
    </div>
  </div>
</div>


<div v-if="show_modal" class="modal" id="add-people" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add People</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="submitPeople">
      <div class="modal-body">      
        <div class="row">
        <div class="col-md-12 columns">
  <div class="input-group input-group-outline null is-filled">
     <Multiselect
        v-model="addPeopleForm.people"
        mode="tags"
        placeholder="Search People...."
        :options="people_options"
        :searchable="true"
      />
    </div>
 <div v-if="errors.people" class="text-danger">{{ errors.people }}</div>
</div>
</div>  
 </div>
  
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
  <button type="submit" class="btn btn-primary" :disabled="addPeopleForm.processing">
    <span v-if="addPeopleForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    Save</button>
</div>
</form>
    </div>
  </div>
</div>

<div class="modal" id="get-modal-data" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit {{ editPerson.full_name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form @submit.prevent="updatePerson">
      <div class="modal-body">      
        <div class="row">
        <div class="col-md-12 columns">
        <div class="input-group input-group-outline null is-filled ">
        <label class="form-label">Position</label>
        <input type="text" required class="form-control form-control-default" v-model="editPerson.position">
        </div>
        <div v-if="errors.position" class="text-danger">{{ errors.position }}</div>
        </div>
        </div>   

  <div class="col-md-12" v-if="success">
   <div class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
   <span class="alert-icon"><i class=""></i></span>
   <span class="alert-text"> 
   <span class="text-sm">{{ success }}</span></span>
   <button type="button" class="btn-close d-flex justify-content-center align-items-center"
    data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="text-lg font-weight-bold">×</span>
    </button>
    </div>
    </div>
      </div>
  {{ message }}
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" :disabled="editPerson.processing">
         <span v-if="editPerson.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
         Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
</Layout>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
<style>
  #active-checkbox{
    margin-top: 3px;
    margin-left: 3px;
  }
  .columns{
      margin-bottom: 1rem;
    }



</style>

<script>
import Layout from "../Shared/Layout.vue";
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import Multiselect from '@vueform/multiselect';
import { ref } from 'vue'

export default {
 props: {
    tasks: Object,
    errors: Object,
    company: Object,
    people: Array,
    contrib_cert: Object,
    bee_cert: Object,
    cipc_cert: Object,
    lta_cert: Object,
    success: String,
    error: String,
    message: String
 },  
  
  setup (props) {
    let showMenu = false;
    let body_max = 100;
    let people_options = props.people;
    let show_modal = ref(true);  

    const form = useForm({
            company_name: props.company.name,
            company_type: props.company.company_type,
            reg_number: props.company.reg_number,
            vat_number: props.company.vat_number,
            email_address_1: props.company.email,
            email_address_2: props.company.email1,
            email_address_3: props.company.email2,
            telephone_number_1: props.company.tel_number,
            telephone_number_2: props.company.tel_number1,
            website: props.company.website,
            business_address: props.company.business_address,
            business_address2: props.company.business_address2,
            business_address3: props.company.business_address3,
            business_province: props.company.business_province,
            business_address_postal_code: props.company.business_address_postal_code,
            postal_address: props.company.postal_address,
            postal_address2: props.company.postal_code2,
            postal_address3: props.company.postal_code3,
            postal_province: props.company.postal_province,
            postal_code: props.company.postal_code,
           company_id: props.company.id,
            active: props.company.active,
            province: props.company.business_province 
    })

      const createTask = useForm({
          body: '',
          taskDate: null,
          model_type: 'Company',
          model_id: props.company.id,
      })

      const documentsForm = useForm({
            document: null,
            doc_name: null,
            expiry_date: null,
            doc_type: null,
            company_id: props.company.id,
      })

      const addPeopleForm = useForm({
          people: [],
        })

      const editPerson = useForm({
          position: null,
          director: null,
          full_name: null,
          shareholder: null,
          pivot_id: null,
        })
          function getPersonEditModalData(position,director,full_name,shareholder,pivot_id){
            this.editPerson.position = position;
            this.editPerson.director = director;
            this.editPerson.full_name = full_name;
            this.editPerson.shareholder = shareholder;
            this.editPerson.pivot_id = pivot_id;
          }
        function updatePerson(){//Update when you adit position 
        
           editPerson.patch(`/update-company-people`, {
           preserveScroll: true,
           onSuccess: () => {
            //         
           },
          })  
        }

        function submitPeople(){
          addPeopleForm.post(`/add-people-to-company/${props.company.id}`, {
           preserveScroll: true,
           onSuccess: (page) => { 
            this.show_modal = false;
          let dismiss =  document.querySelector('.modal-backdrop') 
         
           dismiss.remove();
            addPeopleForm.reset();
           },
          })  
        }

      function submitDocuments(){
          documentsForm.post(`/submit-company-documents`, {
          preserveScroll: true,
          onSuccess: (page) => {
            documentsForm.reset();
          },
        })    
        }

      function getDocType(doc_type){
        this.documentsForm.doc_type = doc_type;
      }

      function deleteDocument(id){
          if(confirm('Document will be deleted permanently!! Continue??')){
            Inertia.delete(`/delete-company-document/${id}`)
          }
        }

    function submit() {//Update company details
      form.post('/update-company', {
        preserveScroll: true,
      })
      
    }
            // store task
      function submitTask() {
        createTask.post('/submit-task',{
          ///do something
        })
        createTask.reset()
      }

      function deleteTask(task_id){
        if(confirm('Are you sure??')){
          createTask.delete(`/delete-task/${task_id}`,{
          ///do something
        })
        }
      }

      function checkBodyLength(){//Monitor task body length..
          if(this.createTask.body.length > this.body_max){
              this.createTask.body = this.createTask.body.substring(0,this.body_max)
          }
      }

      function unlinkPerson(full_name,id){
        if(confirm(full_name + ' will be removed from this company...Continue..??')){
          createTask.delete(`/unlink-person/${id}`,{
          ///do something
        })
        }
      }

      function addUser(){
        this.$inertia.post('/add-company-admin', this.addUserForm)
      } 

      function assignActiveValue(event){
        this.form.active = event
      }

      function redirectToWebsite(url){
        if(url.includes('https://') || url.includes('http://')){
          window.open(url, "_blank");
        }else{
          window.open('https://' + url, "_blank");
        }
        
      }
    return {
      submit,
      submitTask,
      deleteTask,
      checkBodyLength,
      addUser,
      unlinkPerson,
      assignActiveValue,
      redirectToWebsite,
      body_max,
      createTask,
      people_options,
      form,
      documentsForm,
      getDocType,
      submitDocuments,
      deleteDocument,
      addPeopleForm,
      submitPeople,
      getPersonEditModalData,
      editPerson,
      updatePerson,
      show_modal
    }
  },

   components: {
    Layout,
    Link,
    Head,
    Multiselect
  },
  
};
</script>

