<?php
$attention_message = isset($attention_message) && $attention_message ? $attention_message : FALSE;
$post_data = isset($post_data) ? $post_data : FALSE;
$top_bidang = isset($top_bidang) ? $top_bidang : FALSE;
$id_m_rancangan_rpjm_desa = isset($id_m_rancangan_rpjm_desa) ? $id_m_rancangan_rpjm_desa : FALSE;
?>

<h3><?= $page_title ?></h3>
<h5><b><?= $deskripsi_title ?></b></h5>

<?php
echo $attention_message ? '<p class="message">' . $attention_message . '</p>' : '';
?>

<?php echo form_open_multipart('rencanaPembangunan/c_rancangan_rpjm_desa/add_detail/' . $id_m_rancangan_rpjm_desa, array('id' => 'frmTambahDetailRPJM')); ?>
<legend></legend>
<div class="form-group">
    <label class="col-md-3 control-label" for="id_provinsi"> Bidang *</label>
    <div id="divRadioBidang"  class="col-md-9">
        <?php if ($top_bidang): ?>
            <?php foreach ($top_bidang as $key => $bidang): ?>
        <input type="radio" value="<?php echo $bidang["id_bidang"]; ?>" name="id_bidang" id="bidang_1" autocomplete="off" checked> <?php echo ucwords(strtolower($bidang["deskripsi"])); ?>
                <br />
            <?php endforeach; ?>
        <?php else: ?>
            Bidang tidak ditemukan
        <?php endif; ?>
    </div>
    <span class="help-block">
        <div class="dvAlert"></div>
    </span>
</div>
<div class="form-group">&nbsp;</div>

<div class="form-group">
    <label class="col-md-3 control-label" for="sub_bidang"> Sub Bidang *</label>
    <div class="col-md-9">
        <input class="form-control input-md required" type="text" name="sub_bidang" id="sub_bidang" size="80" value="<?php echo $post_data ? $post_data["sub_bidang"] : ''; ?>"   />
        <span class="help-block">
            <div class="dvAlert"></div>
        </span>
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label" for="jenis_kegiatan"> Jenis Kegiatan *</label>
    <div class="col-md-9">
        <input class="form-control input-md required" type="text" name="jenis_kegiatan" id="jenis_kegiatan" size="80" value="<?php echo $post_data ? $post_data["jenis_kegiatan"] : ''; ?>"   />
        <span class="help-block">
            <div class="dvAlert"></div>
        </span>
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label" for="lokasi_rt_rw">Lokasi *</label>
    <div class="col-md-9">
        <input class="form-control input-md required" type="text" name="lokasi_rt_rw" id="lokasi_rt_rw" size="80" value="<?php echo $post_data ? $post_data["lokasi_rt_rw"] : ''; ?>"   />
        <span class="help-block">
            <b>( RT / RW / Dusun )</b>
            <br />
            <div class="dvAlert"></div>
        </span>
    </div>
</div>
<div class="form-group">&nbsp;</div>

<div class="form-group">
    <label class="col-md-3 control-label" for="prakiraan_volume"> Prakiraan Volume *</label>
    <div class="col-md-9">
        <input class="form-control input-md required" type="text" name="prakiraan_volume" id="prakiraan_volume" size="80" value="<?php echo $post_data ? $post_data["prakiraan_volume"] : ''; ?>"   />
        <span class="help-block">
            <div class="dvAlert"></div>
        </span>
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label" for="sasaran_manfaat"> Sasaran / Manfaat *</label>
    <div class="col-md-9">
        <input class="form-control input-md required" type="text" name="sasaran_manfaat" id="sasaran_manfaat" size="80" value="<?php echo $post_data ? $post_data["sasaran_manfaat"] : ''; ?>"   />
        <span class="help-block">
            <div class="dvAlert"></div>
        </span>
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label" for="kepala_desa"> Tahun Pelaksanaan *</label>
    <div class="col-md-9">
        <div class="col-md-4">
            <input type="checkbox" value="1" name="tahun_pelaksanaan_1" id="tahun_pelaksanaan_1" autocomplete="off"> Tahun ke-1
            <br />
            <input type="checkbox" value="1" name="tahun_pelaksanaan_2" id="tahun_pelaksanaan_2" autocomplete="off"> Tahun ke-2
            <br />
            <input type="checkbox" value="1" name="tahun_pelaksanaan_3" id="tahun_pelaksanaan_3" autocomplete="off"> Tahun ke-3
        </div>
        <div class="col-md-4">
            <input type="checkbox" value="1" name="tahun_pelaksanaan_4" id="tahun_pelaksanaan_4" autocomplete="off"> Tahun ke-4
            <br />
            <input type="checkbox" value="1" name="tahun_pelaksanaan_5" id="tahun_pelaksanaan_5" autocomplete="off"> Tahun ke-5
            <br />
            <input type="checkbox" value="1" name="tahun_pelaksanaan_6" id="tahun_pelaksanaan_6" autocomplete="off"> Tahun ke-6
        </div>

    </div>
    <span class="help-block">
        <div class="dvAlert"></div>
    </span>
