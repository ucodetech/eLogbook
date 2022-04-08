<?php


class FileUpload
{
    private $_filename,
            $_max_filesize = 500000000,
            $_extension,
            $_path,
            $_db,
            $_user;

    public function __construct()
    {
        $this->_db = Database::getInstance();
        $this->_user = new User();
    }

    public function User(){
        return $this->_user;
    }
    /**
     * get file name
     * @return mixed
     */
    public function getFilename()
    {
        return $this->_filename;
    }

    /**
     * set file name
     * @param mixed $filename
     */
    public function setFilename($file, $name = '')
    {
        if ($name === ""){
            $name = pathinfo($file, PATHINFO_FILENAME);
        }

        $name = strtolower(str_replace(['_', ' '], '-', $name));
        $hash =  rand(1000,9999).md5(microtime());
        $ext = $this->fileExtension($file);

        $this->_filename = "{$name}-{$hash}.{$ext}";

    }
//check file extension
    public function fileExtension($file)
    {
        return $this->_extension = pathinfo($file, PATHINFO_EXTENSION);
    }
//check file size
    public static function fileSize($file)
    {
        $fileObj = new static;
        return $file > $fileObj->_max_filesize ? true : false;
    }
//validate file
    public static function isImage($file){

        $fileObj = new static;
        $ext = $fileObj->fileExtension($file);
        $validExt = array('jpg', 'jpeg', 'png','gif');

        if (!in_array(strtolower($ext), $validExt)){
            return false;
        }

        return true;
    }

//return path
    public function path(){
        return $this->_path;
    }
//move file
    public static function moveFile($temp_path, $initPath,$dir,$file, $new_filename = ''){
        $fileObj = new static;
        $dirs = DIRECTORY_SEPARATOR;

        $fileObj->setFilename($file, $new_filename);
        $file_name = $fileObj->getFilename();
//
//        if (!is_dir($dir)){
//            mkdir($dir, 0750, true);
//        }

        $fileObj->_path = "{$file_name}";
        $absolute_path = APPROOT."{$dirs}{$initPath}{$dirs}{$dir}{$dirs}$fileObj->_path";

        if(move_uploaded_file($temp_path, $absolute_path)){
            return $fileObj;
        }
        return false;
    }

    public static function isAudio($file){

        $fileObj = new static;
        $ext = $fileObj->fileExtension($file);
        $validExt = array('mp3', 'wav');
        if (!in_array(strtolower($ext), $validExt)){
            return false;
        }

        return true;
    }


    public static function isVideo($file){

        $fileObj = new static;
        $ext = $fileObj->fileExtension($file);
        $validExt = array('mp4', 'mkv');
        if (!in_array(strtolower($ext), $validExt)){
            return false;
        }

        return true;
    }

      public static function isPdf($file){

        $fileObj = new static;
        $ext = $fileObj->fileExtension($file);
        $validExt = array('pdf');
        if (!in_array(strtolower($ext), $validExt)){
            return false;
        }

        return true;
    }

    public function  moveToDatabase($table, $fields = array()){
        return $this->_db->insert($table, $fields);


    }
    public function  moveToDatabaseUpdate($table, $id, $fields = array()){
        return $this->_db->update($table,'id',$id, $fields);


    }

    //get requirements

    /**
     * @return Database|null
     */
    public function getDocTypes($stage)
    {

        $userPermission = $this->User()->data()->permission;
        if ($userPermission === 'student_hnd'){
            $value = 'ND';
        }else if($userPermission === 'student_nd'){
            $value = 'HND';
        }


        $sql = "SELECT * FROM Clearance_requirements WHERE levels != '$value' AND stage != '$stage' AND deleted = 0 ";
        $query = $this->_db->query($sql);
        if ($query->count()){
            return  $query->results();

        }else{
            return false;
        }

    }


public function getHistory($table, $user_id){
        $data = $this->_db->get($table, array('user_id', '=', $user_id));
        if ($data->count()){
            $row =  $data->first();
            $requirements = $row->requirement_array;
            $newRequire = explode(',',$requirements);

            $done = '';
            foreach ($newRequire as $showAS){

               $done .=  '<span class="text-info"><i class="fa fa-circle"></i>&nbsp;'.$showAS.'</span><br>';
            }
            return $done;

        }else{
            return '<span class="text-danger">No document uploaded yet!</span>';
        }
}

public function getIfUserIsDere($table, $user_id){
    $data = $this->_db->get($table, array('user_id', '=', $user_id));
    if ($data->count()) {
        return true;
    }else{
        return false;
    }
}
    public function checkIfUploaded($table,$field, $user_id){
        $sql = "SELECT * FROM $table WHERE $field = 1 AND user_id = '$user_id' ";
        $data = $this->_db->query($sql);
        if ($data->count()) {
            return true;
        }else{
            return false;
        }
    }


