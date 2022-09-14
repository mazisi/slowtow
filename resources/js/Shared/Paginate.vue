<template>
  <nav aria-label="..." class="justify-content-lg-end">
    <ul class="pagination">
      <template v-for="(link,index) in links" :key="link.id">
        <li class="page-item active" aria-current="page">
          <div v-html="link.label"></div>
        </li>
      </template>
      
    </ul>
  </nav>
</template>
<script>
  export default {
    props: {
      links: Array,
    },
  }
  </script> 