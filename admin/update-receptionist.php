<?php include('partials/menu.php'); ?>
  <div class="main-content">
        <div class="wrapper">
            <h1>Update Receptionist</h1>
            <br><br>
              <?php
                 
                 //check wheather id is set or not
                 if(isset($_GET['id']))
                 {
                     //get id and all details
                     //echo "Getting the data";
                     $id=$_GET['id'];
                     //Create SQL query to get all other details 
                     $sql = "SELECT * FROM add_receptionist WHERE id=$id";
                     //execute the query

                     $res=mysqli_query($conn,$sql);
                     //count the rows to check wheather the id is valid or not
                     $count = mysqli_num_rows($res);
                     if($count==1)
                     {
                         //Get all the data
                         $row = mysqli_fetch_assoc($res);
                         $username = $row['username'];
                         $password = $row['password'];
                         $email = $row['email'];
                         $address = $row['address'];
                         $district = $row['district'];
                         $age = $row['age'];
                         $n_id = $row['n_id'];
                         $current_image = $row['image_name'];
                         $active = $row['active'];



                     }
                     else 
                     {
                         //redirect to appoint doctor page with session message
                         $_SESSION['no-receptionist-found']="<div class='error'>Receptionist not found</div>";
                         header('location:'.SITEURL.'admin/appoint-receptionist.php');
                     }



                 }
                 else{
                     //redirect to appoint doctor page
                     $_SESSION['unauthorize']="<div class='error'>Unauthorize Access</div>";
                     header('location:'.SITEURL.'admin/appoint-receptionist.php');
                 }

              ?>
                    <form action="" method="POST" enctype="multipart/form-data">

                            <table class="tbl-30">
                                <tr>
                                        
                                    <td>
                                        <input type="text" class="d_update" name="username" placeholder="Username" value="<?php echo $username; ?>">
                                    </td>
                                </tr>
                
                                <tr>
                            
                                    <td>
                                        <input type="email" class="d_update" name="email" placeholder="Email" value="<?php echo $email; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    
                                    <td>
                                        <input type="text" class="d_update" name="address" placeholder="address" value="<?php echo $address; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    
                                    <td>
                                        <input type="text" class="d_update" name="district" placeholder="district" value="<?php echo $district; ?>"><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    
                                    <td>
                                        <input type="text" class="d_update" name="age" placeholder="age" value="<?php echo $age; ?>"><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    
                                    <td>
                                        <input type="text" class="d_update" name="n_id" placeholder="n_id" value="<?php echo $n_id; ?>"><br><br>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>Current Image</td> 
                                    <td>
                                        <?php
                                            if($current_image != "")
                                            {
                                                ?>
                                                <img src="<?php echo SITEURL; ?>images/add-receptionist/<?php echo $current_image; ?>" width="150px">

                                                <?php

                                            }
                                            else{
                                                echo "<div class='error'>No Image Added</div>";
                                            }

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>New Image</td>
                                    <td>
                                        <input type="file" name="image">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Active</td>
                                    <td>
                                        <input <?php if($active=="Yes"){ echo "checked";}?> type="radio" name="active" value="Yes">Yes
                                        <input <?php if($active=="Yes"){ echo "checked";}?> type="radio" name="active" value="No">No
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                                        <input type="hidden" name="id" value="<?php echo $id;?>">
                                        <input type="submit" name="submit" value="Update Receptionist" class="btn-secondary">
                                     </td>
                                </tr>


                            </table>
                    </form>
                    <?php
                        if(isset($_POST['submit']))
                        {
                            //echo "Clicked";
                            //Get all the valuses from form
                            $username = $_POST['username'];
                            $password = $_POST['password'];
                            $email = $_POST['email'];
                            $address = $_POST['address'];
                            $district = $_POST['district'];
                            $age = $_POST['age'];
                            $n_id = $_POST['n_id'];
                            $current_image = $_POST['image_name'];
                            $active = $_POST['active'];

                            //updating new image if selected
                            if(isset($_FILES['image']['name']))
                            {
                                //Get the image details
                                $image_name = $_FILES['image']['name'];
                                //check whether the image is available or not
                                if($image_name != "")
                                {
                                    //image availabe
                                    //upload the new image
                                     //Auto rename our Image
                                    //to get the extension of our image(jpg,jpeg,png,gif)
                                    $ext = end(explode('.',$image_name));

                                    //rename the image
                                    $image_name="appointed_doctor_".rand(000,999).".".$ext;


                                    //to upload image we need image name ,source path and destinaiton path
                                    $source_path=$_FILES['image']['tmp_name'];
                                    $destination_path="../images/add-receptionist/".$image_name;

                                    //upload the image
                                    $upload =move_uploaded_file($source_path,$destination_path);
                                    //check whether the image is uploaded or not
                                    //if the file uploaded we will stop the process and redirect with error message
                                    if($upload==false){
                                        //set message
                                        $_SESSION['upload']="<div class='error'>Failed to upload image</div>";
                                        header('location:'.SITEURL.'admin/appoint-receptionist.php');
                                        //stop the process
                                        die();

                                    }
                                     
                                    //remove the current image if available
                                    if($current_image != "")
                                    {
                                        $remove_path = "../images/add-receptionist/".$current_image;
                                        $remove = unlink($remove_path);
    
                                        //check whether the remove of not
                                        //if failed to remove displaymsg and stop process
                                        if($remove==false)
                                        {
                                            $_SESSION['failed-remove'] = "<div class='error'>Failed to remove the image</div>";
                                            echo "<script>window.location.href='appoint-receptionist.php';</script>";
                                            die();
                                        }

                                    }
                      

                                    
                                }
                                else
                                {
                                    $image_name = $current_image;
                                }



                            }
                            else{
                                $image_name = $current_image;
                            }


                            //update database
                            $sql2 = "UPDATE add_receptionist SET 
                            username='$username',
                            password='$password',
                            email='$email',
                            address='$address',
                            district='$district',
                            age='$age',
                            n_id='$n_id',
                            image_name='$image_name',
                            active='$active' WHERE id=$id

                            ";
                            //execute the query
                            $res2=mysqli_query($conn,$sql2);
                            if($res2==true)
                            {
                                $_SESSION['update'] = "<div class='success'>Receptionist Updated Successfully</div>";
                                echo "<script>window.location.href='appoint-receptionist.php';</script>";
                            }
                            else{
                                $_SESSION['update'] = "<div class='error'>Failed to update Receptionist</div>";
                                echo "<script>window.location.href='appoint-receptionist.php';</script>";
                            }
                        }


                    ?>


        </div>

  </div>
<?php include('partials/footer.php');?>