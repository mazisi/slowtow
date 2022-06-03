<template>
  <footer class="py-3 footer">
    <div class="container-fluid">
      <div class="row align-items-center justify-content-lg-between">
        
        <div class="col-lg-6">
          <ul
            class="nav nav-footer "
          >
            <li class="nav-item">
              <inertia-link
                href="/settings"
                class="nav-link text-muted"
                target="_blank"> You are logged in as: {{ user.name }}</inertia-link>
            </li>
          </ul>
        </div>

        <div class="col-lg-6">
        <ul class="nav nav-footer d-flex justify-content-center justify-content-lg-end">
            <li class="nav-item">
              <a href="#" class="nav-link text-muted" target="_blank"> Copyright &copy; {{ new Date().getFullYear() }} Slotow Family Trust </a
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
