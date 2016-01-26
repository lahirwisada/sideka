<?php
class M_coa extends CI_Model {

  function __construct()
  {
    parent::__construct();
    $this->_table='ref_rp_coa';
    //get instance
    $this->CI = get_instance();
  }
	public function get_coa_flexigrid()
    {
        //Build contents query
        $this->db->select(
		'ref_rp_coa.id_coa,
		ref_rp_coa.kode_rekening,
		ref_rp_coa.deskripsi,
		ref_rp_coa.id_parent_coa,
		ref_rp_coa.id_top_coa,
		ref_rp_coa.level
		'
		)->from($this->_table);
        $this->db->where('ref_rp_coa.id_parent_coa', null);
        $this->db->order_by('ref_rp_coa.id_parent_coa ', null);
		$this->db->group_by('ref_rp_coa.id_coa ', null);
        $this->CI->flexigrid->build_query();

        //Get contents
        $return['records'] = $this->db->get();

        //Build count query
        $this->db->select("count(ref_rp_coa.id_coa) as record_count")->from($this->_table);        
        $this->db->where('ref_rp_coa.id_parent_coa', null);
        $this->db->order_by('ref_rp_coa.id_parent_coa ', null);
		
        $this->CI->flexigrid->build_query(FALSE);
        $record_count = $this->db->get();
        $row = $record_count->row();

        //Get Record Count
        $return['record_count'] = $row->record_count;

        //Return all
        return $return;
    }
	
	public function get_coa_flexigrid_byIdCoa($id)
    {
        //Build contents query
        $this->db->select(
		'ref_rp_coa.id_coa,
		ref_rp_coa.kode_rekening,
		ref_rp_coa.deskripsi,
		ref_rp_coa.id_parent_coa,
		ref_rp_coa.id_top_coa,
		ref_rp_coa.level
		'
		)->from($this->_table);
        $this->db->where('ref_rp_coa.id_coa', $id);
        $this->db->or_where('ref_rp_coa.id_top_coa', $id);
        $this->db->order_by('ref_rp_coa.id_parent_coa ', null);
        $this->db->group_by('ref_rp_coa.id_coa ', null);
        $this->CI->flexigrid->build_query();

        //Get contents
        $return['records'] = $this->db->get();

        //Build count query
        $this->db->select("count(id_coa) as record_count")->from($this->_table);        
		$this->db->where('ref_rp_coa.id_coa', $id);
		$this->db->or_where('ref_rp_coa.id_top_coa', $id);
        $this->db->where('ref_rp_coa.id_coa !=', 0);
		
		//$this->db->where('id_parent_coa ', null);
		//$this->db->order_by('ref_rp_coa.id_parent_coa ', null);
		
        $this->CI->flexigrid->build_query(FALSE);
        $record_count = $this->db->get();
        $row = $record_count->row();

        //Get Record Count
        $return['record_count'] = $row->record_count;

        //Return all
        return $return;
    }
	
  function insertCoa($data)
  {
    $this->db->insert($this->_table, $data);
  } 
  
  function deleteCoa($id)
  {
    $this->db->where('id_coa', $id);
    $this->db->delete($this->_table);
  }
  
  function getCoaByIdCoa($id) //edit
  {	
    return $this->db->get_where($this->_table,array('id_coa' => $id))->row();
  }
  
  function updateCoa($where, $data) //update
  {
    $this->db->where($where);
    $this->db->update($this->_table, $data);
    return $this->db->affected_rows();
  }
  
	function cekFIleExist($kode_rekening)
	{	
		return $this->db->get_where($this->_table,array('kode_rekening' => $kode_rekening))->row();
	}
	
	function getRowCoa_ByIdCoa($id_coa)
	{
		$this->db->select('*');
		$this->db->where('id_coa',$id_coa);
		$query = $this->db->get('ref_rp_coa');
		return $query->row();
	}
	
	function getResult_CoaByIdCoa($id)
	{
		$this->db->select('
		ref_rp_coa.id_coa,
		ref_rp_coa.kode_rekening,
		ref_rp_coa.kondisi_awal,
		ref_rp_coa.target,
		ref_rp_coa.id_parent_coa,
		ref_rp_coa.id_top_coa,
		ref_rp_coa.id_tahun_anggaran,
		');
		$this->db->where('ref_rp_coa.id_parent_coa', null);
		$this->db->where('ref_rp_coa.id_coa', $id);
		$this->db->or_where('ref_rp_coa.id_top_coa', $id);
		$q = $this->db->get('ref_rp_coa');
		return $q->result();
	}
	
	function getNumRowCoa_ByIdCoa($id)
	{
		$this->db->select('
		ref_rp_coa.id_coa,
		ref_rp_coa.kode_rekening,
		ref_rp_coa.kondisi_awal,
		ref_rp_coa.target,
		ref_rp_coa.id_parent_coa,
		ref_rp_coa.id_top_coa,
		ref_rp_coa.id_tahun_anggaran,
		');
		$this->db->where('ref_rp_coa.id_parent_coa', null);
		$this->db->where('ref_rp_coa.id_coa', $id);
		$this->db->or_where('ref_rp_coa.id_top_coa', $id);
		$q = $this->db->get('ref_rp_coa');
		return $q->num_rows();
	}
	
	function getIdParentCoa_ByIdParentCoa($id_parent_coa)
	{
		$this->db->select('id_parent_coa');
		$this->db->where('id_parent_coa',$id_parent_coa);
		$q = $this->db->get('ref_rp_coa');
		$data = array_shift($q->result_array());
		return ($data['id_parent_coa']);
	}
	
	function getIdTopCoa_ByIdCoa($id_coa)
	{
		$this->db->select('id_top_coa');
		$this->db->where('id_coa',$id_coa);
		$q = $this->db->get('ref_rp_coa');
		$data = array_shift($q->result_array());
		return ($data['id_top_coa']);
	}
	
	function getIdCoa_ByIdCoa($id_coa)
	{
		$this->db->select('id_coa');
		$this->db->where('id_coa',$id_coa);
		$q = $this->db->get('ref_rp_coa');
		$data = array_shift($q->result_array());
		return ($data['id_coa']);
	}
	
	function getKodeRekening_ByIdCoa($id_coa)
	{
		$this->db->select('kode_rekening');
		$this->db->where('id_coa',$id_coa);
		$q = $this->db->get('ref_rp_coa');
		$data = array_shift($q->result_array());
		return ($data['kode_rekening']);
	}
	
	function getDeskripsi_ByIdCoa($id_coa)
	{
		$this->db->select('deskripsi');
		$this->db->where('id_coa',$id_coa);
		$q = $this->db->get('ref_rp_coa');
		$data = array_shift($q->result_array());
		return ($data['deskripsi']);
	}
	
	function getId_ByIdCoa($id_coa)
	{
		$this->db->select('id_coa');
		$this->db->where('id_coa',$id_coa);
		$q = $this->db->get('ref_rp_coa');
		$data = array_shift($q->result_array());
		return ($data['id_coa']);
	}
}
?>
