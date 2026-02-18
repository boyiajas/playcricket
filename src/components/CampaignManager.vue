<script setup lang="js">
import { computed, ref } from 'vue';
import { generateTemplate } from '../services/geminiService';
import { MOCK_CAMPAIGNS } from '../services/mockData';

const props = defineProps({
  campaigns: {
    type: Array,
    default: () => [],
  },
});

const showCreate = ref(false);
const topic = ref('');
const generatedContent = ref('');
const loadingAi = ref(false);

const displayCampaigns = computed(() => (props.campaigns && props.campaigns.length ? props.campaigns : MOCK_CAMPAIGNS));

const handleGenerate = async () => {
  loadingAi.value = true;
  const content = await generateTemplate(topic.value, 'WhatsApp', 'Exciting and Sports-focused');
  if (content) generatedContent.value = content;
  loadingAi.value = false;
};
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <h2 class="text-xl md:text-2xl font-bold text-slate-800">Broadcasts</h2>
      <button
        class="w-full sm:w-auto px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium text-xs md:text-sm"
        @click="showCreate = !showCreate"
      >
        <span>+ New Announcement</span>
      </button>
    </div>

    <div
      v-if="showCreate"
      class="bg-white p-4 md:p-6 rounded-xl shadow-md border border-slate-100 animate-in slide-in-from-top-4 duration-300"
    >
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="space-y-4">
          <div>
            <label class="block text-xs font-medium text-slate-700 mb-1">Campaign Name</label>
            <input
              type="text"
              class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm"
              placeholder="e.g., Trial Matches"
            />
          </div>
          <div>
            <label class="block text-xs font-medium text-slate-700 mb-1">Topic for AI</label>
            <textarea
              v-model="topic"
              class="w-full px-3 py-2 border rounded-lg h-24 focus:ring-2 focus:ring-emerald-500 outline-none resize-none text-sm"
              placeholder=" renovation next week..."
            />
            <button
              class="mt-2 text-[10px] md:text-xs text-purple-600 font-medium hover:text-purple-700"
              :disabled="loadingAi"
              @click="handleGenerate"
            >
              {{ loadingAi ? 'Drafting...' : '✨ Generate with Gemini' }}
            </button>
          </div>
        </div>
        <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
          <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Preview</label>
          <textarea
            v-model="generatedContent"
            class="w-full h-full bg-transparent border-none outline-none text-slate-700 font-mono text-xs resize-none min-h-[120px]"
            placeholder="AI content..."
          />
        </div>
      </div>
      <div class="mt-6 flex justify-end space-x-3 pt-4 border-t border-slate-100">
        <button class="px-4 py-2 text-xs text-slate-600" @click="showCreate = false">Cancel</button>
        <button class="px-6 py-2 bg-slate-900 text-white rounded-lg text-xs">Send Blast</button>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto">
      <table class="w-full text-left min-w-[500px]">
        <thead class="bg-slate-50 border-b border-slate-200">
          <tr>
            <th class="px-4 md:px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase">Campaign</th>
            <th class="px-4 md:px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase">Status</th>
            <th class="px-4 md:px-6 py-4 text-[10px] font-semibold text-slate-500 uppercase">Stats</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr v-for="campaign in displayCampaigns" :key="campaign.id" class="hover:bg-slate-50">
            <td class="px-4 md:px-6 py-4">
              <p class="font-medium text-slate-800 text-sm">{{ campaign.name }}</p>
              <p class="text-[10px] text-slate-400">{{ campaign.channel }}</p>
            </td>
            <td class="px-4 md:px-6 py-4">
              <div class="flex items-center space-x-2">
                <div class="w-1.5 h-1.5 rounded-full" :class="campaign.status === 'ACTIVE' ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400'" />
                <span class="text-xs text-slate-600 capitalize">{{ campaign.status.toLowerCase() }}</span>
              </div>
            </td>
            <td class="px-4 md:px-6 py-4 text-[10px] text-slate-600">
              <span class="text-blue-600 font-bold">{{ campaign.stats.sent }}</span> Sent
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
