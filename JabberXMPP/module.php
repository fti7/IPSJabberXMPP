<?

	class JabberXMPP extends IPSModule
	{
		
		public function __construct($InstanceID)
		{
			//Never delete this line!
			parent::__construct($InstanceID);
			
			// Include Library
			set_include_path(__DIR__ . '/lib');
			include_once 'lib/XMPPHP/XMPP.php';



			
		}
		
		public function Create()
		{
			//Never delete this line!
			parent::Create();
			
			$this->RegisterPropertyString("server", "jabber.ccc.de");
			$this->RegisterPropertyString("username", "ips");
			$this->RegisterPropertyString("password", "");
			$this->RegisterPropertyString("resource", "IPSymcon");

			$this->RegisterPropertyString("ssl_verifypeer", False);
			
		}
	
		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();
			
		}
	
		/**
		* This function will be available automatically after the module is imported with the module control.
		* Using the custom prefix this function will be callable from PHP and JSON-RPC through:
		*
		* JabberXMPP_SendMessage($id);
		*
		*/
		public function SendMessage(string $jid, string $message)
		{

			$server              = $this->ReadPropertyString("server");
			$username            = $this->ReadPropertyString("username");
			$password            = $this->ReadPropertyString("password");
			$resource 	     = $this->ReadPropertyString("resource");

			$ssl_verifypeer      = $this->ReadPropertyString("ssl_verifypeer");

			set_time_limit(30);

			try {

				$conn = new XMPPHP_XMPP($server, 5222, $username, $password, $resource, $server, $printlog=true, $loglevel=XMPPHP_Log::LEVEL_ERROR);

				if ($ssl_verifypeer) {
					$conn->setSSLOptions([ 'verify_peer' => true, 'allow_self_signed' => false, 'verify_peer_name' => true ]);
				} else {
					$conn->setSSLOptions([ 'verify_peer' => false, 'allow_self_signed' => true, 'verify_peer_name' => false ]);
				}

    				$conn->connect(10);
    				$conn->processUntil('session_start', 10);
    				$conn->presence();
    				$conn->message($jid, $message);
    				$conn->disconnect();

				unset($conn);

				return true;

			} catch(XMPPHP_Exception $e) {

    				echo "Exception: " . $e->getMessage();
				return false;

			}

			
		}
		
	
	}

?>
