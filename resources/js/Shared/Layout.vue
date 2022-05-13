<template>
  <main>
    <ul class="nav">
  <li class="nav-item">
    <inertia-link href="/" class="nav-link active" aria-current="page" >Home</inertia-link>
  </li>
  <li class="nav-item">
    <inertia-link href="about" class="nav-link">About</inertia-link>
  </li>
  <li class="nav-item">
    <inertia-link href="#" class="nav-link">Contact</inertia-link>
  </li>
</ul>

    <article>
      <slot />
    </article>
  </main>
</template>

