<script setup>
import useCart from '@/composables/useCart';
import Header from '@/mycomponents/Header.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useToast } from 'vue-toastification';

const toast = useToast();
const { cart, addToCart } = useCart();

const props = defineProps({
    product: Object,
});

function directCheckout(product) {
    const added = addToCart(product);
    if (added) {
        toast.success(`${product.title || product.name} added to cart!`);
    }
    router.visit('/checkout');
}

// Main image source reactive state
const mainImage = ref(props.product.images?.[0] || props.product.thumbnail || '');

function handleAddToCart(product) {
    const added = addToCart(product);
    if (added) {
        toast.success(`${product.title || product.name} added to cart!`);
    } else {
        toast.info(`${product.title || product.name} is already in the cart. Increase quantity from cart.`);
    }
}

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
}

function selectImage(img) {
    mainImage.value = img;
}
</script>

<template>
    <Head :title="product.title" />
    <Header />
    <div class="container mx-auto mt-8 rounded bg-white px-4 py-6 shadow-md">
        <!-- Title and Price -->
        <div class="flex flex-col gap-8 md:flex-row">
            <!-- Images -->
            <div class="flex flex-col items-center md:w-1/3">
                <img :src="mainImage" :alt="product.title" class="max-h-96 w-full rounded object-contain shadow-md" />
                <div class="no-scrollbar mt-4 flex w-full space-x-3 overflow-x-auto py-2">
                    <img
                        v-for="(img, idx) in product.images"
                        :key="img"
                        :src="img"
                        :alt="`${product.title} image ${idx + 1}`"
                        class="h-20 w-20 cursor-pointer rounded border-2 object-contain"
                        :class="mainImage.value === img ? 'border-indigo-600' : 'border-gray-300 hover:border-indigo-500'"
                        @click="selectImage(img)"
                    />
                </div>
            </div>

            <!-- Info -->
            <div class="space-y-5 md:w-2/3">
                <h1 class="text-3xl font-bold text-gray-900">{{ product.title }}</h1>
                <p class="text-2xl font-semibold text-green-600">৳{{ product.price.toFixed(2) }}</p>
                <p class="text-gray-600 italic">
                    Brand: <span class="font-medium">{{ product.brand }}</span>
                </p>
                <p class="leading-relaxed text-gray-700">{{ product.description }}</p>

                <!-- Specs -->
                <div class="rounded-lg bg-gray-100 p-5 shadow-inner">
                    <h3 class="mb-3 text-lg font-semibold">Product Details</h3>
                    <ul class="list-inside list-disc space-y-1 text-sm text-gray-700">
                        <li><strong>Category:</strong> {{ product.category || 'N/A' }}</li>
                        <li><strong>Stock:</strong> {{ product.stock ?? 'N/A' }}</li>
                        <li><strong>Discount:</strong> {{ product.discountPercentage ?? '0' }}%</li>
                        <li><strong>Rating:</strong> {{ product.rating ?? 'N/A' }} / 5</li>
                        <li><strong>Weight:</strong> {{ product.weight ?? 'N/A' }} g</li>
                        <li>
                            <strong>Dimensions (W×H×D):</strong>
                            {{ product.dimensions?.width ?? 'N/A' }}×{{ product.dimensions?.height ?? 'N/A' }}×
                            {{ product.dimensions?.depth ?? 'N/A' }} mm
                        </li>
                        <li><strong>SKU:</strong> {{ product.sku || 'N/A' }}</li>
                        <li><strong>Warranty:</strong> {{ product.warrantyInformation || 'N/A' }}</li>
                        <li><strong>Shipping:</strong> {{ product.shippingInformation || 'N/A' }}</li>
                        <li><strong>Availability:</strong> {{ product.availabilityStatus || 'N/A' }}</li>
                        <li><strong>Minimum Order Qty:</strong> {{ product.minimumOrderQuantity || 'N/A' }}</li>
                    </ul>
                </div>

                <button
                    @click="handleAddToCart(product)"
                    class="rounded-lg bg-green-600 px-8 py-3 font-semibold text-white shadow-md transition hover:bg-green-700"
                >
                    Add to Cart
                </button>

                <button
                    @click="directCheckout(product)"
                    class="mr-2 w-full cursor-pointer rounded bg-blue-600 py-2 text-white transition hover:bg-blue-700"
                >
                    Buy Now
                </button>
            </div>
        </div>

        <!-- Reviews -->
        <section class="mt-12">
            <h2 class="mb-6 border-b border-gray-200 pb-2 text-2xl font-bold">Customer Reviews</h2>
            <div v-if="!product.reviews || product.reviews.length === 0" class="text-gray-500 italic">No reviews yet.</div>
            <ul class="space-y-6" v-else>
                <li v-for="(review, idx) in product.reviews" :key="idx" class="rounded border bg-gray-50 p-5 shadow-sm">
                    <div class="mb-3 flex items-center justify-between">
                        <span class="font-semibold text-indigo-700">{{ review.reviewerName }}</span>
                        <span class="font-bold text-yellow-500">{{ review.rating }} / 5 ★</span>
                    </div>
                    <p class="mb-3 text-gray-700 italic">“{{ review.comment }}”</p>
                    <p class="text-xs text-gray-400">{{ formatDate(review.date) }}</p>
                </li>
            </ul>
        </section>

        <!-- Return Policy -->
        <section class="mt-12 rounded-lg bg-gray-100 p-6 shadow-inner">
            <h3 class="mb-3 text-lg font-semibold text-gray-900">Return Policy</h3>
            <p class="text-gray-700">{{ product.returnPolicy || 'No return policy available.' }}</p>
        </section>

        <!-- Meta (barcode & QR) -->
        <section class="mt-10 flex flex-wrap items-center justify-start gap-8">
            <div>
                <h4 class="mb-2 font-semibold">Barcode</h4>
                <p class="font-mono tracking-widest text-gray-700">{{ product.meta?.barcode || 'N/A' }}</p>
            </div>
            <div>
                <h4 class="mb-2 font-semibold">QR Code</h4>
                <img :src="product.meta?.qrCode || ''" alt="QR Code" class="h-24 w-24 rounded border border-gray-300" v-if="product.meta?.qrCode" />
                <span v-else class="text-gray-500 italic">No QR code available</span>
            </div>
        </section>
    </div>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}
</style>
