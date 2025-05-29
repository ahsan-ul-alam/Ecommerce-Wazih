<template>
    <Head title="Order Summary"/>
  <div class="min-h-screen bg-gray-100 py-10 px-4">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-6">
      <div class="text-center">
        <h1 class="text-3xl font-bold text-green-600 mb-2">✅ অর্ডার সফলভাবে সম্পন্ন হয়েছে!</h1>
        <p class="text-gray-600 mb-6">আপনার অর্ডারের বিস্তারিত নিচে দেওয়া হলো:</p>
      </div>

      <!-- Order Info -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-800 mb-6">
        <div class="bg-gray-50 rounded-lg p-4 border">
          <p><strong>নাম:</strong> {{ order.name }}</p>
          <p><strong>মোবাইল:</strong> {{ order.mobile }}</p>
          <p><strong>ঠিকানা:</strong> {{ order.address }}</p>
          <p><strong>জেলা:</strong> {{ order.district }}</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4 border">
          <p><strong>ডেলিভারি চার্জ:</strong> {{ order.delivery_charge }} টাকা</p>
          <p><strong>মোট:</strong> <span class="text-lg font-semibold text-green-700">{{ order.total_amount }} টাকা</span></p>
          <p><strong>Order Number:</strong> <span class="text-lg font-semibold text-green-700">{{ order.order_number }}</span></p>
        </div>
      </div>

      <!-- Order Items Table -->
      <div>
        <h2 class="text-xl font-semibold mb-2">🛒 অর্ডার করা প্রোডাক্টসমূহ:</h2>
        <div class="overflow-x-auto rounded-lg shadow-sm border">
          <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100">
              <tr>
                <th class="text-gray-800 px-4 py-2 border-b">প্রোডাক্ট</th>
                <th class="text-gray-800 px-4 py-2 border-b">দাম</th>
                <th class="text-gray-800 px-4 py-2 border-b">পরিমাণ</th>
                <th class="text-gray-800 px-4 py-2 border-b">মোট</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in JSON.parse(order.cart)" :key="index" class="hover:bg-gray-50">
                <td class="text-gray-800 px-4 py-2 border-b">{{ item.title }}</td>
                <td class="text-gray-800 px-4 py-2 border-b">{{ item.price }} টাকা</td>
                <td class="text-gray-800 px-4 py-2 border-b">{{ item.quantity }}</td>
                <td class="text-gray-800 px-4 py-2 border-b">{{ item.price * item.quantity }} টাকা</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Go Home Button -->
      <div class="text-center mt-8">
        <Link
          href="/"
          class=" inline-block bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition-all"
        >
          🏠 হোমপেজে ফিরে যান
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from "vue";
import { Link,Head } from "@inertiajs/vue3";
defineProps({
  order: Object,
});
</script>