</div>
<div class="form-group">&nbsp;</div>

<div class="form-group">
    <label class="col-md-3 control-label" for="jumlah_biaya"> Jumlah Biaya *</label>
    <div class="col-md-9">
        <input class="form-control input-md required" type="text" name="jumlah_biaya" id="jumlah_biaya" size="80" value="<?php echo $post_data ? $post_data["jumlah_biaya"] : ''; ?>"  />
        <span class="help-block">
            <div class="dvAlert"></div>
        </span>
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label" for="sumber_dana"> Sumber Dana *</label>
    <div class="col-md-9">
        <input class="form-control input-md required" type="text" name="sumber_dana" id="sumber_dana" size="80" <?php echo $post_data ? $post_data["tanggal_disusun"] : ''; ?>  />
        <span class="help-block">
            <div class="dvAlert"></div>
        </span>
    </div>
</div>

<div class="form-group">
    <label class="col-md-3 control-label" for="kepala_desa"> Pola Pelaksanaan *</label>
    <div class="col-md-9">

        <input type="checkbox" value="1" name="swakelola" id="swakelola" autocomplete="off"> Swakelola
        <br />
        <input type="checkbox" value="1" name="kerjasama_antar_desa" id="kerjasama_antar_desa" autocomplete="off"> Kerjasama Antar Desa
        <br />
        <input type="checkbox" value="1" name="kerjasama_pihak_ketiga" id="kerjasama_pihak_ketiga" autocomplete="off"> Kerjasama Pihak Ketiga

    </div>
    <span class="help-block">
        <div class="dvAlert"></div>
    </span>
</div>
<div class="form-group">&nbsp;</div>

<p>
<legend></legend>
<input type="submit" value="Simpan" class="btn btn-success" id="simpan"/>
<input type="button" value="Batal" class="btn btn-danger" id="batal" onclick="location.href = '<?= base_url() ?>rencanaPembangunan/c_rancangan_rpjm_desa/'" />
</p>

<?php echo form_close(); ?>

<?php
echo isset($js_general_helper) ? $js_general_helper : '';
?>
<script>
    function nav_active() {

        document.getElementById("a-data-perencanaan").className = "collapsed active";
        document.getElementById("perencanaan").className = "collapsed active";

        document.getElementById("a-data-rancangan_rpjm_desa").className = "collapsed active";
        document.getElementById("rancangan_rpjm_desa").className = "collapsed active";

        var d = document.getElementById("nav-list_rancangan_rpjm_desa");
        d.className = d.className + "active";
    }



// very simple to use!
    $(document).ready(function () {
        nav_active();

        $("#simpan").click(function () {
//            ResetValidationMessage();

//            var formvalid = ValidateInput("slc_tahun_anggaran_awal", "dvAlertTahunAnggaranAwal", "Tahun Anggaran Awal harus diisi");
//            formvalid = formvalid && ValidateInput("slc_tahun_anggaran_akhir", "dvAlertTahunAnggaranAwal", "<br />Tahun Anggaran Akhir harus diisi");
//            formvalid = formvalid && ValidateInput("slc_provinsi", "dvAlertProvinsi", "Provinsi harus diisi");
//            formvalid = formvalid && ValidateInput("slc_kab_kota", "dvAlertKabupatenKota", "Kabupaten / Kota harus diisi");
//            formvalid = formvalid && ValidateInput("slc_id_kecamatan", "dvAlertKecamatan", "Kecamatan harus diisi");
//            formvalid = formvalid && ValidateInput("slc_desa", "dvAlertDesa", "Desa harus diisi");
//            formvalid = formvalid && ValidateInput("kepala_desa", "dvAlertKepalaDesa", "Kepala Desa harus diisi");
//            formvalid = formvalid && ValidateInput("disusun_oleh", "dvAlertDisusunOleh", "Disusun oleh harus diisi");
//            formvalid = formvalid && ValidateInput("tanggal_disusun", "dvAlertTanggalDisusun", "Tanggal Disusun harus diisi");

            var formvalid = true;
            if (formvalid) {
                if (confirm("Mohon tetap di halaman ini ketika proses sedang berjalan.\nProses Akan berhenti ketika anda berpindah halaman.\nAnda yakin akan mengimpor file ini ? ")) {
                    $(this).attr("disabled", true);
                    $(this).attr("value", "Tunggu .. ");

                    $("#batal").attr("disabled", true);

                    $("#frmTambahDetailRPJM").submit();
                }
            }
            return false;
        });

    });
</script>