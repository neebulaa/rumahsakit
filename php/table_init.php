<?php 

// fillable fields 
$tables = [
    "tb_dokter" => [
        // fields => type
        "id" => "id",
        "nama_dokter" => "basic",
        "spesialis" => "basic",
        "alamat" => "basic",
        "no_telp" => "basic"
    ],
    "tb_obat" => [
        // fields => type
        "id" => "id",
        "nama_obat" => "basic", 
        "ket_obat" => "basic"
    ],
    "tb_pasien" => [
        // fields => type
        "id" => "id",
        "nomor_identitas" => "basic", 
        "nama_pasien" => "basic",
        "jenis_kelamin" => "basic",
        "alamat" => "basic",
        "no_telp" => "basic",
    ],
    "tb_poliklinik" => [
        // fields => type
        "id" => "id",
        "nama_poliklinik" => "basic", 
        "gedung" => "basic"
    ],
    "tb_rekammedis" => [
        "id" => "id",
        "id_pasien" => "foreign:tb_pasien",
        "keluhan" => "basic",
        "id_dokter" => "foreign:tb_dokter",
        "diagnosa" => "basic",
        "id_poliklinik" => "foreign:tb_poliklinik",
        "tgl_periksa" => "basic"
    ]
];

$tablesRules = [
    "tb_dokter" => [
        "nama_dokter" => "required|min:3|max:255",
        "spesialis" => "required|min:3|max:255",
        "alamat" => "required|min:8|max:255",
        "no_telp" => "required|digit|min:10|max:12"
    ],
    "tb_obat" => [
        "nama_obat" => "required|min:3|max:255",
        "ket_obat" => "required|min:5|max:500",
    ],
    "tb_pasien" => [
        "nomor_identitas" => "required|digit|min:3|max:15",
        "nama_pasien" => "required|min:5|max:255",
        "jenis_kelamin" => "required|enum:L,P",
        "alamat" => "required|min:8|max:255",
        "no_telp" => "required|digit|min:10|max:12",
    ],
    "tb_poliklinik" => [
        "nama_poliklinik" => "required|min:3|max:255",
        "gedung" => "required|min:5|max:255",
    ],
    "tb_rekammedis" => [
        "id_pasien" => "required|in:tb_pasien,id",
        "keluhan" => "required|min:3|max:255",
        "id_dokter" => "required|in:tb_dokter,id",
        "diagnosa" => "required|min:3|max:255",
        "id_poliklinik" => "required|in:tb_poliklinik,id",
        "tgl_periksa" => "required",

        "id_obat" => "required|in:tb_obat,id"   
    ]
];

$tableRelations = [
    // table => [tb_relation => ['field to select in join']]
    "tb_rekammedis" => [
        "tb_pasien" => [
            "nama_pasien:nama_pasien"
        ],
        "tb_dokter" => [
            "nama_dokter:nama_dokter"
        ],
        "tb_poliklinik" => [
            "nama_poliklinik:nama_poliklinik"
        ],
    ]
]

?>