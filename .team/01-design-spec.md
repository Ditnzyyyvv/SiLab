# 01 — UI/UX Design Specification (.team)

> **Pemilik Dokumen:** Divisi 1 (UI/UX Designer)  
> **Terakhir Diperbarui:** 2026-11-13  
> **Fokus Saat Ini:** Fase 6 (Laporan & Ekspor PDF/Excel) & Fase 7 (Penyempurnaan Mobile)

---

## 1. Tata Letak (Layout Architecture)

### 1.1 Struktur Halaman
- **Grid / Breakpoints:** Standar Tailwind CSS (`sm: 640px`, `md: 768px`, `lg: 1024px`, `xl: 1280px`).
- **Sidebar & Topbar:** Sidebar responsive (drawer collapsible pada mobile dengan overlay backdrop) dan sticky topbar header.
- **Content Container:** Padding konsisten `px-4 sm:px-6 lg:px-8 py-6` dengan pembungkus `space-y-6`.

### 1.2 Hirarki Spacing & Elevation
- **Card Background:** `bg-white rounded-xl border border-slate-200 shadow-xs`.
- **Card Header:** Padding `p-5 border-b border-slate-100 flex items-center justify-between`.

---

## 2. Komponen & Design Tokens

### 2.1 Palet Warna & Semantic Status
- **Primary Brand (Admin):** Indigo (`#4f46e5`, `bg-indigo-600`, `text-indigo-700`).
- **Accent Brand (Teknisi):** Amber (`#d97706`, `bg-amber-600`, `text-amber-800`).
- **Success Brand (Member / Selesai):** Emerald (`#059669`, `bg-emerald-600`, `text-emerald-800`).
- **Status Tiket:**
  - `Pending`: Abu-abu / Kuning lembut (`bg-amber-100 text-amber-800 border-amber-200`)
  - `Diproses`: Biru (`bg-blue-100 text-blue-800 border-blue-200`)
  - `Menunggu Komponen/Sparepart`: Oranye (`bg-orange-100 text-orange-800 border-orange-200`)
  - `Selesai`: Hijau zamrud (`bg-emerald-100 text-emerald-800 border-emerald-200`)
  - `Rusak Total`: Merah pekat (`bg-red-100 text-red-800 border-red-200`)
  - `Ditolak`: Mawar / Rose (`bg-rose-100 text-rose-800 border-rose-200`)

### 2.2 Tombol & Kontrol Form
- **Primary Button:** `px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition-colors`.
- **Secondary / Filter Reset:** `px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-medium rounded-lg transition-colors`.
- **Form Input & Select:** `text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400`.

---

## 3. Panduan Copywriting & Nada Suara (Voice & Tone)

- **Bahasa:** Bahasa Indonesia baku, ramah, dan profesional (PRD Bagian 13).
- **Format Label:** Title Case atau Sentence Case konsisten (e.g., "Validasi & Disposisi Tiket", "Laporan Kerusakan Aset").
- **Pesan Konfirmasi:** Jelas dan menjelaskan dampak aksi (e.g., "Apakah Anda yakin ingin menyetujui penutupan tiket ini?").

---

## 4. Spesifikasi State UI (Empty, Loading, Error, Success)

### 4.1 Empty State
- Ikon representatif berwarna abu-abu pudar (`text-slate-300`), heading teks `text-sm font-medium text-slate-600`, deskripsi `text-xs text-slate-400`, dan opsional tombol aksi pemulihan/pembuatan baru.

### 4.2 Loading State
- Tombol dengan state `opacity-75 cursor-not-allowed` saat request dikirim, indikator spinner halus atau skeleton loader.

### 4.3 Error State & Validasi
- Pesan validasi inline di bawah input: `text-xs text-rose-600 mt-1`.
- Alert error: `bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 text-xs`.

### 4.4 Success State
- Alert flash notification: `bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 text-xs flex items-center`.
