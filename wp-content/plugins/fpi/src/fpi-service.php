<?php
/**
 * The core service of the plugin.
 *
 * @link       http://www.bobsyd.com/demos
 * @since      1.0.0
 *
 * @package    Fpi
 * @subpackage Fpi/src
 */

/**
 * The core service of the plugin.
 *
 * All service logic here 
 *
 * @package    Fpi
 * @subpackage Fpi/src
 * @author     Bob Xiao
 */

class Fpi_Service
{
    /**
     * Process json data from Facebook
     *
     * @since     1.0.0
     * @param     json      $jsonData           
     * @return    array     Process result
     */
    public function fpi_process_fb_data($jsonData):array
    {
        $returnData = [];
        $data = json_decode($jsonData, true);
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $facebook_post) {
                $returnData[] = $this->fpi_create_post($facebook_post);
            }
        } else {
            $returnData[] = [
                'status' => 'fail',
                'msg' => 'Invalid data format(Json needed)',
            ];
        }
        return $returnData;
    }
    /**
     * import single post data from Facebook
     *
     * @since     1.0.0
     * @param     array     $facebook_post        
     * @return    array     Import result
     */
    private function fpi_create_post(array $facebook_post):array
    {
        if ($this->fpi_validate_post_data($facebook_post)) {
            $create_date = strtotime($facebook_post['created_time']);
            // name for normal post and story for video post, any others？
            $title = ($facebook_post['name'])?? (($facebook_post['story']) ?? 'No title');
            $content =
                "A new post from Facebook user: ".$facebook_post['from']['name']."<br>"
                .$facebook_post['message'] ."<br/>"
                ."<a href='".$facebook_post['link']."'>".$title."</a></br>";

            $postarr = [
                'post_date'=> date("Y-m-d H:i:s", $create_date),
                'post_content'=>$content,
                'post_title'=>$title,
                'post_status' => 'Publish'
            ];
            $post_id = wp_insert_post($postarr);
            return [
                'status'=> 'success',
                'msg' => 'Facebook post '. $facebook_post['id']. " has been imported. <a href='".get_permalink($post_id)."'>View</a><br/>",
            ];
        } else {
            return [
                'status' => 'fail',
                'msg' => 'Invalid facebook data format '.((isset($facebook_post['id'])?'at facebook ID:'.$facebook_post['id'] : '')),
            ];
        }
    }
    /**
     * Validate Facebook data 
     *
     * @since     1.0.0
     * @param     array     $facebook_post 
     * @return    bool      
     */
    private function fpi_validate_post_data(array $facebook_post):bool
    {
        //TODO  should use a schema validate 
        return (isset($facebook_post['id'])
            && isset($facebook_post['created_time'])
            && isset($facebook_post['from']['name'])
            && (isset($facebook_post['name']) || isset($facebook_post['story']))
            && isset($facebook_post['link'])
            && isset($facebook_post['message'])
        );
    }
}
