<script setup lang="js">
import { ref } from 'vue';
import { Role } from '../types';

const emit = defineEmits(['login', 'back', 'register']);

const email = ref('');
const password = ref('');

const handleCustomLogin = () => {
  const e = email.value.trim().toLowerCase();
  if (e.includes('admin') || e.includes('carter')) {
    emit('login', Role.SUPER_ADMIN);
  } else if (e.includes('gary') || e.includes('manager')) {
    emit('login', Role.MANAGER);
  } else if (e.includes('sarah') || e.includes('staff')) {
    emit('login', Role.STAFF);
  } else {
    emit('login', Role.MEMBER);
  }
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
      <div class="absolute inset-0 bg-slate-900/80 z-10"></div>
      <img 
        src="https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
        alt="Background" 
        class="w-full h-full object-cover"
      />
    </div>

    <button 
      @click="emit('back')" 
      class="absolute top-4 left-4 md:top-8 md:left-8 text-white hover:text-emerald-400 flex items-center gap-2 transition-colors font-medium border border-white/20 hover:border-emerald-400 rounded-lg px-4 py-2 z-20"
    >
      <span>←</span> Back to Home
    </button>
    <div class="bg-white w-full max-w-md rounded-2xl p-6 md:p-8 shadow-2xl relative overflow-hidden z-20">
      <div class="absolute top-0 left-0 w-full h-2 bg-emerald-500" />
      <div class="text-center mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900">PlayCricket</h1>
        <p class="text-sm text-slate-500">Facility Management Login</p>
      </div>

      <form @submit.prevent="handleCustomLogin" class="space-y-4">
        <div>
          <label class="block text-xs font-medium text-slate-700 mb-1">Email</label>
          <input
            v-model="email"
            type="email"
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm"
            placeholder="coach@playcricket.com"
          />
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-700 mb-1">Password</label>
          <input
            v-model="password"
            type="password"
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm"
            placeholder="••••••••"
          />
        </div>

        <button
          type="submit"
          class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg shadow-md transition-all active:scale-[0.99] text-sm"
        >
          Sign In
        </button>

        <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 text-center">
          <p class="text-[10px] text-slate-500 mb-2 uppercase font-bold tracking-wider">Quick Demo Login as Role</p>
          <div class="grid grid-cols-2 gap-2">
            <button
              type="button"
              class="px-3 py-1.5 bg-purple-600 text-white text-[10px] rounded hover:bg-purple-700 font-bold uppercase shadow-sm"
              @click="emit('login', Role.SUPER_ADMIN)"
            >
              Admin
            </button>
            <button
              type="button"
              class="px-3 py-1.5 bg-blue-600 text-white text-[10px] rounded hover:bg-blue-700 font-bold uppercase shadow-sm"
              @click="emit('login', Role.MANAGER)"
            >
              Manager
            </button>
            <button
              type="button"
              class="px-3 py-1.5 bg-emerald-600 text-white text-[10px] rounded hover:bg-emerald-700 font-bold uppercase shadow-sm"
              @click="emit('login', Role.STAFF)"
            >
              Staff
            </button>
            <button
              type="button"
              class="px-3 py-1.5 bg-orange-500 text-white text-[10px] rounded hover:bg-orange-600 font-bold uppercase shadow-sm"
              @click="emit('login', Role.MEMBER)"
            >
              Member
            </button>
          </div>
        </div>
      </form>

      <div class="text-center mt-4">
            <p class="text-sm text-slate-600">
                Don't have an account? 
                <button @click="emit('register')" class="text-emerald-600 font-bold hover:underline">Register</button>
            </p>
        </div>
      </div>
    </div>

</template>
