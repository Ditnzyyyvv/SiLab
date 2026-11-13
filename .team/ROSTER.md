# ROSTER TIM PENGEMBANGAN SiLab (.team)

Dokumen ini mendefinisikan pembagian tugas 4 divisi tim web otonom, pemetaan skill sistem yang wajib digunakan, batas wewenang ketat, dan mekanisme kerja kolaboratif paralel/asinkron.

---

## 1. Matriks Pembagian Divisi & Skill Global

| Divisi | Peran & Fokus Utama | Skill Global Wajib | Batas Wewenang & Aturan Akses | Berkas Output Utama |
| :--- | :--- | :--- | :--- | :--- |
| **Divisi 1: UI/UX Designer** | Perancangan antarmuka, layout responsif, design tokens, tipografi, UX states, copywriting, animasi micro-interactions. | `ui-ux-pro-max`<br>`impeccable`<br>`motion`<br>`bang-motion`<br>`generative_ui` | **Spesifikasi Saja**. Dilarang mengedit kode implementasi backend/frontend (`app/`, `resources/views/`). Seluruh rancangan ditulis ke spec. | [`.team/01-design-spec.md`](file:///c:/Users/Lenovo/Documents/.ukk/.team/01-design-spec.md) |
| **Divisi 2: Fullstack Coder** | Eksekusi kode Laravel 11, Service Layer, Controller, Blade Views, Tailwind CSS, Alpine.js, Migrations, Models. | `generative_ui`<br>`automation`<br>`plugin`<br>`motion`<br>`remotion` | **Eksekutor Kode Penuh**. Mengimplementasikan fitur sesuai PRD & `01-design-spec.md`, serta menuntaskan tiket dari Auditor. | Direktori kode aplikasi (`app/`, `resources/`, `routes/`, dll) |
| **Divisi 3: Context & Bug Auditor** | Audit kepatuhan PRD (`docs/PRD.md`), audit aturan agen (`.agent/rules/`), review arsitektur, keamanan RBAC, dan audit logika bisnis. | `antigravity-guide`<br>`agy-customizations`<br>`migrate-agent-settings` | **READ-ONLY pada Kode Sumber**. Dilarang memodifikasi langsung file aplikasi. Segala temuan wajib dituangkan sebagai tiket terstruktur. | [`.team/02-audit-tickets.md`](file:///c:/Users/Lenovo/Documents/.ukk/.team/02-audit-tickets.md) |
| **Divisi 4: QA Tester** | Pengujian fungsional unit & feature test terminal (`php artisan test`), verifikasi browser UI/UX, simulasi edge-cases, dan verifikasi tiket. | Testing framework Laravel (`PHPUnit`/`Pest`), Terminal CLI, Browser Subagent | **Penguji & Validator**. Menjalankan pengujian regresi, mencatat status `PASS / FAIL`, dan memvalidasi tiket bug yang diperbaiki Coder. | [`.team/03-qa-report.md`](file:///c:/Users/Lenovo/Documents/.ukk/.team/03-qa-report.md) |

---

## 2. Aturan Batas Wewenang (Guardrails)

1. **Designer Tidak Boleh Koding Langsung:**
   - Designer menuangkan ide tata letak, skema warna, state antarmuka (empty, loading, error, success), dan class helper ke `.team/01-design-spec.md`.
   - Coder yang bertugas mentranslasikan spesifikasi tersebut ke dalam file Blade dan komponen Tailwind.

2. **Auditor Bersifat Read-Only:**
   - Auditor dilarang menggunakan tool edit/write file pada source code aplikasi (`app/`, `resources/`, `database/`, `routes/`).
   - Jika Auditor menemukan pelanggaran PRD, inkonsistensi nama tabel/kolom, celah otorisasi, atau bug logika, Auditor wajib menulis tiket di `.team/02-audit-tickets.md` dengan format:
     - `[CRITICAL]`: Pelanggaran keamanan, crash fatal, inkonsistensi skema DB, atau pelanggaran PRD mayor.
     - `[LOGIC]`: Kesalahan alur bisnis atau transisi status tiket yang keliru.
     - `[CONTEXT]`: Inkonsistensi teks UI, bahasa, atau missing labels.

3. **Coder Menuntaskan Spesifikasi & Tiket:**
   - Coder memantau `.team/01-design-spec.md` untuk kebutuhan tampilan dan `.team/02-audit-tickets.md` untuk perbaikan bug.
   - Setelah memperbaiki tiket, Coder menandai status tiket menjadi `RESOLVED: [keterangan perbaikan]`.

4. **Tester Memverifikasi & Menutup Tiket:**
   - Tester menjalankan `php artisan test` dan skenario browser.
   - Jika tiket yang ditandai `RESOLVED` berhasil lolos uji, Tester mengubah status menjadi `VERIFIED: PASS`. Jika masih gagal, ubah menjadi `REOPENED: FAIL`.

---

## 3. Mekanisme Kerja Paralel / Asinkron (Async Workflow)

Masing-masing divisi dapat diaktifkan pada tab **Agent Manager** yang berbeda secara bersamaan:

```
[Tab 1: UI/UX Designer] ----> Menulis & memperbarui .team/01-design-spec.md
                                      |
[Tab 2: Fullstack Coder] <----+ Membaca spec & Mengimplementasikan kode
       |                              ^
       v                              |
[Tab 3: Bug Auditor]     ----> Menulis tiket temuan di .team/02-audit-tickets.md
                                      |
[Tab 4: QA Tester]       ----> Menguji kode & Menulis hasil di .team/03-qa-report.md
```

- Komunikasi antar agen dilakukan melalui berkas di `.team/`.
- Setiap divisi memperbarui catatan status dan tanggal/jam terakhir pembaruan pada berkas masing-masing.
