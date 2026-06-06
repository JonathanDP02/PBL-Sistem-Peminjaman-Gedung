<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disposisi Wadir II - Polinema</title>
    <!-- Tailwind CSS CDN dipanggil agar file ini bisa langsung dijalankan di mana saja -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Gaya khusus untuk menyempurnakan tampilan seperti dokumen cetak */
        body {
            background-color: #f3f4f6;
            padding: 2rem;
            font-family: Arial, sans-serif;
        }
        .dokumen-kertas {
            max-width: 210mm; /* Lebar standar A4 */
            margin: 0 auto;
            background-color: white;
            padding: 20px 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            color: black;
            font-size: 13px; /* Ukuran font standar form cetak */
            line-height: 1.3;
        }
        .header-text {
            font-family: "Times New Roman", Times, serif;
        }
        input[type="checkbox"] {
            width: 14px;
            height: 14px;
            border: 1px solid black;
            cursor: pointer;
            accent-color: black; /* Warna centang menjadi hitam/gelap */
        }
    </style>
</head>
<body>

<div class="dokumen-kertas">
    
    <!-- KOP SURAT -->
    <div class="flex items-center pb-2 mb-1 relative border-b-[3px] border-black">
        <!-- Logo Polinema hitam putih agar mirip hasil fotokopi/cetak -->
        <img src="https://upload.wikimedia.org/wikipedia/id/5/52/Politeknik_Negeri_Malang_logo.png" alt="Logo Polinema" class="w-[90px] h-[90px] absolute left-0" style="filter: grayscale(100%);">
        
        <div class="text-center w-full header-text">
            <div class="text-[17px] tracking-wide">KEMENTERIAN PENDIDIKAN TINGGI, SAINS,<br>DAN TEKNOLOGI</div>
            <div class="text-[20px] font-bold tracking-wide mt-1 mb-1">POLITEKNIK NEGERI MALANG</div>
            <div class="text-[14px]">Jalan Soekarno Hatta Nomor 9 Jatimulyo, Lowokwaru, Malang 65141</div>
            <div class="text-[14px]">Telepon (0341) 404424, 404425, Faksimile (0341) 404420</div>
            <div class="text-[14px]">Laman <span class="underline">www.polinema.ac.id</span></div>
        </div>
    </div>
    <div class="border-b border-black mb-6"></div>

    <!-- JUDUL & NOMOR -->
    <div class="relative text-center mb-4">
        <h2 class="text-[18px] font-bold">DISPOSISI WADIR II</h2>
        <div class="absolute right-0 top-0 border border-black px-6 py-1 text-[16px] font-bold">
            268
        </div>
    </div>

    <!-- BAGIAN 1: KLASIFIKASI & DETAIL SURAT -->
    <div class="border border-black">
        <!-- Baris Klasifikasi -->
        <div class="flex justify-between px-4 py-2 border-b border-black">
            <label class="flex items-center gap-2"><input type="checkbox"> Sangat Rahasia</label>
            <label class="flex items-center gap-2"><input type="checkbox"> Rahasia</label>
            <label class="flex items-center gap-2"><input type="checkbox"> Sangat Segera</label>
            <label class="flex items-center gap-2"><input type="checkbox"> Segera</label>
            <label class="flex items-center gap-2"><input type="checkbox"> Biasa</label>
        </div>
        
        <!-- Baris Detail Surat -->
        <div class="flex px-4 py-3">
            <div class="w-3/5 grid grid-cols-[120px_10px_1fr] gap-y-1">
                <div>Nomor Dispo</div><div>:</div><div class="font-bold">268</div>
                <div>Nomor Surat</div><div>:</div><div class="font-bold">268</div>
                <div>Asal Surat</div><div>:</div><div class="font-bold">Forum Mahasiswa Bidikmisi/KIP Kuliah</div>
                <div>Perihal</div><div>:</div><div class="font-bold">Peminjaman Masjid An-Nur</div>
            </div>
            <div class="w-2/5 grid grid-cols-[100px_10px_1fr] gap-y-1 content-start">
                <div>Tanggal Terima</div><div>:</div><div class="font-bold">11/3/2026</div>
                <div>Tanggal Surat</div><div>:</div><div class="font-bold">03/02/2026</div>
            </div>
        </div>
    </div>

    <!-- BAGIAN 2: DITERUSKAN KEPADA -->
    <div class="border border-black mt-4">
        <div class="px-2 py-1">Diteruskan Kepada:</div>
        <div class="grid grid-cols-3 gap-x-4 gap-y-1 px-2 pb-3">
            
            <!-- Kolom 1 -->
            <div class="flex flex-col gap-1">
                <label class="flex items-center gap-2"><input type="checkbox"> Wakil Direktur I</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Wakil Direktur III</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Wakil Direktur IV</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Kajur Teknik Elektro</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Kajur Teknik Mesin</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Kajur Teknik Sipil</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Kajur Teknik Kimia</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Kajur Akuntansi</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Kajur Administrasi Niaga</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Kajur Teknologi Informasi</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Koordinator Kampus Kediri</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Koordinator Kampus Lumajang</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Koordinator Kampus Pamekasan</label>
                <label class="flex items-end gap-2 w-full"><input type="checkbox" class="mb-0.5"> <span>KPS</span> <span class="flex-grow border-b-[1.5px] border-dotted border-black mx-1 mb-[3px]"></span></label>
                <label class="flex items-center gap-2"><input type="checkbox"> Sekretaris Dewas</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Ketua Senat</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Ketua SPI</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Kepala BAK</label>
            </div>

            <!-- Kolom 2 -->
            <div class="flex flex-col gap-1">
                <label class="flex items-center gap-2"><input type="checkbox"> Kasubag Akademik</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pokja Adm. Akademik & Registrasi</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pokja Eval. Akademik & Pengelolaan Data</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pokja Pembinaan Keg. Mhs & Alumni</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Kepala BPKU</label>
                <label class="flex items-center gap-2"><input type="checkbox" checked> Kasubag Umum</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pokja Tata Usaha</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pokja Protokoler</label>
                <label class="flex items-end gap-2 w-full"><input type="checkbox" checked class="mb-0.5"> <span class="whitespace-nowrap">Pokja Rumah Tangga</span> <span class="flex-grow border-b-[1.5px] border-dotted border-black mx-1 mb-[3px]"></span></label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pokja Perencanaan</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pokja Monev</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pokja Keuangan</label>
                <label class="flex items-end gap-2 w-full"><input type="checkbox" class="mb-0.5"> <span class="whitespace-nowrap">Pokja Pengelola BMN</span> <span class="flex-grow border-b-[1.5px] border-dotted border-black mx-1 mb-[3px]"></span></label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pokja Kepegawaian</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pokja Ortala</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pokja Kerja Sama</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pokja Humas</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pokja Hukum</label>
            </div>

            <!-- Kolom 3 -->
            <div class="flex flex-col gap-1">
                <label class="flex items-center gap-2"><input type="checkbox"> Pusat P2MPP</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pusat P3M</label>
                <label class="flex items-center gap-2"><input type="checkbox"> UPA Perpustakaan</label>
                <label class="flex items-center gap-2"><input type="checkbox"> UPA TIK</label>
                <label class="flex items-center gap-2"><input type="checkbox"> UPA Bahasa</label>
                <label class="flex items-center gap-2"><input type="checkbox"> UPA PP</label>
                <label class="flex items-center gap-2"><input type="checkbox"> UPA PKK</label>
                <label class="flex items-center gap-2"><input type="checkbox"> UPA LUK</label>
                <label class="flex items-center gap-2"><input type="checkbox"> UPA Percetakan & Penerbitan</label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pokja Unit Pengelola Usaha</label>
                <label class="flex items-end gap-2 w-full"><input type="checkbox" class="mb-0.5"> <span class="whitespace-nowrap">Tim Kerja Pimpinan</span> <span class="flex-grow border-b-[1.5px] border-dotted border-black mx-1 mb-[3px]"></span></label>
                <label class="flex items-end gap-2 w-full"><input type="checkbox" class="mb-0.5"> <span>PPK</span> <span class="flex-grow border-b-[1.5px] border-dotted border-black mx-1 mb-[3px]"></span></label>
                <label class="flex items-center gap-2"><input type="checkbox"> Pokja UPPBJ</label>
                <label class="flex items-end gap-2 w-full"><input type="checkbox" class="mb-0.5"> <span class="whitespace-nowrap">Tim Teknis PBJ</span> <span class="flex-grow border-b-[1.5px] border-dotted border-black mx-1 mb-[3px]"></span></label>
                <label class="flex items-end gap-2 w-full"><input type="checkbox" class="mb-0.5"> <span class="whitespace-nowrap">Admin PPK</span> <span class="flex-grow border-b-[1.5px] border-dotted border-black mx-1 mb-[3px]"></span></label>
                <label class="flex items-center gap-2"><input type="checkbox"> Adm. Wadir II</label>
                <label class="flex items-end gap-2 w-full"><input type="checkbox" class="mb-0.5"> <span class="flex-grow border-b-[1.5px] border-dotted border-black mx-1 mb-[3px]"></span></label>
            </div>
        </div>
    </div>

    <!-- BAGIAN 3: ISI DISPOSISI & JAWABAN -->
    <div class="border border-black mt-4 flex flex-col">
        
        <!-- Header Tabel -->
        <div class="flex border-b border-black">
            <div class="flex-grow text-center font-bold py-1">ISI DISPOSISI</div>
        </div>
        
        <!-- Konten Tabel -->
        <div class="flex">
            <!-- Kolom Checkbox Isi Disposisi -->
            <div class="flex-grow grid grid-cols-2 gap-x-4 gap-y-2 p-2">
                <div class="flex flex-col gap-1.5">
                    <label class="flex items-start gap-2"><input type="checkbox" checked class="mt-0.5"> <span>Mohon diproses sesuai aturan yang berlaku</span></label>
                    <label class="flex items-start gap-2"><input type="checkbox" checked class="mt-0.5"> <span>Mohon ditindaklanjuti</span></label>
                    <label class="flex items-start gap-2"><input type="checkbox" class="mt-0.5"> <span>Mohon masukan</span></label>
                    <label class="flex items-start gap-2"><input type="checkbox" class="mt-0.5"> <span>Mohon diinfokan</span></label>
                    <label class="flex items-start gap-2"><input type="checkbox" class="mt-0.5"> <span>Mohon bisa dibantu</span></label>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="flex items-start gap-2"><input type="checkbox" class="mt-0.5"> <span>Mohon diterima dengan baik dan dibalas</span></label>
                    <label class="flex items-start gap-2"><input type="checkbox" class="mt-0.5"> <span>Mohon diagendakan</span></label>
                    <label class="flex items-start gap-2"><input type="checkbox" class="mt-0.5"> <span>Untuk diketahui</span></label>
                    <label class="flex items-start gap-2"><input type="checkbox" class="mt-0.5"> <span>Sebagai refrensi</span></label>
                    <label class="flex items-start gap-2"><input type="checkbox" class="mt-0.5"> <span>Arsip</span></label>
                </div>
            </div>
        </div>

        <!-- Header Jawaban Disposisi -->
        <div class="border-t border-b border-black text-center font-bold py-1">
            JAWABAN DISPOSISI
        </div>
        
        <!-- Ruang Kosong Jawaban -->
        <div class="h-32 p-2">
            <!-- Dikosongkan untuk tempat penulisan manual/digital -->
        </div>
    </div>

</div>

</body>
</html>