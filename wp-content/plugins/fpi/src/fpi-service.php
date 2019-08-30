<?php

class Fpi_Service
{
    public function fpi_form_submit()
    {
        // access right ?????
        if (wp_doing_ajax() &&  wp_verify_nonce($_POST['nonce'], 'fpi_admin_save')) {
            $jsonData = wp_unslash($_POST['jsonData']);
            $result = $this->fpi_process_fb_data($jsonData);
            wp_send_json($result);
        } else {
            wp_send_json([['status'=>'fail', 'msg'=>'API access deny']]);
        }
    }
    public function fpi_process_fb_data($jsonData)
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

    private function fpi_create_post(array $facebook_post):array
    {
        if ($this->fpi_validate_post_data($facebook_post)) {
            $create_date = strtotime($facebook_post['created_time']);
            $title = ($facebook_post['name'])?? (($facebook_post['story']) ?? 'No title');
            $content =
            "Post from Facebook user".$facebook_post['from']['name']."<br>"
            ."<a href='".$facebook_post['link']."'>".$title."</a></br>"
             .$facebook_post['message'] ."<br/>";
            $postarr = [
                'post_date'=> date("Y-m-d H:i:s", $create_date),
                'post_content'=>$content,
                'post_title'=>$title,
                'post_status' => 'Publish'
            ];
            $post_id = wp_insert_post($postarr);

            return [
                'status'=> 'success',
                'msg' => 'Facebook post '. $facebook_post['id']. " has been inserted. <a href='".get_permalink($post_id)."'>View</a><br/>",
            ];
        } else {
            return [
                'status' => 'fail',
                'msg' => 'Invalid facebook data format '.((isset($facebook_post['id'])?'at facebook ID:'.$facebook_post['id'] : '')),
            ];
        }
    }

    private function fpi_validate_post_data(array $facebook_post):bool
    {
        return (isset($facebook_post['id'])
            && isset($facebook_post['created_time'])
            && isset($facebook_post['from']['name'])
            && (isset($facebook_post['name']) || isset($facebook_post['story']))
            && isset($facebook_post['link'])
            && isset($facebook_post['message'])
        );
    }
}
