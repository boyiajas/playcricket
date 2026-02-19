<script setup lang="js">
import { computed, ref, watch, onMounted } from 'vue';
import CalendarGrid from './CalendarGrid.vue';
import { MOCK_BOOKINGS, MOCK_MEMBERS } from '../services/mockData';
import { BookingStatus } from '../types';
import { api } from '../services/api';

const props = defineProps({
  initialBookings: {
    type: Array,
    default: () => [],
  },
  memberId: {
    type: String,
    default: 'm1',
  },
  members: {
    type: Array,
    default: () => [],
  },
});

const activeView = ref('calendar');

const normalizeBooking = (b) => ({
  ...b,
  memberId: b.memberId || b.member_id || 'guest',
  startTime: b.startTime || b.start_time || '',
  durationHours: b.durationHours ?? b.duration_hours ?? b.duration ?? 1,
  date: b.date ? String(b.date).slice(0, 10) : '',
  payment_status: b.payment_status || b.paymentStatus || 'UNPAID',
  status: b.status || 'PENDING',
});

const bookings = ref(
  (props.initialBookings && props.initialBookings.length ? [...props.initialBookings] : []).map(normalizeBooking)
);
const showModal = ref(false);
const showExistingModal = ref(false);
const currentDate = ref({ year: 2023, month: 9 });
const cart = ref([]);
const newBooking = ref({ date: '', time: '10:00', facility: 'Pitch 1', duration: 1, ageGroup: 'Under 13', playerName: '' });

const addToCart = () => {
    cart.value.push({ ...newBooking.value, id: Date.now() });
    showModal.value = false;
    // Reset for next
    newBooking.value = { ...newBooking.value, time: '10:00', playerName: '' }; 
};

const removeFromCart = (index) => {
    cart.value.splice(index, 1);
};

const handleBook = async (payNow = false) => {
  if (cart.value.length === 0) return;

  try {
      const results = [];
      // Process all items in cart
      for (const item of cart.value) {
          const bookingData = {
            memberId: props.memberId || 'm1',
            facility: item.facility,
            date: item.date,
            startTime: item.time,
            durationHours: item.duration,
            ageGroup: item.ageGroup,
            playerName: item.playerName,
            payNow: payNow
          };
          
          const response = await api.createBooking(bookingData);
          results.push(response);
          bookings.value.push(normalizeBooking(response.booking));
      }

      // Handle Payment Redirect (if any payNow was selected, we might need a bulk flow, 
      // but for now let's just use the last one's payment link or assume individual processing)
      // *Improvement*: The backend 'payFastService' generates a form for ONE booking. 
      // For multiple, we'd ideally aggregate. For MVP, we'll redirect for the FIRST payNow item found.
      const paymentItem = results.find(r => r.payment_data);
      
      if (payNow && paymentItem) {
        submitPaymentForm(paymentItem.payment_data.url, paymentItem.payment_data.data);
      } else {
        alert('All bookings reserved successfully!');
        cart.value = [];
      }

  } catch (error) {
    console.error('Booking failed', error);
    alert('Failed to process some bookings.');
  }
};

const myBookings = computed(() => bookings.value.filter((b) => b.memberId === props.memberId || b.member_id === props.memberId));
const pastBookings = computed(() =>
  bookings.value.filter((b) => {
    const bDate = new Date(b.date);
    return (b.memberId === props.memberId || b.member_id === props.memberId) && bDate < new Date();
  })
);

const handleCancel = async (id) => {
  try {
    await api.deleteBooking(id);
  } catch (err) {
    console.warn('Delete booking failed, removing locally', err);
  }
  bookings.value = bookings.value.filter((b) => b.id !== id);
};

const receiptBooking = ref(null);
const showReceiptModal = ref(false);

const openReceipt = (booking) => {
  receiptBooking.value = booking;
  showReceiptModal.value = true;
};

import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

