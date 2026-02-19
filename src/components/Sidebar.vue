<script setup lang="js">
import { computed } from 'vue';
import { Role } from '../types';

const props = defineProps({
  currentTab: String,
  userRole: String,
  isOpen: Boolean,
});

const emit = defineEmits(['update:tab', 'update:open', 'logout']);

const menuItems = computed(() => {
  let base = [
    { id: 'dashboard', label: 'Dashboard', icon: '📊' },
    { id: 'bookings', label: 'Bookings', icon: '🏏' },
    { id: 'members', label: 'Members', icon: '👥' },
    { id: 'campaigns', label: 'Broadcasts', icon: '📢' },
    { id: 'chat', label: 'Live Chat', icon: '💬' },
    { id: 'reports', label: 'Reports', icon: '📈' },
  ];

  if (props.userRole === Role.MEMBER) {
    base = base.filter((item) => item.id !== 'members');
  }

  if (props.userRole === Role.SUPER_ADMIN) {
    base.push({ id: 'audit', label: 'Audit Trail', icon: '🛡️' });
    base.push({ id: 'settings', label: 'Settings', icon: '⚙️' });
  }

  return base;
});

const closeSidebar = () => emit('update:open', false);
const setTab = (tab) => emit('update:tab', tab);
</script>

<template>
  <div>
    <div
      class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 transition-opacity duration-300 md:hidden"
      :class="isOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'"
      @click="closeSidebar"
    />

    <aside
      class="fixed left-0 top-0 h-screen bg-slate-900 text-white z-50 transition-all duration-300 ease-in-out flex flex-col overflow-y-auto"
      :class="isOpen ? 'w-64 translate-x-0' : 'w-0 -translate-x-full md:translate-x-0 md:w-20'"
    >
      <div class="p-6 border-b border-slate-700 flex items-center" :class="!isOpen ? 'justify-center' : 'justify-between'">
        <div v-if="isOpen">
          <img src="/logo.png" alt="PlayCricket" class="h-12 w-auto" />
          <p class="text-[10px] text-slate-400 mt-0.5 pl-2">Facility Manager</p>
        </div>
        <div v-else class="w-10 h-10 bg-white rounded-lg flex items-center justify-center overflow-hidden">
             <img src="/logo.png" alt="PC" class="w-full h-full object-contain" />
        </div>

        <button class="md:hidden text-slate-400 hover:text-white" @click="closeSidebar">✕</button>
      </div>

      <nav class="flex-1 p-4 space-y-2">
        <button
          v-for="item in menuItems"
          :key="item.id"
          class="w-full flex items-center px-4 py-3 rounded-lg transition-all duration-200 group relative"
          :class="[
            currentTab === item.id
              ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/50'
              : 'text-slate-300 hover:bg-slate-800 hover:text-white',
            !isOpen ? 'justify-center' : 'space-x-3',
          ]"
          :title="item.label"
          @click="setTab(item.id)"
        >
          <span class="text-xl">{{ item.icon }}</span>
          <span v-if="isOpen" class="font-medium whitespace-nowrap">{{ item.label }}</span>
          <span
            v-else
            class="absolute left-full ml-4 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-50"
          >
            {{ item.label }}
          </span>
        </button>
      </nav>

      <div class="p-4 border-t border-slate-700">
        <button
          class="w-full flex items-center py-2 text-slate-400 hover:text-red-400 hover:bg-slate-800 rounded-lg transition-colors"
          :class="!isOpen ? 'justify-center' : 'px-4 space-x-3'"
          @click="emit('logout')"
        >
          <span>🚪</span>
          <span v-if="isOpen">Sign Out</span>
        </button>
      </div>
    </aside>
  </div>
</template>
