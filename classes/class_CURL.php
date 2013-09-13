<?

class CURL_handle extends Handle {

	private static $handler = null;

	protected $_response = "";
	protected $_url = "";

	public function  __construct($options=null) {
		$this->__init($options);
	}

	public function  __destruct() {
		curl_close($this->handler);
	}

	public function exec() {

		if(!$this->handler) $this->__init ();
#echo "going to exec curl\n";
		$response = curl_exec($this->handler);
#print_r($response);
		$this->setResponse($response);
		return $this->getResponse();
	}

	// PRIVATE

	private function __init($options=null) {
		$this->handler = curl_init();
		curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->handler, CURLOPT_ENCODING, 'deflate');
		#curl_setopt($this->handler, CURLOPT_URL, $this->_url);

		if(is_array($options)) {
			curl_setopt_array($this->handler, $options);
		}
	}


	public function getMicrotime() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	// ENCAPSULATION

	public function setResponse($response) {
		$this->setString('_response', $response);
	}
	public function getResponse() {
		return $this->getString('_response');
	}

	public function setUrl($url) {
		$this->setString('_url', $url);
        curl_setopt($this->handler, CURLOPT_URL, $url);
	}
	public function getUrl() {
		return $this->getString('_url');
	}

	public function setOption($option_name, $option_value) {
		curl_setopt($this->handler, $option_name, $option_value);
	}
	public function getOption($option_name=null) {
		return curl_getinfo($this->handler, $option_name);
	}



}
