CREATE DATABASE IF NOT EXISTS db_akademik CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_akademik;
CREATE TABLE IF NOT EXISTS mahasiswa (
    nim VARCHAR(15) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    program_studi VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    PRIMARY KEY (nim)
) ENGINE = InnoDB;
INSERT INTO mahasiswa (nim, nama, program_studi, alamat)
VALUES (
        '24781103',
        'Govin Gautama',
        'Manajemen Informatika',
        'Bandar Lampung'
    ),
    (
        '24781102',
        'Fiqa Khairunisa',
        'Manajemen Informasi',
        'Bandar Lampung'
    ),
    (
        '24781100',
        'Elya Dianis',
        'Manajemen Informatika',
        'Bandar Lampung'
    ),
    (
        '24781106',
        'M Haidir Septian',
        'Manajemen Informatika',
        'Bandar Lampung'
    );