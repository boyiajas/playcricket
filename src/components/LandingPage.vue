<script setup>
import { ref } from 'vue';

const emit = defineEmits(['login']);

const slides = [
  {
    id: 1,
    image: 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
    title: 'Precision Cricket Training',
    subtitle: 'Book our state-of-the-art lanes and bowling machines.'
  },
  {
    id: 2,
    image: 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
    title: 'Professional Coaching',
    subtitle: 'Elevate your game with expert guidance.'
  },
  {
    id: 3,
    image: 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
    title: 'Junior Development',
    subtitle: 'Specialized programs for future stars.'
  }
];

const currentSlide = ref(0);

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % slides.length;
};

const prevSlide = () => {
    currentSlide.value = (currentSlide.value - 1 + slides.length) % slides.length;
};

// Auto-advance slider
setInterval(nextSlide, 5000);

const news = [
    { id: 1, title: 'Summer Camp Registration Open', date: 'Feb 20, 2026', image: 'https://picsum.photos/seed/news1/800/600' },
    { id: 2, title: 'New Bowling Machine Installed', date: 'Feb 15, 2026', image: 'https://picsum.photos/seed/news2/800/600' },
    { id: 3, title: 'U13 League Winners Announced', date: 'Feb 10, 2026', image: 'https://picsum.photos/seed/news3/800/600' },
];

const videos = [
    { id: 1, title: 'Batting Masterclass', thumbnail: 'https://placehold.co/600x338/darkblue/white?text=Video+1' },
    { id: 2, title: 'Spin Bowling Tips', thumbnail: 'https://placehold.co/600x338/darkred/white?text=Video+2' },
    { id: 3, title: 'Fielding Drills', thumbnail: 'https://placehold.co/600x338/darkgreen/white?text=Video+3' },
];

const partners = [
    { id: 1, name: 'Kookaburra', logo: 'https://placehold.co/150x80/eee/333?text=Logo+1' },
    { id: 2, name: 'Gray-Nicolls', logo: 'https://placehold.co/150x80/eee/333?text=Logo+2' },
    { id: 3, name: 'Adidas', logo: 'https://placehold.co/150x80/eee/333?text=Logo+3' },
    { id: 4, name: 'New Balance', logo: 'https://placehold.co/150x80/eee/333?text=Logo+4' },
];

const contactForm = ref({
    name: '',
    email: '',
    message: ''
});

const submitContact = () => {
    alert('Thank you! We will contact you shortly.');
    contactForm.value = { name: '', email: '', message: '' };
};

</script>

