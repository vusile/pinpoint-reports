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
			$crud->set_relation('payment_status','yes_no','title');
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


	function check_previous_unpaid($key,$website)
	{
		//$this->db->where('payment_status',0);
		$unpaid = 0;
		$this->db->where('id',$key);
		$campaign_obj=$this->db->get('campaignz');
		
		$campaign = $campaign_obj->row();
		if($campaign->previous_campaign != 0)
		{
			$this->db->where('website', $website);
			$this->db->where('campaign', $campaign->previous_campaign);
			$trafficked=$this->db->get('websites_campaigns');
			if($trafficked->num_rows() > 0)
			{
				return ($unpaid + $trafficked->row()->value_after_percentage + $this->check_previous_unpaid($campaign->previous_campaign,$website));
			}

			else return 0;
		}

		else
			return 0;
			
	}
	
	
	function make_payment($primary_key)
	{
		$data = array();
		$this->db->where('payment_status',1);
		$this->db->where('id',$primary_key);
		$campaign_obj=$this->db->get('campaignz');
		$campaign = $campaign_obj->row();
		
		$data[$primary_key] = $campaign->name;
		
		$this->db->where('payment_status',1);
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

	function save_payment()
	{
		$datas=array();
		foreach($_POST as $key=>$value)
		{
			$data=array();
			$data['id']=$key;
			$data['payment_status']=2;
			$datas[]=$data;
		}

		$this->db->update_batch('campaignz',$datas,'id');

	}

	function websites_campaigns($report=0)
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

			if($report == 1)
				$datas['report']=1;
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
		$campaigns_string = "";
		$this->db->where('id', $_POST['website']);
		$website=$this->db->get('webs');
		$datas['website_name']=$website->row()->website_name;
		$datas['website_id']=$_POST['website'];
		$campaigns=array();
		$date = explode('-', $_POST['month']);
		$query="select * from campaignz where month(start_date) = " . $date[1] . " and year(start_date) = " . $date[0];
		$res = $this->db->query($query);
		foreach($res->result() as $r)
		{
			$campaigns[]=$r->id;
			$campaign_names[$r->id]=$r->name;
			$campaigns_string .= $r->id . ",";
		}

		$datas['campaigns_string'] = substr($campaigns_string, 0,-1);	

		//print_r($campaigns); die();

		$datas['campaign_names'] =$campaign_names;

		$this->db->where_in('campaign', $campaigns);
		$this->db->where('website', $_POST['website']);
		$datas['websites_campaigns']=$this->db->get('websites_campaigns');
	//	$datas['count'] = $datas['websites_campaigns']->num_rows();

		$this->load->view('header');
		$this->load->view('deliveries',$datas);
		$this->load->view('footer');
	
	}
	

	function save_deliveries()
	{


		$campaigns=explode(',', $_POST['string']);

		foreach($campaigns as $campaign)
		{
			$this->db->where('id', $campaign);
			$eff=$this->db->get('campaignz');
			$effective_cpa_cpc_or_cpm = $eff->row()->effective_cpa_cpc_or_cpm;
			$campaign_type=$eff->row()->type;


			$data = array();

			$data['website']=$_POST['website'];
			$data['campaign']=$campaign;
			$data['deliveries']=$_POST['deliveries-' . $campaign];


			$data['percentage']=$_POST['percentage-' . $campaign];

			$data['value_before_percentage'] = $data['deliveries'] * $effective_cpa_cpc_or_cpm;
			if($campaign_type == 1)
				$data['value_before_percentage'] = $data['value_before_percentage']/1000;
			$data['value_after_percentage'] = $data['value_before_percentage']  * ($data['percentage']/100);
			$this->db->where('website', $_POST['website']);
			$this->db->where('campaign', $campaign);
			$this->db->update('websites_campaigns', $data);

		}
		
	}

 	function gen_report()
	{
		$date = explode('-', $_POST['month']);
		$query="select * from campaignz where month(start_date) = " . $date[1] . " and year(start_date) = " . $date[0];
		$res = $this->db->query($query);
		foreach($res->result() as $r)
		{
			$campaigns[]=$r->id;
			$campaign_names[$r->id]=$r->name;
			$campaign_effectives[$r->id] = $r->effective_cpa_cpc_or_cpm;
		}


		$this->db->where_in('campaign', $campaigns);
		$this->db->where('website', $_POST['website']);
		$websites_campaigns=$this->db->get('websites_campaigns');

		$table = "<table border='1'>";
		$table .= "<tr><th>Campaign Name</th><th>Delivery</th><th>effective CPA, CPC or CPM</th><th>Value</th><th>%</th><th>Revenue</th><th>Previous Unpaid</th><th>Total Unapid</th></tr>";
		foreach($websites_campaigns->result() as $cmp)
		{
			$unpaid = $this->check_previous_unpaid($cmp->campaign, $_POST['website']);
			$table .= "<tr>";
			$table .= "<td>". $campaign_names[$cmp->campaign] ."</td>";
			$table .= "<td>". $cmp->deliveries ."</td>";
			$table .= "<td>". $campaign_effectives[$cmp->campaign] ."</td>";
			$table .= "<td>". $cmp->value_before_percentage ."</td>";
			$table .= "<td>". $cmp->percentage ."</td>";
			$table .= "<td>". $cmp->value_after_percentage ."</td>";
			$table .= "<td>" .  $unpaid  . "</td>";
			$table .= "<td>". ($cmp->value_after_percentage + $unpaid) ."</td>";
			$table .= "</tr>";
		}
		$table .= "</table>";

		echo $table;
	}
	
	
	
	function valueToEuro($value, $row)
	{
		return $value.' &euro;';
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */