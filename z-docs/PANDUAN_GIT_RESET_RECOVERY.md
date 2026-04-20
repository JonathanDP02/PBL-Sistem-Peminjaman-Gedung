# Panduan Lengkap Git: Reset, Revert, Pull, dan Recovery

Dokumen ini berisi panduan komprehensif tentang manajemen riwayat commit, cara melakukan pull dari berbagai branch, mengatasi conflict, dan yang paling penting: cara menyelamatkan diri saat terlanjur melakukan kesalahan seperti salah `git reset --hard` atau salah `git pull`.

---

## 1. Melihat Riwayat dan Pergerakan Git

Sebelum memanipulasi riwayat, Anda harus tahu posisi Anda saat ini.

*   **Riwayat normal (Commit history):**
    ```bash
    git log --oneline
    ```
*   **Riwayat pergerakan HEAD (Super penting untuk recovery!):**
    ```bash
    git reflog
    ```

---

## 2. Cara Kembali ke Commit Sebelumnya

### A. Hanya Melihat (Tanpa Mengubah Permanen)
```bash
git checkout <commit-hash>
```
*Untuk kembali ke titik terakhir:* `git checkout <nama-branch>`

### B. Mengembalikan Perubahan dengan Aman (Revert)
Membuat commit baru yang membatalkan efek commit lama (sangat disarankan saat berkolaborasi).
```bash
git revert <commit-hash>
```

**Membatalkan `git revert` (Jika berubah pikiran)**
*   Jika revert **sedang berlangsung** (kena conflict & ingin batal):
    ```bash
    git revert --abort
    ```
*   Jika revert **sudah selesai** (commit revert sudah terbuat) dan ingin dikembalikan ke sebelum revert:
    ```bash
    git reset --hard HEAD~1
    ```

### C. Menghapus Commit (Reset)
*   **Soft Reset** (Kembali, tapi perubahan masuk ke area Staging): `git reset --soft <commit-hash>`
*   **Mixed Reset** (Kembali, perubahan tidak di-stage - Default): `git reset <commit-hash>`
*   **Hard Reset** (AWAS: Kembali dan HAPUS semua perubahan): `git reset --hard <commit-hash>`

---

## 3. Menetapkan Perubahan Reset ke Git Remote (GitHub/GitLab)

Jika Anda melakukan `git reset` dan mencoba `git push`, remote akan menolak. Gunakan opsi force secara aman:
```bash
git push origin <nama-branch> --force-with-lease
```
*`--force-with-lease` jauh lebih aman daripada `--force` karena akan mengecek apakah ada orang lain yang sudah push. Jika ada, Git akan memblokirnya agar kode orang lain tidak tertimpa.*

---

## 4. PENYELAMATAN: Terlanjur `git reset --hard`

Git tidak segera menghapus file jika Anda terlanjur `git reset --hard`.

1.  Selalu mulai dengan memeriksa reflog:
    ```bash
    git reflog
    ```
    Output:
    ```text
    11d4c60 HEAD@{0}: reset: moving to 11d4c60
    a9f728a HEAD@{1}: commit: Update admin unit fitur
    ```
2.  Posisi salah Anda ada di `HEAD@{0}`. Posisi sebelum reset ada di `HEAD@{1}` (atau `a9f728a`).
3.  Kembali ke masa sebelum reset menggunakan:
    ```bash
    git reset --hard HEAD@{1}
    ```
    *(Atau langsung `git reset --hard a9f728a`). Pekerjaan Anda akan kembali seutuhnya!*

---

## 5. Panduan `git pull` (Mengambil Perubahan)

`git pull` adalah gabungan dari `git fetch` (mengambil data dari remot) dan `git merge` (menggabungkannya ke kode lokal Anda).

*   **Menarik data dari branch utama (main):**
    ```bash
    git pull origin main
    ```
*   **Menarik data dari branch lain:**
    Contoh, Anda sedang di branch `tugas-saya`, tapi butuh kode teman Anda dari branch `Febri`:
    ```bash
    git pull origin Febri
    ```

---

## 6. Mengatasi Merge Conflict Setelah Pull

Terkadang setelah `git pull`, Git tidak bisa menggabungkan kode otomatis karena Anda dan rekan Anda mengedit baris kode yang sama. Ini disebut *Conflict*.

1.  Cek file mana saja yang bentrok:
    ```bash
    git status
    ```
2.  Buka file yang conflict (VS Code/editor akan memberitahu). Anda akan melihat kode seperti ini:
    ```text
    <<<<<<< HEAD
    Kode Anda saat ini
    =======
    Kode dari branch yang di-pull
    >>>>>>> nama-branch-asal
    ```
3.  **Cara Menyelesaikan:**
    *   Hapus baris `<<<<<<<`, `=======`, dan `>>>>>>>`.
    *   Sisakan kode yang paling benar (apakah gabungan keduanya, milik Anda saja, atau milik teman Anda saja).
    *   Di VS Code, biasanya ada tombol klik cepat: *Accept Current Change*, *Accept Incoming Change*, atau *Accept Both Changes*.
4.  Jika semuanya sudah diperbaiki, simpan file, lalu commit:
    ```bash
    git add .
    git commit -m "Menyelesaikan merge conflict dari pull"
    ```

---

## 7. PENYELAMATAN: Terlanjur `git pull` dan Ingin Dibatalkan

Jika performa pull berantakan (misal conflict terlalu banyak) atau Anda menarik dari branch yang salah, Anda bisa **membatalkan pull** tersebut dan kembali ke posisi sebelumnya.

### A. Membatalkan saat Conflict Sedang Terjadi (Belum Selesai)
Jika Anda dalam mode conflict dan ingin "menyerah / kembali ke awal saja sebelum pull":
```bash
git merge --abort
```
*Ini akan mengembalikan kode lokal Anda persis sebelum perintah `git pull` dijalankan.*

### B. Membatalkan Setelah Pull Selesai (Already Merged)
Jika pull berhasil (tidak ada conflict atau Anda sudah commit conflict-nya), tapi Anda sadar "Waduh, salah pull branch nih!".

**Cara 1 (ORIG_HEAD):**
Git secara otomatis menyimpan posisi HEAD *sebelum* operasi berbahaya seperti `pull` atau `merge` di alias `ORIG_HEAD`.
```bash
git reset --hard ORIG_HEAD
```

**Cara 2 (Reflog - Cara paling sakti):**
```bash
git reflog
```
Cari baris sebelum tulisan `pull` atau `merge`, misalnya `HEAD@{1}`.
```bash
git reset --hard HEAD@{1}
```

Dua cara di atas sama-sama kuat untuk memutar balik waktu ke momen sebelum Anda salah melakukan Pull.
