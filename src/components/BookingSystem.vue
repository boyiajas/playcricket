<script setup lang="js">
import { computed, ref, watch } from 'vue';
import CalendarGrid from './CalendarGrid.vue';
import { MOCK_BOOKINGS, MOCK_MEMBERS } from '../services/mockData';
import { BookingStatus } from '../types';

const props = defineProps({
  initialBookings: {
    type: Array,
    default: () => [],
  },
  members: {
    type: Array,
    default: () => [],
  },
});

const activeView = ref('calendar');
const bookings = ref(props.initialBookings && props.initialBookings.length ? [...props.initialBookings] : [...MOCK_BOOKINGS]);
const showModal = ref(false);
const currentDate = ref({ year: 2023, month: 9 });
const newBooking = ref({ date: '', time: '10:00', facility: 'Pitch 1', duration: 1 });
const displayMembers = computed(() => (props.members && props.members.length ? props.members : MOCK_MEMBERS));

const currentMonthLabel = computed(() =>
  new Date(currentDate.value.year, currentDate.value.month).toLocaleString('default', { month: 'long', year: 'numeric' })
);

const handleDateClick = (date) => {
  newBooking.value.date = date;
  showModal.value = true;
};

const handleBook = () => {
  const booking = {
    id: Date.now().toString(),
    memberId: 'm1',
    facility: newBooking.value.facility,
    date: newBooking.value.date,
    startTime: newBooking.value.time,
    durationHours: newBooking.value.duration,
    status: BookingStatus.CONFIRMED,
    syncedToGoogle: false,
  };
  bookings.value.push(booking);
  showModal.value = false;
};

const myBookings = computed(() => bookings.value.filter((b) => b.memberId === 'm1'));

watch(
  () => props.initialBookings,
  (val) => {
    bookings.value = val && val.length ? [...val] : [...MOCK_BOOKINGS];
  },
  { deep: true }
);
</script>

<template>
  <div class="space-y-4 md:space-y-6">
    <div class="flex flex-col xl:flex-row justify-between items-center gap-4">
      <div class="flex w-full md:w-auto bg-white p-1 rounded-lg border border-slate-200 shadow-sm overflow-x-auto scrollbar-hide">
        <button
          class="flex-1 md:flex-none px-3 md:px-4 py-2 rounded-md font-medium text-xs md:text-sm transition-colors flex items-center justify-center space-x-2 whitespace-nowrap"
          :class="activeView === 'calendar' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'"
          @click="activeView = 'calendar'"
        >
          <span>📅</span>
          <span>Calendar</span>
        </button>
        <button
          class="flex-1 md:flex-none px-3 md:px-4 py-2 rounded-md font-medium text-xs md:text-sm transition-colors flex items-center justify-center space-x-2 whitespace-nowrap"
          :class="activeView === 'my-bookings' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'"
          @click="activeView = 'my-bookings'"
        >
          <span>📋</span>
          <span>Schedule</span>
        </button>
      </div>

      <div class="flex w-full md:w-auto items-center justify-between md:justify-end space-x-3">
        <div
          v-if="activeView === 'calendar'"
          class="flex items-center bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm"
        >
          <button
            class="text-slate-400 hover:text-emerald-600"
            @click="
              currentDate =
                currentDate.month === 0 ? { year: currentDate.year - 1, month: 11 } : { ...currentDate, month: currentDate.month - 1 }
            "
          >
            ◀
          </button>
          <span class="mx-2 md:mx-4 font-bold text-slate-700 text-xs md:text-sm whitespace-nowrap">{{ currentMonthLabel }}</span>
          <button
            class="text-slate-400 hover:text-emerald-600"
            @click="
              currentDate =
                currentDate.month === 11 ? { year: currentDate.year + 1, month: 0 } : { ...currentDate, month: currentDate.month + 1 }
            "
          >
            ▶
          </button>
        </div>
        <button
          class="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 text-xs md:text-sm font-medium flex items-center shadow-lg transition-all"
          @click="
            newBooking = { ...newBooking, date: new Date().toISOString().split('T')[0] };
            showModal = true;
          "
        >
          <span>+ Book</span>
        </button>
      </div>
    </div>

    <div
      v-if="showModal"
      class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-[100] p-4"
    >
      <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl animate-in fade-in zoom-in duration-200">
        <h3 class="text-xl font-bold mb-4 text-slate-800 flex items-center">
          <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mr-2 text-sm">🏏</span>
          Book a Slot
        </h3>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Facility</label>
            <select
              v-model="newBooking.facility"
              class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
            >
              <option>Pitch 1</option>
              <option>Pitch 2</option>
              <option>Indoor Nets</option>
              <option>Bowling Machine</option>
            </select>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Date</label>
              <input
                v-model="newBooking.date"
                type="date"
                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Time</label>
              <input
                v-model="newBooking.time"
                type="time"
                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
              />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Duration (Hours)</label>
            <input
              v-model.number="newBooking.duration"
              type="number"
              min="1"
              max="8"
              class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
            />
          </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3 pt-4 border-t border-slate-100">
          <button class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors" @click="showModal = false">
            Cancel
          </button>
          <button class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 shadow-lg transition-all" @click="handleBook">
            Confirm
          </button>
        </div>
      </div>
    </div>

    <CalendarGrid
      v-if="activeView === 'calendar'"
      :year="currentDate.year"
      :month="currentDate.month"
      :bookings="bookings"
      @select-date="handleDateClick"
    />

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
      <div
        v-if="myBookings.length === 0"
        class="col-span-full py-12 text-center text-slate-400 border-2 border-dashed border-slate-200 rounded-xl"
      >
        <span class="text-4xl block mb-2">📅</span>
        <p>No upcoming bookings found.</p>
      </div>
      <div
        v-for="booking in myBookings"
        v-else
        :key="booking.id"
        class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-slate-200 hover:border-emerald-300 transition-colors relative overflow-hidden group"
      >
        <div class="absolute top-0 right-0 p-3">
          <span
            class="px-2 py-1 text-[8px] md:text-[10px] font-bold rounded uppercase"
            :class="booking.status === 'CONFIRMED' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700'"
          >
            {{ booking.status }}
          </span>
        </div>

        <h4 class="text-base md:text-lg font-bold text-slate-800 mb-1">{{ booking.facility }}</h4>
        <p class="text-xs text-slate-500 mb-4">🗓 {{ booking.date }} | ⏰ {{ booking.startTime }}</p>

        <div class="flex items-center mb-4">
          <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-600 mr-2">
            {{ displayMembers.find((m) => m.id === booking.memberId)?.name.charAt(0) }}
          </div>
          <div>
            <p class="text-xs font-medium text-slate-700">{{ displayMembers.find((m) => m.id === booking.memberId)?.name }}</p>
            <p class="text-[10px] text-slate-400">{{ displayMembers.find((m) => m.id === booking.memberId)?.membershipType }}</p>
          </div>
        </div>

        <div class="flex items-center space-x-2 border-t pt-3 md:pt-4">
          <button class="flex-1 text-[10px] font-medium py-1.5 rounded border border-slate-200 text-slate-600 hover:bg-slate-50">
            Sync
          </button>
          <button
            class="flex-1 text-[10px] font-medium py-1.5 rounded border border-slate-200 text-slate-600 hover:bg-red-50 hover:text-red-600"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
