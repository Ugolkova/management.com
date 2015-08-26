<h1>Add User</h1>

<section>            
    <form action="<?php echo URL . 'users/add'; ?>" method="POST" class="publishForm">
        <p>
            <label class="required">User Name</label>
            <input type="text" name="user_name" value="" />
        </p> 
        <p>
            <label class="required">User Email</label>
            <input type="text" name="user_email" value="" />
        </p> 
        <p>
            <label class="required">User Skype</label>
            <input type="text" name="user_skype" value="" />
        </p>   
        <p><input type="submit" name="submit" value="Submit" /></p>
    </form>
</section>    