const downloadReceipt = (booking) => {
  const doc = new jsPDF();
  const pageWidth = doc.internal.pageSize.width;
  const pageHeight = doc.internal.pageSize.height;
  const memberName = displayMembers.value.find((m) => m.id === (booking.memberId || booking.member_id))?.name || 'Guest User';
  const memberEmail = 'member@example.com'; // Placeholder as we don't have email in member object yet

  // --- Header ---
  doc.setFillColor(16, 185, 129); // Emerald-500
  doc.rect(0, 0, pageWidth, 40, 'F');

  // Draw Logo (Cricket Ball)
  const logoX = 20;
  const logoY = 20;
  doc.setFillColor(220, 38, 38); // Red-600
  doc.circle(logoX + 6, logoY, 6, 'F'); // Ball
  doc.setDrawColor(255, 255, 255);
  doc.setLineWidth(0.5);
  doc.line(logoX + 4, logoY - 5, logoX + 8, logoY + 5); // Simple seam representation
  doc.line(logoX + 3, logoY - 4, logoX + 9, logoY + 4); 

  // Logo Text
  doc.setTextColor(255, 255, 255);
  doc.setFontSize(22);
  doc.setFont('helvetica', 'bold');
  doc.text('PlayCricket', logoX + 16, logoY + 2); // Offset for text

  // Receipt Title
  doc.setFontSize(16);
  doc.setFont('helvetica', 'normal');
  doc.text('Receipt', pageWidth - 20, 25, { align: 'right' });

  // --- Info Section ---
  doc.setTextColor(60, 60, 60);
  
  // Left Column: Bill To
  doc.setFontSize(10);
  doc.setFont('helvetica', 'bold');
  doc.text('Bill To:', 20, 55);
  doc.setFont('helvetica', 'normal');
  doc.text(memberName, 20, 60);
  doc.setTextColor(100, 100, 100);
  doc.text(`Member ID: ${booking.memberId || booking.member_id}`, 20, 65);

  // Right Column: Receipt Details
  doc.setTextColor(60, 60, 60);
  doc.setFont('helvetica', 'bold');
  doc.text('Receipt Details:', pageWidth - 80, 55);
  doc.setFont('helvetica', 'normal');
  doc.text(`Receipt #: ${String(booking.id).toUpperCase()}`, pageWidth - 80, 60);
  doc.text(`Date: ${new Date().toLocaleDateString()}`, pageWidth - 80, 65);
  
  // Payment Status Badge (Text based)
  const payStatus = (booking.payment_status || 'UNPAID').toUpperCase();
  doc.setTextColor(payStatus === 'PAID' ? 16 : 220, payStatus === 'PAID' ? 185 : 38, payStatus === 'PAID' ? 129 : 38);
  doc.setFont('helvetica', 'bold');
  doc.text(payStatus, pageWidth - 20, 60, { align: 'right' });

  // --- Table ---
  const tableData = [
    ['Facility / Service', booking.facility],
    ['Scheduled Date', booking.date],
    ['Time Slot', booking.startTime || booking.start_time],
    ['Duration', `${booking.durationHours || booking.duration_hours || 1} hour(s)`],
    ['Booking Status', booking.status],
    ['Transaction ID', booking.payment_id || 'N/A']
  ];

  autoTable(doc, {
    startY: 80,
    head: [['Description', 'Details']],
    body: tableData,
    theme: 'grid',
    headStyles: { fillColor: [243, 244, 246], textColor: 60, fontStyle: 'bold', lineWidth: 0 },
    styles: { fontSize: 10, cellPadding: 8, lineColor: [229, 231, 235], lineWidth: 0.1 },
    columnStyles: { 
      0: { fontStyle: 'bold', width: 80, textColor: [75, 85, 99] },
      1: { textColor: [31, 41, 55] }
    },
  });

  // --- Footer ---
  const footerY = pageHeight - 30;
  doc.setFillColor(249, 250, 251); // Gray-50
  doc.rect(0, footerY, pageWidth, 30, 'F');
  
  doc.setTextColor(156, 163, 175); // Gray-400
  doc.setFontSize(10);
  doc.text('Thank you for booking with PlayCricket!', pageWidth / 2, footerY + 12, { align: 'center' });
  
  doc.setFontSize(8);
  doc.text('This is a computer generated receipt.', pageWidth / 2, footerY + 22, { align: 'center' });

  doc.save(`receipt-${booking.id}.pdf`);
};

