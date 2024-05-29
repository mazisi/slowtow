<template>
  <footer class="py-3 footer">
    <div class="container-fluid">
      <div class="row align-items-center justify-content-lg-between">
        
        <div class="col-lg-5">
          <ul
            class="nav nav-footer">
            <li class="nav-item">
              <inertia-link
                href="/logout"
                class="nav-link text-muted"> You are logged in as: <b> {{ user.name }}</b><span class="mx-2 text-danger">(Logout)</span></inertia-link>             
            </li>
          </ul>
        </div>
        <div class="col-lg-2 col-sm-12" v-if="!$page.props.auth.has_slowtow_admin_role">
          <ul
            class="nav nav-footer">
            <li data-bs-toggle="modal" data-bs-target="#edit-password" class="nav-item cursor-pointer">
                <span class="badge bg-success text-default"><i class="fa fa-lock mx-1"> </i>Update Password</span>         
            </li>
          </ul>
        </div>
        <div class="col-lg-2 col-sm-12 ms-auto">
          <ul class="nav nav-footer">
            <li class="nav-item" v-if="$page.props.auth.has_slowtow_admin_role">
              <inertia-link href="/slotow-admins" class="nav-link text-muted">
                <span class="badge bg-success text-default">
                  <i class="fa fa-users mx-1"></i> Admins
                </span>
              </inertia-link>
            </li>
          </ul>
        </div>
        
        <div class="col-lg-3 col-sm-12">
        <ul class="nav nav-footer d-flex justify-content-left justify-content-lg-end">
            <li class="nav-item">
              <a href="#" class="nav-link text-muted"> Copyright &copy; {{ new Date().getFullYear() }} Slotow Family Trust </a
              >
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
import { computed } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';

export default {
  name: "app-footer",
    setup() {
    const user = computed(() => usePage().props.value.auth.user)
    return { user }
  },
};
</script>
