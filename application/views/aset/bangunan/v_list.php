<link href="<?=$this->config->item('base_url');?>css/flexigrid.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=$this->config->item('base_url');?>js/jquery.pack.js"></script>
<script type="text/javascript" src="<?=$this->config->item('base_url');?>js/flexigrid.pack.js"></script>
<?php
echo $js_grid;
?>

<script type="text/javascript">
var _base_url = '<?= base_url() ?>';

function edit_bangunan(id) {
  window.location = _base_url + 'aset/c_bangunan/edit/' + id;
}

function btn(com,grid)
{
    if (com=='Select All')
    {
		$('.bDiv tbody tr',grid).addClass('trSelected');
    }

    if (com=='DeSelect All')
    {
		$('.bDiv tbody tr',grid).removeClass('trSelected');
    }
	
	if (com=='Add')
    {
		window.location = _base_url + 'aset/c_bangunan/add';
    }	
	
	if (com=='Delete Selected Items')
        {
		
           if($('.trSelected',grid).length>0){
			   if(confirm('Hapus ' + $('.trSelected',grid).length + ' item?')){
		            var items = $('.trSelected',grid);
		            var itemlist ='';
		        	for(i=0;i<items.length;i++){
						itemlist+= items[i].id.substr(3)+",";
					}
					$.ajax({
					   type: "POST",
					   url: "<?=site_url("aset/c_bangunan/delete/");?>",
					   data: "items="+itemlist,
					   success: function(data){
					   	$('#flex1').flexReload();
					  	alertify.success("Data berhasil dihapus !");
					   } ,
						error: function() {
							alertify.error("Maaf, data yang akan dihapus masih digunakan !");
						}
					});
				}
			} else {
				return false;
			}
        }
}

$(function(){
  
});

function nav_active(){
	document.getElementById("a-data-bangunan").className = "collapsed active";
	
	document.getElementById("bangunan").className = "collapsed";

	var d = document.getElementById("nav-bangunan");
	d.className = d.className + "active";
	}
 
// very simple to use!
$(document).ready(function() {
  nav_active();
});

</script>
<div class="row">
<div class="col-md-8">
<h3><?= $page_title ?></h3>
</div>
<div class="col-md-4">
			<ul class="nav navbar-right" style="float:right; margin-top:10px;">
	<li class="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			<i class="fa fa-gears fa-fw"></i> Aksi / Tindakan <i class="fa fa-caret-down"> </i>
		</a>
		<ul class="dropdown-menu dropdown-user">
			<li>
				<a href="<?php echo site_url('aset/c_bangunan/export_to_excel/');?>"><i class="fa fa-download fa-fw"></i> Export ke Excel</a>
			</li>
		</ul>
	</li>
</ul> 
</div>
</div>
<legend></legend>

<table id="flex1" style="display:none"></table>
