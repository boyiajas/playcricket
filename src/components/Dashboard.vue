<script setup lang="js">
import { computed } from 'vue';
import StatCard from './StatCard.vue';
import { MOCK_MEMBERS } from '../services/mockData';

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

const channelData = [
  { name: 'WhatsApp', sent: 1500, response: 900, bar: 'bg-emerald-500' },
  { name: 'Email', sent: 800, response: 200, bar: 'bg-blue-500' },
  { name: 'SMS', sent: 1200, response: 300, bar: 'bg-orange-500' },
];

const normalizeBooking = (b) => ({
  ...b,
  memberId: b.memberId || b.member_id,
  date: b.date ? String(b.date).slice(0, 10) : '',
  startTime: b.startTime || b.start_time || '',
  status: b.status || 'PENDING',
});

const normalizedBookings = computed(() => props.bookings.map(normalizeBooking));

const todayStr = new Date().toISOString().slice(0, 10);
const todayBookings = computed(() => normalizedBookings.value.filter((b) => b.date === todayStr).length);
const activeMembers = computed(() => (props.members && props.members.length ? props.members.length : MOCK_MEMBERS.length));

const maxSent = Math.max(...channelData.map((c) => c.sent));
const yTicks = [0, 400, 800, 1200, 1600];

const membersLookup = computed(() => {
  const source = props.members && props.members.length ? props.members : MOCK_MEMBERS;
  return Object.fromEntries(source.map((m) => [m.id, m]));
});
</script>

<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h2 class="text-xl md:text-2xl font-bold text-slate-800">Ground Overview</h2>
      <div class="hidden sm:flex items-center space-x-2 bg-white px-4 py-2 rounded-full shadow-sm border border-slate-100">
        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shadow-sm" />
        <span class="text-sm font-semibold text-slate-600">Pitches Active</span>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
      <StatCard title="Today's Bookings" :value="todayBookings" icon="🏏" color="bg-emerald-500" trend="Full" />
      <StatCard title="Active Members" :value="activeMembers" icon="👥" color="bg-blue-500" trend="+3" />
      <StatCard title="Broadcasts" value="3,500" icon="📡" color="bg-purple-500" />
      <StatCard title="Revenue" value="$4,200" icon="💰" color="bg-orange-500" trend="15%" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-slate-700">Engagement</h3>
          <span class="text-xs text-slate-500">Last 7 days</span>
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
                  class="w-9 rounded-md transition-all"
                  :class="channel.bar"
                  :style="{ height: `${(channel.sent / maxSent) * 100}%` }"
                  title="Sent"
                />
                <div
                  class="w-9 rounded-md bg-blue-400/70"
                  :style="{ height: `${(channel.response / maxSent) * 100}%` }"
                  title="Responses"
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

      <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-slate-100">
        <h3 class="text-lg font-semibold mb-4 text-slate-700">Matches</h3>
        <div class="space-y-3">
          <div
            v-for="booking in normalizedBookings.slice(0, 4)"
            :key="booking.id"
            class="flex items-center justify-between p-3 hover:bg-emerald-50/50 rounded-xl transition-colors border border-slate-100"
          >
            <div class="max-w-[60%]">
              <p class="font-semibold text-slate-800 text-sm truncate">{{ booking.facility }}</p>
              <p class="text-[11px] text-slate-500">{{ booking.date }} • {{ booking.startTime }}</p>
            </div>
            <div class="text-right">
              <span class="text-[11px] font-bold text-slate-700 block truncate">{{ membersLookup[booking.memberId]?.name }}</span>
              <span
                class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-full"
                :class="booking.status === 'CONFIRMED' ? 'text-emerald-700 bg-emerald-50' : 'text-orange-600 bg-orange-50'"
              >
                {{ booking.status }}
              </span>
            </div>
          </div>
        </div>
        <button class="w-full mt-4 text-center text-sm text-emerald-600 font-semibold hover:underline">View All</button>
      </div>
    </div>
  </div>
</template>
