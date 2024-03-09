<?php
// @header("Content-type: text/html; charset=utf-8");

require_once(__DIR__.'/zip.class.php');

class MailReader
{

    public $webPath = 'http://lk0.cn/yd/mail/';

    //定义系统常量
    //用户名
    public function __construct($type, $path = '')
    {
        $this->now = time();
        $configure = require(__DIR__.'/../configure.php');
        $configure = $configure[$type];
        $this->basePath = $path;
        $this->setSavePath();

        $this->mailer = new receiveMail($configure['username'], $configure['password'], $configure['email'], $configure['server'], $configure['server_type'], $configure['port'], $configure['ssl']);
        $this->mailer->connect();
    }
 
    /**
     * mail Received()读取收件箱邮件
     *
     * @param
     * @access public
     * @return result
     */
    public function reador()
    {
        // Get Total Number of Unread Email in mail box
        $tot=$this->mailer->getTotalMails(); //Total Mails in Inbox Return integer value
 
        if($tot) {
            for($i=$tot;$i>0;$i--)
            {
                $head=$this->mailer->getHeaders($i);  // Get Header Info Return Array Of Headers **Array Keys are (subject,to,toOth,toNameOth,from,fromName)
 
                $head['id'] = $i;
                yield $head;
// 				$this->mailer->deleteMails($i); // Delete Mail from Mail box
//         		$this->mailer->move_mails($i,"taskMail");
            }
            // $this->mailer->close_mailbox();   //Close Mail Box
        }
    }
    public function detail($i)
    {
        //处理邮件附件
        $files=$this->mailer->GetAttach($i,$this->savePath); // 获取邮件附件，返回的邮件附件信息数组

        $imageList=array();
        foreach($files as $k => $file) {
            //type=1为附件,0为邮件内容图片
            if($file['type'] == 0) {
                $imageList[$file['title']]=$file['pathname'];
                unset($files[$k]);
            }
        }
        $body = $this->mailer->getBody($i,$this->webPath,$imageList);
 
        return ['body' => $body, 'attachs' => $files];
    }
 
    /**
     * creatBox
     *
     * @access public
     * @return void
     */
    public function creatBox($boxName)
    {
        $this->mailer->creat_mailbox($boxName);
    }
 
    /**
     * Set save path.
     *
     * @access public
     * @return void
     */
    public function setSavePath()
    {
        $savePath = $this->basePath;
        if(!file_exists($savePath))
        {
            @mkdir($savePath, 0777, true);
            touch($savePath . 'index.html');
        }
        $this->savePath = $savePath;
    }
}
 
// Main ReciveMail Class File - Version 1.0 (03-06-2015)
/*
 * File: recivemail.class.php
 * Description: Reciving mail With Attechment
 * Version: 1.1
 * Created: 03-06-2015
 * Author: Sara Zhou
 */
class receiveMail
{
    var $server='';
    var $username='';
    var $password='';
 
    var $marubox='';
 
    var $email='';
 
    function receiveMail($username, $password, $EmailAddress, $mailserver = 'localhost', $servertype = 'pop', $port = '110', $ssl = false)
    { //Constructure
        if ($servertype == 'imap') {
            if ($port == '')
                $port = '143';
            $strConnect = '{' . $mailserver . ':' . $port . '/imap/ssl}INBOX';
        } else {
            $strConnect = '{' . $mailserver . ':' . $port . '/pop3' . '}INBOX';
        }
        $this->server = $strConnect;
        $this->username = $username;
        $this->password = $password;
        $this->email = $EmailAddress;
    }
 
