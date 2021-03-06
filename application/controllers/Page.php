<?php
/**
*
*/
class Page extends CI_Controller
{

	public function index()
	{
		$data['judul'] = 'Pendaftaran Mahasiswa Baru';

		$this->load->view('start', $data);
	}

	public function not_found()
	{
		echo "Error 404: Halaman tidak ditemukan";
	}

	public function beranda()
	{
		$data['judul'] = 'Pendaftaran Mahasiswa Baru';
		$data['konten'] = 'registrasi/beranda';

		$this->load->view('layout', $data);
	}

	public function register()
	{
		// buat captcha dulu
		$this->load->helper('captcha');
		$vals = array(
			'img_path'      => './assets/global/img/captcha/',
			'img_url'       => base_url().'/assets/global/img/captcha/',
			'img_width'     => '200',
			'img_height'    => '50',
			'word_length'   => '5',
			'expiration'    => '7200',
			'pool'			=> 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
			'font_size'     => '20',

			// White background and border, black text and red grid
			'colors'        => array(
					'background' 	=> array(255, 255, 255),
					'border' 		=> array(0, 0, 0),
					'text' 			=> array(0, 0, 0),
					'grid' 			=> array(255, 150, 150)
			)
		);
		$cap = create_captcha($vals);
		//buat session sementara untuk menampung nilai captcha
		$this->session->set_userdata('captcha_word', $cap['word']);

		$data['judul'] = 'Pendaftaran Mahasiswa Baru';
		$data['konten'] = 'registrasi/form_online';
		$data['jurusan'] = $this->m_jurusan->get_all();
		$data['captcha'] = $cap;

		$txtKota = read_file('./daftar_kota.txt');
		$data['kota'] = explode(';', $txtKota);

		$this->load->view('registrasi/layout', $data);
	}

	private function do_upload_images($inputname) {
		$this->load->library('upload');

	    $files = $_FILES;

	    $cpt = count ($_FILES [$inputname] ['name']);
	    for($i = 0; $i < $cpt; $i ++) {

	        $_FILES [$inputname] ['name'] = $files [$inputname] ['name'] [$i];
	        $_FILES [$inputname] ['type'] = $files [$inputname] ['type'] [$i];
	        $_FILES [$inputname] ['tmp_name'] = $files [$inputname] ['tmp_name'] [$i];
	        $_FILES [$inputname] ['error'] = $files [$inputname] ['error'] [$i];
	        $_FILES [$inputname] ['size'] = $files [$inputname] ['size'] [$i];

	        $config = array ();
		    $config ['upload_path'] = './assets/global/img/photo/';
		    $config ['allowed_types'] = 'bmp|jpg|png';
		    $config ['overwrite']	= TRUE;
	        $this->upload->initialize($config);
	        $this->upload->do_upload($inputname);

	        return $_FILES[$inputname]['name'];
	    }
	}

