<footer class="text-center bg-dark text-lg-start mt-xl-5 pt-4">
    <div class="container p-4">

        <div class="row mb-4 d-flex align-items-center">
            <div class="col-lg-6 text-lg-start text-center mb-3 mb-lg-0">
                <p style="font-family: 'Red Hat Display', serif; font-weight: bolder; color: #ffffff; font-size: large;">COURSAT</p>
            </div>

            

        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h3 style="color: #ffffff;">CATEGORIES</h3>
                <ul class="list-unstyled mb-4">
                    
                <?php
                $query = "SELECT * FROM categories";
                $result = mysqli_query($conn, $query);

                if (!$result) {
                    echo'No categories available';
                    }


                while ($row = mysqli_fetch_assoc($result) ) {
   
              
                    echo '<li><a href="browse.php#'.$row['id'].'" class="footer__link">'.$row['categ_name'].'</a></li>';
      
                        }
?>
                    
                    
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h3 style="color: #ffffff;">Subscriptions</h3>
                <ul class="list-unstyled">
                <?php
                $query = "SELECT * FROM subscription";
                $result = mysqli_query($conn, $query);

                if (!$result) {
                    echo'No subscription available';
                    }


                while ($row = mysqli_fetch_assoc($result) ) {
   
                
                    echo '<li><a href="subscription.php" class="footer__link">'.$row['sub_name'].'</a></li>';
      
        
                    } 
                        
?>
                   
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h3 style="color: #ffffff;">COMPANY</h3>
                <ul class="list-unstyled">
                    <li><a href="index.php#whyus" class="footer__link">Why Us</a></li>
                    
                </ul>
            </div>

        
        </div>

    </div>
</footer>
