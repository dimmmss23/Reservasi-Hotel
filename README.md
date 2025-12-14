# Hotel Reservation System 🏨

Hello everyone! 👋

Let me introduce myself, I'm **Dimmmss**. On this occasion, I'd like to share the Hotel Reservation System project that I've developed.

## 🛠️ Tech Stack

This project is built using modern web technologies:

- **Laravel** - PHP web application framework
- **MySQL/MariaDB** - Database management system
- **Bootstrap/Tailwind CSS** - Frontend styling
- **PHP** - Server-side programming language
- **Blade** - Laravel's templating engine

## 📋 Prerequisites

Before running this project, ensure you have the following installed:

- **PHP** (version 8.1 or higher)
- **Composer** - PHP dependency manager
- **MySQL/MariaDB** - Database server
- **Node.js & npm** - For frontend asset compilation (optional)
- **Laragon/XAMPP/WAMP** - Local development environment

## 🏃‍♂️ Getting Started

Follow these steps to run the project locally:

### 1. Clone the Repository

```bash
git clone https://github.com/dimmmss23/reservasi-hotel.git
cd reservasi-hotel
```

### 2. Install Dependencies

```bash
composer install
```

If you have frontend assets to compile:

```bash
npm install
```

### 3. Environment Configuration

```bash
# Copy the environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

1. Create a new database in MySQL/MariaDB:

```sql
CREATE DATABASE reservasi_hotel;
```

2. Update your `.env` file with database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reservasi_hotel
DB_USERNAME=root
DB_PASSWORD=
```

3. Run migrations:

```bash
php artisan migrate
```

4. (Optional) Seed the database with sample data:

```bash
php artisan db:seed
```

### 5. Run the Development Server

```bash
php artisan serve
```

### 6. Open in Browser

Access the application at `http://localhost:8000`

## 🏗️ Building for Production

To deploy this project to production:

1. Set your environment to production in `.env`:

```env
APP_ENV=production
APP_DEBUG=false
```

2. Optimize the application:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. Build frontend assets (if applicable):

```bash
npm run build
```

## 🔧 Environment Variables Setup

Create a `.env` file in the root of your project with the following configuration:

```env
APP_NAME="Hotel Reservation System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reservasi_hotel
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Important:**

- Run `php artisan key:generate` to generate a unique application key.
- **Never** commit your `.env` file to version control. Ensure it's listed in your `.gitignore` file.
- Restart your development server after modifying the `.env` file.

## 🚨 Troubleshooting

If you encounter issues while running the project:

### Common Issues:

1. **"Please provide a valid cache path" error**
   ```bash
   # Create the bootstrap/cache directory
   mkdir bootstrap\cache
   ```

2. **Permission issues (Linux/Mac)**
   ```bash
   chmod -R 775 storage
   chmod -R 775 bootstrap/cache
   ```

3. **Database connection error**
   - Verify your database credentials in `.env`
   - Ensure MySQL/MariaDB service is running
   - Check if the database exists

4. **Composer dependency conflicts**
   ```bash
   composer update --no-scripts
   composer install
   ```

## ✨ Features

- 🏨 Room browsing and search
- 📅 Real-time room availability checking
- 💳 Secure booking and payment system
- 👤 User authentication and profile management
- 📊 Admin dashboard for managing reservations
- 📧 Email notifications for bookings
- 📱 Responsive design for mobile devices

## 📝 Usage & Credits

We would appreciate it if you decide to use this project. Please include proper credit when using it. Thank you! 🙏

## 📞 Contact

If you have any questions or need help with the setup, feel free to reach out!

**Dimmmss**

- GitHub: [dimmmss23](https://github.com/dimmmss23)
- Email: dascretion7878@gmail.com

---

⭐ If this project helped you, please consider giving it a star on GitHub!
