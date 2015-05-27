<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Slugs extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
        if(!$this->ion_auth->in_group('admin'))
        {
            $this->session->set_flashdata('message','You are not allowed to visit the Slugs page');
            redirect('admin','refresh');
        }
        $this->load->model('slug_model');
        $this->load->library('form_validation');
        $this->load->helper('text');
	}

	private function _verify_slug($str,$language)
    {
        if($this->slug_model->where(array('url'=>$str,'language_slug'=>$language))->get() !== FALSE)
        {
            $parts = explode('-',$str);
            if(is_numeric($parts[sizeof($parts)-1]))
            {
                $parts[sizeof($parts)-1] = $parts[sizeof($parts)-1]++;
            }
            else
            {
                $parts[] = '1';
            }
            $str = implode('-',$parts);
            $this->_verify_slug($str,$language);
        }
        return $str;
    }

    public function delete($slug_id)
    {
        if($this->slug_model->delete($slug_id))
        {
            $this->session->set_flashdata('message', 'The slug was deleted');
        }
        else
        {
            $this->session->set_flashdata('message', 'There is no slug with that ID.');
        }
        redirect($_SERVER['HTTP_REFERER'],'refresh');

    }
}