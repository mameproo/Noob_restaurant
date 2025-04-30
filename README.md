# Noob Restaurant Website

A modern, responsive restaurant website with admin panel for managing menu items, testimonials, and feedback.

## Features

- Responsive design for all devices
- Dynamic menu with categories
- Customer testimonials
- Contact form with feedback system
- Admin panel for content management
- Multi-language support (English and Amharic)

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- mod_rewrite enabled (for Apache)

## Installation

1. Upload all files to your web server
2. Create a MySQL database
3. Import the `database.sql` file to create the necessary tables
4. Update the database connection details in `config.php`:
   ```php
   define('DB_HOST', 'your_db_host');
   define('DB_USER', 'your_db_username');
   define('DB_PASS', 'your_db_password');
   define('DB_NAME', 'your_db_name');
   ```
5. Set proper permissions for upload directories:
   ```
   chmod 755 assets/img/menu
   chmod 755 assets/img/testimonials
   ```

## Admin Panel

Access the admin panel at: `your-domain.com/admin`

Default admin credentials:
- Username: admin
- Password: admin123

**Important:** Change the default password after first login.

## Directory Structure

```
/
├── admin/                  # Admin panel files
│   ├── includes/           # Admin includes (header, footer, etc.)
│   ├── assets/             # Admin-specific assets
│   └── *.php               # Admin pages
├── assets/                 # Frontend assets
│   ├── css/                # Stylesheets
│   ├── js/                 # JavaScript files
│   ├── fonts/              # Font files
│   └── img/                # Images
│       ├── menu/           # Menu item images
│       └── testimonials/   # Testimonial images
├── config.php              # Configuration file
├── database.sql            # Database structure and sample data
├── index.php               # Homepage
├── menu.php                # Menu page
├── contact.php             # Contact page
└── submit_feedback.php     # Feedback submission handler
```

## Security Considerations

1. Change default admin credentials
2. Keep PHP and all dependencies updated
3. Use HTTPS for secure data transmission
4. Regularly backup your database
5. Implement proper input validation and sanitization

## Support

For support, please contact [your-email@example.com]

## License

This project is licensed under the MIT License - see the LICENSE file for details. 