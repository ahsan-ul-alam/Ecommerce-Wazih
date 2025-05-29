# 🛍️ Order Checkout System with Laravel + Vue + Inertia

![My E-Commerce Screenshot](https://i.ibb.co/v6dY0mhz/my-ecom.png)

এই প্রজেক্টটি একটি ই-কমার্স অর্ডার প্লেসমেন্ট সিস্টেম যেখানে ব্যবহারকারীরা প্রোডাক্ট নির্বাচন করে চেকআউট করতে পারে এবং সফল অর্ডারের পর অর্ডার সামারি দেখতে পারে।

---

## 🔧 Technologies Used

- ⚙️ **Backend:** Laravel 11
- 🎨 **Frontend:** Vue 3 (with Inertia.js)
- 🧠 **Database:** MySQL
- 🛠️ **Package Manager:** Composer & NPM
- 🔗 **Routing:** Inertia-powered SPA

---

## ✨ Features

- 🔐 ইউজার ফ্রেন্ডলি চেকআউট সিস্টেম
- 📦 একাধিক প্রোডাক্ট অর্ডার করার সুবিধা
- 📋 অর্ডার সামারি ও অর্ডার নাম্বার সহ বিস্তারিত তথ্য
- ✅ অর্ডার সফল হলে কনফার্মেশন মেসেজ
- 📱 মোবাইল রেসপনসিভ ডিজাইন
- 💬 সম্পূর্ণ বাংলা ভাষায় ইন্টারফেস

---

---

## 🚀 Getting Started

### 1️⃣ ক্লোন করো প্রজেক্টটি

```bash
git clone https://github.com/ahsan-ul-alam/Ecommerce-Wazih.git
cd Ecommerce-Wazih
```
### 2️⃣ Composer ও NPM dependency ইনস্টল করো

```bash
composer install
npm install
```


### 3️⃣ .env ফাইল তৈরি ও কনফিগার করো

```bash
cp .env.example .env
php artisan key:generate
```

### 4️⃣ মাইগ্রেশন চালাও এবং সার্ভার রান করো

```bash
php artisan migrate --seed
npm run dev
php artisan serve
```
------------------------------
##📂 Project Structure Overview
<pre>
resources/
├── js/
│   └── Pages/
│       ├── Checkout.vue
│       └── OrderSummary.vue
routes/
├── web.php
</pre>

