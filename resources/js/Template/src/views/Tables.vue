<template>
  <div class="container-fluid">
    <div
      class="page-header min-height-300 border-radius-xl mt-4"
      style="
        background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      "
    >
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="col-12">
        <div class="card my-4">
          
          <div class="card-body px-0 pb-2">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th
                      class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    >
                      Active
                    </th>
                    <th
                      class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"
                    >
                    
                     Trading Name
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    >
                      Liquor License
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    >
                      OLD LIQUOR LICENCE
                    </th>
                    <th
                      class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                    >
                     Company
                    </th>
                    <th class="text-secondary opacity-7"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr >
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-success"
                        >Active</span
                      >
                    </td>
                    <td>
                      <div class="d-flex px-2 py-1">
                       
                        <div class="d-flex flex-column justify-content-left">
                          <h6 class="mb-0 text-sm">
                          <router-link
                            :to="{ name: 'License-Details' }"
                            class="px-0 nav-link font-weight-bold lh-1"
                            :class="color ? color : 'text-body'"
                          >Test License
                          </router-link>
                           </h6>                          
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0 text-center">GAU000000009</p>
                    </td>
                    
                     <td>
                      <p class="text-xs font-weight-bold mb-0 text-center">GAU000000777777</p>
                    </td>
                    <td class="align-middle text-center">
                      <a
                        href="javascript:;"
                        class="text-secondary font-weight-bold text-xs"
                        data-toggle="tooltip"
                        data-original-title="Edit user"
                      >
                        Test Company
                      </a>
                    </td>
                  </tr>
                  <tr >
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-success"
                        >Active</span
                      >
                    </td>
                    <td>
                      <div class="d-flex px-2 py-1">
                       
                        <div class="d-flex flex-column justify-content-left">
                           <h6 class="mb-0 text-sm">
                          <router-link
                            :to="{ name: 'License-Details' }"
                            class="px-0 nav-link font-weight-bold lh-1"
                            :class="color ? color : 'text-body'"
                          >Test License
                          </router-link>
                           </h6> 
                          
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0 text-center">GAU000000009</p>
                    </td>
                    
                     <td>
                      <p class="text-xs font-weight-bold mb-0 text-center">GAU000000777777</p>
                    </td>
                    <td class="align-middle text-center">
                      <a
                        href="javascript:;"
                        class="text-secondary font-weight-bold text-xs"
                        data-toggle="tooltip"
                        data-original-title="Edit user"
                      >
                        Test Company
                      </a>
                    </td>
                  </tr>
                  <tr >
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-success"
                        >Active</span
                      >
                    </td>
                    <td>
                      <div class="d-flex px-2 py-1">
                       
                        <div class="d-flex flex-column justify-content-left">
                           <h6 class="mb-0 text-sm">
                          <router-link
                            :to="{ name: 'License-Details' }"
                            class="px-0 nav-link font-weight-bold lh-1"
                            :class="color ? color : 'text-body'"
                          >Test License
                          </router-link>
                           </h6> 
                          
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0 text-center">GAU000000009</p>
                    </td>
                    
                     <td>
                      <p class="text-xs font-weight-bold mb-0 text-center">GAU000000777777</p>
                    </td>
                    <td class="align-middle text-center">
                      <a
                        href="javascript:;"
                        class="text-secondary font-weight-bold text-xs"
                        data-toggle="tooltip"
                        data-original-title="Edit user"
                      >
                        Test Company
                      </a>
                    </td>
                  </tr>
                  <tr >
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-success"
                        >Active</span
                      >
                    </td>
                    <td>
                      <div class="d-flex px-2 py-1">
                       
                        <div class="d-flex flex-column justify-content-left">
                           <h6 class="mb-0 text-sm">
                          <router-link
                            :to="{ name: 'License-Details' }"
                            class="px-0 nav-link font-weight-bold lh-1"
                            :class="color ? color : 'text-body'"
                          >Test License
                          </router-link>
                           </h6> 
                          
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0 text-center">GAU000000009</p>
                    </td>
                    
                     <td>
                      <p class="text-xs font-weight-bold mb-0 text-center">GAU000000777777</p>
                    </td>
                    <td class="align-middle text-center">
                      <a
                        href="javascript:;"
                        class="text-secondary font-weight-bold text-xs"
                        data-toggle="tooltip"
                        data-original-title="Edit user"
                      >
                        Test Company
                      </a>
                    </td>
                  </tr>
                  <tr >
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-success"
                        >Active</span
                      >
                    </td>
                    <td>
                      <div class="d-flex px-2 py-1">
                       
                        <div class="d-flex flex-column justify-content-left">
                           <h6 class="mb-0 text-sm">
                          <router-link
                            :to="{ name: 'License-Details' }"
                            class="px-0 nav-link font-weight-bold lh-1"
                            :class="color ? color : 'text-body'"
                          >Test License
                          </router-link>
                           </h6> 
                          
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0 text-center">GAU000000009</p>
                    </td>
                    
                     <td>
                      <p class="text-xs font-weight-bold mb-0 text-center">GAU000000777777</p>
                    </td>
                    <td class="align-middle text-center">
                      <a
                        href="javascript:;"
                        class="text-secondary font-weight-bold text-xs"
                        data-toggle="tooltip"
                        data-original-title="Edit user"
                      >
                        Test Company
                      </a>
                    </td>
                  </tr>
                  <tr >
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-success"
                        >Active</span
                      >
                    </td>
                    <td>
                      <div class="d-flex px-2 py-1">
                       
                        <div class="d-flex flex-column justify-content-left">
                           <h6 class="mb-0 text-sm">
                          <router-link
                            :to="{ name: 'License-Details' }"
                            class="px-0 nav-link font-weight-bold lh-1"
                            :class="color ? color : 'text-body'"
                          >Test License
                          </router-link>
                           </h6> 
                          
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0 text-center">GAU000000009</p>
                    </td>
                    
                     <td>
                      <p class="text-xs font-weight-bold mb-0 text-center">GAU000000777777</p>
                    </td>
                    <td class="align-middle text-center">
                      <a
                        href="javascript:;"
                        class="text-secondary font-weight-bold text-xs"
                        data-toggle="tooltip"
                        data-original-title="Edit user"
                      >
                        Test Company
                      </a>
                    </td>
                  </tr>
                  <tr >
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-success"
                        >Active</span
                      >
                    </td>
                    <td>
                      <div class="d-flex px-2 py-1">
                       
                        <div class="d-flex flex-column justify-content-left">
                           <h6 class="mb-0 text-sm">
                          <router-link
                            :to="{ name: 'License-Details' }"
                            class="px-0 nav-link font-weight-bold lh-1"
                            :class="color ? color : 'text-body'"
                          >Test License
                          </router-link>
                           </h6> 
                          
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0 text-center">GAU000000009</p>
                    </td>
                    
                     <td>
                      <p class="text-xs font-weight-bold mb-0 text-center">GAU000000777777</p>
                    </td>
                    <td class="align-middle text-center">
                      <a
                        href="javascript:;"
                        class="text-secondary font-weight-bold text-xs"
                        data-toggle="tooltip"
                        data-original-title="Edit user"
                      >
                        Test Company
                      </a>
                    </td>
                  </tr>
                 
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
