<script setup lang="js">
import { computed, onMounted, ref, watch } from 'vue';
import StatCard from './StatCard.vue';
import { MOCK_MEMBERS } from '../services/mockData';
import { api } from '../services/api';

const props = defineProps({
  bookings: {
    type: Array,
    default: () => [],
  },
  campaigns: {
    type: Array,
    default: () => [],
  },
  members: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(['navigate']);

const reports = ref({
    daily: { bookings: 0, revenue: 0 },
    weekly: { bookings: 0, revenue: 0 },
    monthly: { bookings: 0, revenue: 0 },
    active_pitches: 0,
    total_bookings: 0
});

const fetchReports = async () => {
    try {
        const data = await api.reports();
        if (data) reports.value = { ...reports.value, ...data };
    } catch (e) {
        console.warn('Failed to fetch reports', e);
    }
};

onMounted(() => {
    fetchReports();
});

watch(() => props.bookings, () => {
    fetchReports();
}, { deep: true });

const defaultChannelData = [
  { name: 'WhatsApp', sent: 1500, response: 900, bar: 'bg-emerald-500' },
  { name: 'Email', sent: 800, response: 200, bar: 'bg-blue-500' },
  { name: 'SMS', sent: 1200, response: 300, bar: 'bg-orange-500' },
];

const channelData = computed(() => {
  if (props.campaigns && props.campaigns.length > 0) {
    const map = {
      WHATSAPP: { name: 'WhatsApp', sent: 0, response: 0, bar: 'bg-emerald-500' },
      EMAIL: { name: 'Email', sent: 0, response: 0, bar: 'bg-blue-500' },
      SMS: { name: 'SMS', sent: 0, response: 0, bar: 'bg-orange-500' },
    };
    for (const c of props.campaigns) {
      const ch = (c.channel || '').toUpperCase();
      if (map[ch]) {
        map[ch].sent += (c.stats?.sent ?? c.stats?.total ?? 100);
        map[ch].response += (c.stats?.responses ?? 20);
      }
    }
    return Object.values(map);
  }
  return defaultChannelData;
});

const normalizeBooking = (b) => ({
  ...b,
  id: b.id,
  memberId: b.memberId || b.member_id,
  date: b.date ? String(b.date).slice(0, 10) : '',
  startTime: b.startTime || b.start_time || '',
  status: b.status || 'PENDING',
  lane: b.lane || null,
  playerName: b.playerName || b.player_name || '',
});

const normalizedBookings = computed(() => props.bookings.map(normalizeBooking));

const activeMembers = computed(() => (props.members && props.members.length ? props.members.length : MOCK_MEMBERS.length));

const maxSent = computed(() => Math.max(...channelData.value.map((c) => c.sent), 100));
const yTicks = computed(() => {
  const m = maxSent.value;
  const step = Math.ceil(m / 4 / 50) * 50;
  return [0, step, step * 2, step * 3, step * 4];
});

const membersLookup = computed(() => {
  const source = props.members && props.members.length ? props.members : MOCK_MEMBERS;
  return Object.fromEntries(source.map((m) => [m.id, m]));
});

const activePitchesCount = computed(() => {
  if (reports.value.active_pitches) return reports.value.active_pitches;
  const todayStr = new Date().toISOString().slice(0, 10);
  const todays = normalizedBookings.value.filter(b => b.date === todayStr && b.status !== 'CANCELLED');
  return new Set(todays.map(b => b.facility)).size || 1;
});
</script>

<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div>
        <h2 class="text-xl md:text-2xl font-bold text-slate-800">Ground Overview</h2>
        <p class="text-xs text-slate-500 mt-0.5">Live facilities status, occupancy and financial overview</p>
      </div>
      <div class="flex items-center space-x-3">
        <div class="hidden sm:flex items-center space-x-2 bg-white px-4 py-2 rounded-full shadow-sm border border-slate-100">
          <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shadow-sm" />
          <span class="text-sm font-semibold text-slate-600">{{ activePitchesCount }} / 4 Facilities Active</span>
        </div>
        <button
          @click="$emit('navigate', 'bookings')"
          class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs md:text-sm px-4 py-2 rounded-lg shadow-sm transition-all flex items-center gap-1.5 active:scale-95"
        >
          <span>+</span>
          <span>Book Slot</span>
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
      <StatCard title="Today's Bookings" :value="reports.daily.bookings" icon="🏏" color="bg-emerald-500" trend="Daily" />
      <StatCard title="Weekly Revenue" :value="`R ${Number(reports.weekly.revenue || 0).toLocaleString()}`" icon="💰" color="bg-blue-500" trend="Weekly" />
      <StatCard title="Monthly Revenue" :value="`R ${Number(reports.monthly.revenue || 0).toLocaleString()}`" icon="📈" color="bg-purple-500" trend="Monthly" />
      <StatCard title="Active Members" :value="activeMembers" icon="👥" color="bg-orange-500" trend="Total" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h3 class="text-lg font-semibold text-slate-700">Member Engagement</h3>
            <p class="text-xs text-slate-400">Broadcasts & response tracking</p>
          </div>
          <button 
            @click="$emit('navigate', 'campaigns')" 
            class="text-xs text-emerald-600 font-semibold hover:underline"
          >
            Manage Campaigns →
          </button>
        </div>
        <div class="relative h-72 md:h-80">
          <div class="absolute inset-0 grid grid-rows-5 text-[11px] text-slate-400">
            <div v-for="tick in yTicks" :key="tick" class="flex items-center">
              <span class="w-10 text-right pr-2">{{ tick }}</span>
              <div class="flex-1 border-b border-dashed border-slate-200"></div>
            </div>
          </div>
          <div class="absolute inset-0 ml-10 flex items-end justify-around pb-6 pr-4">
            <div
              v-for="channel in channelData"
              :key="channel.name"
              class="flex flex-col items-center space-y-2"
            >
              <div class="flex items-end space-x-1 h-56">
                <div
                  class="w-9 rounded-md transition-all shadow-sm"
                  :class="channel.bar"
                  :style="{ height: `${(channel.sent / maxSent) * 100}%` }"
                  :title="`Sent: ${channel.sent}`"
                />
                <div
                  class="w-9 rounded-md bg-blue-400/80 shadow-sm"
                  :style="{ height: `${(channel.response / maxSent) * 100}%` }"
                  :title="`Responses: ${channel.response}`"
                />
              </div>
              <span class="text-[11px] font-semibold text-slate-600">{{ channel.name }}</span>
            </div>
          </div>
        </div>
        <div class="flex items-center justify-end mt-3 text-[11px] text-slate-500 space-x-4">
          <span class="flex items-center space-x-1"><span class="w-3 h-3 rounded-sm bg-emerald-500 block"></span><span>Sent</span></span>
          <span class="flex items-center space-x-1"><span class="w-3 h-3 rounded-sm bg-blue-400 block"></span><span>Responses</span></span>
        </div>
      </div>

      <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-700">Recent Bookings</h3>
            <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold">Live</span>
          </div>
          <div v-if="normalizedBookings.length === 0" class="py-12 text-center text-slate-400">
            <span class="text-3xl block mb-2">🏏</span>
            <p class="text-xs">No bookings recorded yet.</p>
          </div>
          <div v-else class="space-y-3">
            <div
              v-for="booking in normalizedBookings.slice(0, 5)"
              :key="booking.id"
              class="flex items-center justify-between p-3 hover:bg-emerald-50/50 rounded-xl transition-colors border border-slate-100"
            >
              <div class="max-w-[58%]">
                <p class="font-semibold text-slate-800 text-sm truncate">
                  {{ booking.facility }}
                  <span v-if="booking.lane" class="text-[10px] text-emerald-600 font-bold ml-1">({{ booking.lane }})</span>
                </p>
                <p class="text-[11px] text-slate-500">🗓 {{ booking.date }} • {{ booking.startTime }}</p>
              </div>
              <div class="text-right">
                <span class="text-[11px] font-bold text-slate-700 block truncate">
                  {{ booking.playerName || booking.player_name || membersLookup[booking.memberId]?.name || 'Guest' }}
                </span>
                <span
                  class="text-[9px] uppercase font-bold px-2 py-0.5 rounded-full"
                  :class="booking.status === 'CONFIRMED' ? 'text-emerald-700 bg-emerald-50' : (booking.status === 'COMPLETED' ? 'text-blue-700 bg-blue-50' : 'text-orange-600 bg-orange-50')"
                >
                  {{ booking.status }}
                </span>
              </div>
            </div>
          </div>
        </div>
        <button
          @click="$emit('navigate', 'bookings')"
          class="w-full mt-4 text-center text-xs md:text-sm text-emerald-600 font-bold hover:text-emerald-700 hover:underline pt-2 border-t border-slate-100"
        >
          View Calendar & All Bookings →
        </button>
      </div>
    </div>
  </div>
</template>
