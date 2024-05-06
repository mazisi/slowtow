<template>
    <Layout>
        <Head title="Process Application" />
        <div class="container-fluid">
            <Banner/>
            <div class="card card-body mx-3 mx-md-4 mt-n6">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6 class="mb-1 ml-4">Process New Application </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="mt-3 ">
                        <form class="row" @submit.prevent="submit">

                            <div class="col-6">
                                <div class="card card-plain h-100">
                                    <div class="p-3 card-body">

                                        <div class="row" style="margin-top: -1rem;">


                                            <TextInputComponent
                                                :inputType="'text'"
                                                :required="true"
                                                :label="'Trading Name *'"
                                                v-model="form.trading_name"
                                                :column="'col-12'"
                                                :value="form.trading_name"
                                                :errors="errors.trading_name "
                                                :input_id="trading_name"
                                            />


                                            <div class="col-12 columns">
                                                <div class="input-group input-group-outline null is-filled">
                                                    <label class="form-label">Applicant</label>
                                                    <select v-model="form.belongs_to" class="form-control form-control-default" required>
                                                        <option :value="''" disabled selected>Select Applicant</option>
                                                        <option value="Company">Company</option>
                                                        <option value="Individual">Individual</option>
                                                    </select>
                                                </div>
                                                <div v-if="errors.belongs_to" class="text-danger">{{ errors.belongs_to }}</div>
                                            </div>

                                            <ProvinceSelectDropdownComponent
                                               v-if="form.type == 'retail'"
                                                :provinceList="computedProvinces"
                                                :required="true"
                                                :label="'Province'"
                                                :defaultDisabledText="'Select province..'"
                                                :column="'col-12'"
                                                :value="form.province"
                                                v-model="form.province"
                                                :errors="errors.province"
                                                @change="filterLicenceTypes()"
                                            />

                                            <ProvinceSelectDropdownComponent
                                            v-if="form.type == 'wholesale'"
                                                :provinceList="computedProvinces"
                                                :required="true"
                                                :label="'Province'"
                                                :defaultDisabledText="'Select province..'"
                                                :column="'col-12'"
                                                :value="form.province"
                                                v-model="form.province"
                                                :errors="errors.province"
                                                @change="filterLicenceTypes()"
                                            />

                                            <div class="col-12 columns" v-if="form.belongs_to ==='Company'">
                                                <Multiselect
                                                    v-model="form.company"
                                                    placeholder="Search company"
                                                    :options="companies"
                                                    :searchable="true"
                                                    @select="selectApplicant"
                                                    style="margin-top: 1rem;"
                                                />
                                                <div v-if="errors.company" class="text-danger">{{ errors.company }}</div>
                                            </div>

                                            <div class="col-12 columns" v-if="form.belongs_to ==='Individual'">
                                                <Multiselect
                                                    v-model="form.person"
                                                    placeholder="Search Individual"
                                                    :options="persons"
                                                    :searchable="true"
                                                    @select="selectApplicant"
                                                    style="margin-top: 1rem;"
                                                />
                                                <div v-if="errors.person" class="text-danger">{{ errors.person }}</div>
                                            </div>



                                            <TextInputComponent v-if="form.belongs_to ==='Individual'"
                                                                :inputType="'text'"
                                                                :required="true"
                                                                :disabled="true"
                                                                :label="'ID Number'"
                                                                :column="'col-12'"
                                                                :value="get_reg_num_or_id_number"
                                                                :errors="errors.trading_name "
                                                                input_id="belongs-to-company"
                                            />

                                            <TextInputComponent v-if="form.belongs_to ==='Company'"
                                                                :inputType="'text'"
                                                                :required="true"
                                                                :disabled="true"
                                                                :label="'Company Registration Number'"
                                                                :column="'col-12'"
                                                                :value="get_reg_num_or_id_number"
                                                                :errors="errors.trading_name "
                                                                input_id="belongs-to-company"
                                            />


                                            <LicenceTypeDropDownComponent
                                                v-if="form.province !== '' && form.type == 'retail'"
                                                :dropdownList="licenceByProvince"
                                                :label="'Licence Type *'"
                                                :defaultDisabledText="'Select Licence Type'"
                                                :column="'col-12'"
                                                :value="form.licence_type"
                                                v-model="form.licence_type"
                                                :errors="errors.licence_type"
                                                :input_id="licence_type"
                                                :required="true"

                                            />


                                            <LicenceTypeDropDownComponent
                                                v-if="form.type == 'wholesale'"
                                                :dropdownList="wholesaleLicences"
                                                :label="'Licence Type **'"
                                                :defaultDisabledText="'Select...'"
                                                :column="'col-12'"
                                                :value="form.licence_type"
                                                v-model="form.licence_type"
                                                :errors="errors.licence_type"
                                                :input_id="licence_type"
                                                :required="true"

                                            />

                                            <LiquorBoardRegionComponent
                                                :dropdownList="computedBoardRegions"
                                                :label="'Liquor Board Region*'"
                                                :defaultDisabledText="'Select Liquor Board Region'"
                                                :column="'col-12'"
                                                :value="form.board_region"
                                                v-model="form.board_region"
                                                :errors="errors.board_region"
                                                :input_id="board_region"
                                                :required="false"
                                                v-if="form.province !== '' && form.province === 'Gauteng' && form.type == 'retail'"
                                            />



                                        </div>

                                    </div>

                                </div>
                            </div>


                            <div class="col-6">
                                <TextInputComponent
                                    :inputType="'text'"
                                    :label="'Address Line 1'"
                                    v-model="form.address"
                                    :column="'col-12'"
                                    :value="form.address"
                                    :errors="errors.address "
                                    :input_id="address"
                                />

                                <TextInputComponent
                                    :inputType="'text'"
                                    :label="'Address Line 2'"
                                    v-model="form.address2"
                                    :column="'col-12'"
                                    :value="form.address2"
                                    :errors="errors.address2 "
                                    :input_id="address2"
                                />

                                <TextInputComponent
                                    :inputType="'text'"
                                    :label="'Address Line 3'"
                                    v-model="form.address3"
                                    :column="'col-12'"
                                    :value="form.address3"
                                    :errors="errors.address3 "
                                    :input_id="address3"
                                />

                                <TextInputComponent
                                    :inputType="'number'"
                                    :label="'Postal Code'"
                                    v-model="form.postal_code"
                                    :column="'col-12'"
                                    :value="form.postal_code"
                                    :errors="errors.postal_code "
                                    :input_id="postal_code"
                                />

                                <div class="col-12 columns" v-if="form.type == 'wholesale'">
                                    <div class="input-group input-group-outline null is-filled">
                                        <label class="form-label">Import/Export</label>
                                        <select v-model="form.import_export" class="form-control form-control-default" required>
                                            <option :value="''" disabled selected>Choose...</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div v-if="errors.import_export" class="text-danger">{{ errors.import_export }}</div>
                                </div>

                               <TextInputComponent 
                               :inputType="'text'" 
                               v-model="form.coordinates" 
                               :value="form.coordinates" 
                               :column="'col-12'" 
                               :label="'Coordinates'" 
                               :errors="errors.coordinates"
                               :input_id="coordinates" />


                                <div>
                                    <button :disabled="form.processing || filterForm.processing" :style="{float: 'right'}" class="btn btn-sm btn-secondary ms-2" type="submit">
                                        <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        <span class="visually-hidden">Loading...</span> Submit</button>
                                </div>
                            </div>

                        </form>

                    </div>

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
<script src="./create_new_app.js"></script>


