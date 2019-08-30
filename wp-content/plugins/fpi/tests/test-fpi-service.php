<?php
/**
 * Class SampleTest
 *
 * @package Fpi
 */

/**
 * Sample test case.
 */
require_once './src/fpi-service.php';

class test_fpi_service extends WP_UnitTestCase {
	protected $service;
	protected $singel_data;

	public function setUp()
	{
		$this->service = new Fpi_Service();
		$this->singel_data = file_get_contents('./public/facebook-single-data.json');
		$this->mutli_data = file_get_contents('./public/facebook-large-data.json');
		$this->invalid_data = file_get_contents('./public/facebook-invalid-data.json');
	}

	public function test_fpi_process_fb_data() {
		// test invalid data import
		$existPublishedPosts = wp_count_posts()->publish;
		$this->assertEquals($existPublishedPosts,0);
		$this->service->fpi_process_fb_data($this->invalid_data);
		$existPublishedPosts = wp_count_posts()->publish;
		$this->assertEquals($existPublishedPosts,0);

		// test single data import
		$existPublishedPosts = wp_count_posts()->publish;
		$this->assertEquals($existPublishedPosts,0);
		$this->service->fpi_process_fb_data($this->singel_data);
		$existPublishedPosts = wp_count_posts()->publish;
		$this->assertEquals($existPublishedPosts,1);
		$newPosts = wp_get_recent_posts(['numberposts'=>100]);
		$this->assertEquals(count($newPosts),1);
		$this->assertEquals($newPosts[0]['post_title'],"Zoo names monkey after British royal baby. Regrets it");
		$this->assertEquals($newPosts[0]['post_date'],'2015-05-08 11:01:03');

		// test multi data import
		$existPublishedPosts = wp_count_posts()->publish;
		$this->assertEquals($existPublishedPosts,1);
		$this->service->fpi_process_fb_data($this->mutli_data);
		$existPublishedPosts = wp_count_posts()->publish;
		$this->assertEquals($existPublishedPosts,26);
		$newPosts = wp_get_recent_posts(['numberposts'=>100]);
		$this->assertEquals(count($newPosts),26);
		$this->assertEquals($newPosts[25]['post_title'],"Bride’s wedding photo nightmare: 'Ugliest bride I have ever photographed'");
		$this->assertEquals($newPosts[25]['post_date'],'2015-05-07 23:19:53');
	}
}
