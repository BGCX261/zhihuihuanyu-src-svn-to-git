<?php
/************************************************
 ** @author gumeng
 ** @class description 通用库
 ************************************************/

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Libcommon {
	
	function __construct()
    {    	
    	//$this->logfile = getenv("SINAMOBILE_APPLOGS_DIR").'charge_rule'.date("Ymd").'.log';
    }
    
    /** 
     * 将数组$parms_in转成'', '', ''....格式的一行信息，以便于sql语句中的in ('', '') 语法
     *
     * @access public
     * @param $parms_in: array of string
     * @return string
     */
	public function get_sql_in_string($parms_in)
	{
		$parms_in = array_values(array_unique($parms_in)); // 数据滤重
		 
		$ret = '';
		foreach($parms_in as $one_string) {
			$ret .= "'".$one_string."',";
		}
		// 删除最后一个逗号
		$ret = substr($ret, 0, strlen($ret) - 1);
		return $ret;
	}
	
     /** 
     * 将数组$parms_in转成$logdata_out格式的一行信息，以便于日志输出
     *
     * @access public
     * @param $logdata_out: infos without \n
     * @return null
     */
	public function create_logdata($parms_in)
	{
		$logdata_out = '';
		foreach($parms_in as $key=>$val) {
			$logdata_out .= $key.'='.$val.'`';
		}
		if($logdata_out != '') {
			$logdata_out = rtrim($logdata_out, "`");
		}
		return $logdata_out;
	}
    /** 
     * 日志记录功能,不可用！
     *
     * @access public
     * @param 3. $data: infos without \n
     * @return null
     */
	public function mylog($phpfilename, $data)
	{
		$logfile = $this->logfile;
		$fp = fopen($logfile,'a');
		if($fp===false) {
			return;
		}
		if (!flock($fp, LOCK_EX)) { // do an exclusive lock
			fclose($fp);		
			return;
		}else {
			$data = date("[d/M/Y:H:i:s O]",time()).'`'.$phpfilename.'`'.$data."\n";
			fwrite($fp, $data);
		    flock($fp, LOCK_UN); // release the lock
			fclose($fp);
		}	
	}

    /** 
     * 记录info后，退出程序
     *
     * @access public
     * @param $data: infos without \n
     * @return null
     */
	public function myexit($exitcode, $explain, $start_time, $phpfilename, $data)
	{
		$logfile = $this->logfile;

		$end_time = $this->get_microtime();
		$runtime = round($end_time - $start_time, 4);	
		
		// [datetime]`php_file_name`返回值`运行阶段或错误解释`处理时间`各类参数
		$this->mylog($phpfilename, 'ret='.$exitcode.'`'.$explain.'`time='.$runtime.'`'.$data);
		exit($exitcode); // 只给合作方返回错误码。详细错误由自己的log中记录。
	}
	
    /** 
     * 记录info后，控制权返回调用者
     *
     * @access public
     * @param $data: infos without \n
     * @return null
     */
	public function myreturn($exitcode, $explain, $start_time, $phpfilename, $data)
	{
		$logfile = $this->logfile;

		$end_time = $this->get_microtime();
		$runtime = round($end_time - $start_time, 4);	
		
		// [datetime]`php_file_name`返回值`运行阶段或错误解释`处理时间`各类参数
		$this->mylog($phpfilename, 'ret='.$exitcode.'`'.$explain.'`time='.$runtime.'`'.$data);
		return ($exitcode); // 只给合作方返回错误码。详细错误由自己的log中记录。
	}
    

	/**
	 * @desc	列出path路径下的文件。不含. &　..
	 * @param	$path: 路径。$time_order: 排序规则，默认是以文件修改时间升序[由早到晚]
	 * @return	path下的文件列表.
	 */
	public function get_files($path, $time_order='asc')
	{
		$files = array();
		
		//path末尾若无/，则添加一个
		if(substr($path, strlen($path)-1)!='/') {
			$path .= $path.'/';
		}
		
		if(!is_dir($path)) {
			return $files;
		}else {
			if (($dh = @opendir($path)) === false) {
				return $files;
			}else {
				while (($file = readdir($dh)) !== false) {								
					if($file=="." || $file==".." || is_dir($path.$file))continue;
					array_push($files, $path.$file); // 添加元素于数组的末尾
				}
				usort($files, array($this,"sort_query")); 
				if($time_order=='desc') {
					$files = array_reverse($files, false);
				}
				return $files;
			}
		}
	}
	
	/**
	 * @desc	xml解析函数。可将xml数据解析成数组，并返回该数组
	 * @param	$xml_data: input data.
	 * @return	$xml_arr: output data. key-value fmt.
	 * 
	 */
	public function xml_parse($xml_data)
	{
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, $xml_data, $values, $tags);
		xml_parser_free($parser);
	
		$xml_arr = array();
		$p = array();
		foreach ($values as $k=>$v){
		        if($v[type] == 'open'){
		                $p[] = $v[tag];
		        }elseif($v[type] == 'complete'){
		                switch (count($p)){
		                        case 1: $xml_arr[$p[0]][$v[tag]] = $v[value];break;
		                        case 2: $xml_arr[$p[0]][$p[1]][$v[tag]] = $v[value];break;
		                        case 3: $xml_arr[$p[0]][$p[1]][$p[2]][$v[tag]] = $v[value];break;
		                        case 4: $xml_arr[$p[0]][$p[1]][$p[2]][$p[3]][$v[tag]] = $v[value];break;
		                        case 5: $xml_arr[$p[0]][$p[1]][$p[2]][$p[3]][$p[4]][$v[tag]] = $v[value];break;
		                }
		                //print_r($arr);echo "<br /><br />";
		        }elseif($v[type] == 'close'){
		                unset($p[count($p)-1]);
		        }
		}
		return $xml_arr;
	}
	
	public function get_microtime()
	{
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}
	
	// 默认以修改时间递增排序
	public function sort_query($file1,$file2)
	{
		$time1 = filemtime($file1);
		$time2 = filemtime($file2);      
	    if($time1==$time2) return 0;
	    return ($time1<$time2) ? -1 : 1;
	}
		
}


	
