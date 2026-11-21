<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * LANGKAH 3: controller Welcome diganti nama menjadi Page.
 *
 * Route (application/config/routes.php):
 *   $route['default_controller'] = 'page';      ->  /         = Page::index()
 *   $route['about']   = 'page/about';           ->  /about    = Page::about()
 *   $route['contact'] = 'page/contact';         ->  /contact  = Page::contact()
 *
 * Pola URI bawaan /page/about dan /page/contact juga tetap bisa diakses.
 */
class Page extends CI_Controller {

	public function index()
	{
		$data['title'] = 'Home';
		$data['buah']  = array(
			array('nama' => 'Apel Malang',   'harga' => 32000, 'emoji' => '🍎'),
			array('nama' => 'Jeruk Medan',   'harga' => 25000, 'emoji' => '🍊'),
			array('nama' => 'Pisang Cavendish', 'harga' => 18000, 'emoji' => '🍌'),
			array('nama' => 'Semangka',      'harga' => 15000, 'emoji' => '🍉'),
		);

		$this->load->view('templates/header', $data);
		$this->load->view('home', $data);
		$this->load->view('templates/footer');
	}

	public function about()
	{
		$data['title'] = 'About';
		$this->load->view('templates/header', $data);
		$this->load->view('about');
		$this->load->view('templates/footer');
	}

	public function contact()
	{
		$data['title'] = 'Contact';
		$this->load->view('templates/header', $data);
		$this->load->view('contact');
		$this->load->view('templates/footer');
	}
}
