<script setup lang="js">
defineProps({
  title: String,
  value: [String, Number],
  icon: String,
  color: String,
  trend: String,
});
</script>

<template>
  <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
    <div>
      <p class="text-[11px] md:text-xs font-semibold text-slate-500">{{ title }}</p>
      <p class="text-xl md:text-2xl font-bold text-slate-900 mt-1">{{ value }}</p>
      <p v-if="trend" class="text-[11px] text-emerald-600 font-semibold mt-1">↑ {{ trend }} this week</p>
    </div>
    <div class="w-11 h-11 md:w-12 md:h-12 rounded-2xl flex items-center justify-center text-lg md:text-xl shadow-inner"
      :class="[color, 'bg-opacity-10 text-slate-700 border border-slate-100']">
      {{ icon }}
    </div>
  </div>
</template>
