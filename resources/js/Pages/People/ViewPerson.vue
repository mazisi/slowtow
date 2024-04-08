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
          <div class="col-lg-6 col-5 my-auto text-end">


            <button @click="previewPerson" type="button" class="btn btn-sm btn-dark mx-2"> <i class="fa fa-file-o text-md"></i>
              Preview
            </button>

            <button @click="deletePerson(person.full_name)" type="button" class="btn btn-sm btn-danger">
              <i class="fa fa-trash-alt text-md"></i> Delete
            </button>
          </div>
        </div>
        <div class="row">
          <div class="mt-3 row">
            <div class="col-12 col-md-12 col-xl-12 position-relative">
              <div class="card card-plain h-100">
                <div class="p-3 card-body">
                  <form @submit.prevent="updatePerson">
                    <input type="hidden" v-model="form.slug">
                    <div class="row">

                      <CheckBoxInputComponent
                          :label="'Active Person'"
                          :value="'1'"
                          :isChecked="person.active == '1'"
                          :column="'col-md-12'"
                          @input="assignActiveValue($event,1)"
                      />



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
                                    :documentModel="person"
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
                                  :documentModel="person"
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
                                  :documentModel="person"
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
                                  :documentModel="person"
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
                                  :documentModel="person"
                                  @file-value-changed="submitDocument"
                                  @file-deleted="deleteDocument"
                                  :hasFile="hasFile('Valid Fingerprints')"
                                  :errors="errors"
                                  :error="error"
                                  :docType="'Valid Fingerprints'"
                                  :success="success"
                              />
                        </div>
                      </div>


                    </div>
                    <div class="d-flex float-end" style="float: right;">
                      <button :disabled="form.processing" class="btn btn-sm btn-primary ms-2" type="submit">
                        <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Save
                      </button>
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

                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                  Position
                </th>

                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                  Action
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

                <td class="align-middle text-sm">
                  <Link :href="`/view-company/${linked_company.slug}`" class="text-sm text-center align-middle">
                    <h6 class="mb-0 ">{{ linked_company.pivot.position }}</h6>
                  </Link>
                </td>

                <td class="text-center">
                  <Link :href="`/view-company/${linked_company.slug}`"><i class="fa fa-eye" aria-hidden="true"></i></Link>
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

        <div class="row">
          <div class="col-sm-12 col-lg-4"></div>
          <div class="col-sm-12 col-lg-12">
            <h6 class="text-center">Licences Linked To : {{ person.full_name ? person.full_name : '' }}</h6>
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
               
              <tr v-if="linked_licences.data?.length > 0" v-for="licence in linked_licences.data" :key="licence.id">
                <td>
                  <div class="d-flex px-2">
                    <div class="d-flex flex-column">
                      <Link @click="redirect(licence)"><h6 class="mb-0 text-sm">{{ licence.trading_name ? licence.trading_name : ''  }}</h6></Link>
                    </div>
                  </div>
                </td>
                <td class="text-center"><Link @click="redirect(licence)">{{ licence.licence_number ? licence.licence_number : '' }}</Link></td>
                <td class="text-center"><Link @click="redirect(licence)">{{ licence.licence_date }}</Link></td>
                <td class="text-center">
                  <Link @click="redirect(licence)" class="mx-2 ms-2 justify-content-center">
                    <i class="fa fa-eye"></i></Link>
                </td>

              </tr>

              <tr v-else>
                <td colspan="6" class="text-center text-danger">No Licences Found.</td>
            </tr>


              </tbody>
            </table>
          </div>
          <Paginate v-if="linked_licences.data?.length > 0"
              :modelName="linked_licences"
              :modelType="ViewPerson"
          />
          <hr>
        </div>



        <Task :tasks="tasks" :model_id="person.id" :errors="errors" :success="success" :error="error" :model_type="'Person'"/>


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

