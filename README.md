# API Berita

**Tugas Pemrograman Web Interaktif — Pertemuan 14**

Dosen: *(isi nama dosen)*

---

## Informasi Tugas

| | |
|---|---|
| **Mata Kuliah** | Pemrograman Web Interaktif |
| **Pertemuan** | 14 |
| **Topik** | Membangun RESTful API menggunakan Laravel |
| **Tahun Akademik** | 2025/2026 |

Tugas ini bertujuan untuk membangun sebuah **RESTful API** berbasis Laravel yang dapat melakukan operasi CRUD (Create, Read, Update, Delete) pada data berita. API ini dilengkapi dengan fitur upload gambar yang secara otomatis dikonversi ke format **WebP** menggunakan library Intervention Image agar ukuran file lebih kecil dan performa lebih baik.

---

## Teknologi yang Digunakan

| Teknologi | Keterangan |
|---|---|
| **Laravel 13.x** | Framework PHP untuk pengembangan web |
| **MySQL / MariaDB** | Database management system |
| **Intervention Image 4.x** | Library untuk manipulasi gambar, digunakan untuk konversi ke WebP |
| **Postman** | Tools untuk testing API |

---

## Instalasi & Konfigurasi

### 1. Clone Project

```bash
git clone https://github.com/muadzhdz/api-berita.git
cd api-berita
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_berita
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Key & Migrate

```bash
php artisan key:generate
php artisan migrate
```

### 5. Buat Storage Link

```bash
php artisan storage:link
```

### 6. Jalankan Server

```bash
php artisan serve
```

Akses API di `http://localhost:8000/api/berita`

---

## Struktur Database

