<template>
    <Layout>
      <Head title="View Person" />
      <div class="container-fluid">
        <Banner/>
        <div class="card card-body mx-3 mx-md-4 mt-n6">
          <div class="row">
            <div class="col-lg-6 col-7">
              <h6 class="mb-1">Person Info: {{ person.full_name }}</h6>
            </div>
            
          </div>
          <div class="row">
            <div class="mt-3 row">
              <div class="col-sm-12 col-md-12 col-xl-12 position-relative">
                <div class="card card-plain h-100">
                  <div class="p-3 card-body">
                    <form @submit.prevent="updatePerson">
                      <input type="hidden" v-model="form.slug">
                      <div class="row">
  
                        <TextInputComponent
                            :inputType="'text'"
                            v-model="form.name"
                            :value="form.name"
                            :column="'col-4'"
                            :label="'Full Name And Surname *'"
                            :errors="errors.name"
                            :input_id="name"
                            :required="true"
                        />
  
  
                        <TextInputComponent
                            :inputType="'text'"
                            v-model="form.id_or_passport"
                            :value="form.id_or_passport"
                            :column="'col-4'"
                            :label="'ID/Passport Number'"
                            :errors="errors.id_or_passport"
                            :input_id="id_or_passport"
                        />
  
                        <TextInputComponent
                            :inputType="'text'"
                            v-model="form.date_of_birth"
                            :value="form.date_of_birth"
                            :column="'col-4'"
                            :label="'Date of Birth'"
                            :errors="errors.date_of_birth"
                            :input_id="date_of_birth"
                            :disabled="true"
                        />
  
                        <TextInputComponent
                            :inputType="'email'"
                            v-model="form.email_address_1"
                            :value="form.email_address_1"
                            :column="'col-4'"
                            :label="'Email Address'"
                            :errors="errors.email_address_1"
                            :input_id="email_address_1"
                        />
  
  
  
                        <TextInputComponent
                            :inputType="'email'"
                            v-model="form.email_address_2"
                            :value="form.email_address_2"
                            :column="'col-4'"
                            :label="'Email Address'"
                            :errors="errors.email_address_2"
                            :input_id="email_address_2"
                        />
  
                        <TextInputComponent
                            :inputType="'text'"
                            v-model="form.cell_number"
                            :value="form.cell_number"
                            :column="'col-4'"
                            :label="'Phone Number'"
                            :errors="errors.cell_number"
                            :input_id="cell_number"
                        />
  
                        <TextInputComponent
                            :inputType="'tel'"
                            v-model="form.telephone"
                            :value="form.telephone"
                            :column="'col-4'"
                            :label="'Work Number'"
                            :errors="errors.telephone"
                            :input_id="telephone"
                        />
                        <hr>
                        <h6 class="text-center">Documents</h6>
  
                         <div class="row mt-4">
                          <div class="col-4">
                                  <PeopleDocComponent
                                      :documentModel="person.person"
                                      @file-value-changed="submitDocument"
                                      @file-deleted="deleteDocument"
                                      :hasFile="hasFile('ID Document')"
                                      :errors="errors"
                                      :error="error"
                                      :docType="'ID Document'"
                                      :success="success"
                                  />
                          </div>
  
                          <div class="col-4">
                                <PeopleDocComponent
                                    :documentModel="person.person"
                                    @file-value-changed="submitDocument"
                                    @file-deleted="deleteDocument"
                                    :hasFile="hasFile('Police Clearance')"
                                    :errors="errors"
                                    :error="error"
                                    :docType="'Police Clearance'"
                                    :success="success"
                                />
                          </div>
  
                          <div class="col-4">
                                <PeopleDocComponent
                                    :documentModel="person.person"
                                    @file-value-changed="submitDocument"
                                    @file-deleted="deleteDocument"
                                    :hasFile="hasFile('Passport')"
                                    :errors="errors"
                                    :error="error"
                                    :docType="'Passport'"
                                    :success="success"
                                />
                             
                          </div>
  
  
                          <div class="col-4">
                                <PeopleDocComponent
                                    :documentModel="person.person"
                                    @file-value-changed="submitDocument"
                                    @file-deleted="deleteDocument"
                                    :hasFile="hasFile('Work Permit')"
                                    :errors="errors"
                                    :error="error"
                                    :docType="'Work Permit'"
                                    :success="success"
                                />
                          </div>
  
                          <div class="col-4">
                                <PeopleDocComponent                              
                                    :documentModel="person.person"
                                    @file-value-changed="submitDocument"
                                    :hasFile="hasFile('Valid Fingerprints')"
                                    :errors="errors"
                                    :error="error"
                                    :docType="'Valid Fingerprints'"
                                    :success="success"
                                />
                          </div>
                        </div>
  
  
                      </div>
                      
                    </form>
                  </div>
                </div>
  
              </div>
              <!-- //tasks were here -->
  
            </div>
  
          </div>
  
          <div class="row">
            <h6 class="text-center mb-2 ">Companies Linked To : {{ person.full_name ? person.full_name : '' }}</h6>
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
  
  
                </tr>
                </thead>
                <tbody>
  
                <tr v-if="person.company?.length > 0" v-for="linked_company in person.company">
                  <td v-if="linked_company ==1 && linked_company.active !== null" class="text-sm"><i class="fa fa-check text-info" aria-hidden="true"></i></td>
                  <td v-else class="text-sm"><i class="fa fa-check text-info" aria-hidden="true"></i></td>
                  <td class="align-left text-sm">
                    <Link :href="`/view-company/${linked_company.slug}`" class="text-sm text-center align-left">
                      <h6 class="mb-0 ">{{ linked_company.name }}</h6>
                    </Link>
                  </td>
  
                  
                </tr>
                <tr v-else>
                  <td colspan="6" class="text-center text-danger">No companies Found.</td>
              </tr>
                </tbody>
              </table>
            </div>
            <hr>
          </div> 
  
       
  
  
        </div>
      </div>
  
  
    </Layout>
  </template>
  
  
  <style>
  .columns{
    margin-bottom: 1rem;
  }
  #active-checkbox{
    margin-top: -3px;
    margin-left: 3px;
  }
  .display-text-length{
    margin-left: 10rem;
    font-size: 14px;
  }
  .fa-file-pdf{
    font-size: 30em;
  }
  </style>
  
  <script src="./view_person.js"></script>
  
  