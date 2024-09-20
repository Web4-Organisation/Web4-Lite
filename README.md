# Web4 Lite

**Welcome to Web4 Lite!** 🌐🚀

Web4 Lite is your open-source foundation to kickstart and build your own Web4 network. Designed with cutting-edge technology and a sleek, futuristic design, Web4 Lite empowers developers and innovators to create unique social communities under the Web4 Foundation umbrella. Whether you're an aspiring developer or an experienced creator, Web4 Lite gives you the tools to develop your own tailored social platform that thrives in the Web4 ecosystem. ✨

---

## Vision 🌟

At **Linkspreed**, our mission is to create an open and connected world that empowers individuals to build personalized, decentralized social networks. **Web4 Lite** is just the beginning—a foundational layer that provides the flexibility to create your vision of the future of social interaction. By utilizing Web4 Lite, you’re not just building a platform, you’re contributing to the next evolution of the web, pushing the boundaries of what’s possible with Web4 technology.

We envision a future where **anyone can launch their own Web4-powered social network**, fostering communities around specific interests, values, or regions. Web4 Lite gives you the freedom to explore new social paradigms while staying in control of your platform.

---

## Getting Started 🚀

To set up your own Web4 Lite instance, follow these steps:

### 1. Clone the repository

Clone the Web4 Lite repository from GitHub:

```bash
git clone https://github.com/Web4-Organisation/Web4-Lite.git
```

### 2. Update the configuration file

After cloning, navigate to the project directory and update the database configuration:

```bash
cd Web4-Lite
```

Modify the file located at `sys/config/db.inc.php` with your database credentials and domain information:

```php
// Your domain (host) and URL! See comments! Carefully!

$B['APP_HOST'] = "localhost";                 //edit to your domain, example (WARNING - without http://, https:// and www): yourdomain.com
$B['APP_URL'] = "http://localhost";           //edit to your domain URL, example (WARNING - with http:// or https://): https://yourdomain.com

// Please edit database data

$C['DB_HOST'] = "";                           //localhost or your db host
$C['DB_USER'] = "";                           //your db user
$C['DB_PASS'] = "";                           //your db password
$C['DB_NAME'] = "";                           //your db name
```

### 3. Run the installation

Once you've configured the database, proceed with the installation by navigating to the following URL in your browser:

```bash
http://your-domain.com/install
```

Follow the on-screen instructions to complete the installation process.

---

## License ⚖️

Web4 Lite is published under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License (BY-NC-SA 4.0).

You are free to:
- **Share** — copy and redistribute the material in any medium or format.
- **Adapt** — remix, transform, and build upon the material.

Under the following terms:
- **Attribution** — You must give appropriate credit, provide a link to the license, and indicate if changes were made.
- **NonCommercial** — You may not use the material for commercial purposes.
- **ShareAlike** — If you remix, transform, or build upon the material, you must distribute your contributions under the same license as the original.

For more information, see the full license: Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.

---

## Contact 📧

For any inquiries or questions, feel free to contact us:

- **Linkspreed UG**: hello@linkspreed.com
- **Marc Herdina**: marc.herdina@linkspreed.com

**Linkspreed Website**

© 2024 Linkspreed UG & Marc Herdina

Web4 Lite is part of a growing vision to reshape the digital landscape. We're excited to see what you build!
