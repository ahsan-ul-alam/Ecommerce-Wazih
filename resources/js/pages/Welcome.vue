<script setup>
import useCart from '@/composables/useCart';
import Header from '@/mycomponents/Header.vue';
import { Head, Link , router } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, onBeforeMount, ref } from 'vue';
import { useToast } from 'vue-toastification';

const toast = useToast();
const { cart, addToCart } = useCart();

const products = ref([]);
const loading = ref(true);

onBeforeMount(async () => {
    try {
        const response = await axios.get('https://dummyjson.com/products?limit=10');
        products.value = response.data.products;
    } catch (error) {
        toast.error('Failed to fetch products');
    } finally {
        loading.value = false;
    }
});

function handleAddToCart(product) {
    const added = addToCart(product);
    if (added) {
        toast.success(`${product.title || product.name} added to cart!`);
    } else {
        toast.info(`${product.title || product.name} is already in the cart. Increase quantity from cart.`);
    }
}

function directCheckout(product) {
    const added = addToCart(product);
    if (added) {
        toast.success(`${product.title || product.name} added to cart!`);
    }
    router.visit('/checkout');
}

const totalPrice = computed(() => cart.value.reduce((sum, item) => sum + item.price * item.quantity, 0));
</script>

<template>
    <Head title="Home" />
    <Header />
    <div class="container mx-auto p-5">
        <h1 class="mb-6 text-3xl font-bold">New Arrival Products</h1>

        <div class="mb-10 grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-4">
            <!-- Skeleton loading placeholders -->
            <template v-if="loading">
                <div v-for="n in 8" :key="n" class="animate-pulse rounded-lg border bg-gray-700 p-4 shadow">
                    <div class="mb-4 h-60 w-full bg-gray-500"></div>
                    <div class="mb-2 h-6 w-3/4 bg-gray-500"></div>
                    <div class="mb-4 h-5 w-1/2 bg-gray-600"></div>
                    <div class="flex gap-2">
                        <div class="h-10 flex-1 rounded bg-gray-600"></div>
                        <div class="h-10 flex-1 rounded bg-gray-600"></div>
                    </div>
                </div>
            </template>

            <!-- Actual product cards -->
            <template v-else>
                <div v-for="product in products" :key="product.id" class="rounded-lg border bg-gray-600 p-4 shadow transition hover:shadow-lg">
                    <Link :href="route('product.details', { id: product.id, name: product.title || product.name })">
                        <img
                            draggable="false"
                            :src="product.thumbnail || product.image"
                            :alt="product.title || product.name"
                            class="mb-4 h-60 w-full object-contain"
                        />
                        <h3 class="text-lg font-semibold">{{ product.title || product.name }}</h3>
                    </Link>
                    <p class="mb-3 font-bold text-green-600">৳{{ product.price }}</p>
                    <div class="flex justify-between gap-2 text-center">
                        <button @click="directCheckout(product)" class="cursor-pointer mr-2 w-full rounded bg-blue-600 py-2 text-white transition hover:bg-blue-700">
                            Buy Now
                        </button>
                        <button @click="handleAddToCart(product)" class="cursor-pointer w-full rounded bg-green-600 py-2 text-white transition hover:bg-green-700">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
