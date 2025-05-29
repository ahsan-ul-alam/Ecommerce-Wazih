<script setup>
import useCart from '@/composables/useCart';
import Header from '@/mycomponents/Header.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const { cart, removeFromCart, clearCart, increaseQuantity, decreaseQuantity } = useCart();

const totalPrice = computed(() => cart.value.reduce((sum, item) => sum + item.price * item.quantity, 0));
</script>

<template>
    <Head title="Your Cart" />
    <Header />

    <main class="mx-auto max-w-3xl px-4 py-8">
        <h1 class="mb-4 text-2xl font-bold">Your Cart</h1>

        <section v-if="cart.length" class="space-y-4">
            <article v-for="item in cart" :key="item.id" class="flex items-center justify-between rounded bg-white p-4 shadow">
                <div class="flex items-center gap-4">
                    <!-- Product Image -->
                    <img :src="item.images" alt="Product Image" class="h-16 w-16 rounded object-cover" />

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">{{ item.name }}</h2>
                        <p class="text-green-600">৳{{ item.price }}</p>

                        <div class="mt-2 flex items-center gap-2">
                            <button
                                @click="decreaseQuantity(item.id)"
                                aria-label="Decrease quantity"
                                class="rounded bg-gray-400 px-2 py-1 hover:bg-gray-300"
                            >
                                -
                            </button>
                            <span class="text-gray-600">{{ item.quantity }}</span>
                            <button
                                @click="increaseQuantity(item.id)"
                                aria-label="Increase quantity"
                                class="rounded bg-gray-400 px-2 py-1 hover:bg-gray-300"
                            >
                                +
                            </button>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <p class="font-bold text-gray-600">৳{{ item.price * item.quantity }}</p>
                    <button
                        @click="removeFromCart(item.id)"
                        class="mt-2 block text-sm text-red-600 hover:underline"
                        aria-label="Remove item from cart"
                    >
                        Remove
                    </button>
                </div>
            </article>

            <footer class="flex justify-between">
                <div class="mt-4">
                    <p class="text-xl font-bold">Total: ৳{{ totalPrice }}</p>
                    <button @click="clearCart" class="mt-2 rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">Clear Cart</button>
                </div>
                <div class="mt-4 text-right">
                    <p class="text-xl font-bold">Ready to checkout?</p>
                    <Link :href="route('checkout')" class="mt-2 rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">Checkout</Link>
                </div>
            </footer>
        </section>

        <p v-else class="text-gray-600">Your cart is empty.</p>
        <Link href="/" class="mt-4 block border border-gray-300 px-4 py-2 text-sm hover:bg-blue-100 hover:text-blue-800">Back to Home</Link>
    </main>
</template>