    function connect() //Connect To the Mail Box
    {
        $this->marubox=@imap_open($this->server,$this->username,$this->password);
 
        if(!$this->marubox)
        {
 
 			echo "Error: Connecting to mail server";
 			exit;
        }
        return true;
    }
 
 
    function getHeaders($mid) // Get Header info
    {
        if(!$this->marubox)
            return false;
 
        $mail_header=imap_header($this->marubox,$mid);
        $sender=$mail_header->from[0];
        $sender_replyto=$mail_header->reply_to[0];
        if(strtolower($sender->mailbox)!='mailer-daemon' && strtolower($sender->mailbox)!='postmaster')
        {
            $subject=$this->decode_mime($mail_header->subject);
 
            $ccList=array();
            foreach ($mail_header->cc as $k => $v)
            {
                $ccList[]=$v->mailbox.'@'.$v->host;
            }
            $toList=array();
            foreach ($mail_header->to as $k => $v)
            {
                $toList[]=$v->mailbox.'@'.$v->host;
            }
            $ccList=implode(",", $ccList);
            $toList=implode(",", $toList);
            $mail_details=array(
                'fromBy'=>strtolower($sender->mailbox).'@'.$sender->host,
                'fromName'=>$this->decode_mime($sender->personal),
                'ccList'=>$ccList,//strtolower($sender_replyto->mailbox).'@'.$sender_replyto->host,
                'toNameOth'=>$this->decode_mime($sender_replyto->personal),
                'subject'=>trim($subject),
                'mailDate'=>date("Y-m-d H:i:s",$mail_header->udate),
                'udate'=>$mail_header->udate,
                'toList'=>$toList//strtolower($mail_header->to[0]->mailbox).'@'.$mail_header->to[0]->host
// 					'to'=>strtolower($mail_header->toaddress)
            );
        }
        return $mail_details;
    }
    function get_mime_type(&$structure) //Get Mime type Internal Private Use
    {
        $primary_mime_type = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");
 
        if($structure->subtype && $structure->subtype!="PNG") {
            return $primary_mime_type[(int) $structure->type] . '/' . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }
    function get_part($stream, $msg_number, $mime_type, $structure = false, $part_number = false) //Get Part Of Message Internal Private Use
    {
 
        if(!$structure) {
            $structure = imap_fetchstructure($stream, $msg_number);
        }
        if($structure) {
            if($mime_type == $this->get_mime_type($structure))
            {
                if(!$part_number)
                {
                    $part_number = "1";
                }
                $text = imap_fetchbody($stream, $msg_number, $part_number);
 
                if($structure->encoding == 3)
                {
                    return imap_base64($text);
                }
                else if($structure->encoding == 4)
                {
                    return iconv('GBK','UTF-8',imap_qprint($text));
                }
                else
                {
                    return iconv('GBK','UTF-8',$text);
                }
            }
            if($structure->type == 1) /* multipart */
            {
                while(list($index, $sub_structure) = each($structure->parts))
                {
                    if($part_number)
                    {
                        $prefix = $part_number . '.';
                    }
                    $data = $this->get_part($stream, $msg_number, $mime_type, $sub_structure, $prefix . ($index + 1));
                    if($data)
                    {
                        return $data;
                    }
                }
            }
        }
        return false;
    }
   function getTotalMails() //Get Total Number off Unread Email In Mailbox
   {
       if(!$this->marubox)
           return false;

        // return imap_headers($this->marubox);
       return imap_num_msg($this->marubox);
   }
 
    function GetAttach($mid,$path) // Get Atteced File from Mail
    {
        if(!$this->marubox)
            return false;
 
        $struckture = imap_fetchstructure($this->marubox,$mid);
        // $struckture = json_decode(file_get_contents(__DIR__."/strucktrue.txt"));
        
        $files=array();
        if($struckture->parts)
        {
            foreach($struckture->parts as $key => $value)
            {
                $enc=$struckture->parts[$key]->encoding;
 
                //取邮件附件
                if($struckture->parts[$key]->ifdparameters)
                {
                    //命名附件,转码
                    $name=$this->decode_mime($struckture->parts[$key]->dparameters[0]->value);
                    $extend =explode("." , $name);
                    $file['extension'] = $extend[count($extend)-1];
                    $file['title']     = !empty($name) ? htmlspecialchars($name) : str_replace('.' . $file['extension'], '', $name);
                    $file['pathname']  = $this->setPathName($path, $file['extension']);
                    $file['size']      = $struckture->parts[$key]->dparameters[1]->value;
// 					$file['tmpname']   = $struckture->parts[$key]->dparameters[0]->value;
                    if(@$struckture->parts[$key]->disposition=="ATTACHMENT")
                    {
                        $file['type']      = 1;
                    }
                    else
                    {
                        $file['type']      = 0;
                    }
                    $files[] = $file;
 
                    $message = imap_fetchbody($this->marubox,$mid,$key+1);
                    // $message = file_get_contents(__DIR__."/strucktrue_{$mid}_{$key}.txt");
                    if ($enc == 0)
                        $message = imap_8bit($message);
                    if ($enc == 1)
                        $message = imap_8bit ($message);
                    if ($enc == 2)
                        $message = imap_binary ($message);
                    if ($enc == 3)//图片
                        $message = imap_base64 ($message);
                    if ($enc == 4)
                        $message = quoted_printable_decode($message);
                    if ($enc == 5)
                        $message = $message;
                    // echo 'path: ', $path.$file['pathname'], "\n";
                    file_put_contents($path.$file['pathname'], $message);
                    // $fp=fopen($path.$file['pathname'],"w");
                    // fwrite($fp, '123');
                    // fwrite($fp,$message);
                    // fclose($fp);
 
                }
                // 处理内容中包含图片的部分
                if($struckture->parts[$key]->parts)
                {
                    foreach($struckture->parts[$key]->parts as $keyb => $valueb)
                    {
                        $enc=$struckture->parts[$key]->parts[$keyb]->encoding;
                        if($struckture->parts[$key]->parts[$keyb]->ifdparameters)
                        {
                            //命名图片
                            $name=$this->decode_mime($struckture->parts[$key]->parts[$keyb]->dparameters[0]->value);
                            $extend =explode("." , $name);
                            $file['extension'] = $extend[count($extend)-1];
                            $file['title']     = !empty($name) ? htmlspecialchars($name) : str_replace('.' . $file['extension'], '', $name);
                            $file['pathname']  = $this->setPathName($path, $file['extension']);
                            $file['size']      = $struckture->parts[$key]->parts[$keyb]->dparameters[1]->value;
// 							$file['tmpname']   = $struckture->parts[$key]->dparameters[0]->value;
                            $file['type']      = 0;
                            $files[] = $file;
 
                            $partnro = ($key+1).".".($keyb+1);
 
                            $message = imap_fetchbody($this->marubox,$mid,$partnro);
                            if ($enc == 0)
                                $message = imap_8bit($message);
                            if ($enc == 1)
                                $message = imap_8bit ($message);
                            if ($enc == 2)
                                $message = imap_binary ($message);
                            if ($enc == 3)
                                $message = imap_base64 ($message);
                            if ($enc == 4)
                                $message = quoted_printable_decode($message);
                            if ($enc == 5)
                                $message = $message;
                            file_put_contents($path.$file['pathname'],$message);
                        }
                    }
                }
            }
        }
        //move mail to taskMailBox
        $this->move_mails($mid, $this->marubox);
 
        return $files;
    }
 
    function getBody($mid,&$path,$imageList) // Get Message Body
    {
        if(!$this->marubox)
            return false;
 
        $body = $this->get_part($this->marubox, $mid, "TEXT/HTML");
        if ($body == "")
            $body = $this->get_part($this->marubox, $mid, "TEXT/PLAIN");
        if ($body == "") {
            return "";
        }
        //处理图片
        $body=$this->embed_images($body,$path,$imageList);
        return $body;
    }
 
    function embed_images(&$body,&$path,$imageList)
    {
        // get all img tags
        preg_match_all('/<img.*?>/', $body, $matches);
        if (!isset($matches[0])) return;
 
        foreach ($matches[0] as $img)
        {
            // replace image web path with local path
            preg_match('/src="(.*?)"/', $img, $m);
            if (!isset($m[1])) continue;
            $arr = parse_url($m[1]);
            if (!isset($arr['scheme']) || !isset($arr['path']))continue;
 
// 			if (!isset($arr['host']) || !isset($arr['path']))continue;
            if ($arr['scheme']!="http")
            {
                $filename=explode("@", $arr['path']);
                $body = str_replace($img, '<img alt="" src="'.$path.$imageList[$filename[0]].'" style="border: none;" />', $body);
            }
        }
        return $body;
    }
 
    function deleteMails($mid) // Delete That Mail
    {
        if(!$this->marubox)
            return false;
 
        imap_delete($this->marubox,$mid);
    }
    function close_mailbox() //Close Mail Box
    {
        if(!$this->marubox)
            return false;
 
        imap_close($this->marubox,CL_EXPUNGE);
    }
 
    //移动邮件到指定分组
    function move_mails($msglist,$mailbox)
    {
        if(!$this->marubox)
            return false;
 
        imap_mail_move($this->marubox, $msglist, $mailbox);
    }
 
    function creat_mailbox($mailbox)
    {
        if(!$this->marubox)
            return false;
 
        //imap_renamemailbox($imap_stream, $old_mbox, $new_mbox);
        imap_create($this->marubox, $mailbox);
    }
 
    /*
     * decode_mime()转换邮件标题的字符编码,处理乱码
     */
    function decode_mime($str){
        $str=imap_mime_header_decode($str);
        // print_r($str); echo "\n";
        $text = $str[0]->text;
        if(!in_array(strtoupper($str[0]->charset), ['UTF-8', 'DEFAULT'])) {
            // echo "in\n";
            $from = strtoupper($str[0]->charset);
            if($from == 'GB2312') {
                $from = 'GBK';
            }
            $text = iconv($from, 'UTF-8', $str[0]->text);
        }
        // echo $text;
        return $text;
    }
 
    /**
     * Set path name of the uploaded file to be saved.
     *
     * @param  int    $fileID
     * @param  string $extension
     * @access public
     * @return string
     */
    public function setPathName($path, $name)
    {
        $dir = $path.date('Y/');
        if(!is_dir($dir)) {
            @mkdir($dir, 0777);
            touch($dir . 'index.html');
        }
        return date('Y/mdhis_') .rand(100, 999) .rand(100, 999).'.'. $name;
    }
 
}
?>