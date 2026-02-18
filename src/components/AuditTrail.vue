<script setup lang="js">
import { computed } from 'vue';
import { MOCK_AUDIT_LOGS } from '../services/mockData';

const props = defineProps({
  auditLogs: {
    type: Array,
    default: () => [],
  },
});

const logs = computed(() => (props.auditLogs && props.auditLogs.length ? props.auditLogs : MOCK_AUDIT_LOGS));
</script>

<template>
  <div class="space-y-6">
    <h2 class="text-xl md:text-2xl font-bold text-slate-800">Audit Trail</h2>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
      <table class="w-full text-left min-w-[600px]">
        <thead class="bg-slate-50 border-b border-slate-200">
          <tr>
            <th class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase">Timestamp</th>
            <th class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase">User</th>
            <th class="px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="log in logs" :key="log.id" class="hover:bg-slate-50">
            <td class="px-6 py-4 text-xs text-slate-500">
              {{ new Date(log.timestamp).toLocaleString([], { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) }}
            </td>
            <td class="px-6 py-4 text-xs font-medium text-slate-800">{{ log.userId }}</td>
            <td class="px-6 py-4 text-xs text-slate-600">{{ log.details }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
