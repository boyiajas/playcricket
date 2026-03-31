<script setup>
import { ref } from 'vue';

const emit = defineEmits(['login', 'contact']);
const isMobileMenuOpen = ref(false);
const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const closeMobileMenu = () => {
    isMobileMenuOpen.value = false;
};

const handleMobileLogin = () => {
    closeMobileMenu();
    emit('login');
};

const handleLogoClick = () => {
    if (typeof window !== 'undefined' && window.innerWidth < 768) {
        return;
    }
    emit('login');
};

const services = [
    { 
        id: 1, 
        title: 'Lane Hire', 
        description: 'Three full-size indoor cricket lanes available for hire. Perfect for net sessions, team drills, or individual practice.',
        image: '/images/lane-hire.png'
    },
    { 
        id: 2, 
        title: 'Bowling Machines', 
        description: 'State-of-the-art bowling machines delivering pace, spin, swing and seam. Dial up any delivery type from gentle pace to express speed.',
        image: '/images/bowling-machine.png'
    },
    { 
        id: 3, 
        title: 'Private Coaching', 
        description: '1-on-1 sessions with our expert coaches. Technical refinement, mental game, batting, bowling, or fielding - your session, your goals.',
        image: '/images/private-coaching.jpg'
    },
    { 
        id: 4, 
        title: 'Group Coaching', 
        description: 'Small-group sessions for schools, club & squads. Our coaches run structured programs designed to build team skill and cohesion simultaneously.',
        image: '/images/group-coaching.png'
    },
    { 
        id: 5, 
        title: 'Junior Academy', 
        description: 'Structured programs for U9–U19 players. Skill development, match preparation and age-appropriate coaching in a safe, high-energy environment.',
        image: '/images/junior-academy.png'
    },
    { 
        id: 6, 
        title: 'Online Booking', 
        description: 'Book lanes, coaching sessions, and machine time instantly online. Secure payment, instant confirmation, and easy rescheduling - all from your phone.',
        image: '/images/online-booking.png'
    },
];

const contactForm = ref({
    name: '',
    email: '',
    message: ''
});

const dateInput = ref(null);

const isTimeDropdownOpen = ref(false);
const isServiceDropdownOpen = ref(false);

const selectTime = (t) => {
    bookingData.value.time = t;
    isTimeDropdownOpen.value = false;
};

const selectService = (s) => {
    bookingData.value.service = s;
    isServiceDropdownOpen.value = false;
};

const showVideoModal = ref(false);
const activeVideoUrl = ref('');

const openVideo = (url) => {
    activeVideoUrl.value = url;
    showVideoModal.value = true;
};

const closeVideo = () => {
    showVideoModal.value = false;
    activeVideoUrl.value = '';
};

const bookingData = ref({
    date: new Date(Date.now() + 86400000).toISOString().substr(0, 10), // Default to tomorrow
    time: '16:00 - 17:00',
    service: 'Lane 02 + Bowling Machine'
});

const availableTimes = [
    '09:00 - 10:00', '10:00 - 11:00', '11:00 - 12:00', '12:00 - 13:00',
    '13:00 - 14:00', '14:00 - 15:00', '15:00 - 16:00', '16:00 - 17:00',
    '17:00 - 18:00', '18:00 - 19:00', '19:00 - 20:00'
];

const availableServices = [
    'Lane 01 Hire', 'Lane 02 Hire', 'Lane 02 + Bowling Machine',
    'Private Coaching', 'Group Session', 'Junior Academy'
];

const submitContact = () => {
    alert('Thank you! We will contact you shortly.');
    contactForm.value = { name: '', email: '', message: '' };
};

const testimonials = [
    { 
        name: 'James K.', 
        role: 'Club Cricketer', 
        quote: '"Priya transformed my run-up and I went from 115 to 130 kph in eight weeks. I didn\'t think that was possible at my age. Absolutely recommend her to any aspiring pacer."', 
        stars: 5, 
        avatar: '/images/parent-1.png' 
    },
    { 
        name: 'Melissa Turner', 
        role: 'Parent of U12 player', 
        quote: '"Daniel has a gift for working with kids. My daughter was shy and unsure - now she\'s obsessed with cricket and talks about it non-stop. Cricket changed everything for her."', 
        stars: 5, 
        avatar: '/images/parent-2.png' 
    },
    { 
        name: 'Vikram Sharma', 
        role: 'Club Captain', 
        type: 'video', 
        avatar: '/images/player-vikram.png' 
    },
    { 
        name: 'Amir M.', 
        role: 'U19 Fast Bowler', 
        type: 'video', 
        avatar: '/images/player-amir.png' 
    },
    { 
        name: 'Emily T.', 
        role: 'U17 Representative', 
        quote: '"Booked our whole squad in for a pre-season net session. The lanes are immaculate, booking was dead easy, and the staff were super helpful. We\'ll be back every month."', 
        stars: 5, 
        avatar: '/images/parent-3.png' 
    },
    { 
        name: 'Melissa Turner', 
        role: 'Parent of U10 player', 
        quote: '"My son went from being nervous at the crease to one of the top batters in his U12 team. The coaches here actually care about development."', 
        stars: 5, 
        avatar: '/images/parent-4.png' 
    },
];

