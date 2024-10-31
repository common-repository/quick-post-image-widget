 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        function validateForm()
        {
            jQuery('#piw_msg').html('');
            var piw_link = jQuery('#piw_link').val();
            var piw_caption = jQuery('#piw_caption').val();
            var piw_desc = jQuery('#piw_desc').val();
            if(!validateURL(piw_link) || piw_link=="") {
                jQuery('#piw_msg').html("Valid link is required.");
                return false;
            } if(piw_caption == "") {
                jQuery('#piw_msg').html("Caption is required.");
                return false;
            } if(piw_desc == "") {
                jQuery('#piw_msg').html("Description is required.");
                return false;
            }
        }
        
         function validateURL(textval) {
      var urlregex = new RegExp(
            "^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$");
      return urlregex.test(textval);
    }
    var message_box = function() {
	var button = '<input type="button" onclick="message_box.close_message();" value="Okay!" />';
	return {
		show_message: function(title, body) {
			if(jQuery('#message_box').html() === null) {
				var message = '<div id="message_box"><h1>' + title + '</h1>' + body + '<br/>' + button + '</div>';
				jQuery(document.body).append( message );
				jQuery(document.body).append( '<div id="darkbg"></div>' );
				jQuery('#darkbg').show();
				jQuery('#darkbg').css('height', jQuery(document).height());
 
				jQuery('#message_box').css('top', 150);
				jQuery('#message_box').show('slow');
			} else {
				var message = '<h1>' + title + '</h1>' + body + '<br/>' + button;
				jQuery('#darkbg').show();
				jQuery('#darkbg').css('height', jQuery(document).height());
 
				jQuery('#message_box').css('top', 150);
				jQuery('#message_box').show('slow');
				jQuery('#message_box').html( message );
			}
		},
		close_message: function() {
			jQuery('#message_box').hide('fast');
			jQuery('#darkbg').hide();
		}
	}
}();

    </script>
<LINK href="<?php echo get_plugin_url_piw(); ?>/style.css" rel="stylesheet" type="text/css">
<?php
define('MAX_SIZE',50000);
function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }
 function getUserRole() {
     global $wp_roles;
     foreach ( $wp_roles->role_names as $role => $name ) :
         if ( current_user_can( $role ) )
             return $role;
         endforeach;
 }
if(isset($_POST['piw_action']) && is_user_logged_in()){

    $errors=0;
    //checks if the form has been submitted
    if(isset($_POST['piw_action'])) {
        //reads the name of the file the user submitted for uploading
        $image=$_FILES['image']['name'];
 	//if it is not empty
 	if ($image) {
            //get the original name of the file from the clients machine
            $filename = stripslashes($_FILES['image']['name']);
            //get the extension of the file in a lower case format
            $extension = getExtension($filename);
            $extension = strtolower($extension);
            //if it is not a known extension, we will suppose it is an error and 
            //// will not  upload the file,  
            ////otherwise we will do more tests
            if (($extension != "jpg") && ($extension != "jpeg") && ($extension !="png") && ($extension != "gif")) {
                //print error message
                echo '<h1>Unknown extension!</h1>';
                $errors=1;
            } else {
                //get the size of the image in bytes
                //$_FILES['image']['tmp_name'] is the temporary filename of the file
                //in which the uploaded file was stored on the server
                $size=filesize($_FILES['image']['tmp_name']);
                //compare the size with the maxim size we defined and print error if bigger
                if ($size > MAX_SIZE*1024) {
                    echo '<h1>You have exceeded the size limit!</h1>';
                    $errors=1;
                }
                //we will give an unique name, for example the time in unix time format
                $image_name=time().'.'.$extension;
                //the new name will be containing the full path where will be stored (images //folder)
                $newname= ABSPATH."wp-content/uploads/".$image_name;
                //we verify if the image has been uploaded, and print error instead
                $copied = copy($_FILES['image']['tmp_name'], $newname);
                if (!$copied) {
                    echo '<h1>Copy unsuccessfull!</h1>';
                    $errors=1;
                }
            }
        }
    }
    //If no errors registred, print the success message
    if(isset($_POST['piw_action']) && !$errors && empty($_FILES['image']['name'])) {
        ?>
                <script type="text/javascript">
                   message_box.show_message('Error!', 'Post upload failed!');
                        </script>
                        <?php
    }
    else if(isset($_POST['piw_action']) && !$errors && !empty($_FILES['image']['name'])) {
        
        // Create post object
        $content_temp = '[caption id="CAP_ID" align="alignnone" width="300" caption="CAP_TEXT"]
            <a href="POST_LINK"><img class="size-medium " title="" src="POST_IMG" alt="" width="300" height="247" /></a>
            [/caption]';
        $img = explode("wp-content", $newname);
        $newname = get_site_url()."/wp-content/".$img[1];
        $post_cont = $content_temp;
        $post_cont = str_replace("CAP_ID",time(),$post_cont);
        $post_cont = str_replace("CAP_TEXT",$_POST['piw_desc'],$post_cont);
        $post_cont = str_replace("POST_LINK",$_POST['piw_link'],$post_cont);
        $post_cont = str_replace("POST_IMG",$newname,$post_cont);
      //  $post_cont.="<br>". $_POST['piw_desc']; 
       
        $current_user = wp_get_current_user();
        $post_status = "pending";
       
//        if(getUserRole() == $instance['frole']) {
//            $post_status = $instance['fstatus'];
//           
//        } else if(getUserRole()== $instance['srole']) {
//            $post_status = $instance['sstatus'];
//        }

	if (current_user_can('upload_files')) {
              $post_status = "publish";
        }

        $my_post = array(
            'post_title' => $_POST['piw_caption'],
            'post_content' => $post_cont,
            'post_status' => $post_status,
            'post_author' => $current_user->ID,
            'post_category' => $_POST['piw_cat']
        );
        
        // Insert the post into the database
        $post_id = wp_insert_post( $my_post );
        $post = array(
            'ID' => $post_id,
            'tags_input' => $_POST['piw_tags']
         ); 
        wp_update_post($post);

        if($post_status=="pending") {
            ?>
                <script type="text/javascript">
                   message_box.show_message('Waiting approval!', 'Post has been uploaded and is awaiting approval');
                        </script>
                        <?php
        } else if($post_status=="publish") {
            ?>
                        <script type="text/javascript">
                   message_box.show_message('Done!', 'Post has been uploaded and will be published on the site');
                        </script>
                        <?php
        }

        
    }

} else if(isset($_POST['piw_action']) && !is_user_logged_in()) {
    $message = '<span style="color:red; font-size:13px;">You are not logged in. Please <a href="SL_LOGIN_URL">login</a> to perform actions.</span>';
    echo $message = str_replace("SL_LOGIN_URL", wp_login_url(get_permalink()), $message);
} 
?>

