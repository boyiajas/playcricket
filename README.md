# CricBook Pro - Cricket Facility Management Platform

CricBook Pro is a comprehensive web application for managing cricket facility bookings, member registrations, and coaching clinics. It features a modern Vue 3 frontend and a Laravel backend API skeleton.

## Features

### Frontend (Vue 3 + Vite)

- **Landing Page**: A visually rich, responsive landing page with:
    - Sticky Hero Slider with navigation dots.
    - Latest News & Video sections.
    - Partner logos and Testimonials.
    - Split-layout Motivation Banner.
    - Comprehensive Footer with quick links and contact info.
- **Authentication System**:
    - **Full-Page Login**: Secure login screen with role simulation (Admin, Manager, Staff, Member).
    - **Registration Page**: User-friendly sign-up form linked seamlessly with the login flow.
    - **Backgrounds**: Immersive cricket stadium backgrounds with optimal text readability.
- **Dashboard**: Role-based access to booking management, member lists, and reports.
- **Tech Stack**: Vue 3, Vite, Tailwind CSS, Axios.

### Backend (Laravel API Skeleton)

- **API Routes**: Pre-configured routes for authentication, bookings, and members.
- **Controllers**: Skeleton controllers mirroring the frontend mock data.
- **Database**: Migration files for bookings, users, and payments.

## Setup Instructions

### 1. Frontend Setup

```bash
# Install dependencies
npm install

# Start the development server
npm run dev
```

Visit `http://localhost:5173` to view the application.

### 2. Backend Setup (Laravel)

The Laravel backend lives at the repo root alongside the Vue frontend.

```bash
# Install PHP dependencies
composer install

# Configure .env (create one if missing) and generate an app key
php artisan key:generate

# Run migrations
php artisan migrate

# Start the Laravel server
php artisan serve
```

Ensure the Vue app's API base URL points to your running Laravel instance.

## Project Structure

- `src/components/`: Vue components (LandingPage, Login, Register, Dashboard, etc.).
- `src/services/`: API service wrappers and mock data.
- `app/`, `routes/`, `config/`, `database/`, `resources/`: Laravel backend files.

## Latest Updates

- **Enhanced UI**: Upgraded hero section slider, footer redesign, and motivation banner layout.
- **Improved UX**: Converted login/registration into dedicated full-page routes for a better user experience.
- **Reliable Assets**: Switched to high-quality Unsplash image sources for consistent visuals.