	public function register_act()
	{
		$no_pendaftaran = $this->tbl_pendaftar->create_no_pendaftaran();
		$id_admin = isset($_SESSION['id_admin'])?$_SESSION['id_admin']:'ADM01';
		$nama = $this->input->post('nama');
		$jk = $this->input->post('jk');
		$tempat_lahir = $this->input->post('tmp_lahir');
		$tanggal_lahir = date('Y-m-d', strtotime($this->input->post('tgl_lahir')));
		$agama = $this->input->post('agama');
		$status_pernikahan = $this->input->post('status');
		$pekerjaan = $this->input->post('pekerjaan');
		$kewarganegaraan = $this->input->post('kewarganegaraan');
		$no_identitas = $this->input->post('no_id');
		$alamat_tetap = $this->input->post('alamat_tetap');
		$alamat_sekarang = $this->input->post('alamat_sekarang');
		$alamat_kantor = $this->input->post('alamat_kantor');
		$no_handphone = $this->input->post('no_handphone');
		$no_telepon = $this->input->post('no_telepon');
		$email = $this->input->post('email');
		$evaluasi_diri = '';
		$password = md5($this->input->post('pass'));
		$valid = '0';
		$tanggal_daftar = date('Y-m-d');
		$sumber_informasi = $this->input->post('sumber_informasi');

		$prodi1 = $this->input->post('prodi1');
		$prodi2 = $this->input->post('prodi2');

		$kode_unik = $this->input->post('kode_unik');
		if ($kode_unik != $_SESSION['captcha_word']) {
			$this->session->set_flashdata('pesan', '<b>Gagal!</b> Pastikan \'Kode Unik\' yang Anda masukkan sudah sesuai dengan gambar.');
			redirect('page/register');
		}

		$pas_foto = $this->do_upload_images('pas_foto');

		$act = $this->tbl_pendaftar->add($no_pendaftaran, $id_admin, $nama, $jk, $tempat_lahir, $tanggal_lahir,
			$agama, $status_pernikahan, $pekerjaan, $kewarganegaraan, $no_identitas, $alamat_tetap,
			$alamat_sekarang, $alamat_kantor, $no_handphone, $no_telepon, $email, $evaluasi_diri,
			$password, $valid, $tanggal_daftar, $sumber_informasi, $pas_foto);

		if ($act > 0) {
			if($prodi1 != '' || $prodi1 != null) $this->tbl_pilihan->add($no_pendaftaran, $prodi1);
			if($prodi2 != '' || $prodi2 != null) $this->tbl_pilihan->add($no_pendaftaran, $prodi2);

			$this->session->set_flashdata('pesan', "<b>Berhasil!</b> Data pendaftaran Anda telah disimpan.
				Silahkan <a href='".base_url().'index.php/page/login'."'>LOGIN</a> dan lengkapi data Anda.");

			redirect('page/riwayat_pendidikan/'.$no_pendaftaran);
		} else {
			$this->session->set_flashdata('pesan', '<b>Gagal!</b> Data pendaftaran Anda gagal disimpan.');
			redirect('page/register');
		}
	}

	public function riwayat_pendidikan($no_pendaftaran)
	{
		$pendaftar = $this->tbl_pendaftar->get_id($no_pendaftaran);

		$data['judul'] = "Isi Riwayat Pendidikan";
		$data['konten'] = "registrasi/form_riwayat_pendidikan";
		$data['pendaftar'] = $pendaftar[0];
		$data['pendidikan'] = $this->tbl_riwayat_pendidikan->get_pendaftar($no_pendaftaran);
		$data['id'] = $this->security_check->gen_ai_id('riwayat_pendidikan', 'id');

		$this->load->view('registrasi/layout', $data);
	}

	public function riwayat_pendidikan_act($no_pendaftaran, $act, $id = null)
	{
		switch ($act) {
			case 'tambah':
				$id = $this->input->post('id');
				$jenis = $this->input->post('jenis');
				$nama = $this->input->post('nama');
				$alamat = $this->input->post('alamat');
				$mulai = $this->input->post('mulai');
				$selesai = $this->input->post('selesai');
				$sertifikat = $this->input->post('sertifikat');

				$this->tbl_riwayat_pendidikan->add($id, $no_pendaftaran, $jenis, $nama, $alamat, $mulai, $selesai, $sertifikat);

				redirect('page/riwayat_pendidikan/'.$no_pendaftaran);
				break;

			case 'hapus':
				$this->tbl_riwayat_pendidikan->remove($id);

				redirect('page/riwayat_pendidikan/'.$no_pendaftaran);
				break;

			case 'detil':
				$id = $this->input->post('id');
				$arr_detil = $this->tbl_riwayat_pendidikan->get_id($id)[0];
				header('Content-Type: application/json');
				echo json_encode($arr_detil);
				break;

			case 'ubah':
				$id = $this->input->post('id-u');
				$jenis = $this->input->post('jenis-u');
				$nama = $this->input->post('nama-u');
				$alamat = $this->input->post('alamat-u');
				$mulai = $this->input->post('mulai-u');
				$selesai = $this->input->post('selesai-u');
				$sertifikat = $this->input->post('sertifikat-u');

				$this->tbl_riwayat_pendidikan->edit($id, $no_pendaftaran, $jenis, $nama, $alamat, $mulai, $selesai, $sertifikat);

				redirect('page/riwayat_pendidikan/'.$no_pendaftaran);
				break;
		}
	}

	public function riwayat_pekerjaan($no_pendaftaran)
	{
		$pendaftar = $this->tbl_pendaftar->get_id($no_pendaftaran);

		$data['judul'] = "Isi Riwayat Pekerjaan";
		$data['konten'] = "registrasi/form_riwayat_pekerjaan";
		$data['pendaftar'] = $pendaftar[0];
		$data['pekerjaan'] = $this->tbl_riwayat_pekerjaan->get_pendaftar($no_pendaftaran);
		$data['id'] = $this->security_check->gen_ai_id('riwayat_kerja', 'id');

		$this->load->view('registrasi/layout', $data);
	}

	public function riwayat_pekerjaan_act($no_pendaftaran, $act, $id = null)
	{
		switch ($act) {
			case 'tambah':
				$id = $this->input->post('id');
				$nama = $this->input->post('nama');
				$mulai = $this->input->post('mulai');
				$selesai = $this->input->post('selesai');
				$jabatan = $this->input->post('jabatan');
				$gaji = $this->input->post('gaji');

				$this->tbl_riwayat_pekerjaan->add($id, $no_pendaftaran, $nama, $mulai, $selesai, $jabatan, $gaji);

				redirect('page/riwayat_pekerjaan/'.$no_pendaftaran);
				break;

			case 'hapus':
				$this->tbl_riwayat_pekerjaan->remove($id);

				redirect('page/riwayat_pekerjaan/'.$no_pendaftaran);
				break;

			case 'ubah':
				$id = $this->input->post('id-u');
				$nama = $this->input->post('nama-u');
				$mulai = $this->input->post('mulai-u');
				$selesai = $this->input->post('selesai-u');
				$jabatan = $this->input->post('jabatan-u');
				$gaji = $this->input->post('gaji-u');

				$this->tbl_riwayat_pekerjaan->edit($id, $no_pendaftaran, $nama, $mulai, $selesai, $jabatan, $gaji);

				redirect('page/riwayat_pekerjaan/'.$no_pendaftaran);
				break;
		}
	}

	public function anggota_keluarga($no_pendaftaran)
	{
		$pendaftar = $this->tbl_pendaftar->get_id($no_pendaftaran);

		$data['judul'] = "Isi Anggota Keluarga";
		$data['konten'] = "registrasi/form_anggota_keluarga";
		$data['pendaftar'] = $pendaftar[0];
		$data['anggota_keluarga'] = $this->tbl_anggota_keluarga->get_pendaftar($no_pendaftaran);
		$data['id'] = $this->security_check->gen_ai_id('anggota_keluarga', 'id');

		$this->load->view('registrasi/layout', $data);
	}

	public function anggota_keluarga_act($no_pendaftaran, $act, $id = null)
	{
		switch ($act) {
			case 'tambah':
				$id = $this->input->post('id');
				$nama = $this->input->post('nama');
				$hubungan = $this->input->post('hubungan');
				$usia = $this->input->post('usia');
				$pekerjaan = $this->input->post('pekerjaan');

				$this->tbl_anggota_keluarga->add($id, $no_pendaftaran, $nama, $hubungan, $usia, $pekerjaan);

				redirect('page/anggota_keluarga/'.$no_pendaftaran);
				break;

			case 'hapus':
				$this->tbl_anggota_keluarga->remove($id);

				redirect('page/anggota_keluarga/'.$no_pendaftaran);
				break;

			case 'ubah':
				$id = $this->input->post('id-u');
				$nama = $this->input->post('nama-u');
				$hubungan = $this->input->post('hubungan-u');
				$usia = $this->input->post('usia-u');
				$pekerjaan = $this->input->post('pekerjaan-u');

				$this->tbl_anggota_keluarga->edit($id, $no_pendaftaran, $nama, $hubungan, $usia, $pekerjaan);

				redirect('page/anggota_keluarga/'.$no_pendaftaran);
				break;
		}
	}

	public function evaluasi_diri($no_pendaftaran)
	{
		$pendaftar = $this->tbl_pendaftar->get_id($no_pendaftaran);

		$data['judul'] = "Evaluasi Diri";
		$data['konten'] = "registrasi/form_evaluasi_diri";
		$data['pendaftar'] = $pendaftar[0];

		$this->load->view('registrasi/layout', $data);
	}

	public function evaluasi_diri_act()
	{
		$no_pendaftaran = $this->input->post('id');
		$evaluasi_diri = $this->input->post('evaluasi');

		$this->db->where("no_pendaftaran", $no_pendaftaran);
		$act = $this->db->update("pendaftar", array('evaluasi_diri' => $evaluasi_diri));

		redirect("page/summary/".$no_pendaftaran);
	}

	public function summary($no_pendaftaran)
	{
		$pendaftar = $this->tbl_pendaftar->get_id($no_pendaftaran);

		$data['judul'] = "Selesai";
		$data['konten'] = "registrasi/form_summary";
		$data['pendaftar'] = $pendaftar[0];

		$this->load->view('registrasi/layout', $data);
	}

	public function login()
	{
		$data['judul'] = 'Login - PMB';

		$this->load->view('login', $data);
	}

	public function login_auth()
	{
		$no_pendaftaran 	= $this->input->post('no_pendaftaran');
		$password			= $this->input->post('password');

		$peserta = $this->tbl_pendaftar->auth($no_pendaftaran, $password);

		if (count($peserta) > 0) {
			$peserta_sess = $this->tbl_pendaftar->get_id($peserta[0]->NO_PENDAFTARAN);
			$session_data = array(
				'no_pendaftaran' 	=> $peserta_sess[0]->NO_PENDAFTARAN,
				'nama_peserta' 		=> $peserta_sess[0]->NAMA
			);
			$this->session->set_userdata($session_data);
			redirect('peserta/beranda');
		} else {
			$this->session->set_flashdata('pesan', '<strong>Gagal!</strong> Mohon periksa Nomer pendaftaran dan Password yang Anda masukkan.');
			redirect('page/login');
		}		
	}
}
?>
