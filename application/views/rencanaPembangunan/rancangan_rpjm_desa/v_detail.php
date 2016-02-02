<?php
$attention_message = isset($attention_message) && $attention_message ? $attention_message : FALSE;
?>
<link href="<?= $this->config->item('base_url'); ?>css/flexigrid.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?= $this->config->item('base_url'); ?>js/jquery.pack.js"></script>
<script type="text/javascript" src="<?= $this->config->item('base_url'); ?>js/flexigrid.pack.js"></script>


<h3><?php echo $page_title; ?></h3>
<h5><b><?php echo $deskripsi_title; ?></b></h5>
<legend></legend>

<?php
echo $attention_message ? '<p class="message">' . $attention_message . '</p>' : '';
?>



<?php
echo $js_grid;
?>

<script type="text/javascript">
    var _base_url = '<?= base_url() ?>';

    function edit_rpjmd(id) {
        window.location = _base_url + 'rencanaPembangunan/c_rancangan_rpjm_desa/edit/' + id;
    }

    function add_sub_program(id) {
        window.location = _base_url + 'rencanaPembangunan/c_rancangan_rpjm_desa/add_sub_program/' + id;
    }

    function show_detail_program(id) {
        window.location = _base_url + 'rencanaPembangunan/c_rancangan_rpjm_desa/show_detail_program/' + id;
    }

    function show_tree_rpjmd(id) {
        window.location = _base_url + 'rencanaPembangunan/c_rancangan_rpjm_desa/show_tree_rpjmd/' + id;
    }

    function btn(com, grid)
    {
        if (com == 'Select All')
        {
            $('.bDiv tbody tr', grid).addClass('trSelected');
        }

        if (com == 'DeSelect All')
        {
            $('.bDiv tbody tr', grid).removeClass('trSelected');
        }

        if (com == 'Add')
        {
            window.location = _base_url + 'rencanaPembangunan/c_rancangan_rpjm_desa/add';
        }

        if (com == 'Delete Selected Items')
        {
            if ($('.trSelected', grid).length > 0) {
                if (confirm('Hapus ' + $('.trSelected', grid).length + ' item?')) {
                    var items = $('.trSelected', grid);
                    var itemlist = '';
                    for (i = 0; i < items.length; i++) {
                        itemlist += items[i].id.substr(3) + ",";
                    }
                    $.ajax({
                        type: "POST",
                        url: "<?= site_url("rencanaPembangunan/c_rpjmd/delete/"); ?>",
                        data: "items=" + itemlist,
                        success: function (data) {
                            $('#flex1').flexReload();
                            alertify.success("Data berhasil dihapus !");
                        },
                        error: function () {
                            alertify.error("Maaf, data yang akan dihapus masih digunakan !");
                        }
                    });
                }
            } else {
                return false;
            }
        }
    }

    $(function () {

    });
</script>


<table id="flex1" style="display:none"></table>
<span class="help-block">

    <a href="<?php echo base_url().'rencanaPembangunan/c_rancangan_rpjm_desa'; ?>" class="btn btn-danger">Kembali</a>

</span>

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
    });
</script>