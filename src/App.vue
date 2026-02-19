<script setup lang="js">
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import Sidebar from './components/Sidebar.vue';
import Dashboard from './components/Dashboard.vue';
import BookingSystem from './components/BookingSystem.vue';
import MemberList from './components/MemberList.vue';
import ChatInterface from './components/ChatInterface.vue';
import CampaignManager from './components/CampaignManager.vue';
import AuditTrail from './components/AuditTrail.vue';
import Login from './components/Login.vue';
import { api } from './services/api';
import { MOCK_AUDIT_LOGS, MOCK_BOOKINGS, MOCK_CAMPAIGNS, MOCK_CHATS, MOCK_MEMBERS, MOCK_USERS } from './services/mockData';
import { Role } from './types';

const currentUser = ref(null);
const activeTab = ref('dashboard');
const isSidebarOpen = ref(true);
const members = ref(MOCK_MEMBERS);
const bookings = ref(MOCK_BOOKINGS);
const campaigns = ref(MOCK_CAMPAIGNS);
const chatMessages = ref(MOCK_CHATS);
const auditLogs = ref(MOCK_AUDIT_LOGS);

const handleResize = () => {
  isSidebarOpen.value = window.innerWidth >= 1024;
};

onMounted(() => {
  console.log('App.vue mounted');
  const storedUser = localStorage.getItem('playcricket_user');
  const storedTab = localStorage.getItem('playcricket_tab');
  if (storedUser) {
    currentUser.value = JSON.parse(storedUser);
    activeTab.value = storedTab || (currentUser.value.role === Role.MEMBER ? 'bookings' : 'dashboard');
  }
  handleResize();
  loadData();
  window.addEventListener('resize', handleResize);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', handleResize);
});

watch(
  () => activeTab.value,
  (tab) => {
    if (currentUser.value) {
      localStorage.setItem('playcricket_tab', tab);
    }
  }
);

const handleLogin = async (role) => {
  try {
    const user = await api.login(role);
    currentUser.value = user;
  } catch (error) {
    console.warn('API login failed, using mock user', error);
    const user = MOCK_USERS.find((u) => u.role === role) || MOCK_USERS[0];
    currentUser.value = user;
  }
  activeTab.value = role === Role.MEMBER ? 'bookings' : 'dashboard';
  localStorage.setItem('playcricket_user', JSON.stringify(currentUser.value));
  localStorage.setItem('playcricket_tab', activeTab.value);
};

const loadData = async () => {
  try {
    members.value = await api.members();
  } catch (error) {
    console.warn('Members fallback to mock', error);
    members.value = MOCK_MEMBERS;
  }

  try {
    bookings.value = await api.bookings();
  } catch (error) {
    console.warn('Bookings fallback to empty', error);
    bookings.value = [];
  }

  try {
    campaigns.value = await api.campaigns();
  } catch (error) {
    console.warn('Campaigns fallback to mock', error);
    campaigns.value = MOCK_CAMPAIGNS;
  }

  try {
    chatMessages.value = await api.chat();
  } catch (error) {
    console.warn('Chat fallback to mock', error);
    chatMessages.value = MOCK_CHATS;
  }

  try {
    auditLogs.value = await api.audit();
  } catch (error) {
    console.warn('Audit fallback to mock', error);
    auditLogs.value = MOCK_AUDIT_LOGS;
  }
};

const handleLogout = () => {
  currentUser.value = null;
  localStorage.removeItem('playcricket_user');
  localStorage.removeItem('playcricket_tab');
};

const memberId = computed(() => currentUser.value?.memberId || 'm1');

const visibleBookings = computed(() => {
  if (currentUser.value?.role === Role.MEMBER) {
    return bookings.value.filter((b) => (b.memberId || b.member_id) === memberId.value);
  }
  return bookings.value;
});
</script>

<template>
  <Login v-if="!currentUser" @login="handleLogin" />
  <div v-else class="flex bg-slate-50 min-h-screen font-sans text-slate-900 overflow-x-hidden">
    <Sidebar
      :current-tab="activeTab"
      :user-role="currentUser.role"
      :is-open="isSidebarOpen"
      @update:tab="activeTab = $event"
      @update:open="isSidebarOpen = $event"
      @logout="handleLogout"
    />

    <main class="flex-1 transition-all duration-300 ease-in-out p-4 md:p-8" :class="isSidebarOpen ? 'md:ml-64' : 'md:ml-20'">
      <header class="flex justify-between items-center mb-6 md:mb-8">
        <div class="flex items-center space-x-3">
          <button
            class="p-2 bg-white rounded-lg border border-slate-200 shadow-sm hover:bg-slate-50 transition-colors"
            aria-label="Toggle Sidebar"
            @click="isSidebarOpen = !isSidebarOpen"
          >
            <span class="text-xl">{{ isSidebarOpen ? '⇠' : '☰' }}</span>
          </button>
          <div class="hidden sm:block">
            <h1 class="text-xl md:text-2xl font-bold text-slate-800 capitalize leading-tight">
              {{ activeTab.replace('-', ' ') }}
            </h1>
            <p class="text-xs text-slate-500">Welcome, {{ currentUser.name }}</p>
          </div>
        </div>

        <div class="flex items-center space-x-3 md:space-x-4">
          <div class="text-right hidden md:block">
            <p class="text-sm font-bold text-slate-800">{{ currentUser.name }}</p>
            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-tight">{{ currentUser.role }}</p>
          </div>
          <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden border-2 border-white shadow-sm shrink-0">
            <img :src="currentUser.avatarUrl" alt="User" class="w-full h-full object-cover" />
          </div>
        </div>
      </header>

      <div class="mx-auto">
        <Dashboard v-if="activeTab === 'dashboard'" :bookings="visibleBookings" :campaigns="campaigns" :members="members" />
        <BookingSystem v-else-if="activeTab === 'bookings'" :initial-bookings="visibleBookings" :members="members" :member-id="memberId" />
        <MemberList v-else-if="activeTab === 'members'" :members="members" />
        <CampaignManager v-else-if="activeTab === 'campaigns'" :campaigns="campaigns" />
        <ChatInterface v-else-if="activeTab === 'chat'" :members="members" :messages="chatMessages" />
        <AuditTrail v-else-if="activeTab === 'audit' && currentUser.role === Role.SUPER_ADMIN" :audit-logs="auditLogs" />
        <div
          v-else-if="activeTab === 'reports' || activeTab === 'settings'"
          class="flex flex-col items-center justify-center h-64 md:h-96 border-2 border-dashed border-slate-300 rounded-xl text-slate-400"
        >
          <span class="text-4xl mb-2">🚧</span>
          <p class="font-medium text-sm md:text-base">Settings & Reports Module</p>
          <p class="text-[10px] md:text-xs">Configure Twilio & Mailgun API keys here.</p>
        </div>
      </div>
    </main>
  </div>
</template>
