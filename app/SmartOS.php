<?php
if (! function_exists('components'))
{
	function components($file,$data=[]){
		return view("\App\Views\components\\".$file,$data);
	}
}

if (! function_exists('blocks'))
{
	function blocks($file,$data=[]){
		return view("\App\Views\\".$file,$data);
	}
}


if (! function_exists('admin_url'))
{
	function admin_url($file,$data=[]){
		return "/admin/".$file."?".http_build_query($data);
	}
}


if (! function_exists('userinfo'))
{
	function userinfo(){
		$profile = new \App\Models\UserProfileModel;
		return $profile->getProfile();
	}
}

if (! function_exists('_go'))
{
	function _go($file){
		return redirect()->to($file)->with('message', 'Update data ok');
	}
}


function delay_timeago( $start,$ptime )
{
	
	$etime = strtotime($ptime) - strtotime($start);

	return date("m",$etime)." minute";
}


function get_timeago( $ptime )
{
	$ptime = strtotime($ptime);
	$etime = time() - $ptime;

	if( $etime < 1 )
	{
	    return 'less than '.$etime.' second ago';
	}

	$a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
	            30 * 24 * 60 * 60       =>  'month',
	            24 * 60 * 60            =>  'day',
	            60 * 60             =>  'hour',
	            60                  =>  'minute',
	            1                   =>  'second'
	);

	foreach( $a as $secs => $str )
	{
	    $d = $etime / $secs;

	    if( $d >= 1 )
	    {
	        $r = round( $d );
	        return $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
	    }
	}
}