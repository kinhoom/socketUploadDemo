<?php
class SOCKET_UPLOAD{
	private $host="127.0.0.1";
	private $port=80;
	private $errno=null;
	private $errstr=null;
	public $timeout=30;
	public $url="/upload/handle.php";
	private $ch=null;
	private $header="";
	private $body="";
	private $boundary='----avcss';
	private $res=null;
	private $file=null;
	private $form=null;
	public function __construct($form='',$file=''){
		$this->ch = fsockopen($this->host,$this->port,$this->errno,$this->errstr,$this->timeout);
		if(!$this->ch) exit('connect error!');
		$this->form=$form;
		$this->file=$file;
		$this->setHead();
		$this->setBody();
		$this->getStr();
	}
	public function write(){
		fwrite($this->ch,$this->res);
		$response = '';
		while($row=fread($this->ch,4096)){
			$response .= $row;
		}
		fclose($this->ch);
		// var_dump($response);
		$pos = strpos($response, "\r\n\r\n");
		$response = substr($response,$pos+4);
		echo $response;
	}
	public function getStr(){
		$this->header .= "Content-Length:".strlen($this->body)."\r\n";
		$this->header .= "Connection: close\r\n\r\n";
		$this->res = $this->header.$this->body;
	}
	private function setHead(){
		$this->header .= "POST {$this->url} HTTP/1.1\r\n";
		$this->header .= "HOST:{$this->host} \r\n";
		$this->header .= "Content-Type:multipart/form-data;boundary={$this->boundary}\r\n";
	}
	private function setBody(){
		$this->form();
		$this->file();
	}
	private function form(){
		if($this->form && is_array($this->form)){
			foreach($this->form as $k=>$v){
				$this->body .= "--$this->boundary"."\r\n";
				$this->body .= "Content-Disposition:form-data;name=\"{$k}\"\r\n";
				$this->body .= "Content-type:text/plain\r\n\r\n";
				$this->body .= "{$v}\r\n";
			}
		}
	}
	private function file(){
		if($this->file && is_array($this->file)){
			foreach($this->file as $k=>$val){
				// var_dump($val);
				$this->body .= "--$this->boundary"."\r\n";
				$this->body .= "Content-Disposition:form-data; name=\"{$val['name']}\";filename=\"{$val['filename']}\"\r\n";
				$this->body .= "Content-Type:{$val['type']}\r\n\r\n";
				$this->body .= file_get_contents($val['path'])."\r\n";
				$this->body .= "--{$this->boundary}";
			}
		}
	}

}
	$form = array('name'=>'lemon','age'=>'12');
	$file = array(
				array(
			        'name'=>'file',
			        'filename'=>'a.jpg',
			        'path'=>'a.jpg',
			        'type'=>'image/jpeg',
		        )
			);
	$upload = new SOCKET_UPLOAD($form,$file);
	$upload->write();
	// var_dump($upload);