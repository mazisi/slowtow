<template>
    <Layout>
        <Head title="View Company"/>
        <div class="container-fluid">
            <Banner/>
            <div class="card card-body mx-3 mx-md-4 mt-n6">
                <div class="row">
                    <div class="col-lg-9 col-9">
                        <h6 class="mb-1">Company Info: {{ company.name }}</h6>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="mt-3 ">
                        <form class="row" >
                            <div class="col-sm-6 col-md-8 col-lg-8 col-xl-8 position-relative">
                                <div class="card card-plain h-100">
                                    <div class="p-3 card-body">
                                        <div class="row">

                                            <CheckBoxInputComponent
                                                :column="'col-sm-12 col-md-6 col-lg-6 col-xl-6'"
                                                :label="'Active Company'"
                                                :isChecked="company.active == '1'"
                                                :value="1"
                                                @input="assignActiveValue($event,1)"
                                            />


                                            <TextInputComponent
                                                :inputType="'text'"
                                                v-model="form.company_name"
                                                :value="form.company_name"
                                                :column="'col-sm-12 col-md-6 col-lg-6 col-xl-6'"
                                                :label="'Company Name *'"
                                                :errors="errors.company_name"
                                                :input_id="company_name"
                                            />

                                            <TextInputComponent
                                                :inputType="'email'"
                                                v-model="form.email_address_1"
                                                :value="form.email_address_1"
                                                :column="'col-sm-12 col-md-6 col-lg-6 col-xl-6'"
                                                :label="'Email Address'"
                                                :errors="errors.email_address_1"
                                                :input_id="email_address_1"
                                            />

                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 columns">
                                                <div class="input-group input-group-outline null is-filled ">
                                                    <label class="form-label">Company Type </label>
                                                    <select class="form-control form-control-default" v-model="form.company_type">
                                                        <option :value="''" disabled>Select Type</option>
                                                        <option value="Association">Association</option>
                                                        <option value="Close Corporation -CC">Close Corporation&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;-CC</option>
                                                        <option value="Individual">Individual</option>
                                                        <option value="Non-profit Organization -(NPO)">Non-profit Organization&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;-NPO</option>
                                                        <option value="Partnership">Partnership</option>
                                                        <option value="Private Company  -(Proprietary) Limited">Private Company&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;-  (Proprietary) Limited</option>
                                                        <option value="Public Company">Public Company&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;-Limited</option>
                                                        <option value="Sole Proprietor">Sole Proprietor</option>
                                                        <option value="Trust">Trust&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;-IT</option>
                                                    </select>
                                                </div>
                                                <div v-if="errors.company_type" class="text-danger">{{ errors.company_type }}</div>
                                            </div>


                                            <TextInputComponent
                                                :inputType="'email'"
                                                v-model="form.email_address_2"
                                                :value="form.email_address_2"
                                                :column="'col-sm-12 col-md-6 col-lg-6 col-xl-6'"
                                                :label="'Email Address'"
                                                :errors="errors.email_address_2"
                                                :input_id="email_address_2"
                                            />

                                            <TextInputComponent
                                                :inputType="'text'"
                                                v-model="form.reg_number"
                                                :value="form.reg_number"
                                                :column="'col-sm-12 col-md-6 col-lg-6 col-xl-6'"
                                                :label="'Registration Number'"
                                                :errors="errors.reg_number"
                                                :input_id="reg_number"
                                            />

                                            <TextInputComponent
                                                :inputType="'email'"
                                                v-model="form.email_address_3"
                                                :value="form.email_address_3"
                                                :column="'col-sm-12 col-md-6 col-lg-6 col-xl-6'"
                                                :label="'Email Address'"
                                                :errors="errors.email_address_3"
                                                :input_id="email_address_3"
                                            />


                                            <TextInputComponent
                                                :inputType="'text'"
                                                v-model="form.vat_number"
                                                :value="form.vat_number"
                                                :column="'col-sm-12 col-md-6 col-lg-6 col-xl-6'"
                                                :label="'Vat Number'"
                                                :errors="errors.vat_number"
                                                :input_id="vat_number"
                                            />

                                            <TextInputComponent
                                                :inputType="'text'"
                                                v-model="form.telephone_number_1"
                                                :value="form.telephone_number_1"
                                                :column="'col-sm-12 col-md-6 col-lg-6 col-xl-6'"
                                                :label="'Phone Number'"
                                                :errors="errors.telephone_number_1"
                                                :input_id="telephone_number_1"
                                            />

                                            <div class="col-md-6 columns">
                                                <div class="input-group input-group-outline null is-filled">
                                                    <label class="form-label">Website</label>
                                                    <input type="url" class="form-control form-control-default" v-model="form.website" >
                                                    <Link @click="redirectToWebsite(company.website)" href="#!" class="input-group-text text-primary" id="addon-wrapping">
                                                        <i class="material-icons-round opacity-10 fs-5">open_in_new</i></Link>
                                                </div>
                                                <div v-if="errors.website" class="text-danger">{{ errors.website }}</div>
                                            </div>


                                            <TextInputComponent
                                                :inputType="'text'"
                                                v-model="form.telephone_number_2"
                                                :value="form.telephone_number_2"
                                                :column="'col-sm-12 col-md-6 col-lg-6 col-xl-6'"
                                                :label="'Phone Number'"
                                                :errors="errors.telephone_number_2"
                                                :input_id="telephone_number_2"
                                            />



                                            <h6 class="text-center mt-5">Company Documents</h6>


                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 columns">
                                                    <CompanyFileUploadComponent
                                                        :documentModel="company"
                                                        @file-value-changed="submitDocument"
                                                        @file-deleted="deleteDocument"
                                                        :hasFile="hasFile('Company-Document')"
                                                        :errors="errors"
                                                        :error="error"
                                                        :docType="'Company-Document'"
                                                        docTitle="Company Documents"
                                                        :success="success"
                                                    />

                                                
                                                    <CompanyFileUploadComponent
                                                        :documentModel="company"
                                                        @file-value-changed="submitDocument"
                                                        @file-deleted="deleteDocument"
                                                        :hasFile="hasFile('CIPC-Certificate')"
                                                        :errors="errors"
                                                        :error="error"
                                                        docTitle="CIPC Certificate"
                                                        :docType="'CIPC-Certificate'"
                                                        :success="success"
                                                    />

                                                
                                                    <CompanyFileUploadComponent
                                                        :documentModel="company"
                                                        @file-value-changed="submitDocument"
                                                        @file-deleted="deleteDocument"
                                                        :hasFile="hasFile('BEE-Certificate')"
                                                        :errors="errors"
                                                        :error="error"
                                                        docTitle="BEE Certificate"
                                                        :docType="'BEE-Certificate'"
                                                        :success="success"
                                                    />
                                               

                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 columns">
                                                <ul class="list-group">                                                   
                                                        <CompanyFileUploadComponent
                                                            :documentModel="company"
                                                            @file-value-changed="submitDocument"
                                                            @file-deleted="deleteDocument"
                                                            :hasFile="hasFile('LTA-Certificate')"
                                                            :errors="errors"
                                                            :error="error"
                                                            docTitle="LTA Certificate"
                                                            :docType="'LTA-Certificate'"
                                                            :success="success"
                                                        />


                                                        <CompanyFileUploadComponent
                                                            :documentModel="company"
                                                            @file-value-changed="submitDocument"
                                                            @file-deleted="deleteDocument"
                                                            :hasFile="hasFile('SARS-Certificate')"
                                                            :errors="errors"
                                                            :error="error"
                                                            docTitle="SARS Certificate"
                                                            :docType="'SARS-Certificate'"
                                                            :success="success"
                                                        />
                                                  
                                                </ul>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <hr class="vertical dark" />
                            </div>

                            <div class="col-sm-12 col-md-4 col-xl-4" style="margin-top: 1rem;">
                                <div class="row">

                                    <TextInputComponent
                                        :inputType="'text'"
                                        v-model="form.business_address"
                                        :value="form.business_address"
                                        :column="'col-sm-12 col-md-6 col-lg-6 col-xl-6'"
                                        :label="'Business Address Line 1'"
                                        :errors="errors.business_address"
                                        :input_id="business_address"
                                    />


                                    <TextInputComponent
                                        :inputType="'text'"
                                        v-model="form.business_address2"
                                        :value="form.business_address2"
                                        :column="'col-sm-12 col-md-6 col-lg-6 col-xl-6'"
                                        :label="'Business Address Line 2'"
                                        :errors="errors.business_address2"
                                        :input_id="business_address2"
                                    />

                                    <TextInputComponent
                                        :inputType="'text'"
                                        v-model="form.business_address3"
                                        :value="form.business_address3"
                                        :column="'col-sm-12 col-md-6 col-lg-6 col-xl-6'"
                                        :label="'Business Address Line 3'"
                                        :errors="errors.business_address3"
                                        :input_id="business_address3"
                                    />




                                    <div class="col-6 columns">
                                        <div class="input-group input-group-outline null is-filled">
                                            <label class="form-label">Province</label>
                                            <select class="form-control form-control-default" v-model="form.postal_province" >
                                                <option :value="''" disabled selected >Select Province</option>
                                                <option v-for='province in computedProvinces' :key="province" :value=province> {{ province }}</option>
                                            </select>
                                        </div>
                                        <div v-if="errors.postal_province" class="text-danger">{{ errors.postal_province }}</div>
                                    </div>

                                    <TextInputComponent
                                        :inputType="'text'"
                                        v-model="form.business_address_postal_code"
                                        :value="form.business_address_postal_code"
                                        :column="'col-6'"
                                        :label="'Postal Code'"
                                        :errors="errors.business_address_postal_code"
                                        :input_id="business_address_postal_code"
                                    />


                                    <hr>

                                    <div class="col-12 columns">
                                        <div class="input-group input-group-outline is-filled">
                                            <input style="margin-top: -9px; margin-right: 3px;" @change="copyBusinessAddress"
                                                   type="checkbox"
                                                   :checked="form.postal_address == form.business_address &&
        form.postal_address2 == form.business_address2 &&
        form.postal_address3 == form.business_address3" id="same-address"/>
                                            <label for="same-address">Same as Business Address</label>
                                        </div>
                                        <div v-if="errors.postal_address" class="text-danger">{{ errors.postal_address }}</div>
                                    </div>


                                    <TextInputComponent
                                        :inputType="'text'"
                                        v-model="form.postal_address"
                                        :value="form.postal_address"
                                        :column="'col-12'"
                                        :label="'Postal Address Line 1'"
                                        :errors="errors.postal_address"
                                        :input_id="postal_address"
                                    />


                                    <TextInputComponent
                                        :inputType="'text'"
                                        v-model="form.postal_address2"
                                        :value="form.postal_address2"
                                        :column="'col-12'"
                                        :label="'Postal Address Line 2'"
                                        :errors="errors.postal_address2"
                                        :input_id="postal_address2"
                                    />


                                    <TextInputComponent
                                        :inputType="'text'"
                                        v-model="form.postal_address3"
                                        :value="form.postal_address3"
                                        :column="'col-12'"
                                        :label="'Postal Address Line 3'"
                                        :errors="errors.postal_address3"
                                        :input_id="postal_address3"
                                    />


                                    <div class="col-6 columns">
                                        <div class="input-group input-group-outline null is-filled">
                                            <label class="form-label">Province</label>
                                            <select class="form-control form-control-default" v-model="form.business_province" >
                                                <option :value="''" disabled selected >Select Province</option>
                                                <option v-for='province in computedProvinces' :key="province" :value=province> {{ province }}</option>
                                            </select>
                                        </div>
                                        <div v-if="errors.business_province" class="text-danger">{{ errors.business_province }}</div>
                                    </div>


                                    <TextInputComponent
                                        :inputType="'text'"
                                        v-model="form.postal_code"
                                        :value="form.postal_code"
                                        :column="'col-6'"
                                        :label="'Postal Code'"
                                        :errors="errors.postal_code"
                                        :input_id="postal_code"
                                    />


                                </div>
                            </div>


                            <div v-if="$page.props.auth.has_slowtow_admin_role">
                                <button type="submit" class="btn btn-secondary ms-2"  :disabled="form.processing" :style="{float: 'right'}">
                                    <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Save</button>
                            </div>

                        </form>
                        <hr>

                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-lg-4"></div>
                    <div class="col-sm-12 col-lg-4">
                        <h6 class="text-center">Licences Linked To : {{ company.name ? company.name : '' }}</h6>
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
                            <tr v-for="licence in linked_licences.data" :key="licence.id">
                                <td>
                                    <div class="d-flex px-2">
                                        <div class="d-flex flex-column">
                                            <Link :href="`/company/view-licences/${licence.slug }`"><h6 class="mb-0 text-sm">{{ licence.trading_name ? licence.trading_name : ''  }}</h6></Link>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center"><Link :href="`/company/view-my-licences/${licence.slug }`">{{ licence.licence_number ? licence.licence_number : '' }}</Link></td>
                                <td class="text-center"><Link :href="`/company/view-my-licences/${licence.slug }`">{{ licence.licence_date }}</Link></td>
                                <td class="text-center">
                                    <Link :href="`/company/view-my-licences/${licence.slug }`" class="mx-2 ms-2 justify-content-center">
                                        <i class="fa fa-eye"></i></Link>
                                </td>

                            </tr>


                            </tbody>
                        </table>
                    </div>
                    <Paginate
                        :modelName="linked_licences"
                        :modelType="ViewCompany"
                    />
                    <hr>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4"></div>
                    <div class="col-12 col-lg-4">
                        <h6 class="text-center">People Linked To : {{ company.name }}</h6>
                    </div>
                  
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
                            <tr v-if="company.people?.length > 0" v-for="person in company.people" :key="person.id">
                                <td>
                                    <div class="d-flex px-2">

                                        <div class="d-flex flex-column">
                                            <Link :href="`/company/view-my-person/${person.slug}`"><h6 class="mb-0 text-sm">{{ person.full_name ? person.full_name : '' }}</h6></Link>
                                        </div>
                                    </div>
                                </td>
                                <td class="mx-8">
                                    <div class="input-group input-group-outline null mx-5 is-filled">
                                        <div class="mb-3 mx-10">
                                            <div>{{ person.pivot.position}}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end ">
                                    <div class="d-flex float-end ">
                                        <Link :href="`/company/view-my-person/${person.slug}`"><h6 class="mb-0 text-sm"><i class="fa fa-eye" aria-hidden="true"></i></h6></Link>
                                    </div>
                                </td>

                            </tr>
                           
                            <tr v-else>
                                <td colspan="6" class="text-center text-danger">No people Found.</td>
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

<script src="../../Companies/view_company.js"></script>

