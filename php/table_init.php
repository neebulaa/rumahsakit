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
    ]
]

?>