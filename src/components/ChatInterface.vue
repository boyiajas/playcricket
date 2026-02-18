<script setup lang="js">
import { computed, ref, watch } from 'vue';
import { MOCK_CHATS, MOCK_MEMBERS } from '../services/mockData';

const props = defineProps({
  members: {
    type: Array,
    default: () => [],
  },
  messages: {
    type: Array,
    default: () => [],
  },
});

const selectedMember = ref(null);
const inputText = ref('');

const displayMembers = computed(() => (props.members && props.members.length ? props.members : MOCK_MEMBERS));
const chats = ref(props.messages && props.messages.length ? [...props.messages] : [...MOCK_CHATS]);

watch(
  () => props.messages,
  (val) => {
    chats.value = val && val.length ? [...val] : [...MOCK_CHATS];
  },
  { deep: true }
);

const handleSend = () => {
  if (!inputText.value.trim()) return;
  const newMsg = {
    id: Date.now().toString(),
    memberId: selectedMember.value || 'm1',
    sender: 'AGENT',
    content: inputText.value,
    timestamp: new Date().toISOString(),
  };
  chats.value.push(newMsg);
  inputText.value = '';
};
</script>

<template>
  <div class="h-[calc(100vh-10rem)] md:h-[calc(100vh-8rem)] flex bg-white rounded-xl overflow-hidden shadow-sm border border-slate-200">
    <div class="w-full md:w-1/3 border-r border-slate-200 flex-col" :class="selectedMember ? 'hidden md:flex' : 'flex'">
      <div class="p-4 border-b border-slate-200 bg-slate-50">
        <h3 class="font-bold text-slate-700 text-sm">Member Support</h3>
        <input type="text" placeholder="Search..." class="mt-2 w-full px-3 py-1.5 border border-slate-300 rounded-lg text-xs" />
      </div>
      <div class="flex-1 overflow-y-auto">
        <div
          v-for="member in displayMembers"
          :key="member.id"
          class="p-4 border-b border-slate-100 hover:bg-emerald-50 cursor-pointer transition-colors"
          :class="selectedMember === member.id ? 'bg-emerald-50 border-l-4 border-l-emerald-500' : ''"
          @click="selectedMember = member.id"
        >
          <div class="flex justify-between items-start">
            <h4 class="font-semibold text-slate-800 text-sm">{{ member.name }}</h4>
            <span class="text-[10px] text-slate-400">Now</span>
          </div>
          <p class="text-xs text-slate-500 truncate mt-1">Last message preview...</p>
        </div>
      </div>
    </div>

    <div class="flex-1 flex-col bg-slate-50" :class="!selectedMember ? 'hidden md:flex' : 'flex'">
      <template v-if="selectedMember">
        <div class="p-3 bg-white border-b border-slate-200 flex justify-between items-center shadow-sm">
          <div class="flex items-center space-x-3">
            <button class="md:hidden text-slate-400 mr-1 text-lg" @click="selectedMember = null">←</button>
            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 font-bold text-xs">
              {{ displayMembers.find((c) => c.id === selectedMember)?.name.charAt(0) }}
            </div>
            <div>
              <h3 class="font-bold text-slate-800 text-xs md:text-sm">{{ displayMembers.find((c) => c.id === selectedMember)?.name }}</h3>
              <p class="text-[10px] text-slate-500">Online</p>
            </div>
          </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-4">
          <div
            v-for="msg in chats.filter((c) => c.memberId === selectedMember || (!selectedMember && c.memberId === 'm1'))"
            :key="msg.id"
            class="flex"
            :class="msg.sender === 'MEMBER' ? 'justify-start' : 'justify-end'"
          >
            <div
              class="max-w-[85%] md:max-w-[70%] p-3 rounded-xl shadow-sm"
              :class="
                msg.sender === 'MEMBER'
                  ? 'bg-white text-slate-800 rounded-tl-none border border-slate-100'
                  : msg.sender === 'SYSTEM'
                  ? 'bg-yellow-50 text-yellow-800 text-[10px] border border-yellow-100 mx-auto text-center'
                  : 'bg-emerald-600 text-white rounded-tr-none'
              "
            >
              <p class="text-xs">{{ msg.content }}</p>
              <p class="text-[8px] mt-1" :class="msg.sender === 'MEMBER' ? 'text-slate-400' : 'text-emerald-200'">
                {{ new Date(msg.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}
              </p>
            </div>
          </div>
        </div>

        <div class="p-3 bg-white border-t border-slate-200">
          <div class="flex space-x-2">
            <input
              v-model="inputText"
              type="text"
              placeholder="Message..."
              class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-all text-sm"
              @keydown.enter.prevent="handleSend"
            />
            <button
              class="px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors shadow-sm"
              @click="handleSend"
            >
              ➤
            </button>
          </div>
        </div>
      </template>

      <div v-else class="flex-1 flex flex-col items-center justify-center text-slate-400">
        <span class="text-5xl mb-4">💬</span>
        <p class="text-sm">Select a member to chat</p>
      </div>
    </div>
  </div>
</template>
