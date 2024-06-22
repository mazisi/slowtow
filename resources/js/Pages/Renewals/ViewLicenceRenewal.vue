<template>
  <Layout>
    <Head title="View Renewal" />
    <div class="container-fluid">
      <Banner/>
      <div class="card card-body mx-3 mx-md-4 mt-n6">
        <div class="row">
          <div class="col-lg-9 col-9">
            <h6>Process Renewal for: {{ renewal.date  }}/{{ getRenewalYear(renewal.date)  }}
              <Link @click="redirect(renewal.licence)" class="text-success">: {{ renewal.licence.trading_name }}</Link></h6>
            <p class="text-sm mb-0">Current Stage:

              <span class="font-weight-bold ms-1">{{ getStatus(renewal.status)}}</span>
            </p>

          </div>
          <div class="col-lg-3 col-3 my-auto text-end">
            <button v-if="$page.props.auth.has_slowtow_admin_role" @click="deleteRenewal" type="button" class="btn btn-sm btn-danger float-lg-end pe-4"> Delete</button>
          </div>
        </div>

        <div class="row">
          <div class="mt-3 row">
            <div class="col-12 col-md-12 col-xl-12 position-relative">
              <div class="card card-plain h-100">
                <div class="p-3 card-body">
              <!--  start injecting components-->
                  <form @submit.prevent="updateRenewal">

                    <div class="row">

                      <StageComponent
                          :column=12
                          :dbStatus="renewal.status"
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
                          :documentModel="renewal"
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


                      <StageComponent
                          :column=12
                          :dbStatus="renewal.status"
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
                          :documentModel="renewal"
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

                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=300
                          :prevStage=200
                          :licence_id=null
                          :stageTitle="'Client Paid'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'Client Paid'"
                          :model="renewal"
                          :licence="client_paid_at"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Client Paid').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"
                      />

                      <!-- <DocComponent
                          :documentModel="renewal"
                          @file-value-changed="submitDocument"
                          @file-deleted="deleteDocument"
                          :hasFile="hasFile('Client Paid')"
                          :errors="errors"
                          :error="error"
                          :orderByNumber=100
                          :docType="'Client Paid'"
                          :success="success"
                          :stage="300"
                      /> -->

                      <hr>
                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
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
                          :model="renewal"
                          :licence="payment_to_liquor_board_at"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Payment To The Liquor Board').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"

                      />
                      <DocComponent
                          :documentModel="renewal"
                          @file-value-changed="submitDocument"
                          @file-deleted="deleteDocument"
                          :hasFile="hasFile('Payment To The Liquor Board')"
                          :errors="errors"
                          :error="error"
                          :orderByNumber=400
                          :docType="'Payment To The Liquor Board'"
                          :success="success"
                          :stage="400"
                      />


                      <hr>

                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=500
                          :prevStage=400
                          :licence_id=null
                          :stageTitle="'Renewal Issued'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'Renewal Issued'"
                          :model="renewal"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Renewal Issued').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"
                      />
                      <DocComponent
                          :documentModel="renewal"
                          @file-value-changed="submitDocument"
                          @file-deleted="deleteDocument"
                          :hasFile="hasFile('Renewal Issued')"
                          :errors="errors"
                          :error="error"
                          :orderByNumber=500
                          :docType="'Renewal Issued'"
                          :success="success"
                          :stage="500"
                      />


                      <hr>
                      <StageComponent
                          :column=6
                          :dbStatus="renewal.status"
                          :errors="errors"
                          :error="error"
                          :stageValue=600
                          :prevStage=500
                          :licence_id=null
                          :stageTitle="'Renewal Delivered'"
                          :success="success"
                          @stage-value-changed="pushData"
                      />

                      <DateComponent
                          :stage="'Renewal Delivered'"
                          :model="renewal"
                          :canSave="$page.props.auth.has_slowtow_admin_role"
                          :errors="errors"
                          :error="error"
                          :column=5
                          :dated_at="getRenewalDate('Renewal Delivered').dated_at"
                          @date-value-changed="updateDate"
                          :success="success"
                      />
                      <DocComponent
                          :documentModel="renewal"
                          @file-value-changed="submitDocument"
                          @file-deleted="deleteDocument"
                          :hasFile="hasFile('Renewal Delivered')"
                          :errors="errors"
                          :error="error"
                          :orderByNumber=600
                          :docType="'Renewal Delivered'"
                          :success="success"
                          :stage="600"
                      />

                    </div>
                  </form>
                </div>
              </div>
              <hr class="vertical dark" />
            </div>
            <!-- //tasks were here -->

          </div>

        </div>
        <hr>

        <Task

            :tasks="tasks"
            :model_id="renewal.id"
            :success="success"
            :error="error"
            :errors="errors"
            :model_type="'Licence Renewal'"/>

      </div>
    </div>


  </Layout>
</template>
<style scoped>
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

.limit-file-namee{
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 200px;
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>

<script src="./renewals.js"></script>
