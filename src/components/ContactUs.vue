<script setup>
import { ref } from 'vue';

const emit = defineEmits(['login', 'back']);
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
    emit('back');
};

const contactForm = ref({
    name: '',
    email: '',
    subject: '',
    message: ''
});

const submitContact = () => {
    alert('Thank you! Your message has been sent successfully.');
    contactForm.value = { name: '', email: '', subject: '', message: '' };
};
</script>

<template>
  <div class="font-sans text-slate-800 bg-slate-50 min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white border-b border-slate-100 shadow-sm transition-all duration-300">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
          <div class="flex-shrink-0 flex items-center cursor-pointer" @click="handleLogoClick">
             <img src="/logo.png" alt="PlayCricket" class="h-16 w-auto" />
          </div>
          <div class="hidden md:flex space-x-12 items-center">
            <a href="#" @click.prevent="$emit('back')" class="text-slate-800 hover:text-red-600 font-semibold transition-colors">Home</a>
            <a href="#" @click.prevent="$emit('back')" class="text-slate-800 hover:text-red-600 font-semibold transition-colors">Lanes</a>
            <a href="#" @click.prevent="$emit('back')" class="text-slate-800 hover:text-red-600 font-semibold transition-colors">Coaching</a>
            <a href="#" @click.prevent="$emit('back')" class="text-slate-800 hover:text-red-600 font-semibold transition-colors">Academy</a>
            <a href="#" @click.prevent class="text-red-600 font-semibold transition-colors cursor-default">Contact</a>
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
                    <a href="#" @click.prevent="closeMobileMenu(); $emit('back');" class="block px-4 py-3 rounded-xl text-lg font-bold text-slate-800 hover:bg-slate-50 hover:text-red-600 transition-colors">Home</a>
                    <a href="#" @click.prevent="closeMobileMenu(); $emit('back');" class="block px-4 py-3 rounded-xl text-lg font-bold text-slate-800 hover:bg-slate-50 hover:text-red-600 transition-colors">Lanes</a>
                    <a href="#" @click.prevent="closeMobileMenu(); $emit('back');" class="block px-4 py-3 rounded-xl text-lg font-bold text-slate-800 hover:bg-slate-50 hover:text-red-600 transition-colors">Coaching</a>
                    <a href="#" @click.prevent="closeMobileMenu(); $emit('back');" class="block px-4 py-3 rounded-xl text-lg font-bold text-slate-800 hover:bg-slate-50 hover:text-red-600 transition-colors">Academy</a>
                    <a href="#" @click.prevent class="block px-4 py-3 rounded-xl text-lg font-bold text-red-600 bg-red-50 hover:bg-red-100 transition-colors mb-4">Contact</a>
                    
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

    <!-- Page Header with Breadcrumbs -->
    <header class="relative pt-32 pb-20 bg-[#0f172a] overflow-hidden">
        <!-- Subtle Pattern Overlay -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M0 40L40 0H20L0 20M40 40V20L20 40" stroke="white" stroke-width="1" fill="none"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern)" />
            </svg>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mt-8">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6 tracking-tight">Contact Us</h1>
            <!-- Breadcrumbs -->
            <nav class="flex justify-center" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 md:space-x-3 bg-white/10 px-6 py-2 rounded-full backdrop-blur-sm border border-white/10">
                    <li class="inline-flex items-center">
                        <a href="#" @click.prevent="$emit('back')" class="inline-flex items-center text-sm font-medium text-slate-300 hover:text-white transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 md:ml-2 text-sm font-bold text-[#FF0F20]">Contact</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </header>

    <!-- Main Content Section -->
    <main class="flex-grow py-20 bg-slate-50 relative -mt-8 rounded-t-[3rem] z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                
                <!-- Left: Contact Information -->
                <div>
                    <h2 class="text-4xl font-bold text-slate-900 mb-4">Get in Touch</h2>
                    <p class="text-slate-500 text-lg leading-relaxed mb-10 max-w-lg">
                        Have a question about our coaching programs, lane availability, or looking to host a team event? Our team is always ready to help.
                    </p>

                    <div class="space-y-6">
                        <!-- Address Card -->
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 flex items-start gap-5 shadow-sm hover:shadow-md transition-shadow group">
                            <div class="bg-[#FF0F20]/10 p-4 rounded-2xl group-hover:bg-[#FF0F20] group-hover:text-white transition-colors shrink-0">
                                <span class="text-2xl">📍</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Our Location</h3>
                                <p class="text-slate-500 font-medium leading-relaxed mt-1">
                                    Unit 8, 9 Arbied Street,<br/>
                                    Strijdom Park, Randburg, 2194
                                </p>
                            </div>
                        </div>

                        <!-- Phone Card -->
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 flex items-start gap-5 shadow-sm hover:shadow-md transition-shadow group">
                            <div class="bg-[#FF0F20]/10 p-4 rounded-2xl group-hover:bg-[#FF0F20] group-hover:text-white transition-colors shrink-0">
                                <span class="text-2xl">📞</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Give Us A Call</h3>
                                <a href="tel:0618844217" class="text-slate-500 font-medium hover:text-[#FF0F20] transition-colors inline-block mt-1">
                                    061 884 4217
                                </a>
                            </div>
                        </div>

                        <!-- Email Card -->
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 flex items-start gap-5 shadow-sm hover:shadow-md transition-shadow group">
                            <div class="bg-[#FF0F20]/10 p-4 rounded-2xl group-hover:bg-[#FF0F20] group-hover:text-white transition-colors shrink-0">
                                <span class="text-2xl">📧</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Email Us Directly</h3>
                                <div class="flex flex-col gap-1 mt-1">
                                    <a href="mailto:info@playcricket.co.za" class="text-slate-500 font-medium hover:text-[#FF0F20] transition-colors">info@playcricket.co.za</a>
                                    <a href="mailto:lazarus@playcricket.co.za" class="text-slate-500 font-medium hover:text-[#FF0F20] transition-colors">lazarus@playcricket.co.za</a>
                                </div>
                            </div>
                        </div>

                        <!-- Hours Card -->
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 flex items-start gap-5 shadow-sm hover:shadow-md transition-shadow group">
                            <div class="bg-[#FF0F20]/10 p-4 rounded-2xl group-hover:bg-[#FF0F20] group-hover:text-white transition-colors shrink-0">
                                <span class="text-2xl">⏰</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Working Hours</h3>
                                <p class="text-slate-500 font-medium mt-1">
                                    Monday - Sunday<br/>
                                    6:00 AM - 9:00 PM
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Contact Form -->
                <div>
                    <div class="bg-white w-full rounded-[3rem] p-8 md:p-12 shadow-2xl relative">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-14 h-14 bg-[#FF0F20] rounded-2xl flex items-center justify-center shadow-lg shadow-[#FF0F20]/20">
                                <span class="text-2xl text-white">✉️</span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-900">Send a Message</h3>
                                <p class="text-slate-400 text-sm mt-1">We'll get back to you within 24 hours</p>
                            </div>
                        </div>

                        <form @submit.prevent="submitContact" class="space-y-5">
                            <!-- Name -->
                            <div class="group">
                                <label for="name" class="block text-slate-500 font-bold text-sm mb-2 ml-2">Full Name</label>
                                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-1 flex relative transition-all focus-within:border-[#FF0F20]/30 focus-within:bg-white focus-within:ring-4 focus-within:ring-[#FF0F20]/5">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 z-10">👤</span>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        v-model="contactForm.name" 
                                        required
                                        placeholder="John Doe"
                                        class="w-full bg-transparent border-none outline-none focus:ring-0 text-slate-800 font-medium py-3 pl-10 pr-4 rounded-xl placeholder:text-slate-300"
                                    >
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="group">
                                <label for="email" class="block text-slate-500 font-bold text-sm mb-2 ml-2">Email Address</label>
                                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-1 flex relative transition-all focus-within:border-[#FF0F20]/30 focus-within:bg-white focus-within:ring-4 focus-within:ring-[#FF0F20]/5">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 z-10">✉️</span>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        v-model="contactForm.email" 
                                        required
                                        placeholder="john@example.com"
                                        class="w-full bg-transparent border-none outline-none focus:ring-0 text-slate-800 font-medium py-3 pl-10 pr-4 rounded-xl placeholder:text-slate-300"
                                    >
                                </div>
                            </div>

                            <!-- Subject -->
                            <div class="group">
                                <label for="subject" class="block text-slate-500 font-bold text-sm mb-2 ml-2">Subject</label>
                                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-1 flex relative transition-all focus-within:border-[#FF0F20]/30 focus-within:bg-white focus-within:ring-4 focus-within:ring-[#FF0F20]/5">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 z-10">🏷️</span>
                                    <input 
                                        type="text" 
                                        id="subject" 
                                        v-model="contactForm.subject" 
                                        required
                                        placeholder="How can we help?"
                                        class="w-full bg-transparent border-none outline-none focus:ring-0 text-slate-800 font-medium py-3 pl-10 pr-4 rounded-xl placeholder:text-slate-300"
                                    >
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="group flex flex-col">
                                <label for="message" class="block text-slate-500 font-bold text-sm mb-2 ml-2">Your Message</label>
                                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-1 flex relative transition-all focus-within:border-[#FF0F20]/30 focus-within:bg-white focus-within:ring-4 focus-within:ring-[#FF0F20]/5 flex-grow">
                                    <span class="absolute left-4 top-5 text-slate-400 z-10">📝</span>
                                    <textarea 
                                        id="message" 
                                        v-model="contactForm.message" 
                                        rows="5"
                                        required
                                        placeholder="Write your message here..."
                                        class="w-full bg-transparent border-none outline-none focus:ring-0 text-slate-800 font-medium py-4 pl-10 pr-4 rounded-xl placeholder:text-slate-300 resize-none"
                                    ></textarea>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit"
                                class="w-full mt-4 bg-[#FF0F20] hover:bg-[#E60D1D] text-white font-bold py-5 rounded-2xl transition-all shadow-xl hover:shadow-[#FF0F20]/20 transform active:scale-[0.98] flex items-center justify-center gap-3"
                            >
                                <span>🚀</span>
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white pt-24 pb-12 border-t border-slate-100 mt-auto relative z-10">
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
  </div>
</template>
