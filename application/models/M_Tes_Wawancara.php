<?php
/**
* 
*/
class M_Tes_Wawancara extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function get_all()
	{
		return $this->db->get('tes_wawancara')->result();
	}

	public function get_id($no_pendaftaran,$no_tes,$id_pewawancara)
	{
		return $this->db->get_where('tes_wawancara', array('no_pendaftaran' => $no_pendaftaran,'id' => $no_tes,'id_pewawancara' => $id_pewawancara))->result();
	}

	public function add($no_pendaftaran,$no_tes,$id_pewawancara,$tanggal_tes,$nilai_total,$keterangan)
	{
		return $this->db->insert('tes_wawancara', array(
				'no_pendaftaran' 	=> $no_pendaftaran,
				'id' 				=> $no_tes,
				'id_pewawancara' 	=> $id_pewawancara,
				'tanggal_tes' 		=> $tanggal_tes,
				'total_nilai' 		=> $nilai_total,
				'keterangan' 		=> $keterangan
			));
	}

	public function update($no_pendaftaran,$no_tes,$id_pewawancara,$tanggal_tes,$nilai_total,$keterangan)
	{
		$this->db->where('no_pendaftaran',$no_pendaftaran);
		$this->db->where('id',$no_tes);
		$this->db->where('id_pewawancara',$id_pewawancara);
		return $this->db->update('tes_wawancara', array(
				'tanggal_tes' 		=> $tanggal_tes,
				'total_nilai' 		=> $nilai_total,
				'keterangan' 		=> $keterangan
			));
	}


	// public function edit($id, $nama, $password, $keterangan)
	// {
	// 	$this->db->where('id_pewawancara', $id);
	// 	return $this->db->update('pewawancara', array(
	// 			'nama' => $nama,
	// 			'password' => md5($password),
	// 			'keterangan' => $keterangan
	// 		));
	// }

	// public function remove($id)
	// {
	// 	$this->db->where('id_pewawancara', $id);
	// 	return $this->db->delete('tes_wawancara');
	// }

}
?>