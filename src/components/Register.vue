<script setup>
const emit = defineEmits(['register', 'login', 'back', 'guest']);
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
        <p class="text-sm text-slate-500">Create your account</p>
      </div>

      <div class="space-y-4">
        <div>
          <label class="block text-xs font-medium text-slate-700 mb-1">Full Name</label>
          <input
            type="text"
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm"
            placeholder="John Doe"
          />
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-700 mb-1">Email</label>
          <input
            type="email"
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm"
            placeholder="john@example.com"
          />
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-700 mb-1">Password</label>
          <input
            type="password"
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm"
            placeholder="••••••••"
          />
        </div>
         <div>
          <label class="block text-xs font-medium text-slate-700 mb-1">Confirm Password</label>
          <input
            type="password"
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm"
            placeholder="••••••••"
          />
        </div>

        <button
          class="w-full bg-emerald-600 text-white font-bold py-2.5 rounded-lg hover:bg-emerald-700 transition-colors shadow-lg mt-2"
          @click="emit('register')"
        >
          Register
        </button>

        <div class="text-center mt-4">
            <p class="text-sm text-slate-600">
                Already have an account? 
                <button @click="emit('login')" class="text-emerald-600 font-bold hover:underline">Login</button>
            </p>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-500 mb-2 font-medium">Want to book immediately?</p>
            <button 
              type="button" 
              @click="emit('guest')" 
              class="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-lg transition-colors flex items-center justify-center gap-2 border border-slate-200"
            >
              <span>🏏</span> Continue as Guest (No Account Required)
            </button>
        </div>
      </div>
    </div>
  </div>
</template>
