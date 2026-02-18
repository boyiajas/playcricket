<script setup lang="js">
import { Role } from '../types';

const emit = defineEmits(['login']);
</script>

<template>
  <div class="min-h-screen bg-slate-900 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-2xl p-6 md:p-8 shadow-2xl relative overflow-hidden">
      <div class="absolute top-0 left-0 w-full h-2 bg-emerald-500" />
      <div class="text-center mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900">CricBook Pro</h1>
        <p class="text-sm text-slate-500">Facility Management Login</p>
      </div>

      <div class="space-y-4">
        <div>
          <label class="block text-xs font-medium text-slate-700 mb-1">Email</label>
          <input
            type="email"
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm"
            placeholder="coach@cricbook.com"
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

        <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 text-center">
          <p class="text-[10px] text-slate-500 mb-2 uppercase font-bold tracking-wider">Simulate Role</p>
          <div class="grid grid-cols-2 gap-2">
            <button
              class="px-3 py-1.5 bg-purple-600 text-white text-[10px] rounded hover:bg-purple-700 font-bold uppercase"
              @click="emit('login', Role.SUPER_ADMIN)"
            >
              Admin
            </button>
            <button
              class="px-3 py-1.5 bg-blue-600 text-white text-[10px] rounded hover:bg-blue-700 font-bold uppercase"
              @click="emit('login', Role.MANAGER)"
            >
              Manager
            </button>
            <button
              class="px-3 py-1.5 bg-emerald-600 text-white text-[10px] rounded hover:bg-emerald-700 font-bold uppercase"
              @click="emit('login', Role.STAFF)"
            >
              Staff
            </button>
            <button
              class="px-3 py-1.5 bg-orange-500 text-white text-[10px] rounded hover:bg-orange-600 font-bold uppercase"
              @click="emit('login', Role.MEMBER)"
            >
              Member
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
