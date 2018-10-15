<?php

namespace Shahrukh\Payments\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;


//trait ErrorHelper{
	protected $statusCode = 200;
    protected $result = true;

    /**
     * [getStatusCode description]
	 * 
     * @return [type] [description]
     */
    protected function getStatusCode(){
    	return $this->statusCode;
    }

    /**
    * [setStatusCode description]
    * 
    * @param [type] $statusCode [description]
    */
    protected function setStatusCode($statusCode){
    	$this->statusCode = $statusCode;
    	return $this;
    }

    /*********For making custom error response***********************/
    public function getErrorResponse($data){
        $temp = array();

        foreach($data as $objkey => $value){
            foreach($value as $val){
                $temp[$objkey] = $val ;
            }
        }

        return $temp ;
    }

    public function setErrorResult($result = false){
        $this->result = $result;

        return $this;
    }

    public function getErrorResult(){
        return $this->result;
    }

    /**
    * [respondNotFound description]
    * 
    * @param  string $message [description]
    * @return [type]          [description]
    */
    protected function respondNotFound($message = 'Not Found!', $dataType='object', $status=400){
    	return $this->setStatusCode(200)->respondWithError($message, $dataType,  $status);
    }

    protected function tokenNotFound($message = 'Not Found!', $dataType='object', $have_token=false, $status=400){
    	return $this->setStatusCode(200)->respondWithErrorForToken($message, $dataType, $have_token,  $status);
    }


    /**
    * [respondInternalError description]
    * 
    * @param  string $message [description]
    * @return [type]          [description]
    */
    protected function respondInternalError($message = 'Server Error!', $dataType='object', $status=400){
    	return $this->setStatusCode(200)->respondWithError($message, $dataType,  $status);
    }

    /**
    * [respond description]
    * 
    * @param  [type] $data    [description]
    * @param  array  $headers [description]
    * @return [type]          [description]
    */
    protected function respond($data, $headers = []){
    	//return Response::json($data, $this->getStatusCode(), $headers);
        return response()->json($data, $headers);
    }

    /************To return success response******************************/
    protected function apiRespond($data, $message = 'Operations performed succesfully.'){
    	return $this->setStatusCode(200)->respond([
            'statusCode'=>  200,
            'result'    => true,
            //'error'     => false,
            'message'   => $message,
            'data'      => $data
        ]);
    }

    /**
    * [respondWithError description]
    * 
    * @param  [type] $message [description]
    * @return [type]          [description]
    */
    protected function respondWithError($message, $dataType='object', $status=400){
    	if($dataType == 'object') {
    		$data = new \stdClass();;
    	}

    	if($dataType == 'array') {
    		$data = [];
    	}

    	if($dataType == 'string') {
    		$data = "";
    	}

    	return $this->respond([
    		"status"    => $status,
    		"error" 	=> true,
    		"message" 	=> $message,
    		"data" 		=> $data
    	]);
    }

    protected function respondWithErrorForToken($message, $dataType='object', $have_token=false, $status=400){
    	if($dataType == 'object') {
    		$data = new \stdClass();;
    	}

    	if($dataType == 'array') {
    		$data = [];
    	}

    	if($dataType == 'string') {
    		$data = "";
    	}

        $this->setStatusCode($status);
        
    	return $this->respond([
    		"status"    => $status,
    		"result" 	=> false,
    		"error" 	=> true,
    		"message" 	=> $message,
    		"data" 		=> $data
    	]);
    }
//}