</script>

<template>
  <div class="font-sans text-slate-800 bg-white">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white border-b border-slate-100 shadow-sm transition-all duration-300">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
          <div class="flex-shrink-0 flex items-center cursor-pointer" @click="handleLogoClick">
             <img src="/logo.png" alt="PlayCricket" class="h-16 w-auto" />
          </div>
          <div class="hidden md:flex space-x-12 items-center">
            <a href="#lanes" class="text-slate-800 hover:text-red-600 font-semibold transition-colors">Lanes</a>
            <a href="#coaching" class="text-slate-800 hover:text-red-600 font-semibold transition-colors">Coaching</a>
            <a href="#academy" class="text-slate-800 hover:text-red-600 font-semibold transition-colors">Academy</a>
            <a href="#" @click.prevent="$emit('contact')" class="text-slate-800 hover:text-red-600 font-semibold transition-colors">Contact</a>
            <button
                @click="$emit('login')"
                class="bg-red-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-red-700 transition-all shadow-md hover:shadow-lg transform active:scale-95"
            >
              Book Now
            </button>
          </div>
           <!-- Mobile menu button -->
           <div class="md:hidden flex items-center relative z-50" @click.stop>
              <button
                  type="button"
                  aria-controls="mobile-nav-drawer"
                  :aria-expanded="isMobileMenuOpen"
                  @click.stop="toggleMobileMenu"
                  class="text-slate-900 hover:text-red-600 transition-colors focus:outline-none p-2 -mr-2 relative z-50"
              >
                 <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path v-if="!isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                 </svg>
              </button>
           </div>
        </div>

        <!-- Mobile Menu Drawer -->
        <transition 
            enter-active-class="transition duration-300 ease-out" 
            enter-from-class="transform -translate-y-3 scale-y-95 opacity-0" 
            enter-to-class="transform translate-y-0 scale-y-100 opacity-100" 
            leave-active-class="transition duration-200 ease-in" 
            leave-from-class="transform translate-y-0 scale-y-100 opacity-100" 
            leave-to-class="transform -translate-y-3 scale-y-95 opacity-0"
        >
            <div
                id="mobile-nav-drawer"
                v-show="isMobileMenuOpen"
                class="md:hidden absolute top-20 left-0 w-full bg-white border-t border-slate-100 shadow-xl overflow-hidden z-40 origin-top transform-gpu"
            >
                <div class="px-4 py-6 space-y-2 flex flex-col">
                    <a href="#lanes" @click="closeMobileMenu" class="block px-4 py-3 rounded-xl text-lg font-bold text-slate-800 hover:bg-slate-50 hover:text-red-600 transition-colors">Lanes</a>
                    <a href="#coaching" @click="closeMobileMenu" class="block px-4 py-3 rounded-xl text-lg font-bold text-slate-800 hover:bg-slate-50 hover:text-red-600 transition-colors">Coaching</a>
                    <a href="#academy" @click="closeMobileMenu" class="block px-4 py-3 rounded-xl text-lg font-bold text-slate-800 hover:bg-slate-50 hover:text-red-600 transition-colors">Academy</a>
                    <a href="#" @click.prevent="closeMobileMenu(); $emit('contact');" class="block px-4 py-3 rounded-xl text-lg font-bold text-slate-800 hover:bg-slate-50 hover:text-red-600 transition-colors mb-4">Contact</a>
                    
                    <button 
                        @click="handleMobileLogin" 
                        class="w-full mt-4 bg-red-600 text-white px-6 py-4 rounded-xl font-bold text-lg hover:bg-red-700 transition-all shadow-md active:scale-95 flex justify-center items-center"
                    >
                        Book Now
                    </button>
                </div>
            </div>
        </transition>
      </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative h-screen bg-black overflow-hidden flex items-center justify-center">
       <div class="absolute inset-0">
          <img src="/hero-player.png" class="w-full h-full object-cover opacity-80" alt="Hero Athlete" />
          <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-transparent to-black/60"></div>
       </div>
       
       <!-- Play Button Overlay -->
       <div class="relative z-20 group cursor-pointer" @click="openVideo('/hero-video.mp4')">
          <div class="w-32 h-32 md:w-48 md:h-48 rounded-full border-4 border-white/50 flex items-center justify-center backdrop-blur-sm group-hover:border-white transition-all duration-500 scale-90 group-hover:scale-100 shadow-[0_0_50px_rgba(255,255,255,0.6)] group-hover:shadow-[0_0_100px_rgba(255,255,255,1)] bg-white/5 group-hover:bg-white/10">
             <div class="w-24 h-24 md:w-36 md:h-36 rounded-full border-2 border-white/80 flex items-center justify-center shadow-[inset_0_0_30px_rgba(255,255,255,0.6),_0_0_40px_rgba(255,255,255,0.8)] bg-white/10">
                <div class="w-0 h-0 border-t-[20px] md:border-t-[30px] border-t-transparent border-l-[35px] md:border-l-[50px] border-l-white border-b-[20px] md:border-b-[30px] border-b-transparent ml-3 md:ml-5 drop-shadow-[0_0_25px_rgba(255,255,255,1)] group-hover:drop-shadow-[0_0_40px_rgba(255,255,255,1)]"></div>
             </div>
          </div>
          <!-- Pulse animation circles -->
          <div class="absolute inset-0 rounded-full border-2 border-white/60 animate-ping opacity-50 shadow-[0_0_50px_rgba(255,255,255,1)]"></div>
          <div class="absolute inset-0 rounded-full border-2 border-white/40 animate-ping opacity-40 delay-300 shadow-[0_0_40px_rgba(255,255,255,0.8)]"></div>
       </div>

       <!-- Hero Decorative Line (from screenshot) -->
       <div class="absolute left-0 right-0 top-1/2 border-t border-dashed border-blue-400/30 -z-0 pointer-events-none"></div>
       <div class="absolute top-0 bottom-0 left-1/2 border-l border-dashed border-blue-400/30 -z-0 pointer-events-none"></div>
    </section>

    <!-- Our Services Section -->
    <section id="services" class="relative pb-24 bg-white overflow-hidden">
        <!-- Background Watermark -->
        <div class="absolute inset-0 z-0 opacity-[0.8] pointer-events-none flex items-center justify-center p-20">
            <img 
                src="/images/services-bg.png" 
                class="max-w-4xl w-full h-auto object-contain grayscale"
                alt="Services Background Pattern"
                style="margin-left: 600px; margin-top: -720px;opacity: 0.1;"
            />
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24">
           <div class="text-center mb-20">
               <p class="text-[#FF0F20] font-bold uppercase tracking-widest text-sm mb-2">What we offer</p>
               <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-6">Our Services</h2>
               <p class="text-slate-500 text-lg max-w-3xl mx-auto leading-relaxed">
                   Everything you need to take your game to the next level. From casual practice to professional development.
               </p>
           </div>
           
           <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mb-16">
              <div v-for="service in services" :key="service.id" class="group">
                  <div class="relative h-64 mb-6 overflow-hidden rounded-[2rem] shadow-xl transition-transform duration-500 group-hover:scale-[1.02]">
                     <img :src="service.image" class="w-full h-full object-cover" :alt="service.title">
                     <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors"></div>
                  </div>
                  <h3 class="text-2xl font-bold text-slate-900 mb-3">{{ service.title }}</h3>
                  <p class="text-slate-500 leading-relaxed text-sm md:text-base">
                      {{ service.description }}
                  </p>
              </div>
           </div>

           <div class="flex justify-center mt-12">
               <button 
                  @click="$emit('login')"
                  class="bg-[#FF0F20] hover:bg-[#E60D1D] text-white text-lg font-bold px-10 py-4 rounded-xl transition-all shadow-xl hover:shadow-2xl transform active:scale-95"
               >
                  Book Your Slot Now
               </button>
           </div>
        </div>
    </section>

    <!-- Quick Booking Section -->
    <section id="booking" class="relative py-24 overflow-hidden">
        <!-- Grass Background -->
        <div class="absolute inset-0 z-0">
             <img src="/images/booking-bg.jpg" class="w-full h-full object-cover" alt="Cricket Pitch Grass Background">
             <div class="absolute inset-0 bg-black/65"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Left: Content -->
                <div class="text-white">
                    <p class="text-white/80 font-bold uppercase tracking-widest text-sm mb-4">Play Cricket</p>
                    <h2 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-8 leading-tight">
                        Book Your Session<br>
                        <span class="text-[#FF0F20]">in Seconds</span>
                    </h2>
                    <p class="text-xl text-white/90 max-w-xl mb-10 leading-relaxed">
                        Our modern booking system allows you to secure your lane or coaching session instantly. No phone calls, no waiting. Just pick your time and play.
                    </p>
                    
                    <ul class="space-y-6">
                        <li v-for="feature in ['Real-time Lane Availability', 'Instant Payment Confirmation', 'Manage Bookings via your Profile', 'Automated Session Reminders']" :key="feature" class="flex items-center gap-4 text-lg">
                            <div class="w-6 h-6 rounded-full border-2 border-white/50 flex items-center justify-center text-xs">✓</div>
                            {{ feature }}
                        </li>
                    </ul>
                </div>

                <!-- Right: Booking Card -->
                <div class="flex justify-center lg:justify-end">
                    <div class="bg-white w-full max-w-md rounded-[3rem] p-8 md:p-12 shadow-2xl relative overflow-visible">
                        
                        <!-- Clickaway overlay for dropdowns -->
                        <div v-if="isTimeDropdownOpen || isServiceDropdownOpen" @click="isTimeDropdownOpen = false; isServiceDropdownOpen = false" class="fixed inset-0 z-[40]"></div>

                        <div class="flex items-center gap-6 mb-8 relative z-[41]">
                            <div class="w-16 h-16 bg-[#FF0F20] rounded-2xl flex items-center justify-center shadow-lg shadow-[#FF0F20]/20">
                                <span class="text-3xl text-white">🕒</span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-900">Quick Booking</h3>
                                <p class="text-slate-400 text-sm">Select your preferred slot</p>
                            </div>
                             <div class="ml-auto opacity-20">
                                <span class="text-5xl">📅</span>
                            </div>
                        </div>

                        <div class="space-y-4 mb-8">
                            <!-- Date Field -->
                            <div @click="dateInput?.showPicker()" class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex justify-between items-center group hover:border-[#FF0F20]/20 transition-all cursor-pointer relative lg:h-16">
                                <span class="text-slate-400 font-bold text-sm select-none">Date</span>
                                <div class="flex items-center gap-2">
                                    <input ref="dateInput" type="date" v-model="bookingData.date" 
                                           class="bg-transparent text-slate-800 font-bold text-sm outline-none cursor-pointer focus:ring-0 border-none p-0 text-right appearance-none font-sans min-w-[120px]">
                                </div>
                            </div>

                            <!-- Time Field -->
                            <div class="relative z-[45]">
                                <div @click="isTimeDropdownOpen = !isTimeDropdownOpen; isServiceDropdownOpen = false" class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex justify-between items-center group hover:border-[#FF0F20]/20 transition-all cursor-pointer h-16 relative z-[45]">
                                    <span class="text-slate-400 font-bold text-sm">Time</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-slate-800 font-bold text-sm">{{ bookingData.time }}</span>
                                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': isTimeDropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                
                                <transition enter-active-class="transition duration-200 ease-out" enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-100 ease-in" leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
                                    <div v-if="isTimeDropdownOpen" class="absolute top-[calc(100%+8px)] left-0 right-0 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden max-h-60 overflow-y-auto w-full z-[50]">
                                        <div v-for="t in availableTimes" :key="t" @click="selectTime(t)"
                                             class="px-5 py-3 hover:bg-red-50 hover:text-[#FF0F20] text-sm cursor-pointer transition-colors"
                                             :class="bookingData.time === t ? 'bg-red-50 text-[#FF0F20] font-bold' : 'text-slate-700 font-medium'">
                                            {{ t }}
                                        </div>
                                    </div>
                                </transition>
                            </div>

                            <!-- Service Field -->
                            <div class="relative z-[44]">
                                <div @click="isServiceDropdownOpen = !isServiceDropdownOpen; isTimeDropdownOpen = false" class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex justify-between items-center group hover:border-[#FF0F20]/20 transition-all cursor-pointer h-16 relative z-[44]">
                                    <span class="text-slate-400 font-bold text-sm whitespace-nowrap mr-3">Service</span>
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="text-slate-800 font-bold text-sm truncate">{{ bookingData.service }}</span>
                                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-300 shrink-0" :class="{'rotate-180': isServiceDropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                
                                <transition enter-active-class="transition duration-200 ease-out" enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-100 ease-in" leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
                                    <div v-if="isServiceDropdownOpen" class="absolute top-[calc(100%+8px)] left-0 right-0 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden max-h-60 overflow-y-auto w-full z-[50]">
                                        <div v-for="s in availableServices" :key="s" @click="selectService(s)"
                                             class="px-5 py-3 hover:bg-red-50 hover:text-[#FF0F20] text-sm cursor-pointer transition-colors"
                                             :class="bookingData.service === s ? 'bg-red-50 text-[#FF0F20] font-bold' : 'text-slate-700 font-medium'">
                                            {{ s }}
                                        </div>
                                    </div>
                                </transition>
                            </div>
                        </div>

                        <button 
                           @click="$emit('login')"
                           class="w-full bg-[#FF0F20] hover:bg-[#E60D1D] text-white font-bold py-5 rounded-2xl transition-all shadow-xl hover:shadow-[#FF0F20]/20 transform active:scale-[0.98] flex items-center justify-center gap-3"
                        >
                           <span>🎫</span>
                           Proceed to Payment
                        </button>
                        
                        <p class="text-center text-slate-400 text-[10px] mt-6 uppercase tracking-widest font-bold">
                           Secure 256-bit SSL encrypted payment
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Meet Our Coaches Section -->
    <section id="coaches" class="relative pt-32 pb-0 bg-white overflow-hidden">
        <!-- Decorative Bat -->
        <div class="absolute top-20 left-10 opacity-100 z-10 pointer-events-none scale-110" style="margin-left: 15%;">
            <img src="/images/decorative-bat.png" class="w-[320px] h-auto drop-shadow-2xl" alt="Decorative Cricket Bat">
        </div>

        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-24">
                <p class="text-[#FF0F20] font-extrabold uppercase tracking-[0.2em] text-sm mb-4">Trainers</p>
                <h2 class="text-4xl sm:text-5xl lg:text-7xl font-bold text-[#0f172a] tracking-tight">Meet Our Coaches</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-0">
                <!-- Coach 1: Placeholder -->
                <div class="bg-slate-50/30 rounded-[3rem] border-2 border-dashed border-slate-200 p-0 shadow-none hover:shadow-lg transition-all duration-500 flex flex-col items-center opacity-80 backdrop-blur-sm min-h-[400px]">
                    <div class="pt-16 pb-4 text-center">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-200 text-slate-300 shadow-inner">
                            <svg class="w-8 h-8 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-400 mb-2">More Pros</h3>
                        <p class="text-slate-300 font-extrabold text-xs uppercase tracking-widest">Coming Soon</p>
                    </div>
                    <div class="w-full mt-auto h-48 flex justify-center items-end px-8 pb-8">
                        <div class="w-full h-full bg-gradient-to-t from-slate-100 to-transparent rounded-t-[3rem] opacity-50 border-t-2 border-white/60"></div>
                    </div>
                </div>

                <!-- Coach 2: Coach Lazarus -->
                <div class="bg-slate-50/50 rounded-[3rem] border border-slate-100 p-0 shadow-sm hover:shadow-2xl transition-all duration-500 group overflow-hidden flex flex-col items-center">
                    <div class="pt-10 pb-4 text-center">
                        <h3 class="text-3xl font-bold text-[#0f172a] mb-1">Lazarus Mokeona</h3>
                        <p class="text-red-500 font-extrabold text-xs uppercase tracking-widest">Head Coach</p>
                    </div>
                    <div class="w-full mt-auto flex justify-center h-80">
                        <img src="/images/coach-lazarus.jpg" class="w-full h-full object-cover object-top transform group-hover:scale-110 transition-transform duration-700" alt="Coach Lazarus">
                    </div>
                </div>

                <!-- Coach 3: Placeholder -->
                <div class="bg-slate-50/30 rounded-[3rem] border-2 border-dashed border-slate-200 p-0 shadow-none hover:shadow-lg transition-all duration-500 flex flex-col items-center opacity-80 backdrop-blur-sm min-h-[400px]">
                    <div class="pt-16 pb-4 text-center">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-200 text-slate-300 shadow-inner">
                            <svg class="w-8 h-8 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-400 mb-2">New Specialist</h3>
                        <p class="text-slate-300 font-extrabold text-xs uppercase tracking-widest">Coming Soon</p>
                    </div>
                    <div class="w-full mt-auto h-48 flex justify-center items-end px-8 pb-8">
                        <div class="w-full h-full bg-gradient-to-t from-slate-100 to-transparent rounded-t-[3rem] opacity-50 border-t-2 border-white/60"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Red Stats Bar -->
        <div class="bg-red-600 py-20 relative z-30" style="margin-top: -2px;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-12">
                    <div v-for="stat in [
                        { val: '2,150+', label: 'People Joined' },
                        { val: '150+', label: 'Matches Played' },
                        { val: '05+', label: 'Years of Service' },
                        { val: '07+', label: 'Trophies Won' }
                    ]" :key="stat.label" class="text-center text-white relative flex flex-col items-center border-white/20 last:border-0 lg:border-r border-white/20">
                        <div class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 tracking-tighter">{{ stat.val }}</div>
                        <div class="w-16 h-1.5 bg-white rounded-full mb-6 drop-shadow-md"></div>
                        <div class="text-xl font-bold tracking-wide opacity-95">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Who We Train Section -->
    <section id="programs" class="relative py-24 bg-white overflow-hidden">
        <!-- Trophy Watermark Background -->
        <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none z-0">
            <img src="/images/sketch-bg.png" class="w-[1000px] h-auto" alt="Trophy Sketch Watermark">
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <p class="text-red-600 font-extrabold uppercase tracking-widest text-sm mb-4">Who We Train</p>
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-[#0f172a] mb-8">Built For Every Cricketer</h2>
                <p class="text-slate-500 text-xl max-w-3xl mx-auto leading-relaxed">
                    Whether you're 9 years old holding a bat for the first time, or a seasoned club cricketer 
                    <span class="relative inline-block">
                        chasing consistency
                        <svg class="absolute -bottom-1 left-0 w-full h-1 text-sky-400 opacity-60" fill="none" viewBox="0 0 100 10" preserveAspectRatio="none">
                            <path d="M0 5 Q 50 0 100 5" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                        </svg>
                    </span> 
                    - Play Cricket has a program for you.
                </p>
            </div>

            <div class="relative max-w-5xl mx-auto h-[700px] flex items-center justify-center">
                <!-- SVG Arrows Layer -->
                <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 800 600" fill="none">
                    <!-- Top Left to Center -->
                    <path d="M220 180 Q 320 200 380 270" stroke="#cbd5e1" stroke-width="2" stroke-dasharray="8,8" />
                    <!-- Top Right to Center -->
                    <path d="M580 180 Q 480 200 420 270" stroke="#cbd5e1" stroke-width="2" stroke-dasharray="8,8" />
                    <!-- Center to Bottom Left -->
                    <path d="M380 330 Q 320 400 220 450" stroke="#cbd5e1" stroke-width="2" stroke-dasharray="8,8" />
                    <!-- Center to Bottom Right -->
                    <path d="M420 330 Q 480 400 580 450" stroke="#cbd5e1" stroke-width="2" stroke-dasharray="8,8" />
                    
                    <!-- Arrowheads -->
                    <polygon points="380,270 375,260 385,260" fill="#cbd5e1" transform="rotate(20 380 270)" />
                    <polygon points="420,270 415,260 425,260" fill="#cbd5e1" transform="rotate(-20 420 270)" />
                    <polygon points="220,450 225,440 215,440" fill="#cbd5e1" transform="rotate(-40 220 450)" />
                    <polygon points="580,450 575,440 585,440" fill="#cbd5e1" transform="rotate(40 580 450)" />
                </svg>

                <!-- Icons Hub Grid -->
                <div class="absolute inset-0 grid grid-cols-2 grid-rows-2 h-full gap-40">
                    <!-- Under 9s -->
                    <div class="flex flex-col items-center justify-center p-4">
                        <div class="w-32 h-32 mb-4 drop-shadow-xl hover:scale-110 transition-transform">
                            <img src="/images/prog-u9.png" class="w-full h-full object-contain" alt="Under 9s">
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Under 9s</h3>
                        <p class="text-slate-400 text-sm max-w-[200px] text-center leading-snug">Intro to cricket fundamentals. Fun-first, confidence-building coaching for beginners.</p>
                    </div>

                    <!-- U10-U14 -->
                    <div class="flex flex-col items-center justify-center p-4">
                        <div class="w-32 h-32 mb-4 drop-shadow-xl hover:scale-110 transition-transform">
                            <img src="/images/prog-u14.png" class="w-full h-full object-contain" alt="U10-U14">
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">U10–U14</h3>
                        <p class="text-slate-500 text-sm max-w-[220px] text-center leading-snug">Skill development and competitive prep. The critical years where technique is built properly.</p>
                    </div>

                    <!-- U15-U19 -->
                    <div class="flex flex-col items-center justify-center p-4">
                        <div class="w-32 h-32 mb-4 drop-shadow-xl hover:scale-110 transition-transform">
                            <img src="/images/prog-u19.png" class="w-full h-full object-contain" alt="U15-U19">
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">U15–U19</h3>
                        <p class="text-slate-500 text-sm max-w-[220px] text-center leading-snug">Performance-focused training for serious junior players eyeing rep or elite cricket.</p>
                    </div>

                    <!-- Club Cricketers -->
                    <div class="flex flex-col items-center justify-center p-4">
                        <div class="w-32 h-32 mb-4 drop-shadow-xl hover:scale-110 transition-transform">
                            <img src="/images/prog-club.png" class="w-full h-full object-contain" alt="Club Cricketers">
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Club Cricketers</h3>
                        <p class="text-slate-500 text-sm max-w-[220px] text-center leading-snug">Net sessions, machine time, and optional coaching for adult club players at any level.</p>
                    </div>
                </div>

                <!-- Central Icon: Schools & Teams -->
                <div class="relative z-20 bg-white/80 p-6 flex flex-col items-center justify-center">
                    <div class="absolute opacity-100 z-10 pointer-events-none scale-110" style="margin-left:15%;width: 500px;">
                        <img src="/images/services-bg.png" class="opacity-10 h-auto drop-shadow-2xl" alt="Decorative Cricket Bat">
                    </div>
                    <div class="w-32 h-32 mb-4 drop-shadow-2xl hover:scale-110 transition-transform">
                        <img src="/images/prog-schools.png" class="w-full h-full object-contain" alt="Schools & Teams">
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Schools & Teams</h3>
                    <p class="text-slate-500 text-sm max-w-[250px] text-center leading-snug">Group rates for school programs, club squads, and holiday camps. Enquire for packages.</p>
                </div>
            </div>

            <!-- Bottom Button -->
            <div class="flex justify-center mt-12">
                <button class="bg-[#FF0F20] hover:bg-[#E60D1D] text-white font-bold py-4 px-10 rounded-xl shadow-xl transition-all hover:scale-105 active:scale-95 uppercase tracking-wider text-sm">
                    Book Your Slot Now
                </button>
            </div>
        </div>
    </section>

    <!-- From the Players Section -->
    <section id="testimonials" class="relative py-24 bg-[#111111] overflow-hidden">
        <!-- Geometric Background Pattern -->
        <div class="absolute inset-0 opacity-40 z-0">
            <img src="/images/testimonials-bg.png" class="w-full h-full object-cover" alt="Background Pattern">
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="text-[#FF0F20] font-extrabold uppercase tracking-widest text-sm mb-4">What They're Saying</p>
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6 tracking-tight">From the Players</h2>
                <p class="text-slate-400 text-xl max-w-3xl mx-auto leading-relaxed">
                    At PlayCricket, we use professional coaching and state-of-the-art facilities to simplify your training, 
                    boost performance, and help you make smarter, faster game-day decisions.
                </p>
            </div>

            <!-- Testimonials Staggered Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="(item, idx) in testimonials" :key="idx" 
                     class="group relative bg-[#222222]/80 backdrop-blur-md border border-white/5 rounded-[2.5rem] p-8 hover:bg-[#2a2a2a] transition-all duration-500 overflow-hidden shadow-2xl"
                     :class="{'aspect-square flex items-center justify-center p-0': item.type === 'video'}">
                    
                    <!-- Text Testimonial -->
                    <template v-if="item.type !== 'video'">
                        <div class="flex gap-1 mb-6">
                            <span v-for="s in item.stars" :key="s" class="text-yellow-500 text-lg">★</span>
                        </div>
                        <p class="text-slate-300 text-lg leading-relaxed mb-8 italic">
                            {{ item.quote }}
                        </p>
                        <div class="flex items-center gap-4 border-t border-white/5 pt-6">
                            <img :src="item.avatar" class="w-12 h-12 rounded-full object-cover bg-slate-800" :alt="item.name">
                            <div>
                                <h4 class="text-white font-bold">{{ item.name }}</h4>
                                <p class="text-slate-500 text-sm">{{ item.role }}</p>
                            </div>
                        </div>
                    </template>

                    <!-- Video Testimonial -->
                    <template v-else>
                        <img :src="item.avatar" class="absolute inset-0 w-full h-full object-cover grayscale-[0.3] group-hover:grayscale-0 transition-all duration-700" :alt="item.name">
                        <div class="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition-colors"></div>
                        
                        <!-- Play Button Overlay -->
                        <div class="relative z-20 w-20 h-20 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border border-white/30 group-hover:scale-110 transition-transform cursor-pointer" @click="openVideo(item.video || '/hero-video.mp4')">
                            <div class="w-0 h-0 border-t-[12px] border-t-transparent border-l-[18px] border-l-white border-b-[12px] border-b-transparent ml-2"></div>
                        </div>

                        <!-- Info Overlay -->
                        <div class="absolute bottom-6 left-6 right-6 z-20 flex items-center gap-3 bg-black/40 backdrop-blur-md p-3 rounded-2xl border border-white/10">
                            <img :src="item.avatar" class="w-10 h-10 rounded-full object-cover border border-white/20" alt="">
                            <div>
                                <h4 class="text-white font-bold text-sm">{{ item.name }}</h4>
                                <p class="text-slate-400 text-xs">{{ item.role }}</p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>


    <!-- Ready to Train CTA Banner -->
    <section class="relative py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative rounded-[3rem] overflow-hidden shadow-2xl h-[450px] flex items-center justify-center text-center">
                <!-- Grass Background -->
                <img src="/images/cta-grass-v2.jpg" class="absolute inset-0 w-full h-full object-cover" alt="Cricket Grass Background">
                <div class="absolute inset-0 bg-black/50"></div>
                
                <div class="relative z-10 px-6">
                    <p class="text-[#FF0F20] font-extrabold uppercase tracking-widest text-lg mb-4">Ready to Train?</p>
                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white mb-6 tracking-tight">Your Next Session Starts Here.</h2>
                    <p class="text-white text-xl max-w-2xl mx-auto mb-10 opacity-95">
                        Lanes available 7 days a week. Online booking takes less than 2 minutes.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                        <button @click="$emit('login')" class="bg-[#FF0F20] hover:bg-[#E60D1D] text-white font-bold py-4 px-10 rounded-xl shadow-xl transition-all hover:scale-105 active:scale-95 uppercase tracking-wider text-sm">
                            Book Your Slot Now
                        </button>
                        <button @click="$emit('contact')" class="bg-transparent border-2 border-white hover:bg-white/10 text-white font-bold py-4 px-10 rounded-xl transition-all hover:scale-105 active:scale-95 uppercase tracking-wider text-sm">
                            Contact Us
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- New White Footer -->
    <footer class="bg-white pt-24 pb-12 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-16">
                <!-- Brand Column -->
                <div class="flex flex-col">
                    <div class="flex items-center mb-8">
                        <div class="bg-[#FF0F20]/10 p-2 rounded-xl mr-3">
                            <img src="/logo.png" alt="PlayCricket" class="h-10 w-auto" />
                        </div>
                        <span class="text-2xl font-black text-[#0f172a] tracking-tight">PLAY CRICKET</span>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed mb-8 max-w-xs">
                        Indoor cricket training done right. Three lanes, elite coaches, and the tech to back it up. Open 7 days.
                    </p>
                    <!-- Social Icons (Circular) -->
                    <div class="flex space-x-4">
                        <a v-for="(icon, i) in ['facebook', 'instagram', 'linkedin', 'youtube']" :key="i" href="#" 
                           class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 hover:text-[#FF0F20] hover:border-[#FF0F20] transition-all duration-300">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path v-if="icon === 'facebook'" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                <path v-if="icon === 'instagram'" d="M12 2.163v2.163c4.785 0 8.674 3.889 8.674 8.674 0 4.786-3.889 8.674-8.674 8.674s-8.674-3.888-8.674-8.674c0-4.785 3.889-8.674 8.674-8.674zm0-2.163c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12z"/>
                                <path v-if="icon === 'linkedin'" d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                                <path v-if="icon === 'youtube'" d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Services Column -->
                <div>
                    <h3 class="text-xl font-bold text-[#0f172a] mb-8">Services</h3>
                    <ul class="space-y-4">
                        <li v-for="link in ['Lane Hire', 'Bowling Machine', 'Private Coaching', 'Group Coaching', 'Junior Academy']" :key="link">
                            <a href="#" class="text-slate-500 hover:text-[#FF0F20] text-sm font-medium transition-colors">{{ link }}</a>
                        </li>
                    </ul>
                </div>

                <!-- Academy Column -->
                <div>
                    <h3 class="text-xl font-bold text-[#0f172a] mb-8 border-l-4 border-sky-400 pl-4">Academy</h3>
                    <ul class="space-y-4">
                        <li v-for="link in ['About Us', 'Our Coaches', 'Facility', 'Testimonials', 'Blog']" :key="link">
                            <a href="#" class="text-slate-500 hover:text-[#FF0F20] text-sm font-medium transition-colors">{{ link }}</a>
                        </li>
                    </ul>
                </div>

                <!-- Visit Us Column -->
                <div>
                    <h3 class="text-xl font-bold text-[#0f172a] mb-8">Visit Us</h3>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4 group">
                            <span class="text-[#FF0F20] bg-[#FF0F20]/10 p-2 rounded-lg group-hover:bg-[#FF0F20] group-hover:text-white transition-colors shrink-0 mt-0.5">📍</span>
                            <span class="text-slate-500 text-sm font-medium leading-relaxed">Unit 8, 9 Arbied Street,<br/>Strijdom Park, Randburg, 2194</span>
                        </div>
                        <div class="flex items-center gap-4 group">
                            <span class="text-[#FF0F20] bg-[#FF0F20]/10 p-2 rounded-lg group-hover:bg-[#FF0F20] group-hover:text-white transition-colors shrink-0">📞</span>
                            <a href="tel:0618844217" class="text-slate-500 hover:text-[#FF0F20] text-sm font-medium transition-colors">061 884 4217</a>
                        </div>
                        <div class="flex items-start gap-4 group">
                            <span class="text-[#FF0F20] bg-[#FF0F20]/10 p-2 rounded-lg group-hover:bg-[#FF0F20] group-hover:text-white transition-colors shrink-0 mt-0.5">📧</span>
                            <div class="flex flex-col text-slate-500 text-sm font-medium gap-1">
                                <a href="mailto:info@playcricket.co.za" class="hover:text-[#FF0F20] transition-colors">info@playcricket.co.za</a>
                                <a href="mailto:lazarus@playcricket.co.za" class="hover:text-[#FF0F20] transition-colors">lazarus@playcricket.co.za</a>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 group">
                            <span class="text-[#FF0F20] bg-[#FF0F20]/10 p-2 rounded-lg group-hover:bg-[#FF0F20] group-hover:text-white transition-colors shrink-0">⏰</span>
                            <span class="text-slate-500 text-sm font-medium">Mon-Sun 6am-9pm</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright Bar -->
            <div class="pt-10 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center">
                <p class="text-slate-400 text-sm font-medium mb-4 md:mb-0">
                    &copy; 2026 Play Cricket. All Rights Reserved.
                </p>
                <div class="flex gap-8">
                    <a href="#" class="text-slate-400 hover:text-slate-900 text-sm font-medium transition-colors">Privacy Policy</a>
                    <a href="#" class="text-slate-400 hover:text-slate-900 text-sm font-medium transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Video Modal -->
    <div v-if="showVideoModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4" @click="closeVideo">
        <button class="absolute top-6 right-6 text-white text-4xl hover:text-red-500 z-[101]" @click="closeVideo">&times;</button>
        <div class="relative w-full max-w-5xl aspect-video rounded-2xl overflow-hidden shadow-2xl" @click.stop>
            <video ref="videoPlayer" class="w-full h-full object-cover" controls autoplay>
                <source :src="activeVideoUrl" type="video/mp4" />
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
  </div>
</template>

<style scoped>
.animate-pan {
    animation: pan 20s infinite alternate linear;
}
@keyframes pan {
    from { transform: scale(1); }
    to { transform: scale(1.1); }
}
.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
    opacity: 0;
    transform: translateY(20px);
}
@keyframes fadeInUp {
    to { opacity: 1; transform: translateY(0); }
}
.delay-100 { animation-delay: 0.1s; }
.delay-200 { animation-delay: 0.2s; }

/* Hide scrollbar for videos */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
