<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Campaigns extends CI_Controller {

function __construct()
	{
		parent::__construct();
		
		$this->load->database();
		$this->load->helper('url');
		
		$this->load->library('grocery_CRUD');
		
	}
	
	function _example_output($output = null)
	{
		$this->load->view('example.php',$output);	
	}
	
	
	function index()
	{
		$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
	}	
	

	function advertisers()
	{
		try{
			$crud = new grocery_CRUD();		
			$output = $crud->render();
			
			$this->_example_output($output);
			
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	function campaignz()
	{
		try{
			$crud = new grocery_CRUD();		
			 $crud->set_theme('datatables');
			$crud->unset_fields('effective_cpa_cpc_or_cpm');
			$crud->unset_columns('websites');
			$crud->set_relation('advertiser','advertisers','advertiser_name');
			$crud->set_relation('type','campaign_types','type');
			$crud->set_relation('previous_campaign','campaignz','name');
			$crud->set_relation_n_n('websites', 'websites_campaigns', 'webs', 'campaign', 'website', 'website_name','priority');
			$crud->callback_after_insert(array($this, 'campaignz_callback'));
			$crud->callback_after_update(array($this, 'campaignz_callback'));
			$crud->add_action('Paid', '', 'campaigns/make_payment');
			$output = $crud->render();
			
			$this->_example_output($output);
			
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	function campaignz_callback($post_array,$primary_key)
	{
		try{
			if($post_array['type'] == 4 or $post_array['type'] == 5)
				$rate=0;
			else
			{
				if($post_array['delivered'] > $post_array['booked'] and $post_array['delivered'] > 0)
				{
					if($post_array['type'] == 1)
						$rate = $post_array['budget'] / ($post_array['delivered']/1000);
					else
						$rate = $post_array['budget'] / $post_array['delivered'];
				}
				else
				{
					$this->db->where('id',$post_array['type']);
					$default = $this->db->get('campaign_default_values');
				
					$rate = $default->row()->value;
				}
				
			}
			
			$data = array (
				'effective_cpa_cpc_or_cpm' => $rate
			);
			
			$this->db->where('id',$primary_key);
			$this->db->update('campaignz',$data);
			
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}


	/*function check_previous_unpaid($key)
	{
		$this->db->where('payment_status',0);
		$this->db->where('id',$key);
		$campaign_obj=$this->db->get('campaignz');
		
		$campaign = $campaign_obj->row();
		$data[$key] = $key;
		
		if($campaign->previous_campaign > 0)
		{
			$data[$campaign->previous_campaign] = $campaign->previous_campaign;
			$this->check_previous_unpaid($campaign->previous_campaign);
		}
		return $data;
			
	}*/
	
	
	function make_payment($primary_key)
	{
		$data = array();
		$this->db->where('payment_status',0);
		$this->db->where('id',$primary_key);
		$campaign_obj=$this->db->get('campaignz');
		$campaign = $campaign_obj->row();
		
		$data[$primary_key] = $campaign->name;
		
		$this->db->where('payment_status',0);
		$this->db->where('advertiser',$campaign->advertiser);
		$this->db->where('id <> ',$primary_key);
		$campaigns=$this->db->get('campaignz');
		
		foreach($campaigns->result() as $campgn)
		{
			$data[$campgn->id] = $campgn->name;
		}
		
		$datas['campaigns'] = $data;
		$this->load->view('header');
		$this->load->view('pay_campaign',$datas);
		$this->load->view('footer');
		//print_r($data);
		
	}

	function websites_campaigns()
	{
		try{
			$this->db->order_by('website_name');
			$datas['websites']=$this->db->get('webs');
			$date = new DateTime(date("01-10-2012"));

			$list = '';

			while($date->format("Y-m")!=date('Y-m'))
			{
				$datas['months'][$date->format("Y-m")] = $date->format("F Y");
				$date->add(new DateInterval('P1M'));
			}



			

			$this->load->view('header');
			$this->load->view('select',$datas);
			$this->load->view('footer');

			//$this->db->where('start_date', '2012-10-01');
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	
	function campaign_types()
	{
		try{
			$crud = new grocery_CRUD();		
			

			$output = $crud->render();
			
			$this->_example_output($output);
			
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	function get_campaigns()
	{
		$this->db->where('id', $_POST['website']);
		$website=$this->db->get('webs');
		$campaigns[]=array();
		$date = explode('-', $_POST['month']);
		$query="select * from campaignz where month(start_date) = " . $date[1] . " and year(start_date) = " . $date[0];
		$res = $this->db->query($query);
		foreach($res->result() as $r)
		{
			$campaigns[]=$r->id;
		}

		$this->db->where_in('campaign', $campaigns);
		$this->db->where('website', $_POST['website']);
		$websites_campaigns=$this->db->get('websites_campaigns');

		$this->load->view('header');
		$this->load->view('deliveries',$datas);
		$this->load->view('footer');
	
	}
	
	
	
	
	function valueToEuro($value, $row)
	{
		return $value.' &euro;';
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */