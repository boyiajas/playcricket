<script setup lang="js">
import { computed } from 'vue';
import { MOCK_MEMBERS } from '../services/mockData';

const props = defineProps({
  members: {
    type: Array,
    default: () => [],
  },
});

const displayMembers = computed(() => (props.members && props.members.length ? props.members : MOCK_MEMBERS));
</script>

<template>
  <div class="space-y-4 md:space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <h2 class="text-xl md:text-2xl font-bold text-slate-800">Members</h2>
      <div class="flex space-x-2 w-full sm:w-auto">
        <button class="flex-1 sm:flex-none px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs md:text-sm font-medium">
          CSV
        </button>
        <button class="flex-1 sm:flex-none px-4 py-2 bg-emerald-600 text-white rounded-lg text-xs md:text-sm font-medium">
          + Add
        </button>
      </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
      <table class="w-full text-left min-w-[600px]">
        <thead class="bg-slate-50 border-b border-slate-200">
          <tr>
            <th class="px-4 md:px-6 py-4 text-[10px] md:text-xs font-semibold text-slate-500 uppercase">Member</th>
            <th class="px-4 md:px-6 py-4 text-[10px] md:text-xs font-semibold text-slate-500 uppercase">Membership</th>
            <th class="px-4 md:px-6 py-4 text-[10px] md:text-xs font-semibold text-slate-500 uppercase">Tags</th>
            <th class="px-4 md:px-6 py-4 text-[10px] md:text-xs font-semibold text-slate-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="member in displayMembers" :key="member.id" class="hover:bg-slate-50">
            <td class="px-4 md:px-6 py-4">
              <p class="font-medium text-slate-800 text-sm">{{ member.name }}</p>
              <p class="text-[10px] text-slate-500">{{ member.email }}</p>
            </td>
            <td class="px-4 md:px-6 py-4">
              <span
                class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                :class="member.membershipType === 'Club Pro' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-600'"
              >
                {{ member.membershipType }}
              </span>
            </td>
            <td class="px-4 md:px-6 py-4">
              <div class="flex flex-wrap gap-1">
                <span
                  v-for="tag in member.tags.slice(0, 2)"
                  :key="tag"
                  class="px-2 py-0.5 bg-green-50 text-green-700 border border-green-100 text-[10px] rounded"
                >
                  {{ tag }}
                </span>
              </div>
            </td>
            <td class="px-4 md:px-6 py-4 text-[10px] md:text-sm">
              <button class="text-emerald-600 hover:text-emerald-800 font-medium mr-2 md:mr-3">Msg</button>
              <button class="text-slate-400 hover:text-slate-600">Edit</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
