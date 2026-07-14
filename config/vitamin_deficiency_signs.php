<?php

/**
 * Database tanda-tanda defisiensi vitamin/nutrisi berdasarkan area tubuh.
 * Digunakan sebagai:
 * - Referensi prompt untuk Gemini AI Vision
 * - Basis validasi hasil analisis AI
 * - Konten edukasi di frontend
 *
 * Sumber: modul-gizi-deteksi-foto-ai.md (Tabel Fitur B)
 */

return [

    'mata' => [
        'label'       => 'Mata',
        'icon'        => '👁️',
        'description' => 'Analisis area mata dan sekitarnya untuk mendeteksi tanda kekurangan gizi.',
        'panduan_foto' => 'Ambil foto area mata dengan pencahayaan cukup. Fokuskan kamera pada bagian putih mata (sklera) dan kelopak bawah. Tarik sedikit kelopak bawah agar konjungtiva terlihat jelas.',
        'tanda_visual' => [
            [
                'tanda'         => 'Konjungtiva pucat',
                'deskripsi'     => 'Bagian dalam kelopak mata bawah tampak pucat, tidak merah muda normal',
                'indikasi'      => ['Anemia', 'Kekurangan Zat Besi (Fe)', 'Kekurangan Vitamin B12'],
                'keyakinan'     => 'tinggi',
                'perlu_rujuk'   => false,
            ],
            [
                'tanda'         => 'Bercak Bitot',
                'deskripsi'     => 'Bercak/busa keputihan di permukaan sklera (putih mata)',
                'indikasi'      => ['Kekurangan Vitamin A (Xerophthalmia)'],
                'keyakinan'     => 'tinggi',
                'perlu_rujuk'   => true,
            ],
            [
                'tanda'         => 'Kekeruhan kornea',
                'deskripsi'     => 'Kornea mata tampak tidak jernih/berkabut',
                'indikasi'      => ['Kekurangan Vitamin A berat', 'Keratomalasia'],
                'keyakinan'     => 'tinggi',
                'perlu_rujuk'   => true,
            ],
            [
                'tanda'         => 'Mata cekung',
                'deskripsi'     => 'Area sekitar mata tampak lebih dalam dari normal',
                'indikasi'      => ['Dehidrasi', 'Gizi buruk/malnutrisi berat'],
                'keyakinan'     => 'sedang',
                'perlu_rujuk'   => true,
            ],
            [
                'tanda'         => 'Kemerahan/peradangan',
                'deskripsi'     => 'Mata merah atau iritasi yang persisten',
                'indikasi'      => ['Kekurangan Vitamin B2 (Riboflavin)', 'Infeksi'],
                'keyakinan'     => 'sedang',
                'perlu_rujuk'   => false,
            ],
        ],
    ],

    'kulit' => [
        'label'       => 'Kulit',
        'icon'        => '🤲',
        'description' => 'Analisis kondisi kulit untuk mendeteksi tanda kekurangan nutrisi.',
        'panduan_foto' => 'Ambil foto area kulit lengan bawah atau wajah anak. Pastikan pencahayaan merata (hindari bayangan). Jika ada area kulit yang bermasalah (kering, ruam, dll), fokuskan foto pada area tersebut.',
        'tanda_visual' => [
            [
                'tanda'         => 'Kulit pucat',
                'deskripsi'     => 'Warna kulit tampak lebih pucat dari normal',
                'indikasi'      => ['Anemia', 'Kekurangan Zat Besi (Fe)'],
                'keyakinan'     => 'sedang',
                'perlu_rujuk'   => false,
            ],
            [
                'tanda'         => 'Kering dan bersisik',
                'deskripsi'     => 'Kulit kering, kasar, mengelupas atau bersisik',
                'indikasi'      => ['Kekurangan Vitamin A', 'Kekurangan Zinc', 'Kekurangan Vitamin E'],
                'keyakinan'     => 'sedang',
                'perlu_rujuk'   => false,
            ],
            [
                'tanda'         => 'Kulit kuning (ikterik)',
                'deskripsi'     => 'Kulit dan/atau sklera mata tampak kekuningan',
                'indikasi'      => ['Gangguan hati', 'Gizi berat'],
                'keyakinan'     => 'tinggi',
                'perlu_rujuk'   => true,
            ],
            [
                'tanda'         => 'Ruam/dermatitis',
                'deskripsi'     => 'Ruam kemerahan, peradangan, atau lesi kulit',
                'indikasi'      => ['Kekurangan Vitamin B2', 'Kekurangan Vitamin B3 (Niacin)', 'Kekurangan Zinc'],
                'keyakinan'     => 'sedang',
                'perlu_rujuk'   => false,
            ],
            [
                'tanda'         => 'Hiperpigmentasi',
                'deskripsi'     => 'Area kulit yang lebih gelap dari sekitarnya secara abnormal',
                'indikasi'      => ['Kekurangan Vitamin B3 (Pellagra)', 'Kekurangan Vitamin B12'],
                'keyakinan'     => 'sedang',
                'perlu_rujuk'   => false,
            ],
            [
                'tanda'         => 'Petekie/perdarahan bawah kulit',
                'deskripsi'     => 'Bintik-bintik merah kecil atau memar tanpa sebab jelas',
                'indikasi'      => ['Kekurangan Vitamin C', 'Kekurangan Vitamin K'],
                'keyakinan'     => 'tinggi',
                'perlu_rujuk'   => true,
            ],
        ],
    ],

    'kuku' => [
        'label'       => 'Kuku',
        'icon'        => '💅',
        'description' => 'Analisis bentuk, warna, dan tekstur kuku untuk mendeteksi tanda kekurangan nutrisi.',
        'panduan_foto' => 'Ambil foto semua jari tangan anak dari dekat. Pastikan kuku terlihat jelas. Foto dari atas (tampak atas kuku) dan jika memungkinkan foto dari samping untuk melihat bentuk kuku.',
        'tanda_visual' => [
            [
                'tanda'         => 'Kuku pucat/putih',
                'deskripsi'     => 'Warna kuku pucat, tidak merah muda seperti normal',
                'indikasi'      => ['Anemia', 'Kekurangan Zat Besi (Fe)', 'Kekurangan Protein'],
                'keyakinan'     => 'tinggi',
                'perlu_rujuk'   => false,
            ],
            [
                'tanda'         => 'Koilonikia (kuku sendok)',
                'deskripsi'     => 'Kuku melengkung ke atas seperti sendok, bukan melengkung normal ke bawah',
                'indikasi'      => ['Kekurangan Zat Besi (Fe) berat'],
                'keyakinan'     => 'tinggi',
                'perlu_rujuk'   => true,
            ],
            [
                'tanda'         => 'Kuku rapuh/mudah patah',
                'deskripsi'     => 'Kuku tipis, mudah patah, retak, atau mengelupas',
                'indikasi'      => ['Kekurangan Protein', 'Kekurangan Biotin', 'Kekurangan Zat Besi'],
                'keyakinan'     => 'sedang',
                'perlu_rujuk'   => false,
            ],
            [
                'tanda'         => 'Garis Beau',
                'deskripsi'     => 'Garis horizontal / lekukan melintang pada kuku',
                'indikasi'      => ['Kekurangan Zinc', 'Kekurangan Protein', 'Riwayat sakit berat'],
                'keyakinan'     => 'sedang',
                'perlu_rujuk'   => false,
            ],
            [
                'tanda'         => 'Bercak putih (leukonychia)',
                'deskripsi'     => 'Bintik atau garis putih pada permukaan kuku',
                'indikasi'      => ['Kekurangan Zinc', 'Kekurangan Protein'],
                'keyakinan'     => 'rendah',
                'perlu_rujuk'   => false,
            ],
            [
                'tanda'         => 'Tanda Muehrcke',
                'deskripsi'     => 'Garis putih horizontal berpasangan pada kuku',
                'indikasi'      => ['Kekurangan Protein berat (Hipoalbuminemia)'],
                'keyakinan'     => 'tinggi',
                'perlu_rujuk'   => true,
            ],
        ],
    ],

    // Mapping defisiensi ke rekomendasi makanan
    'rekomendasi_makanan' => [
        'Zat Besi (Fe)' => [
            'makanan'    => ['Hati ayam/sapi', 'Daging merah', 'Bayam', 'Kacang-kacangan', 'Telur', 'Ikan'],
            'suplemen'   => 'Tablet tambah darah (TTD) sesuai dosis usia',
            'durasi'     => '14 hari evaluasi awal',
        ],
        'Vitamin A' => [
            'makanan'    => ['Wortel', 'Ubi jalar', 'Bayam', 'Hati', 'Telur', 'Mangga', 'Pepaya'],
            'suplemen'   => 'Kapsul Vitamin A (sesuai program Posyandu)',
            'durasi'     => '14 hari evaluasi awal',
        ],
        'Vitamin B2' => [
            'makanan'    => ['Susu', 'Telur', 'Daging', 'Sayuran hijau', 'Kacang almond'],
            'suplemen'   => 'Suplemen Vitamin B kompleks',
            'durasi'     => '14 hari evaluasi awal',
        ],
        'Vitamin B3' => [
            'makanan'    => ['Daging ayam', 'Ikan tuna', 'Kacang tanah', 'Jamur', 'Kentang'],
            'suplemen'   => 'Suplemen Niacin sesuai anjuran dokter',
            'durasi'     => '14 hari evaluasi awal',
        ],
        'Vitamin B12' => [
            'makanan'    => ['Hati', 'Ikan', 'Daging', 'Telur', 'Susu'],
            'suplemen'   => 'Suplemen Vitamin B12',
            'durasi'     => '14 hari evaluasi awal',
        ],
        'Vitamin C' => [
            'makanan'    => ['Jeruk', 'Jambu biji', 'Pepaya', 'Tomat', 'Brokoli', 'Stroberi'],
            'suplemen'   => 'Suplemen Vitamin C sesuai dosis usia',
            'durasi'     => '14 hari evaluasi awal',
        ],
        'Vitamin E' => [
            'makanan'    => ['Kacang almond', 'Biji bunga matahari', 'Bayam', 'Alpukat', 'Minyak zaitun'],
            'suplemen'   => 'Suplemen Vitamin E',
            'durasi'     => '14 hari evaluasi awal',
        ],
        'Vitamin K' => [
            'makanan'    => ['Bayam', 'Kangkung', 'Brokoli', 'Kacang kedelai', 'Kubis'],
            'suplemen'   => 'Suplemen Vitamin K sesuai anjuran dokter',
            'durasi'     => '14 hari evaluasi awal',
        ],
        'Zinc' => [
            'makanan'    => ['Daging sapi', 'Kerang', 'Kacang-kacangan', 'Biji labu', 'Telur'],
            'suplemen'   => 'Suplemen Zinc 20mg/hari',
            'durasi'     => '14 hari evaluasi awal',
        ],
        'Protein' => [
            'makanan'    => ['Telur', 'Daging ayam', 'Ikan', 'Tempe', 'Tahu', 'Kacang-kacangan', 'Susu'],
            'suplemen'   => 'Susu formula/pertumbuhan sesuai usia',
            'durasi'     => '14 hari evaluasi awal',
        ],
        'Biotin' => [
            'makanan'    => ['Telur', 'Kacang-kacangan', 'Ubi jalar', 'Jamur', 'Pisang'],
            'suplemen'   => 'Suplemen Biotin',
            'durasi'     => '14 hari evaluasi awal',
        ],
        'Kalsium (Ca)' => [
            'makanan'    => ['Susu', 'Keju', 'Yogurt', 'Ikan teri', 'Brokoli', 'Tahu'],
            'suplemen'   => 'Suplemen Kalsium sesuai dosis usia',
            'durasi'     => '14 hari evaluasi awal',
        ],
    ],
];
