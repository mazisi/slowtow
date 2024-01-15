<script src="./view_nomination.js"></script>
<style>
.columns{
    margin-bottom: 1rem;
}
.active-checkbox{
    margin-top: -10px;
    margin-left: 3px;
}
.status-heading{
    font-weight: 700;
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
<template>
    <Layout>
        <Head title="View Nomination" />
        <div class="container-fluid">
            <Banner/>
            <div class="card card-body mx-3 mx-md-4 mt-n6">
                <div class="row">
                    <div class="col-lg-10 col-10">
                        <h6><Link :href="`/view-licence/?slug=${nomination.licence.slug}`" class="text-success">
                            {{ nomination.licence.trading_name ? nomination.licence.trading_name : '' }}</Link> - {{ nomination.year }} </h6>
                        <!--refactor this code -->
                        <p class="text-sm mb-0">Current Stage:
                            <span class="font-weight-bold ms-1" >{{getStatus(nomination.status)}}</span>
                        </p>
                        <!--end refactor-->

                    </div>
                    <div class="col-lg-2 col-2 my-auto text-end">
                        <button v-if="$page.props.auth.has_slowtow_admin_role"
                                @click="deleteNomination" type="button" class="btn btn-sm btn-danger float-lg-end pe-4"> Delete</button>

                    </div>

                </div>

                <div class="row">
                    <div class="mt-3 row">
                        <div class="col-12 col-md-12 col-xl-12 position-relative">
                            <div class="card card-plain h-100">
                                <div class="p-3 card-body">
                                    <form @submit.prevent="updateNomination">

                                        <div class="row">
                                            <!--  client quoted-->
                                            <StageComponent
                                                :column=12
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=100
                                                :prevStage=null
                                                :licence_id=null
                                                :stageTitle="'Client Quoted'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />
                                            <DocComponent
                                                :documentModel="nomination"
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :hasFile="hasFile('Client Quoted')"
                                                :errors="errors"
                                                :error="error"
                                                :orderByNumber=100
                                                :docType="'Client Quoted'"
                                                :success="success"
                                                :stage="100"
                                            />

                                            <hr>
                                            <!-- Client Invoiced -->
                                            <StageComponent
                                                :column=12
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=200
                                                :prevStage=100
                                                :licence_id=null
                                                :stageTitle="'Client Invoiced'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />
                                            <DocComponent
                                                :documentModel="nomination"
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :hasFile="hasFile('Client Invoiced')"
                                                :errors="errors"
                                                :error="error"
                                                :orderByNumber=200
                                                :docType="'Client Invoiced'"
                                                :success="success"
                                                :stage="200"
                                            />
                                            <hr>
                                            <!-- Client Paid -->
                                            <StageComponent
                                                :column=5
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=300
                                                :prevStage=null
                                                :licence_id=null
                                                :stageTitle="'Client Paid'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />
                                            <DateComponent
                                                :stage="'Client Paid'"
                                                :model="nomination"
                                                :licence="client_paid_at"
                                                :canSave="$page.props.auth.has_slowtow_admin_role"
                                                :errors="errors"
                                                :error="error"
                                                :column=5
                                                :dated_at="nomination.client_paid_at"
                                                :success="success"
                                                @date-value-changed="updateDate"
                                            />
                                            <DocComponent
                                                :documentModel="nomination"
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :hasFile="hasFile('Client Paid')"
                                                :errors="errors"
                                                :error="error"
                                                :orderByNumber=300
                                                :docType="'Client Paid'"
                                                :success="success"
                                                :stage="100"
                                            />

                                            <hr>

                                            <!-- Payment To The Liquor Board -->
                                            <StageComponent
                                                :column=5
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=400
                                                :prevStage=300
                                                :licence_id=null
                                                :stageTitle="'Payment To The Liquor Board'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />
                                            <DateComponent
                                                :stage="'Payment To The Liquor Board'"
                                                :model="nomination"
                                                :licence="payment_to_liquor_board_at"
                                                :canSave="$page.props.auth.has_slowtow_admin_role"
                                                :errors="errors"
                                                :error="error"
                                                :column=5
                                                :dated_at="nomination.payment_to_liquor_board_at"
                                                :success="success"
                                                @date-value-changed="updateDate"
                                            />
                                            <DocComponent
                                                :documentModel="nomination"
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :hasFile="hasFile('Payment To The Liquor Board')"
                                                :errors="errors"
                                                :error="error"
                                                :orderByNumber=400
                                                :docType="'Payment To The Liquor Board'"
                                                :success="success"
                                                :stage="100"
                                            />

                                            <hr>

                                            <!-- Select Person(s) To Nominate -->
                                            <StageComponent
                                                :column=10
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=500
                                                :prevStage=400
                                                :licence_id=null
                                                :stageTitle="'Select Person(s) To Nominate'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />
                                            <!--add nominiees button-->
                                            <div class="col-md-1">
                                                <button @click="show_modal=true" type="button" data-bs-toggle="modal" data-bs-target="#pop-modal" class="btn btn-sm btn-secondary">Add</button>
                                            </div>

                                            <!-- Nominiees Table -->
                                            <div  class="table-responsive mb-2">
                                                <table  class="table align-items-center mb-0">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                            Full Name And Surname
                                                        </th>
                                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                            Date OF Birth
                                                        </th>
                                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                            ID Number
                                                        </th>

                                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                            Action
                                                        </th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-if="nomination.people" v-for="person in nomination.people" :key="person.id" >
                                                        <td>
                                                            <div class="d-flex px-2 py-1" >

                                                                <div class="d-flex flex-column justify-content-left">
                                                                    <Link :href="`/view-person/${person.slug}`"><h6 class="mb-0 text-sm">{{ person.full_name }} </h6></Link>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">{{ person.date_of_birth }}</td>
                                                        <td class="text-center">{{ person.id_or_passport }}</td>
                                                        <td class="text-center">
                                                            <i @click="removeSelectedNominee(person.id)"
                                                               style="cursor: pointer;"
                                                               class="material-icons-round text-danger fs-10">highlight_off</i>
                                                            <Link :href="`/view-person/${person.slug}`">
                                                                <i @click="removeSelectedNominee(person.id)"
                                                                   style="cursor: pointer;"
                                                                   class="material-icons-round text-secondary fs-10">visibility</i>
                                                            </Link>
                                                        </td>
                                                    </tr>
                                                    <tr v-else >
                                                        <td></td>
                                                        <td><p class="text-danger text-center">No nominees found.</p></td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                                <!-- End Nominiees Table -->
                                            </div>
                                            <hr>

                                            <!-- Prepare Nomination Application -->
                                            <StageComponent
                                                :column=10
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=600
                                                :prevStage=500
                                                :licence_id=null
                                                :stageTitle="'Prepare Nomination Application'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />


                                            <div class="col-md-4 columns">
                                                <ul class="list-group">
                                                    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">

                                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm"> Nomination Forms </h6>

                                                        </div>
                                                        <div class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
                                                        <DocComponent
                                                            :documentModel="nomination"
                                                            @file-value-changed="submitDocument"
                                                            @file-deleted="deleteDocument"
                                                            :hasFile="hasFile('Nomination Forms')"
                                                            :errors="errors"
                                                            :error="error"
                                                            :orderByNumber=100
                                                            :docType="'Nomination Forms'"
                                                            :success="success"
                                                            :stage="600"
                                                        />
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="col-md-4 columns">
                                                <ul class="list-group">
                                                    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
                                                        <div class="avatar me-3" >
                                                        </div>
                                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">Proof Of Payment</h6>
                                                        </div>
                                                        <a v-if="hasFile('Payment To The Liquor Board').id"
                                                           :href="`${$page.props.blob_file_path}${hasFile('Payment To The Liquor Board').docPath}`" target="_blank" class="mb-0 btn btn-link ms-auto" >
                                                            <i class="fa fa-link h5 " aria-hidden="true"></i></a>
                                                        <a v-else style="cursor: no-drop;" title="Liqour document not uploaded" class="mb-0 btn btn-link ms-auto" >
                                                            <i class="fa fa-link h5" aria-hidden="true"></i></a>
                                                    </li>
                                                </ul>
                                            </div>


                                            <div class="col-md-4 columns">
                                                <ul class="list-group">
                                                    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
                                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">Signed Power Of Attorney And Resolution</h6>
                                                        </div>
                                                        <div class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
                                                        <DocComponent
                                                            :documentModel="nomination"
                                                            @file-value-changed="submitDocument"
                                                            @file-deleted="deleteDocument"
                                                            :hasFile="hasFile('Power of Attorney')"
                                                            :errors="errors"
                                                            :error="error"
                                                            :orderByNumber=100
                                                            :docType="'Power of Attorney'"
                                                            :success="success"
                                                            :stage="600"
                                                        />
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="col-md-4 columns">
                                                <ul class="list-group">
                                                    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
                                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">ID Documents</h6>
                                                        </div>
                                                        <div class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
                                                            <DocComponent
                                                                :documentModel="nomination"
                                                                @file-value-changed="submitDocument"
                                                                @file-deleted="deleteDocument"
                                                                :hasFile="hasFile('ID Document')"
                                                                :errors="errors"
                                                                :error="error"
                                                                :orderByNumber=100
                                                                :docType="'ID Document'"
                                                                :success="success"
                                                                :stage="600"
                                                            />
                                                        </div>

                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="col-md-4 columns">
                                                <ul class="list-group">
                                                    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
                                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">Police Clearances</h6>
                                                        </div>
                                                        <div class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
                                                        <DocComponent
                                                            :documentModel="nomination"
                                                            @file-value-changed="submitDocument"
                                                            @file-deleted="deleteDocument"
                                                            :hasFile="hasFile('Police Clearances')"
                                                            :errors="errors"
                                                            :error="error"
                                                            :orderByNumber=100
                                                            :docType="'Police Clearances'"
                                                            :success="success"
                                                            :stage="600"
                                                        />
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="col-md-4 columns">
                                                <ul class="list-group">
                                                    <li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
                                                        <div class="d-flex align-items-start flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">Latest Renewal/Licence</h6>
                                                        </div>
                                                        <div class="mb-0 btn btn-link pe-3 ps-0 ms-auto">
                                                        <DocComponent
                                                            :documentModel="nomination"
                                                            @file-value-changed="submitDocument"
                                                            @file-deleted="deleteDocument"
                                                            :hasFile="hasFile('Latest Renewal/Licence')"
                                                            :errors="errors"
                                                            :error="error"
                                                            :orderByNumber=100
                                                            :docType="'Latest Renewal/Licence'"
                                                            :success="success"
                                                            :stage="600"
                                                        />
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="text-end">
                                              <MergeNominationDocs
                                                :mergeCount="canMerge()"
                                                :nomination="nomination"
                                                @mergeDocs="mergeDocument()"
                                              />
                                            </div>
                                            <hr>

                                            <!-- Scanned Application -->
                                            <StageComponent
                                                :column=10
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=700
                                                :prevStage=600
                                                :licence_id=null
                                                :stageTitle="'Scanned Application'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />

                                            <DocComponent
                                                :documentModel="nomination"
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :hasFile="hasFile('Scanned Application')"
                                                :errors="errors"
                                                :error="error"
                                                :orderByNumber=700
                                                :docType="'Scanned Application'"
                                                :success="success"
                                                :stage="700"
                                            />
                                            <hr/>

                                            <!-- Application Lodged -->
                                            <StageComponent
                                                :column=5
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=800
                                                :prevStage=700
                                                :licence_id=null
                                                :stageTitle="'Application Lodged'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />
                                            <DateComponent
                                                :stage="'Nomination Lodged'"
                                                :model="nomination"
                                                :licence="nomination_lodged_at"
                                                :canSave="$page.props.auth.has_slowtow_admin_role"
                                                :errors="errors"
                                                :error="error"
                                                :column=5
                                                :dated_at="nomination.nomination_lodged_at"
                                                :success="success"
                                                @date-value-changed="updateDate"
                                            />
                                            <DocComponent
                                                :documentModel="nomination"
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :hasFile="hasFile('Nomination Lodged')"
                                                :errors="errors"
                                                :error="error"
                                                :orderByNumber=800
                                                :docType="'Nomination Lodged'"
                                                :success="success"
                                                :stage="100"
                                            />

                                            <hr>

                                            <!-- Nomination Issued -->
                                            <StageComponent
                                                :column=5
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=900
                                                :prevStage=800
                                                :licence_id=null
                                                :stageTitle="'Nomination Issued'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />
                                            <DateComponent
                                                :stage="'Nomination Issued'"
                                                :model="nomination"
                                                :licence="nomination_issued_at"
                                                :canSave="$page.props.auth.has_slowtow_admin_role"
                                                :errors="errors"
                                                :error="error"
                                                :column=5
                                                :dated_at="nomination.nomination_issued_at"
                                                :success="success"
                                                @date-value-changed="updateDate"
                                            />
                                            <DocComponent
                                                :documentModel="nomination"
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :hasFile="hasFile('Nomination Issued')"
                                                :errors="errors"
                                                :error="error"
                                                :orderByNumber=900
                                                :docType="'Nomination Issued'"
                                                :success="success"
                                                :stage="900"
                                            />

                                            <hr>
                                            <!-- Nomination Delivered -->
                                            <StageComponent
                                                :column=5
                                                :dbStatus="nomination.status"
                                                :errors="errors"
                                                :error="error"
                                                :stageValue=1000
                                                :prevStage=900
                                                :licence_id=null
                                                :stageTitle="'Nomination Delivered'"
                                                :success="success"
                                                @stage-value-changed="pushData"
                                            />
                                            <DateComponent
                                                :stage="'Nomination Delivered'"
                                                :model="nomination"
                                                :licence="nomination_delivered_at"
                                                :canSave="$page.props.auth.has_slowtow_admin_role"
                                                :errors="errors"
                                                :error="error"
                                                :column=5
                                                :dated_at="nomination.nomination_delivered_at"
                                                :success="success"
                                                @date-value-changed="updateDate"
                                            />
                                            <DocComponent
                                                :documentModel="nomination"
                                                @file-value-changed="submitDocument"
                                                @file-deleted="deleteDocument"
                                                :hasFile="hasFile('Nomination Delivered')"
                                                :errors="errors"
                                                :error="error"
                                                :orderByNumber=1000
                                                :docType="'Nomination Delivered'"
                                                :success="success"
                                                :stage="100"
                                            />


                                            <hr>

                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr class="vertical dark" />
                        </div>

                    </div>

                    <Task
                        :tasks="tasks"
                        :model_id="nomination.id"
                        :success="success"
                        :error="error"
                        :errors="errors"
                        :model_type="'Nomination'"
                    />

                </div>
            </div>
        </div>


        <div v-if="show_modal" class="modal fade" id="pop-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Select person(s) To Nominate</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="saveNominneesToDatabase">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 columns">
                                    <div class="input-group input-group-outline null is-filled">
                                        <Multiselect
                                            v-model="nomineeForm.selected_nominess"
                                            placeholder="Search..."
                                            mode="tags"
                                            :options="options"
                                            :searchable="true"
                                        />
                                    </div>
                                    <div v-if="errors.selected_nominess" class="text-danger">{{ errors.selected_nominess }}</div>
                                </div>
                            </div>

                            <div class="col-md-12" v-if="message">
                                <div class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
                                    <span class="alert-icon"><i class=""></i></span>
                                    <span class="alert-text">
                                    <span class="text-sm">{{ message }}</span></span>
                                    <button type="button" class="btn-close d-flex justify-content-center align-items-center"
                                            data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true" class="text-lg font-weight-bold">×</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-secondary" :disabled="nomineeForm.processing">
                                <span v-if="nomineeForm.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </Layout>
</template>