**Tabel:** `beritas`

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id_berita` | BIGINT (PK) | Primary Key |
| `judul_berita` | VARCHAR(255) | Judul berita |
| `konten_berita` | LONGTEXT | Konten/isi berita |
| `gambar_berita` | VARCHAR(255) | Nama file gambar (nullable) |
| `created_at` | TIMESTAMP | Tanggal dibuat |
| `updated_at` | TIMESTAMP | Tanggal diupdate |

### Eloquent Model: `App\Models\Berita`

- **Primary Key:** `id_berita`
- **Fillable:** `judul_berita`, `konten_berita`, `gambar_berita`
- **Appends:** `url_gambar` — menghasilkan URL lengkap gambar otomatis

---

## Endpoint API

Semua endpoint dapat diakses melalui prefix `/api/berita`.

| Method | Endpoint | Fungsi | Kode Sukses |
|---|---|---|---|
| `GET` | `/api/berita` | Mengambil seluruh data berita | 200 |
| `POST` | `/api/berita` | Menambahkan berita baru | 201 |
| `GET` | `/api/berita/{id}` | Mengambil detail berita berdasarkan ID | 200 |
| `POST` (dengan `_method=PUT`) | `/api/berita/{id}` | Memperbarui data berita | 200 |
| `DELETE` | `/api/berita/{id}` | Menghapus data berita | 200 |

### 1. GET Semua Berita

**Request:**

```
GET /api/berita
```

**Response (200 OK):**

```json
[
    {
        "id_berita": 1,
        "judul_berita": "Belajar Laravel API",
        "konten_berita": "Panduan lengkap membuat REST API dengan Laravel",
        "gambar_berita": "1745634534.webp",
        "url_gambar": "http://localhost:8000/storage/berita/1745634534.webp",
        "created_at": "2025-06-30T02:30:00.000000Z",
        "updated_at": "2025-06-30T02:30:00.000000Z"
    }
]
```

### 2. GET Detail Berita

**Request:**

```
GET /api/berita/1
```

**Response (200 OK):**

```json
{
    "id_berita": 1,
    "judul_berita": "Belajar Laravel API",
    "konten_berita": "Panduan lengkap membuat REST API dengan Laravel",
    "gambar_berita": "1745634534.webp",
    "url_gambar": "http://localhost:8000/storage/berita/1745634534.webp",
    "created_at": "2025-06-30T02:30:00.000000Z",
    "updated_at": "2025-06-30T02:30:00.000000Z"
}
```

### 3. POST Tambah Berita

**Request:**

```
POST /api/berita
Content-Type: multipart/form-data
```

| Key | Type | Keterangan |
|---|---|---|
| `judul_berita` | Text | Judul berita (required) |
| `konten_berita` | Text | Konten berita (required) |
| `gambar_berita` | File | File gambar max 5MB (required) |

**Response (201 Created):**

```json
{
    "id_berita": 2,
    "judul_berita": "Judul Berita Baru",
    "konten_berita": "Isi konten berita disini",
    "gambar_berita": "1745635500.webp",
    "url_gambar": "http://localhost:8000/storage/berita/1745635500.webp",
    "created_at": "2025-06-30T03:00:00.000000Z",
    "updated_at": "2025-06-30T03:00:00.000000Z"
}
```

### 4. POST Update Berita (dengan `_method=PUT`)

Karena `multipart/form-data` tidak mendukung method `PUT` secara langsung, gunakan method `POST` dengan field `_method=PUT`.

**Request:**

```
POST /api/berita/1
Content-Type: multipart/form-data
```

| Key | Type | Keterangan |
|---|---|---|
| `_method` | Text | `PUT` (wajib) |
| `judul_berita` | Text | Judul berita (required) |
| `konten_berita` | Text | Konten berita (required) |
| `gambar_berita` | File | File gambar max 5MB (opsional) |

**Response (200 OK):**

```json
{
    "id_berita": 1,
    "judul_berita": "Judul Berita Update",
    "konten_berita": "Konten berita setelah diupdate",
    "gambar_berita": "1745635600.webp",
    "url_gambar": "http://localhost:8000/storage/berita/1745635600.webp",
    "created_at": "2025-06-30T02:30:00.000000Z",
    "updated_at": "2025-06-30T03:05:00.000000Z"
}
```

### 5. DELETE Berita

**Request:**

```
DELETE /api/berita/1
```

**Response (200 OK):**

```json
{
    "message": "Data berhasil dihapus"
}
```

---

## Fitur Konversi Gambar ke WebP

Setiap gambar yang diupload akan secara otomatis:

1. Discale ke lebar maksimal **800px** (menjaga aspek rasio)
2. Dikonversi ke format **WebP** dengan kualitas **70%**
3. Disimpan di `storage/app/public/berita/`
4. Dapat diakses melalui URL `/storage/berita/nama_file.webp`

File gambar lama akan otomatis terhapus ketika diperbarui atau data berita dihapus.

---

## Testing dengan Postman

### Import Collection

1. Buka Postman
2. Klik **Import** → **Upload Files**
3. Pilih file `api-berita.postman_collection.json` yang ada di root project
4. Set variable `base_url` menjadi `http://localhost:8000` (atau sesuai server)
5. Jalankan server dengan `php artisan serve`
6. Test semua endpoint

---

## Hasil Testing API

> *Screenshot hasil testing dapat ditambahkan disini*

| Endpoint | Screenshot |
|---|---|
| GET /api/berita | *(tempel screenshot)* |
| POST /api/berita | *(tempel screenshot)* |
| GET /api/berita/{id} | *(tempel screenshot)* |
| POST /api/berita/{id} (PUT) | *(tempel screenshot)* |
| DELETE /api/berita/{id} | *(tempel screenshot)* |

---

## Kesimpulan

Tugas ini berhasil menyelesaikan pembuatan **RESTful API** untuk data berita menggunakan Laravel dengan fitur:
- CRUD lengkap (Create, Read, Update, Delete)
- Upload gambar dengan konversi otomatis ke WebP
- Validasi input di sisi server
- URL gambar otomatis melalui accessor `url_gambar`
- Image scaling otomatis (max width 800px)
- Hapus file gambar lama saat update/delete
- 5 endpoint API siap pakai

Semua endpoint telah diuji menggunakan Postman dan berjalan dengan baik.


