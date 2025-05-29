<script setup>
import useCart from '@/composables/useCart';
import districts from '@/districts.js';
import Header from '@/mycomponents/Header.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useToast } from 'vue-toastification';

const toast = useToast();
const { cart, increaseQuantity, decreaseQuantity, removeFromCart, clearCart } = useCart();

const name = ref('');
const mobile = ref('');
const email = ref('');
const address = ref('');
const selectedDistrict = ref('');
const isSubmitting = ref(false);

function getDeliveryCharge() {
    if (!selectedDistrict.value) return 0;

    if (selectedDistrict.value === 'Dhaka') {
        // এখানে তুমি চাইলে আরো বিস্তারিত লজিক যোগ করতে পারো "ঢাকার ভিতরে" বা "বাইরে" চেক করার জন্য
        return 70;
    } else {
        return 130;
    }
}

const cartTotal = computed(() => cart.value.reduce((sum, item) => sum + item.price * item.quantity, 0));

const total = computed(() => cartTotal.value + getDeliveryCharge());

const validateForm = () => {
    if (!name.value || !mobile.value || !email.value || !selectedDistrict.value) {
        toast.error('সব ফিল্ড পূরণ করুন');
        return false;
    }
    if (!/^\d{10,15}$/.test(mobile.value)) {
        toast.warning('সঠিক মোবাইল নম্বর দিন');
        return false;
    }
    if (!/\S+@\S+\.\S+/.test(email.value)) {
        toast.warning('সঠিক ইমেইল দিন');
        return false;
    }
    return true;
};

const placeOrder = () => {
    if (!validateForm()) return;

    if (cart.value.length === 0) {
        toast.warning('কার্ট খালি!');
        return;
    }

    isSubmitting.value = true;

    // ডাটা Laravel রাউটে পাঠাও
    router.post(
        '/checkout',
        {
            name: name.value,
            mobile: mobile.value,
            email: email.value,
            address: address.value,
            district: selectedDistrict.value,
            cart: cart.value,
            total_amount: total.value,
            delivery_charge: getDeliveryCharge(),
        },
        {
            onSuccess: () => {
                toast.success('অর্ডার সফলভাবে প্লেস হয়েছে!');
                clearCart();
                name.value = '';
                mobile.value = '';
                email.value = '';
                address.value = '';
                selectedDistrict.value = '';
                isSubmitting.value = false;
            },
            onError: (errors) => {
                if (errors && errors.message) {
                    toast.error(errors.message);
                } else {
                    toast.error('কিছু সমস্যা হয়েছে, আবার চেষ্টা করুন');
                }
                isSubmitting.value = false;
            },
        },
    );
};
</script>
<template>
    <Head title="Checkout" />
    <Header />

    <div class="mx-auto mt-10 max-w-5xl rounded-2xl bg-white p-6 shadow-lg">
        <h2 class="mb-8 text-3xl font-bold text-gray-800">Checkout</h2>

        <!-- দুই কলামে ভাগ -->
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <!-- বাম দিক: কার্ট আইটেমস -->
            <div>
                <h3 class="mb-4 text-2xl font-semibold">Your Cart</h3>

                <div v-if="cart.length === 0" class="text-center text-gray-500 italic">আপনার কার্ট খালি 🛒</div>

                <div v-else>
                    <div v-for="item in cart" :key="item.id" class="flex items-center gap-4 border-b py-4">
                        <img :src="item.thumbnail" class="h-16 w-16 rounded-lg object-cover" />
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">{{ item.title }}</h4>
                            <p class="text-sm text-gray-600">৳{{ item.price }} × {{ item.quantity }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                class="rounded-full bg-gray-200 px-3 py-1 font-bold text-gray-800 hover:bg-gray-300"
                                @click="decreaseQuantity(item.id)"
                                :disabled="item.quantity === 1"
                            >
                                −
                            </button>

                            <span class="font-semibold text-gray-800">{{ item.quantity }}</span>

                            <button
                                class="rounded-full bg-gray-200 px-3 py-1 font-bold text-gray-800 hover:bg-gray-300"
                                @click="increaseQuantity(item.id)"
                            >
                                +
                            </button>

                            <button class="ml-4 text-red-600 hover:underline" @click="removeFromCart(item.id)">Remove</button>
                        </div>
                    </div>

                    <div class="mt-6 border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-700">Delivery Charge</h3>
                        <p class="text-lg font-bold text-red-600">৳{{ getDeliveryCharge() }}</p>

                        <h3 class="mt-4 text-xl font-bold text-gray-700">Total</h3>
                        <p class="text-xl font-bold text-green-600">৳{{ total.toFixed(2) }}</p>
                    </div>
                </div>
            </div>

            <!-- ডান দিক: ইউজার ইনফো ফর্ম -->
            <div>
                <h3 class="mb-4 text-2xl font-semibold">Your Details</h3>

                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block font-semibold text-gray-700">Name <span class="text-red-500">*</span></label>
                        <input
                            v-model="name"
                            type="text"
                            placeholder="আপনার নাম"
                            class="w-full rounded-lg border px-4 py-2 text-gray-600 focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                        />
                    </div>

                    <div>
                        <label class="mb-1 block font-semibold text-gray-700">Mobile <span class="text-red-500">*</span></label>
                        <input
                            v-model="mobile"
                            type="tel"
                            placeholder="017xxxxxxxx"
                            class="w-full rounded-lg border px-4 py-2 text-gray-600 focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                        />
                    </div>

                    <div>
                        <label class="mb-1 block font-semibold text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input
                            v-model="email"
                            type="email"
                            placeholder="you@email.com"
                            class="w-full rounded-lg border px-4 py-2 text-gray-600 focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                        />
                    </div>

                    <div>
                        <label class="mb-1 block font-semibold text-gray-700">District (জেলা) <span class="text-red-500">*</span></label>
                        <select
                            v-model="selectedDistrict"
                            class="w-full rounded-lg border px-4 py-2 text-gray-600 focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                        >
                            <option value="" disabled>জেলা নির্বাচন করুন</option>
                            <option class="text-gray-700" v-for="district in districts" :key="district" :value="district">{{ district }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block font-semibold text-gray-700">Address <span class="text-red-500">*</span></label>
                        <textarea
                            v-model="address"
                            placeholder="আপনার ঠিকানা"
                            class="w-full rounded-lg border px-4 py-2 text-gray-600 focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                        ></textarea>
                    </div>

                    <button
                        class="mt-4 w-full rounded-lg bg-indigo-600 py-3 text-lg font-semibold text-white transition hover:bg-indigo-700"
                        @click="placeOrder"
                        :disabled="isSubmitting"
                    >
                        {{ isSubmitting ? 'Placing Order...' : 'Place Order Now' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
