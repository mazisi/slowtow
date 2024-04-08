<template>
    <Layout>
        <Head title="Licences" />
        <div class="container-fluid">
            <!-- <h5 class="text-center">New Applications</h5> -->
            <Banner/>

            <div class="card card-body mt-n6">
                <Navigation/>
                <div class="col-12">
                    <div class="row">
                        <div  class="col-md-12 col-xl-12 col-lg-12">
                            <div class="input-group input-group-outline null is-filled">
                                <i class="fa fa-search h4"></i>&nbsp;&nbsp;&nbsp;
                                <input v-model="term" placeholder="Search New Apps" type="text" class="form-control form-control-default" :autofocus="true">
                            </div>
                        </div>

                        <div class="col-md-3 col-xl-3 col-lg-3 filters">
                            <div class="input-group input-group-outline null is-filled">
                                <select @change="search" v-model="form.status" class="form-control form-control-default centered-select">
                                    <option :value="''" disabled selected>Filter By Stage</option>
                                    <option v-for="status in statuses" :key="status.value" :value="status.value">{{ status.name }}</option>

                                </select>

                            </div>
                        </div>

                        <div class="col-md-3 col-xl-3 col-lg-3 filters">
                            <div class="input-group input-group-outline null is-filled">
                                <select @change="search" v-model="form.province" class="form-control form-control-default centered-select">
                                    <option :value="''" disabled selected>Province</option>
                                    <option v-for="province in computedProvinces" :key="province" :value=province>{{ province }}</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3 col-xl-3 col-lg-3 filters">
                            <div class="input-group input-group-outline null is-filled">
                                <select @change="search" v-model="form.licence_type" class="form-control form-control-default centered-select centered-select">
                                    <option :value="''" disabled selected>Licence Type</option>
                                    <option v-for='licence_type in all_licence_types' :value=licence_type.id> {{ licence_type.licence_type }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-xl-3 col-lg-3 filters">
                            <div class="input-group input-group-outline null is-filled">
                                <select @change="search" v-model="form.licence_date" class="form-control form-control-default centered-select">
                                    <option :value="''" disabled selected>Licence Date</option>
                                    <option v-for="month in months" :value="month.id" :key="month.id">{{ month.name }}</option>
                                </select>
                            </div>
                        </div>





                    </div>
                    <div class="my-4">

                        <div class="px-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table table-striped table-hover" style="font-size:85%; width: 100%">
                                    <thead>
                                    <tr class="text" style="font-size: 16px">
                                        <!-- <th class="w-10">Act</th> -->
                                        <th class="ps-2 text-center">Trading Name</th>
                                        <th class="ps-2 text-center">Licence Number</th>
                                        <!-- <th class="ps-2 text-center">Licence Date</th> -->
                                        <th class="ps-2 text-center">Licence Type</th>
                                        <!-- <th class="ps-2 text-center">Company</th> -->
                                        <th class="ps-2 text-center">Action</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="licences.data?.length > 0" v-for="licence in licences.data" :key="licence.id">
                                        <!-- <td v-if="licence.is_licence_active == '1'"><i class="fa fa-check text-success" aria-hidden="true"></i></td>
                                        <td v-else><i class="fa fa-times text-danger" aria-hidden="true"></i></td> -->
                                        <td><Link @click=redirect(licence) data-bs-placement="top" :title="licence.trading_name">{{ licence.trading_name }}</Link></td>
                                        <td><Link @click=redirect(licence) data-bs-placement="top" :title="licence.licence_number ? licence.licence_number : '' ">{{ licence.licence_number ? licence.licence_number : ''}}</Link></td>
                                        <!-- <td><Link @click=redirect(licence) data-bs-placement="top" :title="licence.licence_date">{{ licence.licence_date }}</Link></td> -->
                                        <td><Link @click=redirect(licence) data-bs-placement="top" :title="licence.licence_type.licence_type">{{ licence.licence_type ? licence.licence_type.licence_type : '' }}</Link></td>
                                        <!-- <td><Link @click=redirect(licence)>{{ licence.belongs_to == 'Individual' ? licence.people.full_name :licence.company.name }}</Link></td> -->

                                        <td class="align-middle text-center">
                                            <Link :href="`/email-comms/get-mail-template/${licence.slug}/new-apps`" class="text-secondary text-center font-weight-bold text-xs">
                                                <i class="fa fa-envelope"></i> Send </Link>


                                            <Link @click=redirect(licence) class="text-secondary text-center font-weight-bold text-xs">
                                                <i class="fa fa-eye"></i> View </Link>
                                        </td>

                                    </tr>
                                    <tr v-else>
                                        <td colspan="6" class="text-center text-danger">No New Apps Found.</td>
                                    </tr>

                                    </tbody>
                                </table>


                                <Paginate v-if="licences.data?.length > 0"
                                          :modelName="licences"
                                          :modelType="Licences"
                                />

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>
.filters{
    margin-top: 10px;
}
.table thead th {
    padding: 0;
}

#with-thrashed{
    margin-top: 3px;
    margin-left: 3px;
}
.text-center{
}
</style>

<script src="./new_apps.js"></script>
