<template>
  <nav aria-label="..." class="justify-content-lg-end">
    <div v-if="links.length > 3">
    <ul class="pagination">
      <template v-for="(link,index) in links" :key="link.id">
        <li class="page-item active" aria-current="page">
          <div v-if="link.url === null" :key="key" v-html="link.label"/>
          <Link v-else :key="`link-${key}`" class="mb-1 mr-1 px-4 py-3 focus:text-indigo-500 text-sm leading-4 hover:bg-white border focus:border-indigo-500 rounded" :class="{ 'bg-white': link.active }" :href="link.url" v-html="link.label" />
        </li>
      </template>      
    </ul>
  </div>
  </nav>
</template>
<script>
  export default {
    props: {
      links: Array,
    },
  }
  </script> 