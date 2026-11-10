# BP Fitness v2.0

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![AI Engine](https://img.shields.io/badge/AI-Laravel_AI_(GPT--4.1)-412991?style=flat-square&logo=openai&logoColor=white)](https://openai.com)

---
<div style="margin-top: 16px; display: flex; justify-content: center; align-items: center; gap: 8px">
    <b style="font-size: 18px; text-align: center;"> Download The App :</b> 
    <span> 
        <a href="https://apps.apple.com/us/app/bpfitnes/id6757735124" style="font-size: 16px;">App Store (iOS)</a>
    </span> 
</div>

---

### ◼ About the Project

It's a mobile-first health and wellness platform integrating AI-driven dietary planning, subscription billing, workout course management, progress tracking, an e-commerce store, community groups, and back-office administration.

---

### ◼ My Role

Responsible for building the complete backend & designing the admin dashboard from scratch.

---

### ◼ Tech Stack

| Category | Technology |
| :--- | :--- |
| **Backend** | Laravel 13, PHP 8.5 |
| **Admin Dashboard** | Blade, Tailwind CSS, Alpine.js, Vite |
| **Frontend (Association)** | Flutter Mobile App |
| **Architecture** | Modular Monolith |
| **Design Pattern** | Action-Domain Pattern (Domain-Oriented MVC) |
| **Database** | MySQL, Eloquent ORM |
| **AI** | `laravel/ai` (OpenAI GPT-4.1) |
| **Auth & Security** | `Sanctum`, `Breeze`, `Socialite` |
| **RBAC** | `Spatie Permission` |
| **Billing** | `Laravel Cashier` (Stripe), RevenueCat (Mobile IAP) |
| **Notifications** | Firebase Cloud Messaging (`FCM`), Laravel Mail, In-App Notifications (Database) |
| **File Handling** | `Laravel FilePond` (Chunked uploads), `mPDF` |
| **Queues** | Laravel Queues, Scheduler |


<div style="margin:48px auto;  background-color: #fff8e1; border: 1px solid #ffe082; border-left: 5px solid #ffb300; padding: 14px 18px; border-radius: 8px; color: #5d4037;">
  <strong>⚠️ Note on Confidentiality</strong><br>
  Due to proprietary restrictions, sensitive credentials have been replaced with fake/personal test data, and files containing proprietary business architecture was removed. Modules, sub-modules, and the underlying structure have been retained for inspection & review.
</div>


### ◼ CORE MODULES

`🔴 E-Commerce` `🔵 AI` `🟡 LMS` `🟢 Community Forum` `🟣 Subscription`

---

### ◼ FEATURES

- **AI Agents:** Generating diet plans based on user inputs, workout progress, and achievement scores.
- **Queue Workers:** Handling heavy AI generations and grocery lists in background chunk jobs.
- **Grocery Lists:** Extracting customizable shopping lists directly from saved meal plans.
- **Billing & Payments:** Stripe checkouts via Cashier, in-app mobile subscriptions via RevenueCat, and duplicate-safe transaction logs.
- **Workout LMS:** Multi-modular workout course management system behind free and premium paywalls.
- **Fitness Tracking:** Daily weight, calorie, and workout metric logs paired with login streak counters.
- **Achievements:** User milestone boards with point balances, unlockable badges, and streak tracking.
- **E-Store:** Product categories with variant options, active cart storage, and order checkout.
- **Community Rooms:** Messenger-style free & premium forum groups.
- **Disputes & Reports:** In-dashboard reports review with private feedback and user penalty tools.
- **Auth & Roles:** Role-based access (Super Admin, Admin, Enrolled, Guest) with dual web/API auth, Google login, and FCM alerts.

---

### ◼ History

The application went through several major version changes. It was originally developed on Laravel 12 in `version 1.0`. Later subsequently upgraded to Laravel 13, introducing autonomous AI agent workflows, tiered paywalls, and modular course engines for `v2.0`.

---

### ◼ Impact of this project

- Helping an established USA-based business in the fitness & training sector.
- Already gained 1000+ users & 300+ subscribers before official launch of `v2.0`.
- Launched `20+ products` in the E-Store.
- Generated significant revenue in combined subscription revenue and merchandise sales within the first 90 days of `v2.0` release.

---

### ◼ Architecture Highlights

```text
app/
├── Actions/                  # Reusable action files (create, update, delete, patch/toggle)
├── Enums/                    # Reusable enum files (e.g., NotifyChannelEnums)
├── Domains/                  # Core business domain folder 
│   ├── Achievements/         # Core domain-1
│   └── Billings/             # Core domain-2
│   └── Meals/                # Core domain-3
|   .    ├── Actions/         # Domain-based action files
|   .    ├── Controllers/     # ApiMealController, AdminMealController
|   .    ├── DTOs/            # Immutable transferrable data formats, e.g., GroceryListDto.php
|   .    ├── Enums/           # Fixed type definitions, e.g., MealStatusEnums.php
|   .    ├── Events/          # Domain-level events, e.g., MealPlanGeneratedEvent.php
|   .    ├── Listeners/       # Domain-level listeners, e.g., MealPlanGeneratedListener.php
|   .    ├── Models/          # Domain-based models, e.g., MealPlan.php, GroceryList.php
|   .    ├── Requests/        # Domain-based Form validations, e.g., MealPlanRequest.php
|   .    ├── Services/        # Business logic orchestrators, e.g., MealGenerationService.php
|   .    ├── Traits/          # Shared behaviors & scopes, e.g., HasGroceryLists.php
│   .    └── ValueObjects/    # Immutable typed values with self-validation, e.g., GeneratedMeal.php
|  ... ... ...
├── Http/Controllers/         # For framework-centric modules (e.g., User)
├── Jobs/                     # Chunked background workers
└── Models/                   # For framework-centric modules (e.g., User)

routes/
├── _registry/
├── Auth/
│   ├── api.auth.php/ 
│   └── web.auth.php/ 
└── Users/
.   ├── api.users.php/ 
.   └── web.users.php/ 
... ... ...  

```

---

### ◼ Setup

```bash
# 1. Install dependencies
composer install
npm install
npm run build

# 2. Environment & key
cp .env.example .env
php artisan key:generate

# 3. Database & storage
php artisan migrate --seed
php artisan storage:link

# 4. Run
composer run dev
php artisan queue:work
```

---

### ◼ License

Proprietary. All rights reserved.