<template>
  <div class="font-sans text-slate-800 bg-white">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-slate-100 shadow-sm transition-all duration-300">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
          <div class="flex-shrink-0 flex items-center cursor-pointer" @click="$emit('login')">
             <img src="/logo.png" alt="PlayCricket" class="h-16 w-auto" />
          </div>
          <div class="hidden md:flex space-x-8 items-center">
            <a href="#home" class="text-slate-600 hover:text-emerald-600 font-medium transition-colors">Home</a>
            <a href="#news" class="text-slate-600 hover:text-emerald-600 font-medium transition-colors">News</a>
            <a href="#partners" class="text-slate-600 hover:text-emerald-600 font-medium transition-colors">Partners</a>
            <a href="#contact" class="text-slate-600 hover:text-emerald-600 font-medium transition-colors">Contact</a>
            <button
                @click="$emit('login')"
                class="bg-slate-900 text-white px-5 py-2.5 rounded-full font-semibold hover:bg-slate-800 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
            >
              Client Portal
            </button>
          </div>
           <!-- Mobile menu button placeholder -->
           <div class="md:hidden">
              <button @click="$emit('login')" class="text-slate-900 font-bold">Menu</button>
           </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section (Sticky Slider) -->
    <section id="home" class="relative h-screen overflow-hidden">
       <div 
         v-for="(slide, index) in slides" 
         :key="slide.id"
         class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
         :class="{ 'opacity-100': currentSlide === index, 'opacity-0': currentSlide !== index }"
       >
          <div class="absolute inset-0 bg-black/40 z-10"></div>
          <img :src="slide.image" class="w-full h-full object-cover animate-pan" alt="Hero Image" />
          <div class="absolute inset-0 z-20 flex flex-col items-center justify-center text-center text-white px-4">
               <h1 class="text-5xl md:text-7xl font-bold mb-6 drop-shadow-lg tracking-tight animate-fade-in-up">{{ slide.title }}</h1>
               <p class="text-xl md:text-2xl mb-10 max-w-2xl font-light text-slate-100 animate-fade-in-up delay-100">{{ slide.subtitle }}</p>
               <button 
                  @click="$emit('login')"
                  class="bg-emerald-600 hover:bg-emerald-500 text-white text-lg font-bold px-8 py-4 rounded-full transition-all shadow-xl hover:shadow-2xl transform hover:scale-105 animate-fade-in-up delay-200"
               >
                  Book Your Slot Now
               </button>
          </div>
       </div>
       
       <!-- Slider Controls -->
       <button @click="prevSlide" class="absolute left-4 top-1/2 -translate-y-1/2 z-30 p-2 bg-white/10 hover:bg-white/20 rounded-full backdrop-blur text-white transition-all">◀</button>
       <button @click="nextSlide" class="absolute right-4 top-1/2 -translate-y-1/2 z-30 p-2 bg-white/10 hover:bg-white/20 rounded-full backdrop-blur text-white transition-all">▶</button>

       <!-- Slider Dots -->
       <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-30 flex space-x-3">
           <button 
               v-for="(slide, index) in slides" 
               :key="'dot-' + slide.id"
               @click="currentSlide = index"
               class="w-3 h-3 rounded-full transition-all duration-300 shadow-sm"
               :class="currentSlide === index ? 'bg-emerald-500 scale-125' : 'bg-white/50 hover:bg-white/80'"
               :aria-label="'Go to slide ' + (index + 1)"
           ></button>
       </div>
    </section>

    <!-- Latest News -->
    <section id="news" class="relative py-20 bg-slate-50 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 z-0 opacity-20 pointer-events-none">
            <img 
                src="https://picsum.photos/seed/cricket/1920/1080?grayscale" 
                class="w-full h-full object-cover"
                alt="Cricket Background Pattern"
            />
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
           <div class="text-center mb-16">
               <h2 class="text-4xl font-bold text-slate-900 mb-4">Latest News From The Pitch</h2>
               <div class="w-24 h-1 bg-emerald-500 mx-auto rounded-full"></div>
           </div>
           <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
              <div v-for="item in news" :key="item.id" class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow group">
                  <div class="h-48 overflow-hidden">
                     <img :src="item.image" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" :alt="item.title">
                  </div>
                  <div class="p-6">
                     <span class="text-emerald-600 text-sm font-bold uppercase tracking-wider">{{ item.date }}</span>
                     <h3 class="text-xl font-bold mt-2 text-slate-800">{{ item.title }}</h3>
                     <p class="mt-3 text-slate-600 text-sm">Ideally this would be a snippet of the news article content to entice reading more...</p>
                     <a href="#" class="inline-block mt-4 text-emerald-600 font-bold hover:text-emerald-700">Read More →</a>
                  </div>
              </div>
           </div>
        </div>
    </section>

    <!-- Motivation Banner -->
    <section class="relative py-32 bg-emerald-900 overflow-hidden">
        <div class="absolute inset-0">
             <img src="https://picsum.photos/seed/womenscricket/1920/800" class="w-full h-full object-cover opacity-40 mix-blend-overlay" alt="Ladies Cricket Match">
             <div class="absolute inset-0 bg-gradient-to-r from-emerald-900/90 to-emerald-900/40"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center h-full">
                <!-- Left: Text -->
                <div class="flex flex-col items-start justify-center">
                    <h2 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                        Empowering the<br>
                        <span class="text-emerald-400">Next Generation</span><br>
                        of Champions.
                    </h2>
                    <p class="text-xl text-emerald-100 max-w-2xl mb-8 font-light">
                        "Cricket is not just a game; it's a journey of discipline, teamwork, and resilience. Whether you're picking up a bat for the first time or perfecting your cover drive, every practice session brings you closer to greatness."
                    </p>
                    <button class="bg-white text-emerald-900 px-8 py-3 rounded-full font-bold hover:bg-emerald-50 transition-colors shadow-lg">
                        Join Our Women's League
                    </button>
                </div>
                <!-- Right: Stadium Image -->
                <div class="hidden md:flex justify-center items-center">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl skew-y-3 transform hover:skew-y-0 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="World Class Cricket Stadium" class="w-full h-auto object-cover border-4 border-emerald-400/30">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 text-white font-bold text-lg drop-shadow-md">
                            Play Where Legends Are Made
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Videos -->
    <section class="py-20 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
           <div class="flex justify-between items-end mb-12">
               <div>
                  <h2 class="text-4xl font-bold mb-2">Training Hub</h2>
                  <p class="text-slate-400">Watch the latest drills and tips from our pros.</p>
               </div>
               <button class="text-emerald-400 hover:text-emerald-300 font-bold border-b-2 border-emerald-500 pb-1">View All Videos</button>
           </div>
           <div class="flex gap-6 overflow-x-auto pb-8 scrollbar-hide snap-x">
               <div v-for="video in videos" :key="video.id" class="min-w-[300px] md:min-w-[400px] snap-center">
                   <div class="relative rounded-xl overflow-hidden shadow-2xl aspect-video group cursor-pointer">
                       <img :src="video.thumbnail" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity" :alt="video.title">
                       <div class="absolute inset-0 flex items-center justify-center">
                           <div class="w-16 h-16 bg-emerald-600/90 rounded-full flex items-center justify-center pl-1 shadow-xl group-hover:scale-110 transition-transform">
                               ▶
                           </div>
                       </div>
                   </div>
                   <h3 class="mt-4 text-lg font-bold">{{ video.title }}</h3>
               </div>
           </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section id="partners" class="py-16 bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-400 font-bold uppercase tracking-widest text-sm mb-8">Trusted by Top Equipment Brands</p>
            <div class="flex flex-wrap justify-center items-center gap-12 opacity-60 hover:opacity-100 transition-opacity">
               <div v-for="partner in partners" :key="partner.id" class="flex items-center space-x-2 grayscale hover:grayscale-0 transition-all duration-300">
                   <!-- Replaced text with visual block placeholder for logo -->
                   <div class="h-12 w-32 bg-slate-200 rounded flex items-center justify-center text-slate-400 font-bold text-xs">{{ partner.name }}</div>
               </div>
            </div>
        </div>
    </section>

    <!-- Contact & Footer -->
    <section id="contact" class="bg-slate-50 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-emerald-900 rounded-3xl overflow-hidden shadow-2xl flex flex-col md:flex-row">
                <div class="md:w-1/2 p-12 text-white">
                    <h2 class="text-3xl font-bold mb-6">Get In Touch</h2>
                    <p class="text-emerald-100 mb-8">Have questions about booking lanes or our coaching programs? Drop us a message.</p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <span class="text-2xl">📍</span>
                            <div>
                                <h4 class="font-bold">Location</h4>
                                <p class="text-emerald-100 text-sm">123 Cricket Avenue, Sportsville<br>Cape Town, 8000</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <span class="text-2xl">📞</span>
                            <div>
                                <h4 class="font-bold">Phone</h4>
                                <p class="text-emerald-100 text-sm">+27 21 123 4567</p>
                            </div>
                        </div>
                         <div class="flex items-start gap-4">
                            <span class="text-2xl">📧</span>
                            <div>
                                <h4 class="font-bold">Email</h4>
                                <p class="text-emerald-100 text-sm">bookings@playcricket.co.za</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/2 bg-white p-12">
                     <form @submit.prevent="submitContact" class="space-y-4">
                         <div>
                             <label class="block text-sm font-bold text-slate-700 mb-1">Name</label>
                             <input v-model="contactForm.name" type="text" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-all" placeholder="Your Name">
                         </div>
                         <div>
                             <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                             <input v-model="contactForm.email" type="email" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-all" placeholder="john@example.com">
                         </div>
                         <div>
                             <label class="block text-sm font-bold text-slate-700 mb-1">Message</label>
                             <textarea v-model="contactForm.message" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-all" placeholder="How can we help?"></textarea>
                         </div>
                         <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-lg hover:bg-emerald-700 transition-colors shadow-lg">Send Message</button>
                     </form>
                </div>
            </div>


        </div>
    </section>

    <!-- Expanded Footer -->
    <footer class="bg-slate-900 text-white pt-16 pb-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <!-- Brand & Description -->
                <div>
                     <div class="flex items-center mb-6">
                        <img src="/logo.png" alt="PlayCricket" class="h-10 w-auto mr-3 brightness-0 invert" />
                        <span class="text-2xl font-bold text-white">PlayCricket</span>
                     </div>
                     <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Your premier destination for professional cricket training and lane rentals. Elevate your game today.
                     </p>
                     <!-- Social Icons -->
                     <div class="flex space-x-4">
                        <a href="#" class="text-slate-400 hover:text-emerald-400 transition-colors">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-emerald-400 transition-colors">
                            <span class="sr-only">Twitter</span>
                             <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-emerald-400 transition-colors">
                             <span class="sr-only">Instagram</span>
                             <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                     </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-white font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Home</a></li>
                        <li><a href="#news" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">News</a></li>
                        <li><a href="#partners" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Partners</a></li>
                        <li><a href="#contact" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Contact</a></li>
                        <li><button @click="$emit('login')" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors text-left">Client Portal</button></li>
                    </ul>
                </div>

                <!-- Services -->
                 <div>
                    <h3 class="text-white font-bold mb-4">Services</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Lane Hire</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Bowling Machines</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Coaching Clinics</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-emerald-400 text-sm transition-colors">Club Events</a></li>
                    </ul>
                </div>

                 <!-- Visit Us -->
                 <div>
                    <h3 class="text-white font-bold mb-4">Visit Us</h3>
                    <p class="text-slate-400 text-sm mb-4">
                        123 Cricket Avenue, Sportsville<br>
                        Cape Town, 8000
                    </p>
                    <h4 class="text-white font-bold text-sm mb-2">Opening Hours</h4>
                    <p class="text-slate-400 text-sm">
                        Mon - Fri: 09:00 - 20:00<br>
                        Sat - Sun: 08:00 - 18:00
                    </p>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center">
                 <p class="text-slate-500 text-sm text-center md:text-left mb-4 md:mb-0">
                    &copy; 2026 PlayCricket. All rights reserved.
                 </p>
                 <div class="flex flex-wrap gap-4 md:gap-6 mt-4 md:mt-0 justify-center md:justify-end">
                    <a href="#" class="text-slate-500 hover:text-white text-sm transition-colors">Terms</a>
                    <a href="#" class="text-slate-500 hover:text-white text-sm transition-colors">Privacy</a>
                    <a href="#" class="text-slate-500 hover:text-white text-sm transition-colors">Cookies</a>
                    <a href="#" class="text-slate-500 hover:text-white text-sm transition-colors">Sitemap</a>
                 </div>
            </div>
        </div>
    </footer>
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
