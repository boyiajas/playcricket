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
    { id: 1, title: 'Summer Camp Registration Open', date: 'Feb 20, 2026', image: 'https://placehold.co/600x400/10b981/ffffff?text=Camp' },
    { id: 2, title: 'New Bowling Machine Installed', date: 'Feb 15, 2026', image: 'https://placehold.co/600x400/0f172a/ffffff?text=Machine' },
    { id: 3, title: 'U13 League Winners Announced', date: 'Feb 10, 2026', image: 'https://placehold.co/600x400/f59e0b/ffffff?text=Winners' },
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
    </section>

    <!-- Latest News -->
    <section id="news" class="py-20 bg-slate-50">
       <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

            <div class="mt-20 pt-8 border-t border-slate-200 text-center text-slate-500 text-sm">
                <p>&copy; 2026 PlayCricket. All rights reserved.</p>
            </div>
        </div>
    </section>
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
