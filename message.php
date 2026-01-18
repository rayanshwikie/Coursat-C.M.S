<?php 
            if(isset($_SESSION[ 'errors']) && count($_SESSION[ 'errors'])>0){ 
                foreach($_SESSION['errors'] as $error){ 
            ?>
            <div class="alert alert-warning animate__animated animate__fadeInDown">
                <?=$error; ?>
            </div>
            <?php 
                } 
                unset($_SESSION[ 'errors']); } 
                if(isset($_SESSION[ 'message'])){
                echo '<div class="alert alert-success animate__animated animate__fadeInDown">'.$_SESSION[ 'message'].'</div>'; unset($_SESSION[ 'message']); }
             ?>