# 02 — Context & Bug Audit Tickets (.team)

> **Pemilik Dokumen:** Divisi 3 (Context & Bug Auditor) — Mode READ-ONLY pada kode aplikasi.  
> **Terakhir Diperbarui:** 2026-11-13  
> **Petunjuk:** Auditor menulis tiket temuan di sini. Coder memperbaikinya dan menandai `RESOLVED`. Tester memverifikasi dan menandai `VERIFIED: PASS`.

---

## Ringkasan Status Tiket Audit

| Kategori Tiket | Total Ditemukan | Open | Resolved (Coder) | Verified (Tester) |
| :--- | :---: | :---: | :---: | :---: |
| `[CRITICAL]` | 0 | 0 | 0 | 0 |
| `[LOGIC]` | 0 | 0 | 0 | 0 |
| `[CONTEXT]` | 0 | 0 | 0 | 0 |

---

## Daftar Tiket Audit Aktif

### 1. Kategori `[CRITICAL]`
*Isu keamanan, pelanggaran akses role (RBAC), kerusakan basis data, atau pelanggaran PRD fatal.*

*(Belum ada tiket kritis terbuka. Kode Fase 0 s.d. Fase 5 telah lolos audit.)*

```markdown
<!-- Template Tiket [CRITICAL] -->
### [CRITICAL-001] Judul Isu Kritis
- **Lokasi Berkas:** `app/Http/...`
- **Pelanggaran PRD:** Bagian X / FR-XX
- **Deskripsi:** Penjelasan temuan dan celah yang timbul.
- **Rekomendasi Perbaikan:** Solusi yang disarankan kepada Coder.
- **Status:** OPEN | RESOLVED: [catatan coder] | VERIFIED: PASS [catatan tester]
```

---

### 2. Kategori `[LOGIC]`
*Kesalahan alur transaksi, transisi status tiket yang melanggar matriks PRD, inkonsistensi sinkronisasi kondisi aset.*

*(Belum ada tiket logika terbuka.)*

```markdown
<!-- Template Tiket [LOGIC] -->
### [LOGIC-001] Judul Isu Alur Bisnis
- **Lokasi Berkas:** `app/Services/...`
- **Aturan Alur:** PRD Bagian 9
- **Deskripsi:** Deskripsi logika yang tidak sesuai.
- **Rekomendasi Perbaikan:** Penyesuaian query atau exception handling.
- **Status:** OPEN | RESOLVED: [catatan coder] | VERIFIED: PASS [catatan tester]
```

---

### 3. Kategori `[CONTEXT]`
*Inkonsistensi bahasa (selain Bahasa Indonesia), label form yang membingungkan, ketidaksesuaian teks UI.*

*(Belum ada tiket konteks terbuka.)*

```markdown
<!-- Template Tiket [CONTEXT] -->
### [CONTEXT-001] Judul Isu Konteks/Copy
- **Lokasi Berkas:** `resources/views/...`
- **Deskripsi:** Teks atau label yang perlu diselaraskan.
- **Rekomendasi:** Teks bahasa Indonesia baku yang dianjurkan.
- **Status:** OPEN | RESOLVED: [catatan coder] | VERIFIED: PASS [catatan tester]
```
