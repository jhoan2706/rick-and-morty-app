# 🌀 Rick and Morty Explorer

A PHP 8+ application that consumes the **Rick and Morty API** and displays characters in a responsive interface built with **Tailwind CSS**, without using any PHP frameworks.

## 📸 Preview

> Add a screenshot of the application here after completing the project.

---

## 🚀 Getting Started

### Prerequisites

- PHP 8.0+
- Composer
- Node.js & npm

### Installation

```bash
git clone https://github.com/jhoan2706/rick-and-morty-app.git
cd rick-and-morty-app

composer install
npm install
npm run build-css
```

### Run the application

Using PHP's built-in server:

```bash
php -S localhost:8000
```

Or place the project inside your web server (e.g., XAMPP `htdocs`).

Open:

```text
http://localhost:8000
```

> 💡 **Note:** During development, run `npm run watch-css` to automatically rebuild Tailwind CSS whenever changes are detected.

---

## 🛠️ Tech Stack

- **PHP 8+** — Server-side rendering and API consumption
- **Tailwind CSS 3** — Utility-first CSS framework
- **Composer (PSR-4 Autoloading)** — Namespace-based autoloading
- **cURL** — HTTP requests to the Rick and Morty API
- **Vanilla JavaScript** — Client-side interactions

---

## ✨ Features

- 🃏 Browse Rick and Morty characters
- 🔍 Search by character name
- 🎯 Filter by status, species, and gender
- 📱 Fully responsive layout using CSS Grid and Flexbox
- 📄 Server-side pagination with preserved filters
- ❌ Graceful error handling and empty states
- 🧩 Clean and reusable component-based architecture

---

## 📁 Project Structure

```text
rick-and-morty-app/
├── index.php
├── config/
│   └── config.php
├── src/
│   ├── Components/
│   │   ├── CharacterCard.php
│   │   ├── Header.php
│   │   ├── Pagination.php
│   │   ├── SearchFilters.php
│   │   └── LoadingSpinner.php
│   ├── Services/
│   │   └── RickAndMortyAPI.php
│   └── Utils/
│       └── Helpers.php
├── assets/
│   ├── css/
│   │   └── tailwind.css
│   └── js/
│       └── app.js
├── tailwind.config.js
├── postcss.config.js
├── composer.json
├── package.json
└── README.md
```

---

## 📡 API Reference

This project consumes the public **Rick and Morty REST API**.

**Base URL**

```text
https://rickandmortyapi.com/api
```

### Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/character` | Retrieve paginated characters |
| GET | `/character?page={page}` | Retrieve a specific page |
| GET | `/character?name={name}` | Search by name |
| GET | `/character?status={status}` | Filter by status |
| GET | `/character?species={species}` | Filter by species |
| GET | `/character?gender={gender}` | Filter by gender |

Supported query parameters:

- `page`
- `name`
- `status`
- `species`
- `gender`

📚 Documentation: https://rickandmortyapi.com/documentation

---

## ✅ Technical Requirements

- [x] PHP 8+ (no frameworks)
- [x] Tailwind CSS
- [x] Responsive layout
- [x] Character cards
- [x] REST API integration
- [x] Server-side pagination
- [x] Search by name
- [x] Filters (status, species, gender)
- [x] Clean and maintainable code
- [x] PSR-4 autoloading

---

## 👨‍💻 Author

**Gonzalo Gutierrez**

- 📧 gonzalo2706@gmail.com
- 🔗 https://github.com/jhoan2706

---

*Built for the Blossom Technologies technical assessment.*