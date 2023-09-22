# Virtual University

## Description
Virtual University is a comprehensive educational management system built on the CodeIgniter framework. It empowers educational institutions to streamline administrative tasks, enhance communication, and create an engaging online environment for both students and teachers. Join us in revolutionizing education management!

## Prerequisites
Before getting started, ensure you have the following prerequisites:
- **LAMP Stack**: You'll need a Linux, Apache, MySQL, and PHP environment.
- **Proficiency in CodeIgniter 3.1, PHP, jQuery, and JavaScript**: Familiarity with these technologies is essential for customizing and extending the project.
- **Requirements for technologies are the necessaries for CodeIgniter 3.1** [here](https://codeigniter.com/userguide3/general/requirements.html).

## Installation
Follow these step-by-step instructions to set up Virtual University:

1. **Environment Setup**:
   - Prepare a LAMP environment, using Ubuntu 20.04 LTS or a similar Linux distribution.
   - Clone this repository to your local machine.

   - **PHP version 5.6 or newer**: Ensure you have PHP installed on your system with a version of 5.6 or higher. You can check your PHP version by running `php -v` in your terminal.

   - **MySQL (5.1+)**: Virtual University relies on MySQL as its database management system. Make sure you have MySQL installed with a version of 5.1 or newer. You can verify your MySQL installation by running `mysql --version`.

   - **Apache Web Server**: Ensure you have the Apache web server installed. You can typically install Apache on Ubuntu using `sudo apt install apache2`.

   - **Git**: You'll need Git to clone this repository. If you don't have it installed, you can do so with `sudo apt install git`.

2. **Project Configuration**:
   - Move the "Learning" folder to your Apache's HTML directory (typically `/var/www/html` on Ubuntu).

3. **Apache Configuration**:
   - Configure Apache to allow URL rewriting by editing the `/etc/apache2/apache2.conf` file.
   ```html Change
      <Directory /var/www/>
         Options Indexes FollowSymLinks
         AllowOverride None
         Require all granted
      </Directory>
   ```
   ```html To
      <Directory /var/www/>
         Options Indexes FollowSymLinks
         AllowOverride All
         Require all granted
      </Directory>
   ```

4. **Enable URL Rewriting**:
   - Run the following command to enable URL rewriting: `sudo a2enmod rewrite`.
   - Restart the Apache server: `sudo systemctl restart apache2`.

5. **Database Setup**:
   - In the `scripts` folder, you'll find a `.sql` file. Use this file to create the required database.

6. **Database Configuration**:
   - Open the `/var/www/html/virtual-university/Learning/application/config/database.php` file and provide the necessary database credentials.

## Usage
To access Virtual University:

1. Open your web browser and navigate to your localhost using the name "Learning" (or the name you assigned to the project folder).
2. Use the default user account provided in the `.sql` file to log in to the web system.

## Contribution
We welcome contributions! Feel free to fork this project, make improvements, and submit pull requests. Let's collaborate to enhance Virtual University.

## License
This project is licensed under the MIT License.

## Meet the Author 🚀

- **Alberto Vargas**
  - GitHub: [@Alberto-Vgs](https://github.com/Alberto-Vgs)
  - TikTok: [@alberto_vgs](https://www.tiktok.com/@alberto_vgs)
  - Instagram: [@alberto_vgs](https://www.instagram.com/alberto_vgs_/)
  - LinkedIn: [Alberto Vargas](https://www.linkedin.com/in/alberto-vgs/)
  
Feel free to connect with Alberto on GitHub, TikTok, Instagram, and LinkedIn for exciting updates and insights!



## Additional Notes
This project was initially developed during my university studies to provide valuable open-source resources, especially during the pandemic. While it may not adhere to all best practices and may contain some errors, your contributions can help us improve and expand its capabilities.