<form method="post" action="" enctype="multipart/form-data">
<table class="piw_table">
    <tr>
        <td style="text-align: center; font-weight: bold;">
            <?php
            if ( is_user_logged_in() ) {
                $current_user = wp_get_current_user();
                echo "Hi ".$current_user->user_login;
            } else {
                $message = 'You are not logged in. Please <a href="SL_LOGIN_URL">login</a> to perform actions.';
                echo $message = str_replace("SL_LOGIN_URL", wp_login_url(get_permalink()), $message);
            }?>
            <br /><br /><br />
        </td>
    </tr>
    <tr>
        <td>
            Image (No Resizing needed):
        </td>
    </tr>
    <tr>
        <td>
           <input type="file" name="image" class="file" />
        </td>
    </tr>
    <tr>
        <td>
            Image Link (will be added to images):
        </td>
    </tr>
    <tr>
        <td>
            <input type="text" name="piw_link" value="" class="piw_input" id="piw_link" />
        </td>
    </tr>
    <tr>
        <td>
            Post Title:
        </td>
    </tr>
    <tr>
        <td>
            <input type="text" name="piw_caption" value="" class="piw_input" id="piw_caption" />
        </td>
    </tr>
    <tr>
        <td>
            Category:
        </td>
    </tr>
    <tr>
        <td>
            
                    
                        <?php
                        $category_ids = get_all_category_ids();
                        foreach($category_ids as $cat_id) {
                        $cat_name = get_cat_name($cat_id);
                        echo '<div class="categoryBox">
                            <input type="checkbox" name="piw_cat[]"  value="'.$cat_id.'" class="categoryCheckBox" /> '.$cat_name.'
                                </div>';

                        }
                        ?> 
                    
        </td>
    </tr>
    <tr>
        <td>
            <textarea id="piw_desc" name="piw_desc" rows="2" class="piw_textare" cols="70" onfocus="if(this.value == 'Enter image caption here') this.value='';" onblur="if(this.value == '') this.value='Enter image caption here';" >Enter image caption here</textarea>
        </td>
    </tr>
    <tr>
        <td>
            Tags:<span class="piw_small">(Add comma separated tags here)</span>
        </td>
    </tr>
    <tr>
        <td>
            <input type="text" name="piw_tags" id="piw_tags" class="piw_input" value="" /><br>
        </td>
    </tr>
    <tr>
        <td>
            <input type="hidden" name="piw_action" value="" />
            <input type="reset" name="Cancel" value="Cancel" class="piw-styled-button" /> &nbsp; <input type="submit" name="post" value="Post" class="piw-styled-button" onclick="return validateForm();" />
            
        </td>
    </tr>
    <tr>
        <td>
            <p id="piw_msg" style="color:red">&nbsp;</p> 
        </td>
    </tr>
            
            
</table>
   