    public function  updateDatabaseHistroy($table,$user_id, $fields = array()){
        return  $this->_db->update($table, 'user_id',  $user_id, $fields);

    }

    public function checkIfNull($table, $field, $user_id){
        $sql = "SELECT * FROM $table WHERE $field = null AND user_id = '$user_id' ";
        $data = $this->_db->query($sql);
        if ($data->count()) {
            return true;
        }else{
            return false;
        }
    }

    public  function pendingTrueDntShow($table,$userid)
    {
        $sql = "SELECT * FROM $table WHERE pending = 0 AND approved = 0 AND user_id = '$userid' ";
        $data = $this->_db->query($sql);
        if ($data->count()) {
            return true;
        }else{
            return false;
        }
    }

    public function getRequestStatus($table,$field, $user_id){
        $sql = "SELECT * FROM $table  WHERE $field = 1 AND user_id = '$user_id' ";
        $data = $this->_db->query($sql);
        if ($data->count()) {
            return 'Yes';
        }else{
            return 'No';
        }
    }

    public function checkIfClearedFromAdmin($user_id){
        $sql = "SELECT * FROM userRequestsAdmin WHERE approved = 1 AND user_id = '$user_id' ";
        $data = $this->_db->query($sql);
        if ($data->count()) {
            return true;
        }else{
            return false;
        }
    }

    public function checkIfClearedFromDpt($user_id){
        $sql = "SELECT * FROM userRequestsDepartmentFinal WHERE approved = 1 AND user_id = '$user_id' ";
        $data = $this->_db->query($sql);
        if ($data->count()) {
            return true;
        }else{
            return false;
        }
    }

    public function transferUserFiles($userid)
    {
       $data =  $this->_db->get('userRequestsAdmin', array('user_id', '=', $userid));
       if ($data->count()){
//           return user row
           $row = $data->first();
//           find the user from user table
            $user = new User($row->user_id);
            $userPermission = $user->data()->permission;
            //check student permission
            if ($userPermission == 'student_nd'){
                $jambresult = $row->jamb_result;
                $it_letter = 'Null';
                $nd_result = 'Null';
            }elseif($userPermission == 'student_hnd'){
                $jambresult = 'Null';
                $it_letter = $row->it_letter;
                $nd_result = $row->nd_result;
            }

           $this->_db->insert('userRequestsDepartmentFinal', array(
               'user_id'	=> $row->user_id,
               'school_form' => $row->school_form,
               'admission_letter'	=> $row->admission_letter,
               'acceptance_letter'	=>$row->acceptance_letter,
               'undertaken_form' =>$row->undertaken_form,
               'state_of_origin' =>$row->state_of_origin,
               'jamb_result' =>$jambresult,
               'medical_report'	 =>$row->medical_report,
               'clearance_form_hod'	 =>$row->clearance_form_hod,
               'school_fees_breakdown'	 =>$row->school_fees_breakdown,
               'olevel_result' =>$row->olevel_result,
               'birth_certificate' =>$row->birth_certificate,
               'it_letter'	 =>$it_letter,
               'nd_result'	=>$nd_result,
               'jamb_admission_letter' =>$row->jamb_admission_letter

           ));

       }
        return true;
    }


    public  function checkIfFilesAreTransfered($user_id){
        $data = $this->_db->get('userRequestsDepartmentFinal', array('user_id', '=', $user_id));
        if ($data->count()){
            return true;
        }else{
            return false;
        }
    }

}//end of class
