<script setup lang="js">
import { computed } from 'vue';

const props = defineProps({
  year: Number,
  month: Number,
  bookings: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(['select-date', 'select-booking']);

const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

const daysInMonth = computed(() => new Date(props.year, props.month + 1, 0).getDate());
const firstDay = computed(() => new Date(props.year, props.month, 1).getDay());

const cells = computed(() => {
  const list = [];
  for (let i = 0; i < firstDay.value; i += 1) {
    list.push({ key: `empty-${i}` });
  }

  for (let d = 1; d <= daysInMonth.value; d += 1) {
    const dateStr = `${props.year}-${String(props.month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
    const dayBookings = props.bookings.filter((b) => b.date === dateStr);
    const isToday = props.year === 2023 && props.month === 9 && d === 28;
    list.push({ key: dateStr, date: dateStr, day: d, bookings: dayBookings, isToday });
  }
  return list;
});

const selectDate = (date) => {
  if (date) emit('select-date', date);
};

const selectBooking = (booking) => {
  emit('select-booking', booking);
};
</script>

<template>
  <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50">
      <div v-for="day in days" :key="day" class="py-2 text-center text-[10px] md:text-xs font-bold text-slate-500 uppercase tracking-wider">
        {{ day }}
      </div>
    </div>
    <div class="grid grid-cols-7 bg-slate-100 gap-px border-l border-t border-slate-200">
      <div
        v-for="cell in cells"
        :key="cell.key"
        class="bg-slate-50/50 h-20 md:h-32 border-r border-b border-slate-100"
        :class="cell.date ? 'bg-white cursor-pointer group hover:bg-emerald-50/30 relative p-1 md:p-2' : ''"
        @click="selectDate(cell.date)"
      >
        <template v-if="cell.date">
          <div class="flex justify-between items-start">
            <span
              class="text-xs md:text-sm font-semibold w-6 h-6 md:w-7 md:h-7 flex items-center justify-center rounded-full"
              :class="cell.isToday ? 'bg-blue-600 text-white shadow-md' : 'text-slate-700'"
            >
              {{ cell.day }}
            </span>
            <button
              class="hidden md:flex opacity-0 group-hover:opacity-100 text-emerald-600 bg-emerald-100 hover:bg-emerald-200 w-5 h-5 md:w-6 md:h-6 rounded-full items-center justify-center text-lg leading-none transition-all shadow-sm transform hover:scale-110"
            >
              +
            </button>
          </div>
          <div class="mt-1 space-y-1 overflow-y-auto max-h-[calc(100%-1.5rem)] scrollbar-hide">
            <div
              v-for="booking in cell.bookings"
              :key="booking.id"
              class="text-[8px] md:text-[10px] px-1 md:px-1.5 py-0.5 md:py-1 rounded border truncate shadow-sm cursor-pointer hover:ring-1 hover:ring-emerald-400"
              :class="booking.status === 'CONFIRMED' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-orange-50 text-orange-800 border-orange-200'"
              @click.stop="selectBooking(booking)"
            >
              <span class="font-bold hidden md:inline">{{ booking.startTime }} </span>
              {{ booking.facility }}
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>
