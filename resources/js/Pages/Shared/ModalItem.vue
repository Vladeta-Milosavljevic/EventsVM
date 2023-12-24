<script setup>
defineProps({
  show: Boolean
})
</script>

<template>

  <Transition
    enter-from-class="opacity-0 scale-125"
    enter-to-class="opacity-100 scale-100"
    enter-active-class="transition duration-300"
    leave-from-class="opacity-100 scale-100"
    leave-to-class="opacity-0 scale-125"
    leave-active-class="transition duration-200"
  >
    <div v-if="show" class="inset-0 fixed grid items-center justify-items-center overflow-auto  bg-slate-400 bg-opacity-30 dark:bg-opacity-10" @click="$emit('remove')">
      <div class="bg-white p-4 w-1/2 max-w-4/5 min-h-3/4 max-h-4/5  text-black dark:bg-slate-700 dark:text-slate-100 grid items-center rounded-lg" @click.stop="">
        <header>
          <slot name="header">Header text</slot>
        </header>
        <div>
          <slot>main body</slot>
        </div>
        <footer class="modal-footer">
          <slot name="footer">Footer</slot>
        </footer>
      </div>
    </div>
  </Transition>
</template>



