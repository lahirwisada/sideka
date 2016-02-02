<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


/**
 * 
 * array('title', 'lebar kolom', ?, 'text alignment', ?)
 * array('Tahun Anggaran', 120, TRUE, 'center', 2);
 */
$config['rp_rancangan_rpjm_desa'] = array(
    'colModel' => array(
        'id_rancangan_rpjm_desa' => array('ID', 30, TRUE, 'center', 0),
        'bidang' => array('Bidang', 100, TRUE, 'center', 2),
        'sub_bidang' => array('Sub Bidang', 120, TRUE, 'center', 2),
        'jenis_kegiatan' => array('Jenis Kegiatan', 120, TRUE, 'center', 2),
        'lokasi_rt_rw' => array('Lokasi<br />(RT/RW<br />/Dusun)', 60, TRUE, 'center', 2),
        'prakiraan_volume' => array('Prakiraa<br />Volume', 60, TRUE, 'center', 2),
        'sasaran_manfaat' => array('Sasaran Manfaat', 120, TRUE, 'center', 2),
        'tahun_pelaksanaan' => array('Tahun<br />Pelaksanaan', 50, TRUE, 'center', 2),
        'jumlah_biaya' => array('Prakiraan<br />Jumlah Biaya<br />(Rp)', 80, TRUE, 'center', 2),
        'sumber_dana' => array('Sumber<br />Pembiayaan', 100, TRUE, 'center', 2),
        'pelaksanaan_swakelola' => array('Swakelola', 100, TRUE, 'center', 2),
        'pelaksanaan_kerjasama_antar_desa' => array('Kerjasama<br />Antar Desa', 100, TRUE, 'center', 2),
        'pelaksanaan_kerjasama_pihak_ketiga' => array('Kerjasama<br />Pihak Ketiga', 100, TRUE, 'center', 0),
        'aksi' => array('AKSI', 120, FALSE, 'center', 0)
    ),
    'buttons' => array(
//        array('Select All', 'check', 'btn'),
//        array('separator'),
//        array('DeSelect All', 'uncheck', 'btn'),
//        array('separator'),
        array('Add', 'add', 'btn'),
//        array('separator'),
//        array('Delete Selected Items', 'delete', 'btn'),
//        array('separator')
    ),
    'gridParams' => array(
        'height' => 300,
        'usepager' => FALSE,
        'rp' => 10,
        'rpOptions' => '[10,20,30,40]', /* jumlah opsi untuk mengganti jumlah halaman */
        'pagestat' => 'Displaying: {from} to {to} of {total} items.',
        'blockOpacity' => 0.5,
        'title' => '',
        'showTableToggleBtn' => FALSE
    )
);