const saveExisting = () => {
  if (!editableBooking.value) return;
  const idx = bookings.value.findIndex((b) => b.id === editableBooking.value.id);
  if (idx !== -1) {
    bookings.value[idx] = normalizeBooking(editableBooking.value);
  }
  showExistingModal.value = false;
};

const deleteExisting = () => {
  if (!editableBooking.value) return;
  bookings.value = bookings.value.filter((b) => b.id !== editableBooking.value.id);
  showExistingModal.value = false;
};

watch(
  () => props.initialBookings,
  (val) => {
    bookings.value = (val && val.length ? [...val] : []).map(normalizeBooking);
  },
  { deep: true }
);

onMounted(() => {
    const params = new URLSearchParams(window.location.search);
    if (params.get('payment') === 'success') {
        alert('Payment successful! Your booking is confirmed.');
        // Clean URL
        window.history.replaceState({}, document.title, "/");
    } else if (params.get('payment') === 'cancelled') {
        alert('Payment was cancelled.');
        window.history.replaceState({}, document.title, "/");
    }
});
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
        <button
          class="flex-1 md:flex-none px-3 md:px-4 py-2 rounded-md font-medium text-xs md:text-sm transition-colors flex items-center justify-center space-x-2 whitespace-nowrap"
          :class="activeView === 'history' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50'"
          @click="activeView = 'history'"
        >
          <span>🧾</span>
          <span>Purchase History</span>
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
      <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl animate-in fade-in zoom-in duration-200 relative max-h-[90vh] overflow-y-auto">
        <button
          class="absolute top-3 right-3 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 flex items-center justify-center transition-colors"
          aria-label="Close booking modal"
          @click="showModal = false"
        >
          x
        </button>
        <h3 class="text-xl font-bold mb-4 text-slate-800 flex items-center pr-10">
          <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mr-2 text-sm">🏏</span>
          Book a Slot
        </h3>
        
        <!-- Cart Visualization -->
        <div v-if="cart.length > 0" class="mb-6 p-4 bg-slate-50 rounded-xl border border-slate-200">
            <h4 class="font-bold text-sm text-slate-700 mb-2">Selected Slots ({{ cart.length }})</h4>
            <div class="space-y-2 max-h-40 overflow-y-auto">
                <div v-for="(item, idx) in cart" :key="idx" class="flex justify-between items-center text-xs bg-white p-2 rounded border border-slate-100">
                    <div>
                        <span class="font-bold text-emerald-700">{{ item.facility }}</span>
                        <p class="text-slate-500">{{ item.date }} @ {{ item.time }} ({{ item.ageGroup }})</p>
                    </div>
                    <button @click="removeFromCart(idx)" class="text-red-500 hover:text-red-700">✕</button>
                </div>
            </div>
            <div class="mt-3 grid grid-cols-2 gap-2 pt-3 border-t border-slate-200">
                 <button class="px-3 py-1.5 border border-emerald-600 text-emerald-700 rounded text-xs font-bold hover:bg-emerald-50" @click="handleBook(false)">
                    Checkout (Reserve)
                 </button>
                 <button class="px-3 py-1.5 bg-emerald-600 text-white rounded text-xs font-bold hover:bg-emerald-700" @click="handleBook(true)">
                    Checkout (Pay)
                 </button>
            </div>
        </div>

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
          <!-- New Fields -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
               <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Age Group</label>
                <select v-model="newBooking.ageGroup" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
                  <option>Under 13</option>
                  <option>Above 13</option>
                </select>
                <p class="text-[10px] text-slate-500 mt-1">
                    Assigned Lane: <span class="font-bold text-emerald-600">{{ newBooking.ageGroup === 'Under 13' ? 'Lane 1' : 'Lane 2' }}</span>
                </p>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Player Name</label>
                <input
                    v-model="newBooking.playerName"
                    type="text"
                    placeholder="Optional"
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
                />
              </div>
          </div>
        </div>
        <div class="mt-6 pt-4 border-t border-slate-100">
             <button class="w-full px-4 py-3 bg-slate-900 text-white font-bold rounded-lg hover:bg-slate-800 shadow-lg transition-all flex justify-center items-center gap-2" @click="addToCart">
                <span>+ Add to Cart</span>
             </button>
        </div>
      </div>
    </div>

    <div
      v-if="showExistingModal && editableBooking"
      class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-[101] p-4"
    >
      <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl animate-in fade-in zoom-in duration-200 relative">
        <button
          class="absolute top-3 right-3 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 flex items-center justify-center transition-colors"
          aria-label="Close booking modal"
          @click="showExistingModal = false"
        >
          x
        </button>
        <h3 class="text-xl font-bold mb-4 text-slate-800 flex items-center pr-10">
          <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mr-2 text-sm">✏️</span>
          Edit Booking
        </h3>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Facility</label>
            <select v-model="editableBooking.facility" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none">
              <option>Pitch 1</option>
              <option>Pitch 2</option>
              <option>Indoor Nets</option>
              <option>Bowling Machine</option>
            </select>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Date</label>
              <input v-model="editableBooking.date" type="date" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" />
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Time</label>
              <input v-model="editableBooking.startTime" type="time" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Duration (Hours)</label>
            <input
              v-model.number="editableBooking.durationHours"
              type="number"
              min="1"
              max="8"
              class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
            />
          </div>
        </div>
        <div class="mt-6 grid grid-cols-2 gap-3 pt-4 border-t border-slate-100">
          <button
            class="px-4 py-2 border-2 border-red-600 text-red-700 font-bold rounded-lg hover:bg-red-50 transition-colors"
            @click="deleteExisting"
          >
            Delete
          </button>
          <button class="px-4 py-2 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 shadow-lg transition-all" @click="saveExisting">
            Update
          </button>
        </div>
      </div>
    </div>

    <div
      v-if="showReceiptModal && receiptBooking"
      class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-[102] p-4"
    >
      <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl animate-in fade-in zoom-in duration-200 relative">
        <button
          class="absolute top-3 right-3 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 flex items-center justify-center transition-colors"
          aria-label="Close receipt modal"
          @click="showReceiptModal = false"
        >
          x
        </button>
        <h3 class="text-xl font-bold mb-4 text-slate-800 flex items-center pr-10">
          <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mr-2 text-sm">🧾</span>
          Receipt
        </h3>
        <div class="space-y-2 text-sm text-slate-700">
          <p><span class="font-semibold">Booking ID:</span> {{ receiptBooking.id }}</p>
          <p><span class="font-semibold">Member:</span> {{ displayMembers.find((m) => m.id === (receiptBooking.memberId || receiptBooking.member_id))?.name || '-' }}</p>
          <p><span class="font-semibold">Facility:</span> {{ receiptBooking.facility }}</p>
          <p><span class="font-semibold">Date:</span> {{ receiptBooking.date }} at {{ receiptBooking.startTime || receiptBooking.start_time }}</p>
          <p><span class="font-semibold">Duration:</span> {{ receiptBooking.durationHours || receiptBooking.duration_hours || receiptBooking.duration || 1 }} hours</p>
          <p><span class="font-semibold">Status:</span> {{ receiptBooking.status }}</p>
          <p><span class="font-semibold">Payment Status:</span> {{ receiptBooking.payment_status || 'UNPAID' }}</p>
          <p v-if="receiptBooking.payment_id"><span class="font-semibold">Payment ID:</span> {{ receiptBooking.payment_id }}</p>
        </div>
        <div class="mt-6 grid grid-cols-2 gap-3 pt-4 border-t border-slate-100">
          <button
            class="px-4 py-2 border-2 border-emerald-600 text-emerald-700 font-bold rounded-lg hover:bg-emerald-50 transition-colors"
            @click="downloadReceipt(receiptBooking)"
          >
            Download
          </button>
          <button
            class="px-4 py-2 bg-slate-900 text-white font-bold rounded-lg hover:bg-slate-800 shadow-lg transition-all"
            @click="showReceiptModal = false"
          >
            Close
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
      @select-booking="handleBookingClick"
    />

    <div v-else-if="activeView === 'my-bookings'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
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
            {{ booking.status }} ({{ booking.payment_status || 'UNPAID' }})
          </span>
        </div>

        <h4 class="text-base md:text-lg font-bold text-slate-800 mb-1">{{ booking.facility }}</h4>
        <p class="text-xs text-slate-500 mb-4">🗓 {{ booking.date }} | ⏰ {{ booking.startTime || booking.start_time }}</p>

        <div class="flex items-center mb-4">
          <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-600 mr-2">
            {{ displayMembers.find((m) => m.id === (booking.memberId || booking.member_id))?.name.charAt(0) }}
          </div>
          <div>
            <p class="text-xs font-medium text-slate-700">{{ displayMembers.find((m) => m.id === (booking.memberId || booking.member_id))?.name }}</p>
            <p class="text-[10px] text-slate-400">{{ displayMembers.find((m) => m.id === (booking.memberId || booking.member_id))?.membershipType }}</p>
          </div>
        </div>

        <div class="flex items-center space-x-2 border-t pt-3 md:pt-4">
          <button class="flex-1 text-[10px] font-medium py-1.5 rounded border border-slate-200 text-slate-600 hover:bg-slate-50">
            Sync
          </button>
          <button
            v-if="booking.payment_status === 'PAID' || booking.status === 'CONFIRMED'"
            class="flex-1 text-[10px] font-medium py-1.5 rounded border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700"
            @click="openReceipt(booking)"
          >
            Receipt
          </button>
          <button
            class="flex-1 text-[10px] font-medium py-1.5 rounded border border-slate-200 text-slate-600 hover:bg-blue-50 hover:text-blue-600"
            @click="handleBookingClick(booking)"
          >
            Update
          </button>
          <button
            class="flex-1 text-[10px] font-medium py-1.5 rounded border border-slate-200 text-slate-600 hover:bg-red-50 hover:text-red-600"
            @click="handleCancel(booking.id)"
          >
            Delete
          </button>
        </div>
      </div>
    </div>

    <div v-else-if="activeView === 'history'" class="mt-4 space-y-3">
      <h3 class="text-lg font-bold text-slate-800">Purchase History</h3>
      <div
        v-for="booking in pastBookings"
        :key="booking.id"
        class="flex items-center justify-between bg-white border border-slate-200 rounded-xl p-4 shadow-sm"
      >
        <div>
          <p class="font-semibold text-slate-800 text-sm">{{ booking.facility }}</p>
          <p class="text-xs text-slate-500">{{ booking.date }} • {{ booking.startTime || booking.start_time }}</p>
          <p class="text-[11px] text-slate-500">Status: {{ booking.status }} | Payment: {{ booking.payment_status || 'UNPAID' }}</p>
        </div>
        <div class="flex items-center space-x-2">
          <button
            class="px-3 py-1.5 border border-slate-200 rounded-lg text-[11px] text-slate-600 hover:bg-emerald-50 hover:text-emerald-700"
            @click="openReceipt(booking)"
          >
            View Receipt
          </button>
          <button
            class="px-3 py-1.5 bg-slate-900 text-white rounded-lg text-[11px] font-semibold hover:bg-slate-800"
            @click="downloadReceipt(booking)"
          >
            Download
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
