## 1. List & Search Books
- **Endpoint:** `GET /api/books`
- **Query Param:** `search` (optional)
- **Fungsi:** Menampilkan katalog buku atau mencari berdasarkan judul.

## 2. Create Book
- **Endpoint:** `POST /api/books`
- **Body:** `title`, `author`, `stock`, `category_id`
- **Fungsi:** Menambah stok buku baru ke perpustakaan.